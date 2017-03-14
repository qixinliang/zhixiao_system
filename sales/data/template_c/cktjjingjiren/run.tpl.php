<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2017-02-09 18:31:06, compiled from ./web/template/cktjjingjiren/run.htm */ ?>
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
	.table th{padding:8px;};
	.table td{padding:8px;}
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
<div class="panel admin-panel" style="width: 1500px;">
  <div class="panel-head"><strong class="icon-reorder"> 推荐经纪人列表</strong></div>
  <div class="padding border-bottom">
			<ul class="search" style="padding-left:10px;">
				<li>按时间搜索：</li>
				<li>开始时间
				 <input type="text" class="sang_Calender" id="start_date" name="start_date" value="<?php echo $start_date;?>"/>
				  &nbsp;&nbsp;
				  结束时间
				  <input type="text" class="sang_Calender" id="end_date" name="end_date" value="<?php echo $end_date;?>" />
				</li> 
				<li>
				  <a href="javascript:void(0)" class="button border-main icon-search" onclick="jingjirentuijianjingjirenserch('<?php echo $page;?>')" > 搜索</a>
				</li>
		  </ul>
	</div>
  <table class="table table-hover text-center" style="max-width:none; width:1500px">
    <tr>   
	  <th>角色组</th>
      <th>角色帐号</th>  
      <th>真实姓名</th>    
	  <th>手机号</th> 
	  <th>是否投资</th>  
	  <th>名下投资人数量</th>
	  <th>交易额</th><!-- 就是推荐的经纪人下的客户交易额总数 -->
	  <th>年化交易额</th><!-- 就是推荐的经纪人下的客户年化交易额总数 -->
	  <th>推荐津贴比例</th><!-- 固定值后台配置 -->
	  <th>推荐津贴</th><!-- 包含当前我登录的业绩和经纪人投资的业绩和邀请的客户业绩 -->
	  <th>创建时间</th> 	
	  <th>状态</th>  	
      <!-- <th width="250">详情</th> -->
    </tr>
   <?php foreach ($list as $key=>$vo) { ?>
	<tr>
		<td><?php echo $vo['gname'];?></td>
		<td><?php echo $vo['user'];?></td>
		<td><?php echo $vo['UsrName'];?></td>
		<td><?php echo $vo['phone'];?></td>
		<td><?php if ($vo['isTouZi']=='T') { ?>已投资<?php } else { ?>未投资<?php } ?></td>
		<td><?php echo $vo['touzirenCout'];?></td>
		<td><?php echo $vo['touzizongCout'];?></td>
		<td><?php echo $vo['touzinianhuaCout'];?></td>
		<td><?php echo $vo['tuijianjingtiebili'];?>%</td>
		<td><?php echo $vo['tuijianjingtie'];?></td>
		<td><?php echo $vo['regtime'];?></td>
		<td>
			<?php if ($vo['status']==1) { ?>
			启用
			<?php } else { ?>
			禁用
			<?php } ?>
		 <!--</td><td><div class="button-group"><a type="button" class="button border-main" href="/friends/zongjian_edit/id/<?php echo $vo['id'];?>"><span class="icon-edit"></span>查看</a></div></td>-->
	</tr>
	<?php } ?>
	<tr>
	 	<td>交易额合计:<?php echo $zjiaoyieheji;?></td>
        <td>年化交易额合计:<?php echo $znianhuajiaoyieheji;?></td>
        <td>津贴合计<?php echo $ztuijianjingtie;?></td>
        <td colspan="15"></td>
    </tr>
    <tr>
    <td colspan="15" class="td4"><?php echo $pages;?></td>
    </tr>
  </table>
</div>
</body>
<script src="/static/js/datetime.js"></script>
</html>