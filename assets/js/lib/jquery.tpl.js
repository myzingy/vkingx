/*
 * author vking
 */
define(function(require, exports, module){
	var $=require('jquery');
	var common=require('common');
	$.extend({tpl:{
		_fm_date:function(timespace,tpl){
			//console.log(timespace,tpl);
			tpl=tpl || "YYYY年MM月DD日 HH时II分SS秒";
			var d=new Date(timespace*1000);
			tpl=tpl.toUpperCase();
			tpl=tpl.replace('YYYY',d.getFullYear());
			var mm=d.getMonth()+1;
			tpl=tpl.replace('MM',mm>9?mm:'0'+mm);
			var dd=d.getDate();
			tpl=tpl.replace('DD',dd>9?dd:'0'+dd);
			var hh=d.getHours();
			tpl=tpl.replace('HH',hh>9?hh:'0'+hh);
			var ii=d.getMinutes();
			tpl=tpl.replace('II',ii>9?ii:'0'+ii);
			var ss=d.getSeconds();
			tpl=tpl.replace('SS',ss>9?ss:'0'+ss);
			return tpl;
		},
		_tpl_data_format:function($1,$2,$3){
			var that=this;
			if($2){
				$2=$2.replace('|','');
				$3=$3?$3.replace(':',''):'';
				switch($2){
					case 'provider_status':
						return $1==4?'<i class="btn btn-mini btn-info icon-eye-open" title=""></i>':'<i class="btn btn-mini btn-danger icon-eye-close" title=""></i>'
					case 'switch_status':
						//var but_='<a href="#" data-status="'+$1+'"  type="switch_status" class="btn btn-mini btn-success"><i class="icon-white icon-ok-sign" title=""></i></a>';
						var but_0='<a href="#" data-status="'+$1+'"  type="switch_status" class="btn btn-mini btn-success"><i class="icon-white icon-ok-sign" title=""></i></a>';
						var but_1='<a href="#" data-status="'+$1+'" type="switch_status" class="btn btn-mini btn-danger"><i class="icon-white icon-remove-sign" title=""></i></a>';
						return eval("but_"+$1);
					case 'avatar':
						return '<img src="'+common.cgi('apido/avatar','p/'+$1)+'"/>';
					case 'image':
						return '<img src="'+common.cgi('apido/download','id/'+$1+'/width/640')+'"/>';
					case 'substr':
						$3=$3||12;
						return $1.substr(0,$3);
					case 'date':
						//console.log('date',$1,$2,$3);
						$3=$3||"YYYY年MM月DD日 HH时ii分";
						return that._fm_date($1,$3);
					case 'default':
						if($1) return $1;
						switch($3){
							case 'video_image':
								return $common.base_url+'mgui/assets/images/video.png';
							case 'voice_image':
								return $common.base_url+'mgui/assets/images/voice.png';
							case 'foot_image':
								return $common.base_url+'mgui/assets/images/foot.png';
							default:
								return $3;
						}
					default:
						return $1;
				}
			}
			return $1;
		},
		_func:function(row,func){
			var that=this;
			func=func.replace(':','');
			if(that[func]){
				return that[func](row);
			}
			return '$.tpl.'+func+' todo...';
		},
		value:function(data,elm){
			var that=this;
			elm=typeof elm=='undefined'?'.jsdata':elm;
			$(elm).each(function(){
				var jsdata=$(this).attr('jsdata');
				if(jsdata){
					var val=jsdata.replace(/{([^|]+)(|[^:]+)?(:?[^}]*)}/g,function($0,$1,$2,$3){
						//console.log('jsdata-replace',$1,$2,$3);
						return that._tpl_data_format(data[$1],$2,$3);
					});
					//console.log('jsdata',jsdata,val);
					switch(this.tagName.toLowerCase()){
						case 'input':
						case 'select':
						case 'textarea':
							$(this).val(val);
							break;
						default:
							$(this).html(val);
					}
				}
			});
		},
		html:function(tpl,data,callback){
			var that=this;
			var html='';
			for(var i in data){
				if(typeof data[i]=='function') continue;
				var _html=tpl;
				for(var j in data[i]){
					var reg= new RegExp('{'+j+'(\|[^:}]+)?(:[^}]+)?}','g');
					_html=_html.replace(reg,function($1,$2,$3){
						if($2=='|func'){
							return that._func(data[i],$3);
						}
						return that._tpl_data_format(data[i][j],$2,$3);
					});
				}
				html+=_html;
			}
			if(typeof callback!='undefined'){
				return callback(html);
			}
			return html;
		},
	}});
});
