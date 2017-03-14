<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2017-02-10 08:31:01, compiled from ./web/template/friends/zongjian_editinfo.htm */ ?>
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
  <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o"></span>查看详情</strong></div>
  <div class="body-content">
	<form id="form" class="form-x" >
	
		<div class="form-group">
		  <div class="label">
			<label>角色组：</label>
		  </div>
		  <div class="field">
			<select name="gid" id="gid" class="input w50">
				<option value="<?php echo $info['info_group_id'];?>"><?php echo $info['info_group'];?></option>
			</select>
		  </div>
		</div>

      <div class="form-group">
        <div class="label">
          <label>角色账号：</label>
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
          <label>推荐人姓名：</label>
        </div>
        <div class="field">
		  <input name="tuijianrenName" type="text" class="input w50" id="tuijianrenName" value="<?php echo $info['tuijianrenName'];?>"/>       
        </div>
      </div> 
	  
      <div class="form-group">
        <div class="label">
          <label>推荐人手机号：</label>
        </div>
        <div class="field">
		  <input name="tuijianrenPhone" type="text" class="input w50" id="tuijianrenPhone" value="<?php echo $info['tuijianrenPhone'];?>"/>       
        </div>
      </div> 
     </div>
	 
    </form>
  </div>
</div>

</body>
</html>