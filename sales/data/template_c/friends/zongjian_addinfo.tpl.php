<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2017-02-10 08:29:32, compiled from ./web/template/friends/zongjian_addinfo.htm */ ?>
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
  <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o"></span>推荐总监</strong></div>
  <div class="body-content">
	<form id="form" class="form-x" >
      <div class="form-group">
        <div class="label">
          <label>账号：</label>
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
          <label>真实姓名：</label>
        </div>
        <div class="field">
		  <input name="UsrName" type="text" class="input w50" id="UsrName" value="<?php echo $info['UsrName'];?>" onblur="chekUsrName()"/>       
          <div class="tips_UsrName" style="float: left;padding-left: 10px;color: #888;line-height: 42px;"></div>
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
          <label>手机号：</label>
        </div>
        <div class="field">
		  <input name="phone" type="text" class="input w50" id="phone" value="<?php echo $info['phone'];?>" onblur="chekPhone()"/>       
          <div class="tips_Phone" style="float: left;padding-left: 10px;color: #888;line-height: 42px;"></div>
        </div>
      </div> 
	  
      <div class="form-group">
        <div class="label">
          <label>角色密码：</label>
        </div>
        <div class="field">
		  <input name="password" type="password" class="input w50" id="password"  onblur="chekPassWord()"/> 
		  <div class="tips_Password" style="float: left;padding-left: 10px;color: #888;line-height: 42px;"></div>		  
        </div>
      </div> 
	  
      <div class="form-group">
        <div class="label">
          <label>确认密码：</label>
        </div>
        <div class="field">
		  <input name="password2" type="password" class="input w50" id="password2"  onblur="chekRePassWord()"/>   
		  <div class="tips_RePassword" style="float: left;padding-left: 10px;color: #888;line-height: 42px;"></div>	
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
			<input name="gid" id="gid" type="hidden" value="2" />
			<input type="hidden" name="init_token" id="init_token" value="<?php echo $init_token;?>"/>
            <button  class="button bg-main icon-check-square-o" type="button" onclick="sub(this)"> 提交</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript">
var user = 0, UsrName = 0, IdNo = 0, Phone = 0, password = 0, rpassword = 0;
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

function chekPassWord()
{
    var s = $("#password").val();
    if(s == ""){
        $(".tips_Password").html("<font color=\"red\">密码不能为空</font>").show();
		return false;
	}else{
		$(".tips_Password").html("").show();
		password = 1;
	}
}

function chekRePassWord()
{
    if ($("#password").val() == $("#password2").val())
    {
        $(".tips_RePassword").html("").show();
        rpassword = 1;
    } else {
		$('.tips_RePassword').html('<font color="red">两次输入的密码不一致！</font>').show();
    }
}

function sub(obj){

	chekUsName();
	chekUsrName();
	chekIdNo();
	chekPhone();
	chekPassWord();
	chekRePassWord();
	if(user == 1 && UsrName == 1 && IdNo == 1 && Phone == 1 && password == 1 && rpassword == 1)
	{
		$.ajax({
			url: '/friends/<?php echo $action;?>_save',
			type: 'post',
			dataType:'json',
			data: $('#form').serialize(),
			success: function(data) {
				if(data.status==3 || data.status==8 || data.status==10 || data.status==678){
					window.location.href="/friends/run";
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