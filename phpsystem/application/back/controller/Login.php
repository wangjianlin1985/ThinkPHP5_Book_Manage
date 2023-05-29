<?php
namespace app\back\controller;

use app\front\controller\Base;

class Login extends Base {

    public function index() {
        return $this->fetch("login");
    }

    //后台请求验证用户名和密码
    public function auth() {
        $success = false;
        $message = "";
        $username = input("username");
        $password = input("password");


        $captcha = new \think\captcha\Captcha();
        if (!$captcha->check(input("code"))) {
            $message = "验证码输入错误！";
            $this->writeJsonResponse($success,$message);
            return;
        }

        $admin = db('admin')->find($username);//查询数据库
        if($admin && $admin["password"] == $password) {
            $success = true;
            session("username",$username);
            $this->writeJsonResponse($success,$message);
        } else {
            $message = "用户名或密码错误！";
            $this->writeJsonResponse($success,$message);
            return;
        }
    }

    /*后台管理员修改密码*/
    public function changePassword() {
        if($this->request->isPost()) {
            $oldPassword = input("oldPassword");
            $newPassword = input("newPassword");
            $newPassword2 = input("newPassword2");
            if($oldPassword == null || $oldPassword == "") {
                echo "请输入原来的密码";
                return;
            }
            if($newPassword == null || $newPassword == "") {
                echo "请输入新密码";
                return ;
            }
            if($newPassword != $newPassword2) {
                echo "两次输入的新密码不一致";
                return;
            }

            $admin = db('admin')->find(session("user_name"));//查询数据库
            if($admin["password"] != $oldPassword) {
                echo "原来的密码不正确";
                return;
            }
            $admin["password"] = $newPassword;
            db("admin")->update($admin);
            echo "密码修改成功";

        } else {
            return view("login/password_modify");
        }
    }



    public function logout() {
        session(null);
        $this->success("退出成功","Login/index");
    }
}

?>