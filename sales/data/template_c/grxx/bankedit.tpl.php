<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2017-02-10 08:39:24, compiled from ./web/template/grxx/bankedit.htm */ ?>
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
		.w50{width:22%;float:left;}
		.input{padding:0;line-height:30px;text-indent:10px;}
	</style>
</head>
<body>
<div class="panel admin-panel margin-top">
  <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o"></span>修改银行卡信息</strong></div>
  <div class="body-content">
	<form id="form" class="form-x" >
	

      <div class="form-group">
        <div class="label">
          <label>银行卡号：</label>
        </div>
        <div class="field">
		  <input name="bank_number" type="text" class="input w50" id="bank_number" value="<?php echo $info['card_number'];?>"/> 
		  <div class="tips_bank_number" style="float: left;padding-left: 10px;color: #888;line-height: 42px;"></div>		  
        </div>
      </div> 
	  
      <div class="form-group">
        <div class="label">
          <label>开户行名称：</label>
        </div>
        <div class="field">
		  <input name="bank_opens_name" type="text" class="input w50" id="bank_opens_name" value="<?php echo $info['bank_opens_name'];?>" />   
		  <div class="tips_bank_opens_name" style="float: left;padding-left: 10px;color: #888;line-height: 42px;"></div>	
        </div>
      </div> 
      
      <div class="form-group">
        <div class="label">
          <label>开户人姓名：</label>
        </div>
        <div class="field">
		  <input name="bank_opens_user" type="text" class="input w50" id="bank_opens_user" value="<?php echo $info['bank_opens_user'];?>" />   
		  <div class="tips_bank_opens_user" style="float: left;padding-left: 10px;color: #888;line-height: 42px;"></div>	
        </div>
      </div> 
      
      <div class="form-group">
        <div class="label">
          <label>居住地址：</label>
        </div>
        <div class="field">
		  <input name="address" type="text" class="input w50" id="address" value="<?php echo $info['address'];?>" />   
		  <div class="tips_bank_address" style="float: left;padding-left: 10px;color: #888;line-height: 42px;"></div>	
        </div>
      </div> 
      
      
     <div class="form-group">
        <div class="label">
          <label></label>
        </div>
        <div class="field">
		    <input name="id" id="id" type="hidden" value="<?php echo $id;?>" />
			<input type="hidden" name="init_token" id="init_token" value="<?php echo $init_token;?>"/>
            <button  class="button bg-main icon-check-square-o" type="button" onclick="sub(this)"> 修改</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript">
var bank_number = 0, bank_user = 0 ,bank_name=0,bank_address_staus=0;
//银行卡卡号
function chekbank_number()
{
    var s = $("#bank_number").val();
    if(s == ""){
        $(".tips_bank_number").html("<font color=\"red\">卡号不能为空</font>").show();
		return false;
	}else{
		$(".tips_bank_number").html("").show();
		bank_number = 1;
	}
}
//开户行
function chekbank_bank_opens_name()
{
	var bank_opens_name = $("#bank_opens_name").val()
    if (bank_opens_name== "")
    {
    	$('.tips_bank_opens_name').html('<font color="red">请填写开户行名称！</font>').show();
    	return false;
    } else {
    	$(".tips_bank_opens_name").html("").show();
    	bank_name = 1;
    }
}
//开户人姓名
function chekbank_bank_opens_user()
{
	var bank_opens_user = $("#bank_opens_user").val()
    if (bank_opens_user== "")
    {
    	$('.tips_bank_opens_user').html('<font color="red">请填写开户人姓名！</font>').show();
    	return false;
    } else {
    	$(".tips_bank_opens_user").html("").show();
    	bank_user = 1;
    }
}

///居住地址
function chekbank_address()
{
	var bank_address = $("#address").val();
    if (bank_address== "")
    {
    	$('.tips_bank_address').html('<font color="red">请填写居住地址！</font>').show();
    	return false;
    } else {
    	$(".tips_bank_addressr").html("").show();
    	bank_address_staus = 1;
    }
}

function sub(obj){
	chekbank_number();
	chekbank_bank_opens_name();
	chekbank_bank_opens_user();
	chekbank_address();
	if(bank_number == 1 && bank_name==1 && bank_user==1 && bank_address_staus==1)
	{
		$.ajax({
			url: '/GRXX/<?php echo $action;?>_save',
			type: 'post',
			dataType:'json',
			data: $('#form').serialize(),
			success: function(data) {
				if(data.status==1){
					alert(data.message);
					window.location.href="/GRXX/index";
					return true;
				}else{
					alert(data.message);
					return false;
				}
			}
		});
	}else{
		return false;
	}
};

</script>
</body>
</html>