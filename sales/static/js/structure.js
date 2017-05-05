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
			'</span>'+'<span class="pid">'+d.p_dpt_id+'</span>'+'<data class="dataname">'+d.department_name+'</data>'+'<data class="renshu">'+'['+d.user_cnt+'人]'+'</data>'+'<i class="icon_sj">'+'</i>'+'</li>';
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

//根级添加部门事件
 $('.rootBtn').click(function(){
     $('.setModle').show();
     $('#subdep').val('中创晟业');
 	$('.subdepnum').val('1');
    $('#selectlist').remove();
     $('#subdep,.subdepnum').attr('disabled',true);
 });

// 增删改事件
$('#listbox').on('click','i[class^="icons"]',function(e){
		var id = $(this).siblings('.ids').text();
		var nxetElement = $($(this).parent().next().find('.pid')[0]).text();
		var dataname = $(this).siblings('.dataname').text();
		var prevdataname = $(this).parent().parent().prev().find('.dataname').text();
		var previd = $(this).parent().parent().prev().find('.ids').text();
		var thisClass = $(this).attr('class');
		var thisparentClass = $(this).parent().attr('class');
    if(thisClass=='icons_3'){ //删除操作
			var dels = confirm('是否要删除部门?');
			if(dels){
				if(id!=nxetElement){ //判断子集与父级是否有关联id
					//设置ajax请求 发送当前id到后端 执行删除
					  $(this).parent().remove();
						$.ajax({
							url: '/department/del',
        					type: 'post',
        					dataType:'json',
        					data: {'department_id':id},
        					success: function(data) {
            					if(data.status==1){
									alert('删除成功！');
                					window.location.href="/department/run";
            					}else{
									alert('删除失败!');
                					return false;
            					}
							}
    					});
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
		$('#depname').val(dataname).parent().append('<span id="cur_dep_id" style="display:none">'+id+'</span>');
		$('#subdep').val(prevdataname).attr('disabled',false);
		$('.subdepnum').val(previd);
	if($('#selectlist')!=Object){
 			$('<div id="selectlist"></div>').appendTo('.selectlist');
 		}
		$('#selectlist').append(listtree(arr));
        $('.add_btn').addClass('edit');
	if(parentClass.indexOf('list1')!=-1){
		$('#subdep').val('中创晟业');
		$('.subdepnum').val('1');
		$('#selectlist').remove();
	}
	}
	$('.setModle').show();
}
// 下拉框点击事件
$('#subdep').click(function(){
   $('#selectlist').slideToggle(300);
});
$('#selectlist').on('click','li[class^="list"]',function(){
	var dataname = $(this).find('.dataname').text();
	var id = $(this).find('.ids').text();
	$('.subdepnum').val(id);
	$('#subdep').val(dataname);
	$('#selectlist').css('display','none');
});

$(".add_btn").click(function(){
	var url='/department/addSave';
 	var depname = $('#depname').val();
 	var subdep = $('#subdep').val();
 	var pDptId = $('#p_dpt_id').val();

 	var curDepId = $('#cur_dep_id').text();

 	var data={'p_dpt_id':pDptId,'name':depname};

    var dpt = $(this).attr('class');
	if(dpt.indexOf('edit') != -1){
		url='/department/editSave';
		//当前部门ID
 		data={'department_id':curDepId,'p_dpt_id':pDptId,'name':depname};
	}
	dropDown(url,data);
})

//弹窗提交按钮事件
function dropDown(url,data){
    var depname = $('#depname').val();
    if(depname){
    	$.ajax({
      		url: url,
        	type: 'post',
        	dataType:'json',
        	data: data,
        	success: function(data) {
            	if(data.status==1){
                	window.location.href="/department/run";
            	}else{
                	$("#tipss").html("<font color=\"red\">"+data.message+"</font>");
                	setTimeout(function() {
                    	$("#tipss").html('');
                	}, 3000);
                	return false;
            	}
			}
    	});
    }else {
    	alert('部门名称不能为空！');
    }

}
