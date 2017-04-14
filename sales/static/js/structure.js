// 无限级函数
var arr = [];
var jsonData = $("#d_list").text();
var data = JSON.parse(jsonData);
for(k in data) {
	arr.push(data[k]);
}

function listtree(data, id, list, level) {
	id = parseInt(id) || 1;
	level = parseInt(level) || 1;
	list = list || '';
	var k, d, h = '';
	for(k in data) {
		d = data[k];
		if (d.p_dpt_id == id) {
			h+= '<li class="list'+level+'"'+'>'+'<span class="ids">'+d.department_id+
			'</span>'+'<span class="pid">'+d.p_dpt_id+'</span>'+'<data>'+d.department_name+'</data>'+'</li>';
			h+= arguments.callee(data.slice(k), d.department_id, list, level+1);
		}
	}
	d = null;
	if (h) {
		list+= '<ul>'+ h +'</ul>';
		h = null;
	}
	return list;
}

/*执行*/
$('#listbox').append(listtree(arr));


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
		console.log(previd);
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
		$('#selectlist').append(listtree(arr));
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
