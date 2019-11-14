<?php
namespace app\admin\controller;
use think\Request;
use app\admin\common\Base;
use app\admin\model\Admin as AdminModel;
class Register extends Base
{
    //渲染登录界面
    public function index()
    {
      return $this->view->fetch('register');
    }

    public function register(Request $request)
    {
      $data=$request->param();
      $rule = [
                'adminName|用户名' =>'email|require',
                'password|密码' =>'require|max:16|min:6',
                'adminPassword|确认密码' =>'require|max:16|min:6',  
                'adminEmail|邮箱' =>'email|require',
               ];
      $validate = new Validate($rule);
      $result=$validate->check($data);
      if($result===true){
      $adminName=$data['adminName'];
      $adminEmail=$data['adminEmail'];
      $adminPassword=md5($data['adminPassword']);
      $map = ['admin_name'=> $adminName];
      $admin = AdminModel::get($map);
      if (is_null($admin)){
       $admin= new AdminModel;
       $admin->admin_name=$adminName;
       $admin->admin_email=$adminEmail;
       $admin->admin_password=$adminPassword;
       $admin->save(['admin_create_time'=> time()]);
       $res=$admin->save();
                if (!is_null($res)) {
                    $status=1;
                    $result='注册成功！返回登录界面！';
                } else {
                    $status = 0;
                    $message = '注册失败';
                }
      }
      else
      {
        $status=0;
        $result='该用户名已被使用，请重新起名';
      }
      }
      else
      {
        $message=$validate->getError();
      }
      return ['status'=>$status,'message'=>$result];
    }
    }


}
