/*
 * @个人统查询
 * 
 */
function changesearch(p){
	var start_date 	= $("#start_date").val();
	var end_date 	= $("#end_date").val();
	this.location.href = p+'&start_date='+start_date+'&end_date='+end_date;
}

/*
*
*@经纪人查询
*/
function jingjirensearch(p){
	var start_date 	= $("#start_date").val();
	var end_date 	= $("#end_date").val();
	var username	= $("#username").val();
	var phone		= $("#phone").val();
	var str = '';
	if(username !=''){
		str+='&username='+username;
	}
	if(phone!=''){
		str+='&phone='+phone;
	}
	this.location.href = p+'&start_date='+start_date+'&end_date='+end_date+str;
}

/*
*
*@总监查询
*/
function zongjiansearch(p){
	var start_date 	= $("#start_date").val();
	var end_date 	= $("#end_date").val();
	var username	= $("#username").val();
	var phone		= $("#phone").val();
	var str = '';
	if(username !=''){
		str+='&username='+username;
	}
	if(phone!=''){
		str+='&phone='+phone;
	}
	this.location.href = p+'&start_date='+start_date+'&end_date='+end_date+str;
}


/*
*
*@经纪人查看推荐经纪人列表
*/
function jingjirentuijianjingjirenserch(p){
	var start_date 	= $("#start_date").val();
	var end_date 	= $("#end_date").val();
	this.location.href = p+'&start_date='+start_date+'&end_date='+end_date;
}

/*
*@用户修改银行卡信息
*/
function bankedit(id){
	this.location.href = 'GRXX/bankedit/id/'+id;
}

/*
*@选择年份
*/
function showyue(nianfen){
	$.post("/WDSR/getYuefen",{"nianfen":nianfen},  
    function(data){  
		$("#showyuefen").html(data);
    });  
}

/*
*
*@年份和月份查询，我的收入专用
*/
function searchnianyue(){
	var nian = $("#nianfen").val();
	var yue  = $("#yuefen").val();
	if(nian!='0' && yue!='0'){
		this.location.href = '/WDSR/run/nian/'+nian+'/yue/'+yue;
	}else{
		this.location.href = '/WDSR/run/';
	}
}