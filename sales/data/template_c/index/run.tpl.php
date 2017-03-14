<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2017-03-14 10:53:01, compiled from ./web/template/index/run.htm */ ?>
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
<body style="background-color:#f2f9fd;">
<div class="header bg-main">
  <div class="logo margin-big-left fadein-top">
    <h1>业务管理中心</h1>
  </div>
  <div class="head-l">
	<a class="button button-little bg-green" href="/" ><span class="icon-home"></span> 前台首页</a>  &nbsp;&nbsp;<a onclick="logout()" class="button button-little bg-red" style="cursor:pointer;"><span class="icon-power-off"></span>退出登录</a> </div>
</div>
<?php include('./data/template_c/left_nav.tpl.php'); ?>
<script type="text/javascript">
$(function(){
  $(".leftnav h2").click(function(){
	  $(this).next().slideToggle(200);	
	  $(this).toggleClass("on"); 
  })
  $(".leftnav ul li a").click(function(){
	    $("#a_leader_txt").text($(this).text());
  		$(".leftnav ul li a").removeClass("on");
		$(this).addClass("on");
  })
});
</script>
<ul class="bread">
  <li><a target="right" class="icon-home"> 首页</a></li>
  <li><b>当前语言：</b><span style="color:red;">中文</php></span></li>
</ul>
<div class="admin">
  <iframe scrolling="auto" rameborder="0" src="/index/home" name="right" width="100%" height="100%"></iframe>
</div>
<script type="text/javascript">
function logout(){
	$.ajax({
		type: 'POST',
		url:'/login/logout',
		data: {'out':'true'},
		dataType: 'json',
		success: function(json) {
            window.location.reload();
        }
	});
}
</script>
</body>
</html>