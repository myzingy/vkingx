// JavaScript Document
// $("#featured").slideVking({auto:true,time:3000,width:680,height:300});
jQuery.fn.slideVking=function(settings){
	settings = jQuery.extend({
		auto:true,
		time:4000,
		width:719,
		height:400,
		color:"#7ABA02"
	}, settings);
	var div=$(this).children("div");
	var ul=$(this).children("ul");
	var pos=0;
	var length=$(ul).find("li").length;
	//初始化
	$(this).css({width:settings.width+'px',height:settings.height+'px','border-color':settings.color});
	$(ul).css({left:settings.width+'px','border-color':settings.color});
	//渲染结束
	var now=$(ul).find('li')[pos];
	$(now).addClass("ui-tabs-selected");
	var sid=$(now).find("a").attr("rel");
	$(sid).removeClass("ui-tabs-hide");
	//初始化
	var atime='';
	if(settings.auto){
		atime=setInterval(function(){next();},settings.time);
		$(this).mouseover(function(){
			clearInterval(atime);
		}).mouseout(function(){
			clearInterval(atime);
			atime=setInterval(function(){next();},settings.time);
		});
	}
	return $(ul).find("li").click(function(){
		var now=$(this).find("a").attr("rel").match(/[0-9]/);
		pos=now-1;
		show(pos);
	});
	function next(){
		pos=(pos<length-1)?(pos+1):0;
		show(pos);
	}
	function show(pos){
		$(ul).find('li').removeClass("ui-tabs-selected");
		$(div).addClass("ui-tabs-hide");
		var now=$(ul).find('li')[pos];
		$(now).addClass("ui-tabs-selected");
		var sid=$(now).find("a").attr("rel");
		$(sid).removeClass("ui-tabs-hide");
		if(settings.auto){
			clearInterval(atime);
			atime=setInterval(function(){next();},settings.time);
		}
	}
}
$(function(){
	$('.featured2').slideVking({color:'#203F4A'});
});