<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2017-03-11 21:41:50, compiled from ./web/template/admingroup/editinfo.htm */ ?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title>业务管理系统</title>
	<base href="<?php echo InitPHP::getConfig('url');?>"/>
    <link rel="stylesheet" href="/static/css/base.css">
    <link rel="stylesheet" href="/static/css/css.css">
    <script src="/static/js/jquery.js"></script>
</head>
<body>
<div class="panel admin-panel margin-top">
  <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o"></span>修改角色组</strong></div>
  <div class="body-content">
	<form id="form" class="form-x" >
      <div class="form-group">
        <div class="label">
          <label>角色组名称：</label>
        </div>
        <div class="field">
		  <input name="name" type="text" class="input w50" id="name" value="<?php echo $info['name'];?>" />       
          <div class="tips"></div>
        </div>
      </div> 
      <div class="form-group">
        <div class="label">
          <label>功能权限：</label>
        </div>
        <div class="field" id="power">
			<fieldset class="source">
				<legend>团队管理</legend>
					<input name="model_power[]" type="checkbox" value="1000" <?php if(in_array(1000,$model_power)):?> checked="checked" <?php endif;?>> 推荐总监列表 &nbsp;&nbsp;
					<input name="model_power[]" type="checkbox" value="1001" <?php if(in_array(1001,$model_power)):?> checked="checked" <?php endif;?>> 推荐总监列表显示全手机号和用户 &nbsp;&nbsp;
					<input name="model_power[]" type="checkbox" value="1002" <?php if(in_array(1002,$model_power)):?> checked="checked" <?php endif;?>> 推荐经纪人列表 &nbsp;&nbsp;
					<input name="model_power[]" type="checkbox" value="1003" <?php if(in_array(1003,$model_power)):?> checked="checked" <?php endif;?>> 推荐经纪人列表显示全手机号和用户 &nbsp;&nbsp;
			</fieldset>
			<fieldset class="source">
				<legend>统计报表</legend>
					<input name="model_power[]" type="checkbox" value="1004" <?php if(in_array(1004,$model_power)):?> checked="checked" <?php endif;?>> 个人交易佣金统计 &nbsp;&nbsp;
					<input name="model_power[]" type="checkbox" value="1005" <?php if(in_array(1005,$model_power)):?> checked="checked" <?php endif;?>> 个人交易佣金统计显示全手机号和用户 &nbsp;&nbsp;
					<input name="model_power[]" type="checkbox" value="1006" <?php if(in_array(1006,$model_power)):?> checked="checked" <?php endif;?>>部门业绩统计&nbsp;&nbsp;
					<input name="model_power[]" type="checkbox" value="1007" <?php if(in_array(1007,$model_power)):?> checked="checked" <?php endif;?>> 部门业绩统计显示全手机号和用户 &nbsp;&nbsp;
					<input name="model_power[]" type="checkbox" value="1008" <?php if(in_array(1008,$model_power)):?> checked="checked" <?php endif;?>> 部门业绩统计-查看&nbsp;&nbsp;
					<input name="model_power[]" type="checkbox" value="1009" <?php if(in_array(1009,$model_power)):?> checked="checked" <?php endif;?>> 部门业绩统计-查看-显示全手机号和用户&nbsp;&nbsp;
					<input name="model_power[]" type="checkbox" value="1010" <?php if(in_array(1010,$model_power)):?> checked="checked" <?php endif;?>> 我的投资人明细&nbsp;&nbsp;
					<input name="model_power[]" type="checkbox" value="1011" <?php if(in_array(1011,$model_power)):?> checked="checked" <?php endif;?>> 我的投资人明细-显示全手机号和用户&nbsp;&nbsp;
			</fieldset>
			<fieldset class="source">
				<legend>账户管理</legend>
					<input name="model_power[]" type="checkbox" value="1012" <?php if(in_array(1012,$model_power)):?> checked="checked" <?php endif;?>> 个人信息 &nbsp;&nbsp;
					<input name="model_power[]" type="checkbox" value="1013" <?php if(in_array(1013,$model_power)):?> checked="checked" <?php endif;?>> 修改密码显示页面 &nbsp;&nbsp;
					<input name="model_power[]" type="checkbox" value="1014" <?php if(in_array(1014,$model_power)):?> checked="checked" <?php endif;?>> 修改密码保存 &nbsp;&nbsp;
					<input name="model_power[]" type="checkbox" value="1015" <?php if(in_array(1015,$model_power)):?> checked="checked" <?php endif;?>> 银行卡信息修改页面显示 &nbsp;&nbsp;
					<input name="model_power[]" type="checkbox" value="1016" <?php if(in_array(1016,$model_power)):?> checked="checked" <?php endif;?>> 银行卡信息修改保存&nbsp;&nbsp;
			</fieldset>
			<fieldset class="source">
				<legend>系统管理</legend>
					<input name="model_power[]" type="checkbox" value="1017" <?php if(in_array(1017,$model_power)):?> checked="checked" <?php endif;?>> 角色组管理显示 &nbsp;&nbsp;
					<input name="model_power[]" type="checkbox" value="1018" <?php if(in_array(1018,$model_power)):?> checked="checked" <?php endif;?>> 角色管理-添加-显示 &nbsp;&nbsp;
					<input name="model_power[]" type="checkbox" value="1019" <?php if(in_array(1019,$model_power)):?> checked="checked" <?php endif;?>> 角色管理-添加-保存 &nbsp;&nbsp;
					<input name="model_power[]" type="checkbox" value="1020" <?php if(in_array(1020,$model_power)):?> checked="checked" <?php endif;?>> 角色管理-修改显示 &nbsp;&nbsp;
					<input name="model_power[]" type="checkbox" value="1021" <?php if(in_array(1021,$model_power)):?> checked="checked" <?php endif;?>> 角色管理-修改-保存 &nbsp;&nbsp;
					<input name="model_power[]" type="checkbox" value="1022" <?php if(in_array(1022,$model_power)):?> checked="checked" <?php endif;?>> 角色管理-删除 &nbsp;&nbsp;
					<input name="model_power[]" type="checkbox" value="1023" <?php if(in_array(1023,$model_power)):?> checked="checked" <?php endif;?>> 角色管理 &nbsp;&nbsp;
					<input name="model_power[]" type="checkbox" value="1024" <?php if(in_array(1024,$model_power)):?> checked="checked" <?php endif;?>> 角色管理-添加显示 &nbsp;&nbsp;
					<input name="model_power[]" type="checkbox" value="1025" <?php if(in_array(1025,$model_power)):?> checked="checked" <?php endif;?>> 角色管理-添加-保存 &nbsp;&nbsp;
					<input name="model_power[]" type="checkbox" value="1026" <?php if(in_array(1026,$model_power)):?> checked="checked" <?php endif;?>> 角色管理-修改显示 &nbsp;&nbsp;
					<input name="model_power[]" type="checkbox" value="1027" <?php if(in_array(1027,$model_power)):?> checked="checked" <?php endif;?>> 角色管理-修改-保存 &nbsp;&nbsp;
					<input name="model_power[]" type="checkbox" value="1028" <?php if(in_array(1028,$model_power)):?> checked="checked" <?php endif;?>> 角色管理-删除 &nbsp;&nbsp;
					<input name="model_power[]" type="checkbox" value="1029" <?php if(in_array(1029,$model_power)):?> checked="checked" <?php endif;?>> 推荐经纪人添加 &nbsp;&nbsp;
					<input name="model_power[]" type="checkbox" value="1030" <?php if(in_array(1030,$model_power)):?> checked="checked" <?php endif;?>> 推荐客户添加 &nbsp;&nbsp;
					<input name="model_power[]" type="checkbox" value="1031" <?php if(in_array(1031,$model_power)):?> checked="checked" <?php endif;?>> 查看推荐经纪人列表 &nbsp;&nbsp;
					<input name="model_power[]" type="checkbox" value="1032" <?php if(in_array(1032,$model_power)):?> checked="checked" <?php endif;?>> 查看推荐经纪人列表显示隐藏用户和手机 &nbsp;&nbsp;
			</fieldset>
			<div class="tipss"></div>
        </div>
      </div>

     <div class="form-group">
        <div class="label">
          <label></label>
        </div>
        <div class="field">
		    <input name="id" id="id" type="hidden" value="<?php echo $info['id'];?>" />
			<input name="grade" id="grade" type="hidden" value="1" />
            <button  class="button bg-main icon-check-square-o" type="button" onclick="sub(this)"> 提交</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript">
function sub(obj){

	var name  =  $("#name").val();
	var id    =  $("#id").val();
	var grade =  $("#grade").val();

	if(name == ""){
		$(".tipss").html("<font color=\"red\">用户名不能为空</font>").show();
		return;
	}
	
	var power = null;
	$("#power fieldset input[type=checkbox]").each(function(){
		if(this.checked){
			power = $(this).val();
		}
	});  

	if(power == null){
		$(".tipss").html("<font color=\"red\">功能权限不能为空</font>").show();
		return;
	}
	
	$.ajax({
		url: '/admingroup/<?php echo $action;?>_save',
		type: 'post',
		dataType:'json',
		data: $('#form').serialize(),
		success: function(data) {
			if(data.status==1){
				window.location.href="/admingroup/run";
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