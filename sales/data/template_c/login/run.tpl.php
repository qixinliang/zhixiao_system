<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2017-03-12 03:05:58, compiled from ./web/template/login/run.htm */ ?>
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
<div class="bg"></div>
<div class="container">
    <div class="line bouncein">
        <div class="xs6 xm4 xs3-move xm4-move">
            <div style="height:150px;"></div>
            <div class="media media-y margin-big-bottom">           
            </div>         
            <div class="panel loginbox" >
                <div class="text-center margin-big padding-big-top"><h1>欢迎进入业务管理系统</h1></div>
                <div class="panel-body" style="padding:30px; padding-bottom:10px; padding-top:10px;">
                    <div class="form-group">
                        <div class="field field-icon-right">
                            <input type="text" class="input input-big" name="user" id="user" placeholder="登录账号"/>
                            <span class="icon icon-user margin-small"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="field field-icon-right">
                            <input type="password" class="input input-big" name="password" id="password" placeholder="登录密码"/>
                            <span class="icon icon-key margin-small"></span>
                        </div>
                    </div>
                </div>
				<div id="msg"></div>
                <div style="padding:30px;">
					<input type="hidden" name="init_token" id="init_token" value="<?php echo $init_token ?>"/>
					<input type="button" id="sub" class="button button-block bg-main text-big input-big" value="登录">
				</div>
            </div>      
        </div>
    </div>
</div>
<script type="text/javascript">
$("#sub").click(function()
{
	var username = $("#user").val();
	var password = $("#password").val();
	var init_token =  $("#init_token").val();
	var login = /^\w{1,20}$/; 
	var enoughRegex = new RegExp("(?=.{3,}).*", "g");
	
	if(!login.test(username)){
		$("#msg").html("<div class=\"input-help\"><ul><li><font color=\"red\">用户名不能为空</font></li></ul></div>").show();
		return;
	}else if (false === enoughRegex.test($("#password").val())) {
        $('#msg').html('<div class=\"input-help\"><ul><li><font color=\"red\">密码必须最少3位字母、数字和符号！</font></li></ul></div>').show();
        return;
    }
	
	$.ajax({
		url: '/login/check',
		type: 'post',
		dataType:'json',
		data: {user: username,password: password, init_token: init_token},
		success: function(data) {
			if(data.status==1){
				window.location.href="/index/run";
			}else{
				$("#msg").html("<div class=\"input-help\"><ul><li><font color=\"red\">用户名或密码不正确</font></li></ul></div>");
				setTimeout(function() {
					$("#msg").html('');
				}, 3000);
				return false;
			}
		}
	});

});
</script>

</body>
</html>