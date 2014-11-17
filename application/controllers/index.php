<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 套件的入口文件
 * 
 *
 * @copyright  版权所有(C) 2014-2014 沈阳工业大学ACM实验室 沈阳工业大学网络管理中心 *Chen
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL3.0 License
 * @version    2.0
 * @link       http://acm.sut.edu.cn/
 * @since      File available since Release 2.0
*/
         
class Index extends CI_Controller{
    function __construct() {
        parent::__construct();
    }
    
    /**    
     *  @Purpose:    
     *  安装界面和登录界面的切换    
     *  @Method Name:
     *  Index()    
     *  @Parameter: 
     *     
     *  @Return: 
     *  
     * :WARNING: 请不要地址末尾加上index.php打开 :WARNING:
    */
    public function Index()
    {           
        $this->load->model('index_model');  
        $this->load->view('login_view');
    }
    
    /**    
     *  @Purpose:    
     *  密码验证并后续处理    
     *  @Method Name:
     *  PassCheck()    
     *  @Parameter: 
     *  post db_username 数据库用户名
     *  post db_password 数据库密码    
     *  @Return: 
     *  json 状态码及状态说明
    */
    public function PassCheck(){
        $this->load->library('session');
        $this->load->model('index_model');
        
        if (!$this->input->post('db_username', TRUE)){
            echo json_encode(array(
                '0' => -1,
                '1' => '请填写数据库用户名'
            ));
            exit();
        }
        
        
        if (!$this->input->post('db_password', TRUE)){
            echo json_encode(array(
                '0' => -2,
                '1' => '请填写数据库密码'
            ));
            exit();
        }
        
        
        //过滤注入
        if (strpos($this->input->post('db_username', TRUE), '\'') !== FALSE ||
                strpos($this->input->post('db_username', TRUE), '"') !== FALSE ||
                strpos($this->input->post('db_username', TRUE), '\\') !== FALSE ||
                strpos($this->input->post('db_password', TRUE), '\'') !== FALSE ||
                strpos($this->input->post('db_password', TRUE), '"') !== FALSE ||
                strpos($this->input->post('db_password', TRUE), '\\') !== FALSE){
            echo json_encode(array(
                '0' => -3,
                '1' => '用户名密码不支持特殊字符'
            ));
            exit();
        }
        
                
        if ($this->index_model->dbLogin($this->input->post('db_username', TRUE), $this->input->post('db_password', TRUE))){
            
            $this->session->set_userdata('db_username', $this->input->post('db_username', TRUE));
            $this->session->set_userdata('db_password', $this->input->post('db_password', TRUE));
            echo json_encode(array(
                '0' => 1
            ));
            exit();
        }
        
        return 0;
    }

}