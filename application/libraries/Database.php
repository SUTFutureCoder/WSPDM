<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 数据库连接、基本信息类
 * 
 * 
 *
 * @copyright  版权所有(C) 2014-2014 沈阳工业大学ACM实验室 沈阳工业大学网络管理中心 *Chen
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt   GPL3.0 License
 * @version    2.0
 * @link       http://acm.sut.edu.cn/
 * @since      File available since Release 2.0
*/
class Database{
    
    /**    
     *  @Purpose:    
     *  登录数据库
     *  
     *  @Method Name:
     *  dbConnect()    
     *  @Parameter: 
     *  
     *  @Return: 
     *  状态码|状态
     *      0|失败连接
     *      $conn|数据库连接
     * 
    */
    public function dbConnect(){
        $CI =& get_instance();
        $data = array();
        $CI->load->library('session');
        
        $conn = mysqli_connect($host, $user, $password);
        if (mysqli_connect_errno($conn)){
            return 0;
        } else {
            return $conn;
        }
    
    }
}