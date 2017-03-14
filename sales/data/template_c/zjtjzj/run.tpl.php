<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2017-02-10 08:56:21, compiled from ./web/template/zjtjzj/run.htm */ ?>
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
	.table th{padding:4px;};
	.table td{padding:4px;}
	.name1{width:100px;}
	.td1{padding-left:27px !important;text-align:left;}
	.td2{padding-left:44px !important;text-align:left;}
	.td3{padding-left:55px !important;text-align:left;}
	.td4{padding-left:23px !important;}
	.pagelist{text-align:left;}
	.button {
	    height: 28px;
	    padding: 0px 13px;
	    line-height: 28px;
	}
	</style>
</head>
<body>
<div class="panel admin-panel"  style="width: 1500px;">
  <div class="panel-head"><strong class="icon-reorder"> 推荐总监列表</strong></div>
   <div class="padding border-bottom">
			<ul class="search" style="padding-left:10px;">
				<li>总监姓名
				 <input class="name1" type="text" id="username" name="username" value="<?php echo $username;?>"/>
				  &nbsp;&nbsp;
				  总监手机号
				  <input type="text" id="phone" name="phone" value="<?php echo $phone;?>" />
				</li> 
				<li>投资时间搜索：</li>
				<li>开始时间
				 <input type="text" class="sang_Calender" id="start_date" name="start_date" value="<?php echo $start_date;?>"/>
				  &nbsp;&nbsp;
				  结束时间
				  <input type="text" class="sang_Calender" id="end_date" name="end_date" value="<?php echo $end_date;?>" />
				</li> 
				
				<li>
				  <a href="javascript:void(0)" class="button border-main icon-search" onclick="zongjiansearch('<?php echo $page;?>')" > 搜索</a>
				</li>
		  </ul>
	</div>
  <table class="table table-hover text-center" style="max-width:none; width:1500px">
    <tr>   
      <th width="3%">真实姓名</th>    
	  <th width="4%">手机号</th> 
	  <th width="4%" >是否投资</th> 
	  <th width="4%" >名下经纪人数量</th> 
	  <th width="4%" >名下投资人数量</th>
	  <th width="5%">创建时间</th> 	
	  <th width="3%">推荐人姓名</th>
	  <th width="6%">部门交易额</th> 
	  <th width="6%">部门年化交易额</th>
	  <th width="3%">推荐津贴比例</th> 
	  <th width="3%">推荐津贴</th>	  
	  <th width="2%">状态</th>  	
      <!--  <th width="250">详情</th>-->
    </tr>
   <?php foreach ($list as $key=>$vo) { ?>
	<tr>
		<td width="3%"><?php echo $vo['UsrName'];?></td>
		<td width="4%" ><?php echo $vo['phone'];?></td>
		<td width="4%" ><?php if ($vo['isTouZi']=='T') { ?>已投资<?php } else { ?>未投资<?php } ?></td>
		<td width="4%" ><?php echo $vo['jingjirenCout'];?></td>
		<td width="4%" ><?php echo $vo['touzirenCout'];?></td>
		<td width="5%" ><?php echo $vo['regtime'];?></td>
		<td width="3%"><?php echo $vo['tuijianrenName'];?></td>
		<td width="6%"><?php echo $vo['touzizongCout'];?>(元)</td>
		<td width="6%"><?php echo $vo['touzinianhuaCout'];?>(元)</td>
		<td width="3%"><?php echo $vo['tuijianjingtiebili'];?>%</td>
		<td width="3%"><?php echo $vo['tuijianjingtie'];?></td>
		<td width="2%">
			<?php if ($vo['status']==1) { ?>
			启用
			<?php } else { ?>
			禁用
			<?php } ?>
		</td>
      <!--<td><div class="button-group"><a type="button" class="button border-main" href="/friends/zongjian_edit/id/<?php echo $vo['id'];?>"><span class="icon-edit"></span>查看</a></div></td>-->
	</tr>
	<?php } ?>
	<tr>
		<td width="2%" colspan="2" class="td1">部门交易额合计:<?php echo $zjiaoyieheji;?>(元)</td>
        <td width="2%" colspan="2" class="td2">年化交易额合计:<?php echo $znianhuajiaoyieheji;?>(元)</td>
        <td width="2%" colspan="2" class="td3">推荐津贴合计:<?php echo $ztuijianjingtie;?>(元)</td>
        <td colspan="17"></td>
    </tr>
    <tr>
    <td colspan="17" class="td4"><?php echo $pages;?></td>
    </tr>
  </table>
</div>
</body>
<script src="/static/js/datetime.js"></script>
</html>