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
        
        $conn = mysqli_connect('localhost', $CI->session->userdata('db_username'), $CI->session->userdata('db_password'));
        if (mysqli_connect_errno($conn)){
            return 0;
        } else {
            return $conn;
        }
    }
    
    /**    
     *  @Purpose:    
     *  获取数据库、及各个表
     *  
     *  @Method Name:
     *  getDbList($conn)    
     *  @Parameter: 
     *  $conn 数据库连接
     *  @Return: 
     *  数据库列表
     * 
    */
    public function getDbList($conn){
        error_reporting(0);
        $sql = 'SHOW DATABASES';
        $temp = array();
        if ($result = mysqli_query($conn, $sql)){
            while ($obj = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                $sql_tables = 'SHOW TABLES FROM ' . $obj['Database'];
                $result_tables = mysqli_query($conn, $sql_tables);
                while ($obj_tables = mysqli_fetch_array($result_tables, MYSQL_ASSOC)){
                    $temp = array_values($obj_tables);                    
                    $data[$obj['Database']][] = $temp[0];
                }                
            }
            mysqli_free_result($result);
        }
//        var_dump($data);
        return $data;

    }
    
    /**    
     *  @Purpose:    
     *  获取Mysql基础信息
     *  
     *  @Method Name:
     *  getDbInfo($conn)    
     *  @Parameter: 
     *  $conn 数据库连接
     *  @Return: 
     *  数据库列表
     * 
    */
    public function getDbInfo($conn){
        error_reporting(0);
        $data = array();
        //字符集对象
        $data['char_set'] = mysqli_get_charset($conn);
        //MySQL 客户端库版本
        $data['client_info'] = mysqli_get_client_info($conn);
        //将 MySQL 客户端库版本作为整数返回。
        $data['client_version'] = mysqli_get_client_version($conn);
        //MySQL 服务器主机名和连接类型。
        $data['host_info'] = mysqli_get_host_info($conn);
        //MySQL 协议版本。
        $data['proto_info'] = mysqli_get_proto_info($conn);
        //MySQL 服务器版本。      
        $data['server_info'] = mysqli_get_server_info($conn);
        //将 MySQL 服务器版本作为整数返回。
        $data['server_version'] = mysqli_get_server_version($conn);
        
        return $data;

    }
    
    
    
}