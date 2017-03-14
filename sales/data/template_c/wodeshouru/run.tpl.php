<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2017-02-10 08:56:23, compiled from ./web/template/wodeshouru/run.htm */ ?>
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
	.td1{border:1px solid #ccc;text-align:center;line-height:34px;background-color:#fff;box-sing:border-box;}
	.button{height:28px;padding:0px 13px;line-height:28px;}
	.select1{height:35px;width:150px;}
	.div1 ul{width:100%;padding:0;border-bottom:1px solid #ddd;height:34px;}
	.div1 ul li{float:left;width:20%;border-left:1px solid #ddd;height:34px;text-align:center;line-height:34px;}
	.table th{border-bottom:1px solid #ddd;}
	</style>
</head>
<body>
<form method="post" action="">
  <div class="panel admin-panel"  >
    <div class="panel-head"><strong class="icon-reorder"> 我的收入</strong></div>
		 
		<div class="div1">
			<ul>
				<li style="border-left:none;">个人佣金</li>
				<li>推荐津贴</li>
				<li>育成津贴</li>
				<li>管理津贴</li>
				<li>合计收入</li>
			</ul>
			<ul>
				<li style="border-left:none;"><?php echo $gerenyongjin;?></li>
				<li><?php echo $tuijianjintie;?></li>
				<li><?php echo $yuchengjinte;?></li>
				<li><?php echo $guanlijintie;?></li>
				<li><?php echo $quanbuhejishu;?></li>
			</ul>
		</div>
		<div class="padding border-bottom" style="margin-top:50px;">
			<ul class="search" style="padding-left:10px;">
				<li>搜索：</li>
				<li>
				 <select name="nianfen" onChange="showyue(this.value);" id="nianfen" class="select1">
				 <option value="0">请选择年份</option>
				 <?php foreach ($nianfen as $key=>$vo) { ?>
				   <option value="<?php echo $key;?>" <?php if ($key==$nian) { ?> selected="selected" <?php } ?> ><?php echo $vo;?></option>
				  <?php } ?>
				</select>
				  
				  <span id="showyuefen">
					  <?php if ($yuelist) { ?>
					  <select name="yuefen" id="yuefen">
					  	<?php foreach ($yuelist as $key=>$vo) { ?>
				   			<option value="<?php echo $vo;?>" <?php if ($vo==$yue) { ?> selected="selected" <?php } ?>><?php echo $vo;?></option>
				  		<?php } ?>
				  		</select>
					  <?php } else { ?>
					  	<select name="yuefen" id="yuefen" class="select1">
							<option value="0">请选择月份</option>
					 	</select>
					  <?php } ?>
				  </span>
				</li> 
				<li id="li1">
				  <a href="javascript:void(0)" class="button border-main icon-search" onclick="searchnianyue();" > 搜索</a>
				</li>
		  </ul>
		</div>
    <table class="table table-hover text-center" style="max-width:none;">
      <tr>
      	<th width="2%">日期</th>
      	<th width="2%">交易额</th>
        <th width="2%">年化交易额</th>       
        <th width="2%">佣金</th>
        <th width="2%">推荐津贴</th>
        <th width="2%">育成津贴</th>
        <th width="2%">管理津贴</th>  
      </tr>   
	  <?php foreach ($list as $key=>$vo) { ?>
        <tr>
        <td width="4%"><?php echo $key;?></td>
        <td width="4%"><?php echo $vo['jiaoyie'];?></td>
          <td width="6%"><?php echo $vo['NHJYE'];?></td>
          <td width="4%"><?php echo $vo['YONGJIN'];?>
		  </td> 
          <td width="6%">
			<?php echo $vo['tuijianjintie'];?>
		  </td>     
          <td width="6%">0.00</td>
          <td width="6%"><?php echo $vo['guanlijingite'];?></td>
        </tr>
	  <?php } ?>
	  
    <tr><td colspan="17" class="td4"><?php echo $pages;?></td></tr>
    </table>
    
  </div>
</form>
</body>
</html>
<script src="/static/js/datetime.js"></script>