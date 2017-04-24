
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
var ulhref = window.location.href;
var iR = 'index/run';
var dpR = 'department/run';
$('.right').css('width',(wdWidth-185)+'px');
$('.left').css('height',(rHeight+20)+'px');
if(ulhref.indexOf(iR)!=-1||ulhref.indexOf(dpR)){
  $('.right').css('width',(wdWidth-205)+'px');
}

// 顶部导航高亮
function ArticleFun(){
  var urls = window.location.href;
  var menu = document.getElementById('menu');
  var menuLi = menu.getElementsByTagName('li');
  for(var i=0;i<menuLi.length;i++){
     var attHref = menuLi[i].firstChild.getAttribute('href');
    if(urls.indexOf(attHref)!=-1){
      $(menuLi[i]).find('a').css('color','#fdd82d');
      $('<span class="article"></span>').appendTo($(menuLi[i]));
	 /* $(menuLi[i]).style.color='#FDD82D';*/
     }
   }
//  var leftList = document.getElementsByClassName('left_list')[0];
//  var lnavLi = leftList.getElementsByTagName('li');
//  console.log(lnavLi);
//  for(var j=0;j<lnavLi.length;j++){
//    var leftAhref = $(lnavLi[j]).find('a').attr('href');
//    if(urls.indexOf(leftAhref)!=-1){
//     $(lnavLi[j]).css({'border-left-color':'#1e7cf2','background-color':'#2e3143'});
//   }
//  }
};
//ArticleFun(); //执行高亮函数
