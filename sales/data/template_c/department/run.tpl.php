<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2017-03-14 18:38:01, compiled from ./web/template/department/run.htm */ ?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title>直销管理系统</title>
	<base href="<?php echo InitPHP::getConfig('url');?>"/>
    <link rel="stylesheet" href="/static/css/base.css">
    <link rel="stylesheet" href="/static/css/css.css">
    <link rel="stylesheet" href="/static/css/simpleTree.css">
    <script src="/static/js/jquery.js"></script>
    <script src="/static/js/simpleTree.js"></script>
</head>
<body>
<div class="panel admin-panel">
  <div class="panel-head"><strong class="icon-reorder">部门列表/拓扑图</strong></div>
  <div class="padding border-bottom">  
  <a class="button border-yellow" href="/department/add"><span class="icon-plus-square-o"></span> 新增部门信息</a>
  </div> 
  <table class="table table-hover text-center">
    <tr>
      <th width="5%">ID</th>     
      <th>部门名称</th>  
      <th>状态</th>     
      <th width="250">操作</th>
    </tr>
	
	<?php foreach ($list as $k => $v) { ?>
    <tr>
      <td><?php echo $v['department_id'];?></td>
      <td><?php echo $v['department_name'];?></td>  
      <td><?php if ($v['status'] == 0) { ?>
      	启用
      <?php } else { ?>
      	停用
      <?php } ?></td>      
      <td>
      <div class="button-group">
      <a type="button" class="button border-main" href="/department/edit/department_id/<?php echo $v['department_id'];?>"><span class="icon-edit"></span>修改</a>
       <a class="button border-red" href="javascript:void(0)" onclick="del('<?php echo $v['department_id'];?>',this)"><span class="icon-trash-o"></span> 删除</a>
      </div>
      </td>
    </tr> 
	<?php } ?>

  </table>
</div>
<script>
$(function(){
    $(".st_tree").simpleTree({
        click:function(a){
			console.log($(a).attr("ref"));
            if(!$(a).attr("hasChild")){
                alert($(a).attr("ref"));
			}
        }
    });
});

function del(id,obj){
	if(confirm("您确定要删除吗?")){
		$.ajax({
			url: '/department/del',
			type: 'post',
			dataType:'json',
			data: {department_id: id},
			success: function(msg) {
				if(msg.status == 2){
					alert("无法删除");
				}else if(msg.status == 3){
					alert("越权操作");
				}else{
					$(obj).parent().parent().parent().remove();
				}
			}
		});
	}
}
</script>
</body></html>
