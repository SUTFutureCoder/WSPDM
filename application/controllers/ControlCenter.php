<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 
 * 控制面板
 * 
 *
 * @copyright  版权所有(C) 2014-2014 沈阳工业大学ACM实验室 沈阳工业大学网络管理中心 *Chen
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL3.0 License
 * @version    2.0
 * @link       http://acm.sut.edu.cn/
 * @since      File available since Release 2.0
*/
class ControlCenter extends CI_Controller{
    function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->load->library('session');
        $this->load->library('database');
        
        if (!$this->session->userdata('db_username')){
            header("Content-Type: text/html;charset=utf-8");
            echo '<script>alert("'. $this->session->userdata('db_username') .'")</script>';
            echo '<script>window.location.href= \'' . base_url() . '\';</script>'; 
        }
        $conn = $this->database->dbConnect();                
        $db_list = $this->database->getDbList($conn);
//        var_dump($db_db_list);
        
        //获取Mysql基础信息
        $db_info = $this->database->getDbInfo($conn);
        
        $this->load->view('ControlCenterView', array('db_list' => $db_list, 'db_info' => $db_info));
        
    }
}