<!DOCTYPE html>  
<html>  
    <head>  
        <title></title>
        <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
        <script src="http://libs.baidu.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
        <link href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">         
    </head>
    <body>
        <br/>
        <div class="panel panel-info">
            <div class="panel-heading">常规设置</div>
            <div class="panel-body">
                <p><a data-toggle="modal" data-target=".modal-passupdate">修改密码</a></p>
            </div>
        </div>  
        <br/>
        <div class="panel panel-info">
            <div class="panel-heading">数据库服务器</div>
            <div class="panel-body">
                <p>服务器：<?= $db_info['host_info']?></p>
                <p>服务器类型：MySQL</p>
                <p>服务器版本：<?= $db_info['server_info']?></p>
                <p>协议版本：<?= $db_info['proto_info']?></p>
                <p>用户：<?= $this->session->userdata('db_username')?></p>
            </div>
        </div>         
        <br/>
        <div class="panel panel-info">
            <div class="panel-heading">网站服务器</div>
            <div class="panel-body">
                <p>数据库客户端版本：<?= $db_info['client_info']?></p>
            </div>
        </div>         
        <div class="panel panel-info">
            <div class="panel-heading">WSPDM</div>
            <div class="panel-body">
                <p><a href="https://github.com/SUTFutureCoder/WSPDM" target="_blank" >项目主页</a></p>
                <p><a href="https://github.com/SUTFutureCoder/WSPDM/wiki/" target="_blank" >wiki</a></p>
            </div>
        </div>  
        <div class="modal fade modal-passupdate" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">修改密码</h4>
                    </div>
                    <div class="modal-body">  
                        <form role="form">
                            <div class="form-group">
                                <label for="old_pw">旧密码</label>
                                <input type="password" class="form-control" id="old_pw">
                            </div>
                            <div class="form-group">
                                <label for="new_pw">新密码</label>
                                <input type="password" class="form-control" id="new_pw" placeholder="Password">
                            </div>
                            <div class="form-group">
                                <label for="new_pw_confirm">新密码重复</label>
                                <input type="password" class="form-control" id="new_pw_confirm" placeholder="Password">
                            </div>                            
                        </form>
                    </div>
                    <div class="modal-footer">      
                        <button type="button" onclick="PW_update()" class="btn btn-danger">修改</button>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </body>
    <script>
        
        //接收母窗口传来的值
        function MotherResultRec(data) {
            if (1 == data[2]) {
                $("form").each(function () {
                    this.reset();
                });
                
            }
            alert(data[3]);
            if (data[4]) {
                $("#" + data[4]).focus();
            }
        }
        function PW_update(){
            var data = new Array();
            data['src'] = location.href.slice((location.href.lastIndexOf("/")));
            data['api'] = location.href + '/UpdatePW';
            data['data'] = '{"user_key" : "<?= $user_key ?>", "user_name" : "<?= $user_name ?>", "old_pw" : "' + $("#old_pw").val() + '",';
            data['data'] += '"new_pw" : "' + $("#new_pw").val() + '", "new_pw_confirm" : "' + $("#new_pw_confirm").val() + '"}';
            parent.IframeSend(data);
        }
    </script>
</html>