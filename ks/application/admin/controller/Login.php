<?php
namespace app\admin\controller;
use think\Request;
use app\admin\common\Base;
use app\admin\model\Admin as AdminModel;
use think\Session;
class Login extends Base
{
    //渲染登录界面
    public function index()
    {
      $this ->alreadyLogin();
      return $this->view->fetch('login');
    }
    //验证用户身份
    public function checkLogin(Request $request)
    {
      $status=0;
      $result='验证失败';
      $data=$request->param();

      //规则设定
      $rule=[
        'adminName|用户名'=>'require',
        'adminPassword|密码'=>'require',
        'verification|验证码'=>'require|captcha',
      ];
/*自定义规则，上面是默认规则
       $rule=[
        'adminName|用户名'=>['require'=>用户名不能为空，请检查],
        'adminPassword|密码'=>['require'=>密码不能为空，请检查],
        'verification|验证码'=>['require'=>验证码不能为空，请检查],
      ];
*/
      //验证数据 $this->validate($data, $rule, $msg)
      $result=$this->validate($data,$rule);
      //验证通过执行
      if($result===true)
      {
      $adminName=$data['adminName'];
      $adminPassword=md5($data['adminPassword']);

      //在admin表中进行查询:以用户为条件
      $map = ['admin_name'=> $adminName];
      $admin = AdminModel::get($map);
      //将用户名与密码分开验证
      //如果没有查询到该用户
      if (is_null($admin)){
          //设置返回信息
          $result='用户名不正确';
      } else if($admin ->admin_password!=$adminPassword) 
      {
          //设置一下密码不正确的提示信息
          $result='密码不正确';
      } else {
          //如果用户名和密码都通过了验证,表明是合法用户
          //修改一下返回的信息
          $status=1;
          $result='验证通过,请点击确定进入后台';


          $admin->setInc('admin_count');
          $admin->save(['admin_last_time'=> time()]);

          Session::set('user_id',$adminName);
          Session::set('user_info',$admin->toArray());
      }
    }

      return ['status'=>$status,'message'=>$result];


    }
    //退出登录
    public function logout()
    {
      //删除当前用户session值
        Session::delete('user_id');
        Session::delete('user_info');

        //执行成功,并返回登录界面
        $this -> success('注销成功,正在返回...','login/index');
    }

}
