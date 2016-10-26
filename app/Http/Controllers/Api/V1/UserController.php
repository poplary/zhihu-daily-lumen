<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Http\Transformers\UserTransformer;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends BaseController
{
    /**
     * 获取用户信息.
     *
     * @param Request $request 请求数据
     * @param int     $id      用户id
     *
     * @return \Dingo\Api\Http\Response 用户数据
     */
    public function profile(Request $request, $id = null)
    {  
        if (is_null($id)) {
            $id = $this->getAuthenticatedUser()->id;
        }

        $user = User::find($id);

        if (!$user) {
            throw new NotFoundHttpException('用户不存在.');
        }

        return $this->response->item($user, new UserTransformer());
    }
}
