define(function(require, exports, module){
	var $=require('jquery');
	var common=require('common');
	$.fn.xtable = function(options){                
	    // Setup Plugin options
	    var settings = jQuery.extend({
	        //扩展参数
	    	async:false
			,url:null		//json 请求地址
			,data:null		//json 数据
			,click:null		//单击事件
			,openid:null	//展开栏目
			,fields:null	//表头[{'field':'姓 名',width:100,'display':'name'}]
			,buttons:null
			,total:false
			,height:500
			,offset:0
			,rows:10
	    }, options);
		// Declare main object
	    var vtable = this;
		
		
		
		var vtable_init=function(){
			//构造表头
			var html='<table class="table table-striped table-bordered" cellspacing="0" cellpadding="0" id="alarm_mapping_table"><thead><tr>';
			var tpl='';
			var data='';
			var total={};
			for(var i=0;i<settings.fields.length;i++){
				html+='<th width="'+settings.fields[i].width+'">'+settings.fields[i].field+'</th>';
				tpl+='<td class="'+settings.fields[i].xclass+'">{'+settings.fields[i].display+'}</td>';
				total[settings.fields[i].display]=0;
			}
			if(settings.data.datarows<1){
				data='<tr><td class="nodata" colspan="'+settings.fields.length+'">没有数据!</td></tr>';
			}else{
				for(var j=0;j<settings.data.data.length;j++){
					data+='<tr>'+tpl;
					for(var i=0;i<settings.fields.length;i++){
						var _f=settings.fields[i].display;
						if('BUTTON'==_f){
							var _v=[];
							var _b=settings.fields[i][_f];
							for(var x =0;x<_b.length;x++){
								//_v+='<button onclick="'+_b[x].fun+'(\"'+settings.data.data[j].id+'\");" class="btn btn-mini">'+_b[x].name+'</button>&nbsp;&nbsp;';
								_v.push('<a href="#" type="'+_b[x].type+'" class="btn btn-mini '+_b[x].class+'"><i class="icon-white '+_b[x].icon+'" title="'+_b[x].name+'"></i>'+_b[x].name+'</a>');
							}
							_v=_v.join('&nbsp;&nbsp;');
							
						}else{
							var _m=_f.split('&');
							var _v=settings.data.data[j][_m[0]];
							for(var _i=1;_i<_m.length;_i++){
								if(_m[_i].indexOf('+')>-1){ _v+=((settings.data.data[j][_m[_i].replace('+','')])); continue;}
								if(_m[_i].indexOf('-')>-1){ _v-=((settings.data.data[j][_m[_i].replace('-','')])); continue;}
								if(_m[_i].indexOf('*')>-1){ _v*=((settings.data.data[j][_m[_i].replace('*','')])); continue;}
								if(_m[_i].indexOf('/')>-1){ _v/=((settings.data.data[j][_m[_i].replace('/','')])); continue;}
								_v+="["+settings.data.data[j][_m[_i]]+"]";
							}
							if ( settings.fields[i].total )	total[_f]+=parseFloat(_v);
						}
						//data=data.replace('{'+_f+'}',/^[0-9]+\.[0-9]+$/.test(_v)?parseFloat(_v).toFixed(2):_v);
						data=data.replace('{'+_f+'}',_v);
					}
					data+='</tr>';
				}
				if(settings.total){
					data+='<tr><td align="left" colspan="'+settings.fields.length+'" style="background:url(assets/images/background.gif);border-bottom:3px #E14F1C solid;">汇 总</td></tr>';
					data+='<tr>';
					data+='<td class="xclass">计 '+settings.data.data.length+'</td>';
					for(var i=1;i<settings.fields.length;i++){
						data+='<td class="'+settings.fields[i].xclass+'">'+(total[settings.fields[i].display]?(total[settings.fields[i].display]).toFixed(2):'')+'</td>';
					}
					data+='</tr>';
				}
			}
			
			html+='</tr></thead><tbody id="alarm_mapping_list_tbody">'+data+'</tbody><tfoot id="alarm_mapping_list_tfoot"></tfoot></table>';
			vtable.html(html);
			
			var tpage='<tr><td align="left" colspan="'+settings.fields.length+'" class="navigation">{pagination}</td></tr>';	
			if(settings.data.pagination){
				tpage=tpage.replace('{pagination}',settings.data.pagination);
			}else{
				var pagination='<div class="pagination  pagination-small" style="margin:0"><ul>';
				if(settings.data.total>settings.rows){
					for (var i=0,p=1;i<settings.data.total;i+=settings.rows){
						if(settings.offset/settings.rows==p-1){
							pagination+='<li class="active"><a>'+(p++)+'</a></li>';
						}else{
							pagination+='<li><a href="#" offset="'+i+'">'+(p++)+'</a></li>';
						}
					}
					pagination+='<li class="disabled"><a>(共'+settings.data.total+'条)</a></li></ul><div>';
				}
				tpage=tpage.replace('{pagination}',pagination);
			}
			$('#alarm_mapping_list_tfoot').html(tpage);
		    return vtable;
		};//treeview_init END
		
		
		if(settings.url){
			//ajax_sta();
			$.ajax( {
				//type : "GET",
				async: settings.async,
				url : settings.url,
				dataType : "json",
				data:{offset:settings.offset,rows:settings.rows},
				success : function(json) {
					settings.data=json;
					//ajax_end();
					return vtable_init();
				}
	
			});
		}else{
			return vtable_init();
		}
		
		
	};
	exports.init=function(id,config){
		$("#"+id).xtable(config);
	};
	exports.bindEvent=function(event,type,fun){
		$('#alarm_mapping_list_tbody').on(event,'a[type="'+type+'"]',fun);
	};
});