define(function(require, exports, module){
	var $=require('jquery');
	require('tpl');
	require('page');
	var settings;
	$.fn.table = function(options){
		// Setup Plugin options
	    settings = jQuery.extend({
			that:this
			,selector:this.selector
	        //扩展参数
	    	,async:false
			,url:null		//json 请求地址
			,data:null		//json 数据
			,tpl:null
			,offset:0
			,rows:20
			,total:0
	    }, options);
		// Declare main object
	    var vtable = this;
		var setData=function(){
			if(settings.url){
				//ajax_sta();
				$.ajax( {
					//type : "GET",
					async: false,
					url : settings.url,
					dataType : "json",
					data:{offset:settings.offset,rows:settings.rows},
					success : function(json) {
						settings.data=json.data;
						settings.total=json.total;
					}

				});
			}
		}
		var setTpl=function(){
			if(!settings.tpl){
				settings.tpl='<tr>';
				var _data=settings.data[0];
				if(_data){
					for(var field in _data){
						settings.tpl+='<td data-'+field+'="{'+field+'}">{'+field+'}</td>';
					}
				}
				settings.tpl+='</tr>';
			}
		}
		var page=function(){
			var tfoot='<tfoot><tr><td colspan="20" id="table_tfoot_page"></td></tr></tfoot>';
			if($(vtable).next().find('#table_tfoot_page').length<1){
				$(vtable).after(tfoot);
			}
			$('#table_tfoot_page').page({
				total:settings.total,
				rows:settings.rows,
				offset:settings.offset,
				func:function(offset){
					settings.offset=offset;
					initDisplay();
				}
			});
		}
		var display=function(){
			$.tpl.html(settings.tpl,settings.data,function(html){
				$(vtable).html(html);
			});
			page();
		}
		var initDisplay=function(){
			setData();
			//console.log('setData...');
			setTpl();
			//console.log('setTpl...');
			display();
			//console.log('display...');
		}
		initDisplay();
		return vtable;
	};
	exports.init=function(id,config){
		$("#"+id).table(config);
	};
	exports.flush=function(){
		$(settings.that).table(settings);
	}
});