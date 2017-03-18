<?php  if (!defined("IS_INITPHP")) exit("Access Denied!");  /* INITPHP Version 1.0 ,Create on 2017-03-18 19:48:55, compiled from ./web/template/left_nav.htm */ ?>
<div class="leftnav">
  <h2><span class="icon-user"></span>系统管理</h2>
  <ul style="display:block">
    <li><a href="/department/run" target="right"><span class="icon-caret-right"></span>部门管理</a></li>
	<li><a href="/admingroup/run" target="right"><span class="icon-caret-right"></span>角色管理</a></li>
	<li><a href="/admin/run" target="right"><span class="icon-caret-right"></span>用户管理</a></li>
	<li><a href="/customer/run" target="right"><span class="icon-caret-right"></span>客户分配</a></li>
  </ul> 
  <h2><span class="icon-user"></span>业绩管理</h2>
  <ul style="display:block">
    <li><a href="/myResults/run" target="right"><span class="icon-caret-right"></span>我的业绩</a></li>
<li><a href="/myClients/run" target="right"><span class="icon-caret-right"></span>我的客户</a></li>
  </ul> 

</div>
