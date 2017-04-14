
// 无限级函数
function listtree(data, id, list, level) {
	id = parseInt(id) || 0;
	level = parseInt(level) || 1;
	list = list || '';
	var k, d, h = '';
	for(k in data) {
		d = data[k];
		if (d.classid == id) {
			h+= '<li class="list'+level+'"'+'>'+'<span class="ids">'+d.id+
			'</span>'+'<span class="pid">'+d.classid+'</span>'+'<data>'+d.dataname+'</data>'+'</li>';
			h+= arguments.callee(data.slice(k), d.id, list, level+1);
		}
	}
	d = null;
	if (h) {
		list+= '<ul>'+ h +'</ul>';
		h = null;
	}
	return list;
}
/*测试数据*/
var data = [
	{id:0,classid:-1,dataname:'集团总部'},
  {id:1,classid:0,dataname:'承德'},
  {id:2,classid:0,dataname:'北京'},
  {id:3,classid:0,dataname:'廊坊'},
  //二级承德
  {id:4,classid:1,dataname:'承德一区'},
  {id:5,classid:1,dataname:'承德二区'},
  {id:6,classid:1,dataname:'承德三区'},
  //三级承德
  {id:7,classid:4,dataname:'兴隆'},
  {id:8,classid:4,dataname:'滦平'},
  {id:9,classid:5,dataname:'兴隆'},
  {id:10,classid:5,dataname:'滦平'},
  {id:11,classid:6,dataname:'兴隆'},
  {id:12,classid:6,dataname:'滦平'},
  //四级承德
  {id:13,classid:7,dataname:'兴隆一部'},
  {id:14,classid:7,dataname:'兴隆二部'},
  {id:15,classid:7,dataname:'兴隆三部'},
  //五级承德
  {id:16,classid:13,dataname:'兴隆一部A团'},
  {id:17,classid:13,dataname:'兴隆一部B团'},
  {id:18,classid:13,dataname:'兴隆一部C团'},
  //二级北京
  {id:19,classid:2,dataname:'通州'},
  //三级北京
  // {id:20,classid:19,dataname:'通州一区'},
  // {id:21,classid:19,dataname:'通州二区'},
  //二级廊坊
  {id:22,classid:3,dataname:'廊坊一区'},
  {id:23,classid:3,dataname:'廊坊二区'},
  {id:24,classid:16,dataname:'asdasdasd'},
  {id:25,classid:24,dataname:'asdasdasd'},
  {id:26,classid:25,dataname:'asdasdasd'},

];
/*执行*/
$('#listbox').append(listtree(data));


//离别点击下啦事件
$('#listbox li[class^=list]').hover(function(){
   btnIcon(this);
},function(){
  $(this).removeClass('list2Active');
  $('i[class^="icons"]').remove();
});
//展开事件
$('#listbox').on('click','li[class^="list"]',function(){
   $(this).next('ul').slideToggle(300);
});
// 增删改按钮添加调用函数
function btnIcon(element){
	$(element).addClass('list2Active');
	$('<i class="icons_3"></i><i class="icons_2"></i><i class="icons_1"></i>').appendTo(element);
	var rHeight = $('.right').height();
	$('.left').css('height',(rHeight+20)+'px');
}

// 增删改事件
$('#listbox').on('click','i[class^="icons"]',function(e){
		var id = $(this).siblings('.ids').text();
		var nxetElement = $($(this).parent().next().find('.pid')[0]).text();
		var dataname = $(this).siblings('data').text();
		var prevdataname = $(this).parent().parent().prev().find('data').text();
		var previd = $(this).parent().parent().prev().find('.ids').text();
		console.log(prevdataname);
		var thisClass = $(this).attr('class');
		var thisparentClass = $(this).parent().attr('class');
    if(thisClass=='icons_3'){ //删除操作
			var dels = confirm('是否要删除部门?');
			if(dels){
				if(id!=nxetElement){ //判断子集与父级是否有关联id
					//设置ajax请求 发送当前id到后端 执行删除
					  $(this).parent().remove();
						alert('删除成功!');
					}else {
						alert('部门非空删除失败!');
					}
			}
		}else {
			setModlemsg(thisClass,id,previd,dataname,prevdataname,thisparentClass);
		}
		e.stopPropagation(); //组织默认事件
});

//弹窗文本信息
var setModleText = {
    icons_2:{ //修改
    add_btn:'保存修改',
    tb_titles:'部门信息修改'
    },
    icons_1:{
      add_btn:'新增部门',
      tb_titles:'新建部门'
      }
    };

//设置弹窗信息
function setModlemsg(btnName,id,previd,dataname,prevdataname,parentClass){
	$('.add_btn').text(setModleText[btnName].add_btn);
	$('.tb_titles').text(setModleText[btnName].tb_titles);
	$('.subdepnum').val(id);
	$('#subdep').val(dataname);
	$('#subdep,.subdepnum').attr('disabled',true);
	if(btnName=='icons_2'){ //如果是修改
		$('#depname').val(dataname);
		$('#subdep').val(prevdataname).attr('disabled',false);
		$('.subdepnum').val(previd);
		$('#selectlist').append(listtree(data));
	}
	if(parentClass.indexOf('list1')!=-1){
		$('#subdep').val('中承集团');
		$('.subdepnum').val('0');
	}
	$('.setModle').show();
}
// 下拉框点击事件
$('#subdep').click(function(){
   $('#selectlist').slideToggle(300);
});
$('#selectlist').on('click','li[class^="list"]',function(){
	var dataname = $(this).find('data').text();
	var id = $(this).find('.ids').text();
	$('.subdepnum').val(id);
	$('#subdep').val(dataname);
	$('#selectlist').css('display','none');
});
//弹窗提交按钮事件
function dropDown(){
 //ajax 提交事件处理写在这里
}
