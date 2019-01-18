define(function(require, exports, module){
	var $=require('jquery');
	$.fn.page = function(options) {
		// Setup Plugin options
		var page = this;
		$(page).html();
		var settings = $.extend({
			//扩展参数
			func: "",
			total: 0,
			offset:0,
			rows: 20,
			maxcount: 10,
		}, options);
		// Declare main object
		var pagination='<div class="pagination  pagination-small" style="margin:0"><ul>';
		var isPageEvent=!$(page).html();
		$(page).html('');
		if(settings.total>settings.rows){
			var _maxpage=Math.ceil(settings.total/settings.rows);
			var _nowpage=(settings.offset/settings.rows)+1;
			_nowpage=_nowpage<1?1:(_nowpage>_maxpage?_maxpage:_nowpage);
			//console.log(_nowpage+'/'+_maxpage);
			var _box2=Math.ceil(settings.maxcount/2);
			var _cur=_nowpage-_box2;
			//console.log((_cur+settings.maxcount)+'>'+_maxpage);
			_cur=_cur+settings.maxcount>_maxpage?(_maxpage-settings.maxcount+1):_cur;
			_cur=_cur<1?1:_cur;
			var pagearr=[];
			var maxcount=_maxpage>settings.maxcount?settings.maxcount:_maxpage;
			for(var i=0;i<maxcount;i++){
				pagearr[i]=_cur+i;
			}
			//console.log('pagearr',pagearr);
			if(pagearr[0]>1){
				pagination+='<li><a href="#" offset="0">1...</a></li>';
			}
			for(var i=0;i<maxcount;i++){
				if(pagearr[i]==_nowpage){
					pagination+='<li class="active"><a>'+(pagearr[i])+'</a></li>';
				}else{
					pagination+='<li><a href="#" offset="'+((pagearr[i]-1)*settings.rows)+'">'+(pagearr[i])+'</a></li>';
				}
			}
			if(_maxpage>settings.maxcount && pagearr[settings.maxcount-1]!=_maxpage) {
				pagination += '<li><a href="#" offset="' + ((_maxpage - 1) * settings.rows) + '">...' + (_maxpage) + '</a></li>';
			}
			pagination+='<li class="disabled"><a>(共'+settings.total+'条)</a></li></ul><div>';

			$(page).html(pagination);
			if(isPageEvent){
				$(page).on('click','a',function(e){
					e.preventDefault();
					settings.func($(this).attr('offset'));
					return false;
				});
			}
		}
	}
});