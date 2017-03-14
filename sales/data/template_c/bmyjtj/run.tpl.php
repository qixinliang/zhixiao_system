<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2017-02-10 08:56:24, compiled from ./web/template/bmyjtj/run.htm */ ?>
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
	.td1{padding-left:24px !important;text-align:left;}
	.td2{padding-left:14px !important;text-align:left;}
	.td3{padding-left:42px !important;text-align:left;}
	.td4{padding-left:20px !important;}
	.pagelist{text-align:left;}
	.button {
	    height: 28px;
	    padding: 0px 13px;
	    line-height: 28px;
	}
	</style>
</head>
<body>
<div class="panel admin-panel"  style="width: 1100px;">
  <div class="panel-head"><strong class="icon-reorder"> 部门业绩统计</strong></div>
  <div class="padding border-bottom">
			<ul class="search" style="padding-left:10px;">
				<li>搜索：</li>
				<li>开始时间
				 <input type="text" class="sang_Calender" id="start_date" name="start_date" value="<?php echo $start_date;?>"/>
				  &nbsp;&nbsp;
				  结束时间
				  <input type="text" class="sang_Calender" id="end_date" name="end_date" value="<?php echo $end_date;?>" />
				</li> 
				<li>真实姓名
				 <input type="text" id="username" name="username" value="<?php echo $username;?>"/>
				  &nbsp;&nbsp;
				  手机号
				  <input type="text" id="phone" name="phone" value="<?php echo $phone;?>" />
				</li> 
				<li>
				  <a href="javascript:void(0)" class="button border-main icon-search" onclick="jingjirensearch('<?php echo $page;?>')" > 搜索</a>
				</li>
		  </ul>
		</div>
  <table class="table table-hover text-center" style="max-width:none; width:1100px">
    <tr>   
      <th width="60">经纪人姓名</th>    
	  <th width="70">手机号</th> 
	  <th width="100">创建时间</th> 
	  <th width="80">名下投资人数量</th>
	  <th width="70">推荐人姓名</th> 
	  <th width="70">交易额</th>	
	  <th width="70">年化交易额</th> 
	  <th width="70">推荐津贴比例</th> 
	  <th width="50">推荐津贴</th>  
	  <th width="50">状态</th>  
	  <th width="50">交易明细</th> 	
      <!--  <th width="250">详情</th>-->
    </tr>
   <?php foreach ($list as $key=>$vo) { ?>
	<tr>
		<td><?php echo $vo['UsrName'];?></td>
		<td><?php echo $vo['phone'];?></td>
		<td><?php echo $vo['regtime'];?></td>
		<td><?php echo $vo['touzirenCout'];?></td>
		<td><?php echo $vo['tuijianrenName'];?></td>
		<td><?php echo $vo['touzizongCout'];?></td>
		<td><?php echo $vo['touzinianhuaCout'];?></td>
		<td><?php echo $vo['tuijianjingtiebili'];?>%</td>
		<td><?php echo $vo['tuijianjingtie'];?></td>
		<td>
			<?php if ($vo['status']==1) { ?>
			启用
			<?php } else { ?>
			禁用
			<?php } ?>
		</td>
     <td> 
     	<div class="button-group">
     	<a type="button"  href="/BuMenYeJiTongJi/BuMenMingXi/id/<?php echo $vo['uid'];?>"><span class="icon-edit"></span>查看</a>
     	</div>
     </td>
	</tr>
	<?php } ?>
	
	<tr>
        <td colspan="2" class="td1">交易额合计:<?php echo $zjiaoyieheji;?></td>
        <td colspan="2" class="td2">年化交易额合计:<?php echo $znianhuajiaoyieheji;?></td>
        <td colspan="2" class="td3">部门津贴合计<?php echo $ztuijianjingtie;?></td>
        <td colspan="11"></td>
    </tr>
    <tr><td colspan="17" class="td4"><?php echo $pages;?></td></tr>
  </table>
</div>
</body>
<script src="/static/js/datetime.js"></script>
</html>
