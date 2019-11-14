<?php
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use think\Session;
use app\admin\model\Course as CourseModel;
class Course extends Base
{
    public function courselist()
    {
    	$admin_id=Session::get('user_info.admin_id');
    	$courselist=CourseModel::all(['admin_id'=>$admin_id]);
        //获取记录数量
        $count=CourseModel::count();
        $this -> view -> assign('courselist', $courselist);
        $this -> view -> assign('count', $count);
        return $this->view->fetch('courselist');
    }
    public function course_add()
    {
        return $this->view->fetch('course_add');
    }
    public function add(Request $request)
    {
        $admin_id=Session::get('user_info.admin_id');
        $data=$request->param();
        $course_name=$data['course_name'];
        $course= new CourseModel;
        $course->admin_id=$admin_id;
        $course->save(['course_create_time'=> time()]);
        $course->course_name=$data['course_name'];
        $result=$course->save();
        //设置返回数据
        if (!is_null($result)) {
            $status=1;
            $message='添加成功';
        } else {
            $status = 0;
            $message = '添加失败';
        }
        return ['status'=>$status, 'message'=>$message];
    }
    public function del($course_id)
    {
        $res=CourseModel::destroy($course_id);
        if (!is_null($res)) {
            $status = 1;
            $message = '删除成功';
        } 
        else 
        {
                    $status = 0;
        }
        return ['status'=>$status, 'message'=>$message];
    }
}
