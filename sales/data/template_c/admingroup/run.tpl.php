<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2017-03-14 10:53:21, compiled from ./web/template/admingroup/run.htm */ ?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title>销售管理系统</title>
	<base href="<?php echo InitPHP::getConfig('url');?>"/>
    <link rel="stylesheet" href="/static/css/base.css">
    <link rel="stylesheet" href="/static/css/css.css">
    <script src="/static/js/jquery.js"></script>
</head>
<body>
<div class="panel admin-panel">
  <div class="panel-head"><strong class="icon-reorder"> 角色组列表</strong></div>
  <div class="padding border-bottom">  
  <a class="button border-yellow" href="/admingroup/add"><span class="icon-plus-square-o"></span> 添加角色</a>
  </div> 
  <table class="table table-hover text-center">
    <tr>
      <th width="5%">ID</th>     
      <th>角色名称</th>  
      <th>角色分级</th>     
      <th width="250">操作</th>
    </tr>
	
	<?php foreach ($list as $key=>$vo) { ?>
    <tr>
      <td><?php echo $vo['id'];?></td>      
      <td><?php echo $vo['name'];?></td>  
      <td>1</td>      
      <td>
      <div class="button-group">
      <a type="button" class="button border-main" href="/admingroup/edit/id/<?php echo $vo['id'];?>"><span class="icon-edit"></span>修改</a>
       <a class="button border-red" href="javascript:void(0)" onclick="del('<?php echo $vo['id'];?>',this)"><span class="icon-trash-o"></span> 删除</a>
      </div>
      </td>
    </tr> 
	<?php } ?>

  </table>
</div>
<script>
function del(id,obj){
	if(confirm("您确定要删除吗?")){
		$.ajax({
			url: '/admingroup/del',
			type: 'post',
			dataType:'json',
			data: {id: id},
			success: function(msg) {
				if(msg.status == 2){
					alert("内置角色无法删除");
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