<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2017-03-15 13:20:59, compiled from ./web/template/admin/editinfo.htm */ ?>
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
  <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o"></span>增加角色</strong></div>
  <div class="body-content">
	<form id="form" class="form-x" >
	
	
	<div class="form-group">
        <div class="label">
          <label>用户名：</label>
        </div>
        <div class="field">
			<?php if ($info['user']) { ?>
				<input name="user" type="text" class="input w50" id="user" value="<?php echo $info['user'];?>" disabled="disabled" />
			<?php } else { ?>
				<input name="user" type="text" class="input w50" id="user" value="<?php echo $info['user'];?>" onblur="chekUsName()"/>
		    <?php } ?>
          <div class="tips_user" style="float: left;padding-left: 10px;color: #888;line-height: 42px;"></div>
        </div>
      </div> 
      
      
       <div class="form-group">
        <div class="label">
          <label>登录密码：</label>
        </div>
        <div class="field">
		  <input name="password" type="password" class="input w50" id="password"/> 
		  <div class="tips_Password" style="float: left;padding-left: 10px;color: #888;line-height: 42px;"></div>		  
        </div>
      </div> 
      
     <div class="form-group">
        <div class="label">
          <label>手机号：</label>
        </div>
        <div class="field">
		  <input name="phone" type="text" class="input w50" id="phone" value="<?php echo $info['phone'];?>" onblur="chekPhone()"/>       
          <div class="tips_Phone" style="float: left;padding-left: 10px;color: #888;line-height: 42px;"></div>
        </div>
      </div> 
      
       <div class="form-group">
        <div class="label">
          <label>真实姓名：</label>
        </div>
        <div class="field">
		  <input name="UsrName" type="text" class="input w50" id="UsrName" value="<?php echo $info['UsrName'];?>" onblur="chekUsrName()"/>       
          <div class="tips_UsrName" style="float: left;padding-left: 10px;color: #888;line-height: 42px;"></div>
        </div>
      </div> 
      
      
		<div class="form-group">
		  <div class="label">
			<label>角色类型：</label>
		  </div>
		  <div class="field">
			<select name="gid" id="gid" class="input w50">
				<?php foreach ($user_group as $key=>$vo) { ?>
				<option value="<?php echo $vo['id'];?>" <?php if ($info['gid']==$vo['id']) { ?> selected="selected" <?php } ?> ><?php echo $vo['name'];?></option>
				<?php } ?>
			</select>
			<div class="tips_jiaose" style="float: left;padding-left: 10px;color: #888;line-height: 42px;"></div>
		  </div>
		</div>

      
      <div class="form-group">
		  <div class="label">
			<label>所属部门：</label>
		  </div>
		  <div class="field">
			<select name=department_id id="department_id" class="input w50">
                <?php echo $html;?>
			</select>
			<div class="tips_bumen" style="float: left;padding-left: 10px;color: #888;line-height: 42px;"></div>
		  </div>
		</div>
		
		
    
     
	  
      <div class="form-group">
        <div class="label">
          <label>身份证号：</label>
        </div>
        <div class="field">
		  <input name="IdNo" type="text" class="input w50" id="IdNo" value="<?php echo $info['IdNo'];?>" onblur="chekIdNo()"/>       
          <div class="tips_IdNo" style="float: left;padding-left: 10px;color: #888;line-height: 42px;"></div>
        </div>
      </div> 
	  
	  
      <div class="form-group">
        <div class="label">
          <label>级别：</label>
        </div>
        <div class="field">
          <div class="button-group radio">
			<input name="level_id" type="radio" value="0"  <?php if ($info['level_id']==0) { ?> checked="checked" <?php } ?> />实习&nbsp;&nbsp;
			<input name="level_id" type="radio" value="1" <?php if ($info['level_id']==1) { ?> checked="checked" <?php } ?> />初级&nbsp;&nbsp;
			<input name="level_id" type="radio" value="2" <?php if ($info['level_id']==2) { ?> checked="checked" <?php } ?> />中级&nbsp;&nbsp;
			<input name="level_id" type="radio" value="3" <?php if ($info['level_id']==3) { ?> checked="checked" <?php } ?> />高级&nbsp;&nbsp;
			<span class="tips" style="padding-left: 10px;color: #888;line-height: 42px;"></span>				
           </div>  
        </div>
     </div>
     
     
     <div class="form-group">
        <div class="label">
          <label>性别：</label>
        </div>
        <div class="field">
          <div class="button-group radio">
			<input name="gender" type="radio" value="1"  <?php if ($info['gender']==1) { ?> checked="checked" <?php } ?> />男&nbsp;&nbsp;
			<input name="gender" type="radio" value="2" <?php if ($info['gender']==2) { ?> checked="checked" <?php } ?> />女&nbsp;&nbsp;
			<span class="tips" style="padding-left: 10px;color: #888;line-height: 42px;"></span>				
           </div>  
        </div>
     </div>
	  
     
	  
     <div class="form-group">
        <div class="label">
          <label>状态：</label>
        </div>
        <div class="field">
          <div class="button-group radio">
			<input name="status" type="radio" value="1" <?php if ($info['status']==1) { ?> checked="checked" <?php } ?> />正常&nbsp;&nbsp;
			<input name="status" type="radio" value="0" <?php if ($info['status']==0) { ?> checked="checked" <?php } ?> />禁用
			<span class="tips" style="padding-left: 10px;color: #888;line-height: 42px;"></span>				
           </div>  
        </div>
		
     </div>
	
     <div class="form-group">
        <div class="label">
          <label></label>
        </div>
        <div class="field">
		    <input name="id" id="id" type="hidden" value="<?php echo $info['id'];?>" />
			<input type="hidden" name="init_token" id="init_token" value="<?php echo $init_token;?>"/>
            <button  class="button bg-main icon-check-square-o" type="button" onclick="sub(this)"> 提交</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript">
