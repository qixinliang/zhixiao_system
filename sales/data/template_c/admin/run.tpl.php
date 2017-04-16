<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2017-04-16 14:07:54, compiled from ./web/template/admin/run.htm */ ?>
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
  <link rel="stylesheet" href="/static/css/normalize.css">
  <link rel="stylesheet" href="/static/css/public.css">
  <link rel="stylesheet" href="/static/css/structure.css">
  <script type="text/javascript" src="/static/js/jquery-3.1.1.js"></script>
</head>
<body>
<div class="wrapper">
<?php include('./data/template_c/top.tpl.php'); ?>

<article class="content">
<?php include('./data/template_c/left_corp_nav.tpl.php'); ?>

 <div class="right st_right">
	<div class="rigth_title">
	<h2>用户管理</h2>

<div class="rigth_t_btn">
 <span><i></i><a href="/admin/add" style="color:#fff;">添加用户</a></span>
       </div>
	</div>

<div class="panel admin-panel">
  <div class="padding border-bottom">  
  <?php if ($status==1) { ?>
  	<a class="button border-main" href="/admin/run/status/1" style="color:#fff;border-color:#0ae;background-color:#0ae" >在职用户</a>
   <?php } else { ?>
   	<a class="button border-main" href="/admin/run/status/1" >在职用户</a>
  <?php } ?>
  <?php if ($status==0) { ?>
  	<a class="button border-main" href="/admin/run/status/0" style="color:#fff;border-color:#0ae;background-color:#0ae" >离职用户</a>
  <?php } else { ?>
  	<a class="button border-main" href="/admin/run/status/0" >离职用户</a>
  <?php } ?>
  </div> 
  <div class="padding border-bottom">
      <form action="/admin/run" method="post">
      <select style="width: 160px;height: 25px;margin-right: 30px;" name="part_id">
          <option value="" >职务</option>
          <?php foreach ($adminList as $k=>$v) { ?>
          <option value="<?php echo $v['id'];?>" <?php if ($v[id]==$part) { ?>selected="selected"<?php } ?> >&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $v['name'];?></option>
          <?php } ?>
      </select>
          
      <select style="width: 160px;height: 25px;margin-right: 30px;" name="department_id">
          <option value=''>所属部门</option>
            <?php echo $html;?>
      </select>
          <input type="text" name="uname" style="width: 160px;height: 25px;margin-right: 30px;" value="<?php echo $uname;?>" placeholder="姓名">
      <input type="text" name="phone" style="width: 160px;height: 25px;margin-right: 30px;" value="<?php echo $phone;?>" placeholder="手机号">
      <input type="submit" class="button border-main" value="搜索"> 
      </form>
  </div> 
  <div class="right_list">
  <table class="table table-hover text-center">
    <tr>
        <th>用户名</th> 
        <th>员工姓名</th> 
        <th>联系手机</th>
        <th>所属部门</th>
        <th>角色</th>
        <th>级别</th>
        <th>直属管理人</th>  
        <th>入职时间</th>	
        <th>创建时间</th>
        <th>状态</th>
    <th width="250">操作</th>
    </tr>
   <?php foreach ($list as $key=>$vo) { ?>
	<tr>
                <td><?php echo $vo['user'];?></td>
		<td><?php echo $vo['UsrName'];?></td>
		<td><?php echo $vo['phone'];?></td>
		<td><?php echo $vo['department_name'];?></td>
        <td><?php echo $vo['gname'];?></td>
        <td>
        <?php if ($vo['gid']==10) { ?>
	        <?php if ($vo['level_id']==0) { ?>
			实习
			<?php } elseif ($vo['level_id']==1) { ?>
			初级
			<?php } elseif ($vo['level_id']==2) { ?>
			中级
			<?php } elseif ($vo['level_id']==3) { ?>
			高级
			<?php } ?>
		<?php } ?>
        </td>
		<td><?php echo $vo['superior'];?></td>
		<td><?php echo $vo['Inthetime'];?></td>
		<td><?php echo date("Y-m-d H:i:s",$vo['regtime']);?></td>
		<td>
		<?php if ($vo['status']==1) { ?>
          在职
          <?php } else { ?>
          离职
          <?php } ?>
		</td>
      <td>
		  <div class="button-group">
		  <?php if ($vo['status']==1) { ?>
          		<a type="button" class="button border-main" href="/admin/edit/id/<?php echo $vo['id'];?>"><span class="icon-edit"></span>修改</a>
          		<a class="button border-red" href="javascript:void(0)" onclick="del('<?php echo $vo['id'];?>',0,this)"><span class="icon-trash-o"></span>变离职	</a>
          <?php } else { ?>
          		<a type="button" class="button border-main" href="/admin/edit/id/<?php echo $vo['id'];?>"><span class="icon-edit"></span>修改</a>
          		<a class="button border-red" href="javascript:void(0)" onclick="del('<?php echo $vo['id'];?>',1,this)"><span class="icon-trash-o"></span>变在职	</a>
          <?php } ?>
			
			
		  </div>
      </td>
	</tr>
	<?php } ?>
  </table>
</div>
</div>
<?php if (is_array($list)) { ?>
<div class="padding border-bottom">  
    <?php echo $page_html;?>
</div> 
<?php } ?>
<script>
function del(id,status,obj){
	if(confirm("您确定要操作吗?")){
		$.ajax({
			url: '/admin/del',
			type: 'post',
			dataType:'json',
			data: {id: id,status: status},
			success: function(msg) {
				if(msg.status == 9){
					alert("内置用户无法修改");
				}else{
					$(obj).parent().parent().parent().remove();
				}
			}
		});
	}
}
</script>
</div>
</article>
</div>
<script type="text/javascript" src="/static/js/public.js"></script>

</body></html>
