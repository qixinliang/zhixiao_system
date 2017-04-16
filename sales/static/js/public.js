
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
var wdWidth = document.body.clientWidth;
var rHeight = $('.right').height();
$('.right').css('width',(wdWidth-185-20)+'px');
$('.left').css('min-height',(rHeight+20)+'px');

// 顶部导航高亮
function ArticleFun(){
  var urls = window.location.href;
  var menu = document.getElementById('menu');
  var menuLi = menu.getElementsByTagName('li');
  for(var i=0;i<menuLi.length;i++){
     var attHref = menuLi[i].firstChild.getAttribute('href');
    if(urls.indexOf(attHref)!=-1){
      $('<span class="article"></span>').appendTo($(menuLi[i]));
      $(menuLi[i]).style.color='#FDD82D';
    }
  }
};
ArticleFun(); //执行高亮函数