var user = 0, UsrName = 0, IdNo = 0, Phone = 0;
function chekUsName()
{
    var s = $("#user").val();
    var reg = /^\w{6,16}$/;   
	
    if(reg.test(s)){  
		$(".tips_user").html("").show();
		user = 1;
    }else{
        $(".tips_user").html("<font color=\"red\">角色名必须要6-16位字母、数字和下划线！</font>").show();
		return false;
	}
}

function chekUsrName()
{
    var s = $("#UsrName").val();
    if(s == ""){
        $(".tips_UsrName").html("<font color=\"red\">真实姓名必须填写</font>").show();
		return false;
	}else{
		$(".tips_UsrName").html("").show();
		UsrName = 1;
	}
}

function chekIdNo()
{
    var s = $("#IdNo").val();
    var card = /^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$|^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/;
	
    if(card.test(s)){  
		$(".tips_IdNo").html("").show();
		IdNo = 1;
    }else{
        $(".tips_IdNo").html("<font color=\"red\">身份证号不正确</font>").show();
		return false;
	}
}
function chekJiaose(){
	 var gid = $("#gid").val();
	if(gid <0){
		$(".tips_jiaose").html("<font color=\"red\">请选择角色类型</font>").show();
		return false;
	}
}
function chekBumen(){
	 var department_id = $("#department_id").val();
	if(department_id <0){
		$(".tips_bumen").html("<font color=\"red\">请选择所属部门</font>").show();
		return false;
	}
}

function chekPhone()
{
    var reg = /^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|17[0-9]{9}|18[0-9]{9}$/;
    var s = $("#phone").val();
    if (s !== "" && !reg.test(s) || s.length !== 11) {
        $(".tips_Phone").html("<font color=\"red\">手机格式不正确！</font>").show();
    } else {
		$(".tips_Phone").html("").show();
		Phone = 1;
    }
}

function sub(obj){
	chekUsName();//用户名
	chekPhone();//手机号码
	chekUsrName();//真实姓名
	chekJiaose();//角色类型
	chekBumen();//所属部门
	chekIdNo();//身份证号
	
	//
	//
	if(user == 1 && UsrName == 1 && IdNo == 1 && Phone == 1)
	{
		$.ajax({
			url: '/admin/<?php echo $action;?>_save',
			type: 'post',
			dataType:'json',
			data: $('#form').serialize(),
			success: function(data) {
				if(data.status==3 || data.status==8 || data.status==10 || data.status==678){
					window.location.href="/admin/run";
				}else{
					$(".tips").html("<font color=\"red\">"+data.message+"</font>");
					setTimeout(function() {
						$(".tips").html('');
					}, 30000);
					return false;
				}
			}
		});
	}
	


};

</script>
</body>
</html>
