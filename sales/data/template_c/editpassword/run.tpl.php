<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2017-02-09 18:33:09, compiled from ./web/template/editpassword/run.htm */ ?>
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
    <style>
		.w50{width:22%;float:left;}
		.input{padding:0;line-height:30px;text-indent:10px;}
	</style>
</head>
<body>
<div class="panel admin-panel margin-top">
  <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o"></span>修改密码</strong></div>
  <div class="body-content">
	<form id="form" class="form-x" >
	

      <div class="form-group">
        <div class="label">
          <label>新密码：</label>
        </div>
        <div class="field">
		  <input name="password" type="password" class="input w50" id="password"/> 
		  <div class="tips_Password" style="float: left;padding-left: 10px;color: #888;line-height: 42px;"></div>		  
        </div>
      </div> 
	  
      <div class="form-group">
        <div class="label">
          <label>确认密码：</label>
        </div>
        <div class="field">
		  <input name="password2" type="password" class="input w50" id="rePassword"/>   
		  <div class="tips_RePassword" style="float: left;padding-left: 10px;color: #888;line-height: 42px;"></div>	
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
var password = 0, rpassword = 0;

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
    if ($("#password").val() == $("#rePassword").val())
    {
        $(".tips_RePassword").html("").show();
        rpassword = 1;
    } else {
		$('.tips_RePassword').html('<font color="red">两次输入的密码不一致！</font>').show();
    }
}

function sub(obj){
	chekPassWord();
	chekRePassWord();
	if(password == 1 && rpassword == 1)
	{
		$.ajax({
			url: '/editpassword/<?php echo $action;?>_save',
			type: 'post',
			dataType:'json',
			data: $('#form').serialize(),
			success: function(data) {
				if(data.status==8){
					alert(data.message);
					window.location.href="/editpassword/run";
					return true;
				}else{
					alert(data.message);
					return false;
				}
			}
		});
	}
	


};

</script>
</body>
</html>