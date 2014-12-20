<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 数据库基础信息
 * 
 * 
 *
 * @copyright  版权所有(C) 2014-2014 沈阳工业大学ACM实验室 沈阳工业大学网络管理中心 *Chen
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL3.0 License
 * @version    2.0
 * @link       http://acm.sut.edu.cn/
 * @since      File available since Release 2.0
*/
class BasicDbInfo extends CI_Controller{
    function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->load->library('session');
        $this->load->library('database');
        $this->load->library('secure');
        
        if (!$this->session->userdata('db_username')){
            header("Content-Type: text/html;charset=utf-8");
            echo '<script>alert("您的会话已过期，请重新登录")</script>';
            echo '<script>window.location.href= \'' . base_url() . '\';</script>'; 
        }
        $this->load->view('BasicDbInfoView', array('db_info' => $this->database->getDbInfo($this->database->dbConnect()),
                                                    'user_key' => $this->secure->CreateUserKey($this->session->userdata('db_username'), $this->session->userdata('db_password')),
                                                    'user_name' => $this->session->userdata('db_username')));
    }   
    
    /**    
     *  @Purpose:    
     *  修改数据库密码
     *  
     *  @Method Name:
     *  UpdatePW()
     *  @Parameter: 
     *  $()
     *  
     *  @Return: 
     *  
     * 
    */
    public function UpdatePW(){        
        $this->load->library('secure');
        $this->load->library('database');
        $this->load->library('data');
        
        if ($this->input->post('new_pw_confirm', TRUE) != $this->input->post('new_pw', TRUE)){
            $this->data->Out('iframe', $this->input->post('src', TRUE), -6, '两次输入的密码不一致', 'new_pw');
        }
                
        $db = array();
        if ($this->input->post('user_name', TRUE) && $this->input->post('user_key', TRUE)){
            $db = $this->secure->CheckUserKey($this->input->post('user_key', TRUE));
            if ($this->input->post('user_name', TRUE) != $db['user_name']){
                $this->data->Out('iframe', $this->input->post('src', TRUE), -1, '密钥无法通过安检');
            }
        } else {
            $this->data->Out('iframe', $this->input->post('src', TRUE), -7, '未检测到密钥');
        }
        
        if ($this->input->post('old_pw', TRUE) != $db['password']){
            $this->data->Out('iframe', $this->input->post('src', TRUE), -5, '旧密码错误', 'old_pw');
        }
        
        //过滤注入
        if (strpos($this->input->post('new_pw', TRUE), '\'') !== FALSE ||
                strpos($this->input->post('new_pw', TRUE), '"') !== FALSE ||
                strpos($this->input->post('new_pw', TRUE), '\\') !== FALSE){
            $this->data->Out('iframe', $this->input->post('src', TRUE), -4, '密码不支持特殊字符', 'new_pw');
        }
        
        $conn = $this->database->dbConnect($db['user_name'], $db['password']); 
        if (!$conn){
            $this->data->Out('iframe', $this->input->post('src', TRUE), -2, '用户名密码错误', 'old_pw');
        } else {
            if (!$this->secure->UpdateUserPass($conn, $db['user_name'], $this->input->post('new_pw', TRUE))){
                $this->data->Out('iframe', $this->input->post('src', TRUE), -3, '修改失败');
            }  else {
                $this->data->Out('iframe', $this->input->post('src', TRUE), 1, '修改成功');
            } 
        }
        
    }
}