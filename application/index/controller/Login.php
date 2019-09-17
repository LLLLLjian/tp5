<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Validate;
use think\Db;
use app\index\model\Loginlogs;

class Login extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        // 临时关闭当前模板的布局功能
        $this->view->engine->layout(false);
        return $this->fetch();
    }

    /**
     * 登录
     */
    public function login()
    {
        $res = array(
            'code' => 0,
            'msg' => '登录成功'
        );
        $username = input('username');
        $password1 = input('password');
        if (!$username || !$password1) {
            $res['code'] = -1;
            $res['msg'] = "用户名、密码不能为空";
            echo json_encode($res);
            return;
        }
        $cms_password = "Llllljian";
        if (request()->isPost()) {

            if (!captcha_check(input('code'))) {
                $res['code'] = -1.5;
                $res['msg'] = "验证码错误";
                echo json_encode($res);
                return;
            }

            $password = md5($password1 . $cms_password);
            if ($username) { // 验证用户名
                $rsu = Db::name('user')->where('username', $username)->find();
                $countu = count($rsu);
                if (!$countu) {
                    $res['code'] = -2;
                    $res['msg'] = "用户名不存在";
                    echo json_encode($res);
                    return;
                }
            }

            if ($username && $password1) { // 验证密码
                $rsp = Db::name('user')->where('username', $username)
                    ->where('password', $password)
                    ->find();
                if (empty($rsp)) {
                    $res['code'] = -3;
                    $res['msg'] = "密码错误";
                    echo json_encode($res);
                    return;
                }
            }

            session('id', $rsp['id']);
            session('username', $rsp['username']);
            session('roleslevel', $rsp['roleslevel']);

            $loginLogsModel = new Loginlogs();
            $loginLogsModel->addLoginLogs();

            echo json_encode($res);
            return;
        }
    }

    // 退出登录
    public function logout()
    {
        session(null);
        $this->redirect('index/login/index');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param \think\Request $request            
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param int $id            
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param int $id            
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param \think\Request $request            
     * @param int $id            
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param int $id            
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
