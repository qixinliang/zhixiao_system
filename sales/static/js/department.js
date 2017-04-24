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
  $('#dpt_name').val($(this).html());
   $('#tcbox').css('display','none');
   $('#department_id').val($(this).siblings('.ids').html());
  // alert($(this).html());
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
