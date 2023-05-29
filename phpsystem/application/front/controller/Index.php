<?php
namespace app\front\controller;

class Index extends Base
{
    /*前台首页*/
    public function index() {
        return $this->fetch();
    }

    /*前台用户登录处理*/
    public function frontLogin() {
        $success = true;
		$message = "";
        session("user_name","aa");
		$this->writeJsonResponse($success,$message);

    }

    /*前台用户退出*/
    public function logout() {
        session("user_name",null);
        $this->success("退出成功","Index/index");
    }
}
?>