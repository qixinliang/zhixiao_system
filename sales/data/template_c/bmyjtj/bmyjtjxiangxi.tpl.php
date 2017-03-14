<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2017-02-10 08:51:00, compiled from ./web/template/bmyjtj/bmyjtjxiangxi.htm */ ?>
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
	.table th{padding:8px;}
	.table td{padding:8px;}
	.td1{padding-left:62px !important;text-align:left;}
	.td2{padding-left:43px !important;text-align:left;}
	#button1{width:60px;height:27px;border-radius:5px;background-color:#fff;border:1px solid #ddd;}
	</style>
</head>
<body>
<div class="panel admin-panel"  style="width: 1200px;">
    <div class="panel-head"><strong class="icon-reorder">  部门业绩统计详情 &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="button1" id="button1" value="返回" onclick="history.go(-1);"></strong></div>
    
    <table class="table table-hover text-center" style="max-width:none; width:1200px">
      <tr>
        <th width="200px">姓名</th>       
        <th width="200px">手机号</th>
        <th width="200px">邮箱</th>
        <th width="200px">本人是否投资</th>
        <th width="200px">是否实名认证</th> 
        <th width="200px">注册时间</th> 
        <th width="200px">交易额</th>   
        <th width="150px">年化交易额</th> 
      </tr>   
					
	  <?php foreach ($list as $key=>$vo) { ?>
        <tr>
          <td><?php echo $vo['username'];?></td>
          <td>
			<?php echo $vo['phone'];?>
		  </td> 
          <td>
			<?php echo $vo['email'];?>
		  </td>     
		  <td>
			<?php if ($vo['isTouZi']=='T') { ?>
				是
			<?php } else { ?>
				否
			<?php } ?>
			
			</td>
          <td>
			<?php if (!$vo['IdNo']) { ?>
				否
			<?php } else { ?>
				是
			<?php } ?>
		  </td>
          <td>
          <?php echo date("Y-m-d H:i:s",$vo['regtime']);?>
          </td>
          <td>
          <?php echo $vo['order_money'];?>
      	</td>
      	<td>
          <?php echo $vo['nianhuajiaoyie'];?>
      	</td>
        </tr>
	  <?php } ?>
	  
     <tr>
        <td colspan="2" class="td1">交易额合计:<?php echo $zjiaoyieheji;?></td>
        <td colspan="2" class="td2">年化交易额合计:<?php echo $znianhuajiaoyieheji;?></td>
        <td colspan="10"></td>
    </tr>
      <tr><td colspan="17" class="td4"><?php echo $pages;?></td></tr>
    </table>
  </div>
</form>
</body>
</html>
<script src="/static/js/datetime.js"></script>