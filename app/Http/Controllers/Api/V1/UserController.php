<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    /**
     * 获取用户信息.
     *
     * @param Request $request 请求数据
     * @param int     $id      用户id
     *
     * @return json 用户数据
     */
    public function profile(Request $request, $id = null)
    {
        if (is_null($id)) {
            $id = $this->getAuthenticatedUser()->id;
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json($this->returnData(40101, '用户不存在'), 404);
        }

        return response()->json($this->returnData(0, '获取成功', $user), 200);
    }
}
