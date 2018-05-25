<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client;
use Socialite;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Validator;

class AuthenticateController extends ApiController
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('auth:api')->only(['logout']);
    }
    //自定义登录所需要验证的字段 （将验证手机和密码）
    public function username()
    {
        return 'phone';
    }

    // 登录
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone'    => 'required|exists:user',
            'password' => 'required|between:5,32',
        ]);
        if ($validator->fails()) {
            $request->request->add([
                'errors' => $validator->errors()->toArray(),
                'code' => 401,
            ]);
            return $this->sendFailedLoginResponse($request);
        }
        // 重写登录时需要验证的字段

        $credentials = $this->credentials($request);

        if ($this->guard('api')->attempt($credentials, $request->has('remember'))) {
            return $this->sendLoginResponse($request);
        }

        return $this->setStatusCode(401)->failed('登录失败');
    }

    // 退出登录
    public function logout(Request $request)
    {
        if (Auth::guard('api')->check()){

            Auth::guard('api')->user()->token()->revoke();

        }

        return $this->message('退出登录成功');
    }

    // 第三方登录
    public function redirectToProvider($driver) {

        if (!in_array($driver,['qq','wechat'])){

            throw new NotFoundHttpException;
        }

        return Socialite::driver($driver)->redirect();
    }

    // 第三方登录回调
    public function handleProviderCallback($driver) {

        $user = Socialite::driver($driver)->user();

        $openId = $user->id;

        // 第三方认证
        $db_user = User::where('xxx',$openId)->first();

        if (empty($db_user)){

            $db_user = User::forceCreate([
                'phone' => '',
                'xxUnionId' => $openId,
                'nickname' => $user->nickname,
                'head' => $user->avatar,
            ]);

        }
        // 直接创建token

        $token = $db_user->createToken($openId)->accessToken;

        return $this->success(compact('token'));

    }

    //wechat小程序登录颁发token

    public function weappLogin()
    {
        $miniProgram = \EasyWeChat::miniProgram();

        $responseMini = $miniProgram->auth->session(request('code'));

        $user = User::where('open_id',$responseMini['openid'])->first();

        if (empty($user)){

            $attributes = array_merge(['token' => str_random(32), 'open_id' => $responseMini['openid']], request());

            $user = User::forceCreate($attributes);

        }
        //小程序前端可能获取的微信信息发生了变化
        $user->nickname = request('nickname');
        $user->head_img = request('head_img');
        $user->city = request('city');
        $user->save();

        // 直接创建token
        $token = $user->createToken($responseMini['openid'])->accessToken;

        $user->access_token = $token;

        return $this->success($user);
    }


    //调用认证接口获取授权码
    protected function authenticateClient(Request $request)
    {
        $credentials = $this->credentials($request);

        // 个人感觉通过.env配置太复杂，直接从数据库查更方便
        $password_client = Client::query()->where('password_client',1)->latest()->first();

        $request->request->add([
            'grant_type' => 'password',
            'client_id' => $password_client->id,
            'client_secret' => $password_client->secret,
            'username' => $credentials['phone'],
            'password' => $credentials['password'],
            'scope' => ''
        ]);

        $proxy = Request::create(
            'oauth/token',
            'POST'
        );

        $response = \Route::dispatch($proxy);

        return $response;
    }

    protected function authenticated(Request $request)
    {
        return $this->authenticateClient($request);
    }

    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        return $this->authenticated($request);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $msg = $request['errors'];
        $code = $request['code'];
        return $this->setStatusCode($code)->failed($msg);
    }
}
