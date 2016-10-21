<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use JWTAuth;

class BaseController extends Controller
{
    use Helpers;

    /**
     * 通过 token 判断用户.
     *
     * @return \App\Models\User $user 已认证的用户信息数据
     */
    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }

        // 返回用户数据
        return $user;
    }

    /**
     * 返回数据的格式
     * @return array 返回数据的数组
     */
    /**
     * 返回数据的格式
     * @param  int    $status  状态码
     * @param  string $message 返回的信息
     * @param  array  $data    附加的数据
     * @return array  返回数据的数组
     */
    public function returnData($status, $message, $data=null)
    {
        $returnArr = [
            'status' => (int) $status,
            'message' => $message,
        ];

        if($data)
            $returnArr['data'] = $data;

        return $returnArr;
    }
}
