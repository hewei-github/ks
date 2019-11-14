<?php
namespace app\admin\controller;
use think\Request;
use app\admin\common\Base;
use app\admin\model\Admin as AdminModel;
use think\Session;
use think\validate;
class Admin extends Base
{
    public function index()
    {
        $admin_id=Session::get('user_info.admin_id');
    	$admin=AdminModel::get(['admin_id'=>$admin_id]);
        //2.将当前管理员的信息赋值给模板
        $this -> view -> assign('admin', $admin);

        //3.渲染模板
        return $this -> view -> fetch('admin');

    }

   	public function update(Request $request)
    {
        $status = 0;
        $message = '更新失败';
        if ($request -> isAjax(true))
        {//获取提交的数据,自动过滤一下空值
            $data=$request->param();
            $rule = [
                    'admin_email|邮箱' =>'email|require',
                    'rep|密码'=>'require|max:16|min:6',
                    ];
            $validate = new Validate($rule);
            $result=$validate->check($data);
            if($result===true){
            $admin = AdminModel::get(['admin_id'=>$data['admin_id']]);
            $admin->admin_email = $data['admin_email'];
            $admin['admin_password']=md5($data['rep']);
            $res = $admin->save();
            $admin->save(['admin_update_time'=> time()]);
                if (!is_null($res)) {
                    $status = 1;
                    $message = '更新成功';
                } else {
                    $status = 0;
                    $message = '更新失败1';
                }
            }
            else
            {
                $message=$validate->getError();
            } 
       }
        
        return json(['status'=>$status, 'message'=>$message]);
    }
}
?>