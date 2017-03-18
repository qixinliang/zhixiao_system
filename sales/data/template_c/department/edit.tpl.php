<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2017-03-15 19:57:47, compiled from ./web/template/department/edit.htm */ ?>
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
    <script src="/static/js/jquery.js"></script>
</head>
<body>
<div class="panel admin-panel margin-top">
  <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o"></span>修改部门信息</strong></div>
  <div class="body-content">
	<form id="form" class="form-x" >
      <div class="form-group">
        <div class="label">
          <label>部门名称：</label>
        </div>
        <div class="field">
		  <input name="department_name" type="text" class="input w50" id="department_name" value="<?php echo $data['department_name'];?>" />       
          <div class="tips"></div>
        </div>
      </div> 
	<div class="form-group">
		<div class="label">
			<label>上级部门：</label>
		</div>
        <div class="field" id="power">
            <select name="p_dpt_id" id="p_dpt_id" class="input w50">
                <?php echo $html;?>
            </select>
        </div>
	</div>
      
       <div class="form-group">
        <div class="label">
          <label>状态：</label>
        </div>
        <div class="field">
          <div class="button-group radio">
			<input name="status" type="radio" value="0" <?php if ($data['status']==0) { ?> checked="checked" <?php } ?> />正常&nbsp;&nbsp;
			<input name="status" type="radio" value="1" <?php if ($data['status']==1) { ?> checked="checked" <?php } ?> />禁用
			<span class="tips" style="padding-left: 10px;color: #888;line-height: 42px;"></span>				
           </div>  
        </div>
		
     </div>

     <div class="form-group">
        <div class="label">
          <label></label>
        </div>
        <div class="field">
			<input name="department_id" id="department_id" type="hidden" value="<?php echo $data['department_id'];?>" />
            <button  class="button bg-main icon-check-square-o" type="button" onclick="sub(this)"> 提交</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript">
function sub(obj){
	var department_name  =  $("#department_name").val();
	if(department_name == ""){
		$(".tipss").html("<font color=\"red\">部门名称不能为空!</font>").show();
		return;
	}
	$.ajax({
		url: '/department/<?php echo $action;?>',
		type: 'post',
		dataType:'json',
		data: $('#form').serialize(),
		success: function(data) {
			if(data.status==1){
				window.location.href="/department/run";
			}else{
				$("#tipss").html("<font color=\"red\">"+data.message+"</font>");
				setTimeout(function() {
					$("#tipss").html('');
				}, 3000);
				return false;
			}
		}
	});

};
</script>
</body>
</html>
