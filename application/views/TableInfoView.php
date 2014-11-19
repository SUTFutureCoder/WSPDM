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
        <?php if (!$data):?>
        <div class="alert alert-danger" role="alert" id="alert">未操作</div>
        <?php else: ?>
        <div class="alert alert-success" role="alert" id="alert">
            <p>正在显示第<?= $data['start'] ?>-<?= $data['end'] ?>(共操作<?= $data['rows'] ?>行，查询消耗<?= $data['time'] ?>秒)</p>
            <p><?= $data['sql']?></p>
        </div>
        <?php endif; ?>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#view" role="tab" data-toggle="tab">浏览</a></li>
            <li role="presentation"><a href="#struct" role="tab" data-toggle="tab">结构</a></li>
            <li role="presentation"><a href="#sql" role="tab" data-toggle="tab">SQL</a></li>
            <li role="presentation"><a href="#insert" role="tab" data-toggle="tab">插入</a></li>
            <li role="presentation"><a href="#search" role="tab" data-toggle="tab">搜索</a></li>
            <li role="presentation"><a href="#operating" role="tab" data-toggle="tab">操作</a></li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="view">
                <br/>
                <table class="table table-hover table-bordered" id="data_view">
                    <thead>
                        <tr>
                            <th></th>
                            <?php foreach ($data['cols'] as $col_name => $col_type):?>
                            <th><?= $col_name ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($data['data'] as $key => $value): ?>                    
                        <tr>
                            <td><a>编辑</a><a>|</a><a style="color:red">删除</a></td>
                            <?php foreach($value as $table_name => $table_value): ?>   
                                <td><?=$table_value?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                
            </div>
            <div role="tabpanel" class="tab-pane" id="struct">
                <br/>
                <table class="table table-hover table-bordered" id="struct_view">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>操作</th>
                            <th>名字</th>
                            <th>类型</th>
                            <th>长度</th>
                            <th>字符集</th>
                        </tr>
                    </thead>
                    <tbody>                        
                    <?php $i = 0; ?>
                    <?php foreach ($data['cols'] as $col_name => $col_type): ?>                    
                        <tr>
                            <td><?= ++$i ?></td>
                            <td><a  id="dele_col_name_<?= $col_name?>" style="color:red">删除</a></td>
                            <td><?= $col_name ?></td>
                            <td><?= $data['cols'][$col_name]['type']?></td>
                            <td><?= $data['cols'][$col_name]['length']?></td>
                            <td><?= $data['cols'][$col_name]['charset']?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div role="tabpanel" class="tab-pane" id="sql">
                <br/>
                <div class="col-sm-8">
                    <textarea class="form-control" rows="5" id="sql_area"></textarea>
                    <br/>
                    <button type="button" class="btn btn-default" onclick="sql_button('SELECT * FROM <?= $data['table'] ?> WHERE ', 0)">SELECT *</button>
                    <button type="button" class="btn btn-default" onclick="sql_button('SELECT ', 0)">SELECT</button>
                    <button type="button" class="btn btn-default" onclick="sql_button('UPDATE ', 0)">UPDATE</button>
                    <button type="button" class="btn btn-default" onclick="sql_button('INSERT INTO ', 0)">INSERT</button>
                    <button type="button" class="btn btn-warning" onclick="sql_button('DELETE ', 0)">DELETE</button>
                    <button type="button" class="btn btn-danger" onclick="sql_button('DROP ', 0)">DROP</button>
                    <br/>
                    <br/>
                    <button type="button" class="btn btn-default" onclick="sql_button(' FROM ', 1)">FROM</button>
                    <button type="button" class="btn btn-default" onclick="sql_button(' WHERE ', 1)">WHERE</button>
                    <button type="button" class="btn btn-default" onclick="sql_button(' AND ', 1)">AND</button>
                    <button type="button" class="btn btn-default" onclick="sql_button(' OR ', 1)">OR</button>
                    <button type="button" class="btn btn-default" onclick="sql_button(' ORDER BY ', 1)">ORDER BY</button>
                    <button type="button" class="btn btn-default" onclick="sql_button(' GROUP BY ', 1)">GROUP BY</button>
                    <button type="button" class="btn btn-default" onclick="sql_button(' HAVING BY ', 1)">HAVING</button>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"> memcache缓存查询结果
                        </label>
                    </div>
                    <button type="button" class="btn btn-primary btn-lg btn-block" onclick="launch_sql()">执行</button>
                </div>
                <div class="col-sm-4">
                    <table class="table table-hover table-bordered" id="sql_table_list">                       
                        <tbody>                       
                        <?php foreach ($data['cols'] as $col_name => $col_type): ?>                    
                            <tr>
                                <td><a onclick="sql_button(' <?= $col_name ?> ', 1)"><?= $col_name ?></a></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <br/>
                <br/>
                <div id="sql_result">
                    
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="insert">                
                <table class="table table-hover table-bordered" id="sql_table_list">                       
                    <tbody>                       
                    <?php foreach ($data['cols'] as $col_name => $col_type): ?>                    
                        <tr>
                            <td><?= $col_name ?></td>
                            <td><?= $data['cols'][$col_name]['length'] ?></td>
                            <td><form role="form">
                                <div class="form-group">
                                  <input type="text" class="form-control" id="insert_<?= $col_name?>">
                                </div>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="button" class="btn btn-primary btn-lg btn-block">插入</button>
            </div>
            <div role="tabpanel" class="tab-pane" id="search">
                <table class="table table-hover table-bordered" id="sql_table_list"> 
                    <thead>
                        <tr>
                            <td>字段</td>
                            <td>运算符</td>
                            <td>值</td>
                        </tr>
                    </thead>
                    <tbody>                       
                    <?php foreach ($data['cols'] as $col_name => $col_type): ?>                    
                        <tr>
                            <td><?= $col_name ?></td>
                            <td><select class="form-control" name="">
                                    <option value="LIKE">LIKE</option>
                                    <option value="LIKE %...%">LIKE %...%</option>
                                    <option value="NOT LIKE">NOT LIKE</option>
                                    <option value="=">=</option>
                                    <option value="!=">!=</option>
                                    <option value="REGEXP">REGEXP</option>
                                    <option value="REGEXP ^...$">REGEXP ^...$</option>
                                    <option value="NOT REGEXP">NOT REGEXP</option>
                                    <option value="= ''">= ''</option>
                                    <option value="!= ''">!= ''</option>
                                    <option value="IN (...)">IN (...)</option>
                                    <option value="NOT IN (...)">NOT IN (...)</option>
                                    <option value="BETWEEN">BETWEEN</option>
                                    <option value="NOT BETWEEN">NOT BETWEEN</option>
                                    <option value="IS NULL">IS NULL</option>
                                    <option value="IS NOT NULL">IS NOT NULL</option>
                                </select></td>
                            <td><form role="form">
                                <div class="form-group">
                                  <input type="text" class="form-control" id="insert_<?= $col_name?>">
                                </div>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="button" class="btn btn-primary btn-lg btn-block">搜索</button>
            </div>
            <div role="tabpanel" class="tab-pane" id="operating"></div>
        </div>
        
        
    </body>
    <script>
        
        //接收母窗口传来的值
        function MotherResultRec(data) {
            if (1 == data[2]) {
                $("form").each(function () {
                    this.reset();
                });                
                $("#alert").removeClass("alert-danger");
                $("#alert").addClass("alert-success");
                $("#alert").html("<p>共操作" + data[4]['rows'] + "行，查询消耗" + data[4]['time'] + "秒<p>" + "<p>" + data[4]['sql'] + "<p>");
                switch (data[3]){
                    case 'ExecSQL':
                        $("#sql_result").html("");
                        $("#sql_result").append("<br/><table class=\"table table-hover table-bordered\" id=\"sql_data_view\">");
                        $("#sql_data_view").append("<thead><tr><th>#</th>");
                        //取出字段
                        $.each(data[4]['cols'], function (col_name){
                            $("#sql_data_view thead tr").append("<th>" + col_name + "</th>");
                        });
                        $("#sql_data_view").append("</thead><tbody>");                        
                        //取出数据
                        $.each(data[4]['data'], function (i, data_item){
//                            console.log(data_item);
                            $("#sql_data_view tbody").append("<tr id=" + i + "><td>" + i + "</td></tr>");
                            $.each(data_item, function (m, data_item_val){
//                                console.log(data_item_val);
                                $("#sql_data_view tbody #" + i).append("<td>" + data_item_val + "</td>");
                            })   
                        })
                        $("#sql_data_view").append("</tbody></table>");    
                        break;
                    }
                
            } else {
                $("#alert").removeClass("alert-success");
                $("#alert").addClass("alert-danger");
                $("#alert").html("未操作");
                alert(data[3]);
            }
             
        }       
        
        //SQL输入框按钮
        //@param 
        //sql 准备执行的SQL语句
        //mode 插入到最前还是就地插入
        function sql_button(sql, mode){
            if (!mode){
                $("#sql_area").val(sql);
            } else {
                var old_sql = $("#sql_area").val();
                old_sql += sql;
                $("#sql_area").val(old_sql);
            }            
            $("#sql_area").focus();
        }
        
        //执行SQL语句
        function launch_sql(){
            var data = new Array();
            data['src'] = location.href.slice((location.href.lastIndexOf("/")));
            data['api'] = location.href.slice(0, location.href.lastIndexOf("/")) + '/index.php/TableInfo/ExecSQL';
            data['data'] = '{"user_key" : "<?= $user_key ?>", "user_name" : "<?= $user_name ?>",';
            data['data'] += '"sql" : "' + $("#sql_area").val() + '", "database" : "<?= $data['database'] ?>"}';
            parent.IframeSend(data);
        }
        
    </script>
</html>