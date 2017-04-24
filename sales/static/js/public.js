
// 新建部门窗口
$('#setdep').click(function(){
  var setWidth = $('.setModle').width();
  $('.setModle').css('display','block');
  $('<div class="mask"></div>').appendTo('.wrapper');
});
// 关闭新建部门窗口
$('#closeBtn').click(function(){
  $('.setModle').css('display','none').find('input').attr('disabled',false).val('');
  $('#selectlist').css('display','none');
  $('.mask').remove();
  $('#cur_dep_id').remove();
  $('.add_btn').attr('class','add_btn');

});
//右侧宽度设置
var wdWidth = document.body.offsetWidth;
var rHeight = $('.right').height();
var ulhref = window.location.href;
var iR = 'index/run';
var dpR = 'department/run';
var mR = 'myResults/run'
$('.right').css('width',(wdWidth-185)+'px');
$('.left').css('height',(rHeight+20)+'px');
if(ulhref.indexOf(iR)!=-1||ulhref.indexOf(dpR)!=-1||ulhref.indexOf(mR)!=-1){
  $('.right').css('width',(wdWidth-205)+'px');
}



//搜索按钮
$('[type="submit"]').each(function(i,e){
    if($(e).val()=='搜索'){
      $(e).css({'line-height':'25px','padding':'0 10px'});      
} 
});

