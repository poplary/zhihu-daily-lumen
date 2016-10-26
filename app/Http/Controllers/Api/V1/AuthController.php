<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use App\Http\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Dingo\Api\Exception\ValidationHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AuthController extends BaseController
{
    /**
     * 用户注册.
     *
     * @param Request $request 请求
     *
     * @return \Dingo\Api\Http\Response 注册结果
     */
    public function register(Request $request)
    {
        $credentials = $request->only('email', 'password', 'password_confirmation');

        // 数据验证
        $validator = app('validator')->make($credentials, User::$registerRules);

        // 不符合数据验证规则
        if ($validator->fails()) {
            throw new ValidationHttpException($validator->errors());
        }

        // 用户注册数据存入数据库
        $user = new User();

        $user->email = $request->email;
        $user->password = app('hash')->make($request->password);
        $user->is_active = false;

        $data = $user->save();

        // 返回注册结果
        if (!$data) {
            throw new HttpException('注册失败');
        }

        return $this->response->created(null, ['message' => '注册成功，请等待激活！']);
    }

    /**
     * 认证操作.
     *
     * @param Request $request 请求数据
     *
     * @return \Dingo\Api\Http\Response 认证结果，若成功，返回 token
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // 验证格式
        $validator = app('validator')->make($credentials, User::$authRules);

        // 不符合数据验证规则
        if ($validator->fails()) {
            throw new ValidationHttpException($validator->errors());
        }

        // 验证用户是否存在
        $user = User::where('email', '=', $credentials['email'])->first();
        if (!$user) {
            throw new NotFoundHttpException('用户不存在');
        }

        // 验证用户是否激活
        if (!$user->is_active) {
            throw new AccessDeniedHttpException('用户未激活');
        }

        // 验证账号密码，生成 token
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                throw new BadRequestHttpException('密码错误或账号出错');
            }
        } catch (JWTException $e) {
            throw new HttpException('创建Token失败');
        }

        // 返回认证信息
        return $this->response->item($user, new UserTransformer())->setMeta(['message' => '认证成功', 'token' => $token])->setStatusCode(200);
    }
}
