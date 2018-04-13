<?php
/**
 * 还未选择的课程
 */
namespace app\index\controller;
use think\Controller;
class Unselected extends Important{
    /**
     * 未选择的课程列表
     */
    public function lst(){
        $s_num = session('id');
        $data1= \think\Db::name("student")->where('s_num',$s_num)->find();
        $major = $data1['s_major'];
        $grade = $data1['s_grade'];
        $data2 = \think\Db::table('system_major')->field('m_id')->where('major_name',$major)->where('major_grade',$grade)->find();
        $m_id = $data2['m_id']; 
        $data3 = \think\Db::table("system_"."$m_id"."_course")->field('c_id,lang')->where('s_num',$s_num)->where('status',0)->select();  
        $c_id = array_map('array_shift', $data3);
        $arr = array_map('array_pop', $data3);
        $str = trim(trim(array_pop($arr),'('),')');
        $len = strlen($str)-1;
        if($str){
            $lang = explode(',',$str);
            $this->assign('lang',$str{$len});
        }
        $data4 = \think\Db::name("course")->where('c_id',"in",$c_id)->select();
        
        $this->assign('course',$data4);
        
        return $this->fetch();
    }
    public function choose(){
        $s_num = session('id');
        $data1= \think\Db::name("student")->where('s_num',$s_num)->find();
        $major = $data1['s_major'];
        $grade = $data1['s_grade'];
        $data2 = \think\Db::table('system_major')->field('m_id')->where('major_name',$major)->where('major_grade',$grade)->find();
        $m_id = $data2['m_id']; 
        $c_id =  input("c_id");
        $id = $s_num . $c_id;
        $db=\think\Db::table("system_"."$m_id"."_course")->where('id',$id)->update(['status'=>1]);
        if($db){
            return $this->success('选择成功！','studentcourse/lst');
        }else{
            return $this->redirect('lst');
        }
    }
    /**
     * 未选择的课程详细信息
     */
    public function details(){
        $c_id = input('c_id');
        $data = \think\Db::name("course")->where('c_id',$c_id)->find();
        $this->assign('course',$data);
        return $this->fetch();
    }
}