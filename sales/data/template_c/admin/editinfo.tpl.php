<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2017-04-16 14:07:18, compiled from ./web/template/admin/editinfo.htm */ ?>
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
       <h2>修改用户</h2>
     </div>


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
		  <input name="phone" type="text" class="input w50" id="phone" value="<?php echo $info['phone'];?>" disabled="disabled"/>       
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
          <label>入职时间：</label>
        </div>
        <div class="field">
		  <input name="Inthetime" type="text" class="input w50" id="Inthetime" value="<?php echo $info['Inthetime'];?>" />       
          <div class="tips_Inthetime" style="float: left;padding-left: 10px;color: #888;line-height: 42px;"></div>
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
<script type="text/javascript">
var user = 0, UsrName = 0;
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
function sub(obj){
	chekUsName();//用户名
	chekUsrName();//真实姓名
	chekJiaose();//角色类型
	chekBumen();//所属部门

	if(user == 1 && UsrName == 1)
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

</div>
<article>
</div>
<script type="text/javascript" src="/static/js/public.js"></script>
</body>
</html>
