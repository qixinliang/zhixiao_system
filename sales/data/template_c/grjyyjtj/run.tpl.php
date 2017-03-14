<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2017-02-10 08:56:25, compiled from ./web/template/grjyyjtj/run.htm */ ?>
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
	.td1{padding-left:27px !important;text-align:left;}
	.td2{padding-left:28px !important;text-align:left;}
	.td3{padding-left:39px !important;text-align:left;}
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
<form method="post" action="">
  <div class="panel admin-panel"  style="width: 1100px;">
    <div class="panel-head"><strong class="icon-reorder"> 个人交易佣金统计</strong></div>
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
				  <a href="javascript:void(0)" class="button border-main icon-search" onclick="changesearch('<?php echo $page;?>')" > 搜索</a>
				</li>
		  </ul>
		</div>
    <table class="table table-hover text-center" style="max-width:none;width:1100px;">
      <tr>
      	<th width="50">姓名</th>
      	<th width="70">手机号</th>
        <th width="70">项目名称</th>       
        <th width="60">项目期限</th>
        <th width="70">预期年化收益率</th>
        <th width="60">投资金额</th>
        <th width="100">投资时间</th>  
        <th width="50">年化交易额</th> 
	  	<th width="50">佣金比例</th> 
	  	<th width="50">佣金</th>    
      </tr>   
					
	  <?php foreach ($list as $key=>$vo) { ?>
       <tr>
        <td width="50"><?php echo $vo['username'];?></td>
        <td width="70"><?php echo $vo['phone'];?></td>
          <td width="70"><a href="http://www.baihedai.com/deal/show/id/<?php echo $vo['deal_id'];?>" target="_blank" ><?php echo $vo['title'];?></a></td>
          <td width="60">
			<?php if ($vo['expires_type']==1) { ?>
				<?php echo $vo['expires'];?>天
			<?php } else { ?>
				<?php echo $vo['expires'];?>月
			<?php } ?>
		  </td> 
          <td width="70">
			<?php if ($vo['jiangli_type']==2) { ?>
				<?php echo $vo['syl']-$vo['tiexi'];?>% +  <?php echo $vo['tiexi'];?>%
			<?php } else { ?>
				<?php echo $vo['syl'];?>%
			<?php } ?>
		  </td>     
          <td width="60"><?php echo $vo['order_money'];?>元</td>
          <td width="100"><?php echo date("Y-m-d H:i:s",$vo['order_time']);?></td>
          <td width="50"><?php echo $vo['NHJYE'];?></td>
          <td width="50"><?php echo $vo['yongjinbili'];?>%</td>
          <td width="50"><?php echo $vo['YONGJIN'];?></td>
        </tr>
	  <?php } ?>
	  
      <tr>
      	<td colspan="2" class="td1">投资金合计：<?php echo $touzijine;?></td>
      	<td colspan="2" class="td2">年化交易合计：<?php echo $nianhuajiaoyie;?></td>
      	<td colspan="2" class="td3">佣金合计：<?php echo $yongjin;?></td>
        <td colspan="17"></td>
        <tr><td colspan="17" class="td4"><?php echo $pages;?></td></tr>
      </tr>
    </table>
  </div>
</form>
</body>
</html>
<script src="/static/js/datetime.js"></script>