<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2017-02-10 08:39:25, compiled from ./web/template/grxx/run.htm */ ?>
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
    <script src="/static/js/comm.js"></script>
    <style>
.input {
    border: 1px solid #fff;}
	.yinhangka{float:left;width:25%}
	.user-left{text-align:right;display:inline-block;width:100px;}
	.user-right{display:inline-block;width:200px;}
	.rightButton{display:inline-block;}
	</style>
    
</head>
<body>
<div class="panel admin-panel margin-top">
  <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o"></span>个人信息</strong></div>
  <div class="body-content">
		<div class="user_name">
			<p class="user-left">用户名：</p>
			<p class="user-right"><?php echo $info['yewuusername'];?></p>
		</div>
		<div class="user_name">
			<p class="user-left">真实姓名:</p>
			<p class="user-right"><?php echo $info['huifuname'];?></p>
		</div>
		<div class="user_name">
			<p class="user-left">手机号：</p>
			<p class="user-right"><?php echo $info['phone'];?></p>
		</div>
		<div class="user_name">
			<p class="user-left">电子邮箱：</p>
			<p class="user-right"><?php echo $info['UsrEmail'];?></p>
		</div>
		<div class="user_name">
			<p class="user-left">居住地址：</p>
			<p class="user-right"><?php echo $info['address'];?></p>
		</div>
		<div class="user_name">
			<p class="user-left">银行卡号：</p>
			<p class="user-right"><?php echo $info['card_number'];?></p>
			<p class="rightButton"><?php if ($show==1) { ?>
				<!--<button  class="yinhangka" type="button" onclick="bankedit(<?php echo $info['id'];?>)"> 银行卡信息修改</button>-->
			<?php } ?></p>
		</div>
		<div class="user_name">
			<p class="user-left">开户行名称：</p>
			<p class="user-right"><?php echo $info['bank_opens_name'];?></p>
		</div>
		<div class="user_name">
			<p class="user-left">开户人姓名：</p>
			<p class="user-right"><?php echo $info['bank_opens_user'];?></p>
		</div>
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