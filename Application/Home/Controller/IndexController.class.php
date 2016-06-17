<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	$map = array();
    	$map['area'] = array('eq', '奉贤');
    	$map['result'] = array('eq', '判决结案');
    	$map['year'] = array('eq', '2015');
    	$records = M('knowledge')->where($map)->select();
    	$this->count = count($records);
    	$this->records = array_slice($records, 0, 10);
    	$this->display();
    }

    public function points(){
        $data = M('knowledge')->select();
        for ($i = 0; $i < count($data); $i++) { 
            $data[$i]['x'] = floatval($data[$i]['x']);
            $data[$i]['y'] = floatval($data[$i]['y']);
        }
        echo json_encode($data);
    }

    public function search(){
    	$map = array();
    	$map['area'] = array('eq', I('area'));
        $map['result'] = array('eq', I('result'));
        $map['year'] = array('eq', I('year'));
    	$records = M('knowledge')->where($map)->select();
    	$count = count($records);
    	$records = array_slice($records, 0, 10);
    	echo json_encode(array('count'=>$count,'data'=>$records));
    }

    public function knowledge(){
        $knowledge = M('knowledge')->where(array('id'=>I('id')))->find();
        echo $knowledge['graph'];
    }

    public function fees(){
        $fees = M('knowledge')->where(array('id'=>I('id')))->find();
        echo json_encode(array('fees'=>$fees['fees']));
    }

    public function cases(){
        $map = array();
        $map['id'] = array('neq', I('id'));
        $case = M('knowledge')->where(array('id'=>I('id')))->find();
        $cases = M('knowledge')->where($map)->select();
        $tmp = array();
        for ($i = 0; $i < count($cases); $i++) { 
            $derX = floatval($cases[$i]['x']) - floatval($case['x']);
            $derY = floatval($cases[$i]['y']) - floatval($case['y']);
            $dist = sqrt($derX * $derX + $derY * $derY);
            array_push($tmp, array($dist, $cases[$i]));
        }

        usort($tmp, function($a, $b) {
            if ($a[0] == $b[0]) {
                return 0;   
            }
            elseif ($a[0] > $b[0]) {
                return 1;
            }
            else {
                return -1;
            }
        });

        $cases = array_slice($tmp, 0, 10);
        echo json_encode($cases);
    }
}