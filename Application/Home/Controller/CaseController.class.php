<?php
namespace Home\Controller;
use Think\Controller;
class CaseController extends Controller {
    public function index(){
    	$this->display();
    }

    public function data(){
        $cases = M('features')->query("select distinct(title) from features");
        echo json_encode(array('data'=>$cases));
    }

    public function detail(){
        $record = M('features')->where(array('title'=>I('title'),'category'=>'庭审笔录'))->find();
        if ($record) {
            $record['graph'] = json_decode($record['graph']);
        }
        else {
            $record = array('id'=>'');
        }
        $paper = M('features')->where(array('title'=>I('title'),'category'=>'法律文书'))->find();
        if ($paper) {
            $paper['graph'] = json_decode($paper['graph']);
        }
        else {
            $paper = array('id'=>'');
        }
        echo json_encode(array('record'=>$record, 'paper'=>$paper));
    }

    public function note(){
        M('note')->data(array('title'=>I('title'),'category'=>I('category'),'note'=>I('note'),'timestamp'=>time()))->add();
        echo json_encode(array('result'=>'ok'));
    }
}