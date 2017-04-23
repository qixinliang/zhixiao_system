//每个html页面加个隐藏域，用于存储取到的部门数据。
$(function(){
var arr = [];
var jsonData = $('#d_list').text();
var json = JSON.parse(jsonData);
for(key in json){
	arr.push(json[key]);
}

function listtree(data,id,list,level){
	id = parseInt(id) || 1;
    level = parseInt(level) || 1;
    list = list || '';
	var k, d, h = '';
	
	for(k in data){
		d = data[k];
        if (d.p_dpt_id == id) {
        	h+= '<li class="list'+level+'"'+'>'+'<span class="ap"></span><span class="ficons"></span>'+'<span class="ids">'+d.department_id+'</span>'+'<span class="pid">'+d.p_dpt_id+'</span>'+'<data>'+d.department_name+'</data>'+'</li>';
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

document.getElementById('tcbox').getElementsByClassName('tcbox_left')[0].innerHTML = listtree(arr);


function iconsFun(element){
	var listNext = $(element).next();

    $(element).each(function(i,e){
    	if(!$(e).next('ul').length){
			$(e).find('.ap').hide();
    	}
	});

    if(listNext.is('ul')&&listNext.is(':visible')){
    	$(element).find('.ficons').css('background','url(/static/images/kai.png) no-repeat');
        $(element).find('.ap').css('background','url(/static/images/jian.png) no-repeat');
    }else{
        $(element).find('.ficons').css('background','url(/static/images/bi.png) no-repeat');
        $(element).find('.ap').css('background','url(/static/images/jia.png) no-repeat');
    }
}
iconsFun('[class^="list"]');//执行
//展开事件
$('#tcbox').on('click','.ap',function(){
	var $_this = $(this).parent();
    $_this.next('ul').slideToggle(function(){
    	iconsFun($_this);
    });
});

//数据插入事件
$('#tcbox').on('click','[class^="list"]>data',function(){
  // $('#dpt_name').val($(this).html());
  // $('#tcbox').css('display','none');
  // $('#department_id').val($(this).siblings('.ids').html());
  // alert($(this).html());
  var dpt_id = $(this).siblings('.ids').html();
       console.log(dpt_id);
   $.ajax({
      url:'/customer/user',
      type:'post',
      data:{department_id:dpt_id},
      success:function(data){
       var user = JSON.parse(data).value[0]; 
      $('<tr class="user"><td class="user_uid">'+user.uid+'</td><td class="user_name">'+user.u_name+'</td><td>'+user.role_name+'</td></tr>')
       .appendTo('.tcbox_right>table'); 
      }
  });
});
//人员信息获取
 $('.tcbox_right>table').on('click','.user',function(){
       var uid = $(this).find('.user_uid').text();
       var u_name = $(this).find('.user_name').text();
       console.log(u_name);
});
//焦点弹窗事件
$('#dpt_name').focus(function(){
    $('#tcbox').css('display','block');
});

//关闭事件
$('#ctcs_btn').click(function(){
	$('#tcbox').css('display','none');
});

});
