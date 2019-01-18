(function($){
$.fn.toolTip = function(settings){
    settings = $.extend({}, $.fn.toolTip.defaults, settings);		
    return this.each(function(){
    var obj = $(this);
    var txt = obj[0].title;
    if(!txt) return false;
    var left = obj.offset().left;
    var top = obj.offset().top;
    var divid='showTip';
    if(settings.align=='right'){var divid='showTip_right';}
    var showTip = function(){
	  	
     $(document.body).append("<div id='"+divid+"'><div class='tip-tp'></div><div id='tips' class='tip-ct'></div><div class='tip-bt'></div></div>");
     obj[0].title = '';
     $("#tips").html(txt);
     if(settings.align=='right'){
        var tipLeft = left+obj.outerWidth();
        var tipTop=top;
        
     }else{
        var tipTop = top-$("#"+divid).outerHeight();
        var tipLeft = left;
     }
     $("#"+divid).css({'position':'absolute',left:tipLeft,top:tipTop}).show();
    };
    var hideTip = function(){
     $("#"+divid).remove();
    };
    //$(this).hover(function(){showTip();},function(){hideTip();}).focus(function(){showTip();}).blur(function(){hideTip();});
    $(this).focus(function(){showTip();}).blur(function(){hideTip();});
   });
}
$.fn.toolTip.defaults ={
	align : 'top'
}
})(jQuery)
