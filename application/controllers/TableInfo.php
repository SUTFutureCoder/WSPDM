<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 获取、操作表数据
 * 
 * 
 *
 * @copyright  版权所有(C) 2014-2014 沈阳工业大学ACM实验室 沈阳工业大学网络管理中心 *Chen
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL3.0 License
 * @version    2.0
 * @link       http://acm.sut.edu.cn/
 * @since      File available since Release 2.0
*/
class TableInfo extends CI_Controller{
    function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->load->library('session');
        $this->load->library('secure');
        $this->load->library('database');
        $this->load->model('tableinfo_model');
        
        $conn = $this->database->dbConnect($this->session->userdata('db_username'), $this->session->userdata('db_password'));
        
        $data = $this->tableinfo_model->getTableData($conn, $this->input->get('db', TRUE), $this->input->get('t', TRUE), 0, 30);
        $data['table'] = htmlentities($this->input->get('t', TRUE), ENT_QUOTES);
        $data['database'] = htmlentities($this->input->get('db', TRUE), ENT_QUOTES);
        $data['start'] = 0;
        $data['end'] = 29;
        
        $this->load->view('TableInfoView', array('data' => $data,
                            'user_key' => $this->secure->CreateUserKey($this->session->userdata('db_username'), $this->session->userdata('db_password')),
                            'user_name' => $this->session->userdata('db_username')));
    } 
       
    /**    
     *  @Purpose:    
     *  执行SQL语句   
     *  @Method Name:
     *  getTableData()
     *  @Parameter: 
     *  POST user_name 数据库用户名
     *  POST user_key 用户密钥
     *  POST src      目标地址
     *  POST database 操作数据库
     *  POST sql      SQL指令
     *  
     *  @Return: 
     *  状态码|说明
     *      data
     * 
     *  
    */ 
    public function ExecSQL(){
        $this->load->library('secure');
        $this->load->library('database');
        $this->load->library('data');
        
        $db = array();
        if ($this->input->post('user_name', TRUE) && $this->input->post('user_key', TRUE)){
            $db = $this->secure->CheckUserKey($this->input->post('user_key', TRUE));
            if ($this->input->post('user_name', TRUE) != $db['user_name']){
                $this->data->Out('iframe', $this->input->post('src', TRUE), -1, '密钥无法通过安检');
            }
        } else {
            $this->data->Out('iframe', $this->input->post('src', TRUE), -2, '未检测到密钥');
        }
        
        if (!$this->input->post('sql', TRUE)){
            $this->data->Out('iframe', $this->input->post('src', TRUE), -3, 'SQL命令不能为空');
        }
        
        //连接数据库
        $conn = $this->database->dbConnect($db['user_name'], $db['password']);
        
        //过滤数据库名
        $database = mysqli_real_escape_string($conn, $this->input->post('database', TRUE));
        
        //连接数据库，非记录模式
        $sql = 'USE ' . $database;
        $this->database->execSQL($conn, $sql, 0);
        
        //执行SQL语句，为记录模式
        $sql = $this->input->post('sql', TRUE);
        $data = $this->database->execSQL($conn, $sql, 1);
        
        $this->data->Out('iframe', $this->input->post('src', TRUE), 1, 'ExecSQL', $data);
               
    }
    
    
    
    /**    
     *  @Purpose:    
     *  删除列   
     *  @Method Name:
     *  DeleCol()
     *  @Parameter: 
     *  POST user_name 数据库用户名
     *  POST user_key 用户密钥
     *  POST src      目标地址
     *  POST database 操作数据库
     *  POST table    操作表
     *  POST col_name   列名
     * 
     *  @Return: 
     *  状态码|说明
     *      data
     * 
     *  
    */ 
    public function DeleCol(){
        $this->load->library('secure');
        $this->load->library('database');
        $this->load->library('data');
        
        $db = array();
        if ($this->input->post('user_name', TRUE) && $this->input->post('user_key', TRUE)){
            $db = $this->secure->CheckUserKey($this->input->post('user_key', TRUE));
            if ($this->input->post('user_name', TRUE) != $db['user_name']){
                $this->data->Out('iframe', $this->input->post('src', TRUE), -1, '密钥无法通过安检');
            }
        } else {
            $this->data->Out('iframe', $this->input->post('src', TRUE), -2, '未检测到密钥');
        }
        
        //连接数据库
        $conn = $this->database->dbConnect($db['user_name'], $db['password']);
        
        //过滤数据库名
        $database = mysqli_real_escape_string($conn, $this->input->post('database', TRUE));
        $table = mysqli_real_escape_string($conn, $this->input->post('table', TRUE));
        //过滤表名
        $col_name = mysqli_real_escape_string($conn, $this->input->post('col_name', TRUE));

        //连接数据库，非记录模式
        $sql = 'USE ' . $database;
        $this->database->execSQL($conn, $sql, 0);
        
        //执行SQL语句，为记录模式
        //ALTER TABLE `activity` DROP `act_section`
        $sql = 'ALTER TABLE ' . $table . ' DROP COLUMN ' . $col_name . ' ';
        $data = $this->database->execSQL($conn, $sql, 1);
        $data['col_name'] = $col_name;
        
        $this->data->Out('iframe', $this->input->post('src', TRUE), 1, 'DeleCol', $data);
               
    }
    
    /**    
     *  @Purpose:    
     *  删除列   
     *  @Method Name:
     *  DeleCol()
     *  @Parameter: 
     *  POST user_name 数据库用户名
     *  POST user_key 用户密钥
     *  POST src      目标地址
     *  POST database 操作数据库
     *  POST table    操作表
     *  POST col_name   列名
     * 
     *  @Return: 
     *  状态码|说明
     *      data
     * 
     *  
    */ 
    public function B_DeleCol(){
        
    }
}