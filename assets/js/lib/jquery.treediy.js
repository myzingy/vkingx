define(function(require, exports, module){
	var $=require('jquery');
	var common=require('common');
	$.fn.treediy = function(options){                
	    // Setup Plugin options
	    var settings = jQuery.extend({
	        cookie_name: 'treeview',
	        leafClass: 'leaf',
	        nodeClass: 'node',
	        expandableClass: 'expandable',
	        collapsableClass: 'collapsable',
			errorClass: 'errorexp',
	        animate: { height: 'toggle'},
	        speed: 'fast'
			//扩展参数
			,url:null		//json 请求地址
			,data:null		//json 数据
			,click:null		//单击事件
			,openid:null	//展开栏目
			,field:null		//a 连接 扩展 属性 {url:'href',vname:'title'}
			,firstview:{field:'type',value:'router'}	//显示的顶级元素
	    }, options);
		// Declare main object
	    var tree = this;
		var findField=function(obj){
			if(!settings.field) return '';
			var field=' ';
			$.each(settings.field,function(key,value){
				if(!!obj[value]){
					field+=key+'="'+obj[value]+'" ';	
				}else{
					if(value.indexOf(':')<0) return;	
					var sp=value.split(':');
					field+=key+'="'+sp[0]+obj[sp[1]]+'" ';	
				}							
			});
			if(field.indexOf('href')<0){
				field+='href="#" ';
			}
			return field;
		};
		var right='<a href="#" type="edit" class="btn btn-mini"><i class="icon-pencil" title="编辑"></i></a>'
		+' <a href="#" type="del" class="btn btn-mini btn-danger"><i class="icon-trash icon-white" title="删除"></i></a>';
		var right_add=' <a href="#" type="add" class="btn btn-mini btn-success"><i class="icon-plus-sign" title="添加"></i></a>';
		
		var findParentItem=function(){ //构造父级列表
			var ul='<div class="nav" style="clear:both;">';
			$.each(settings.data,function(i,v){
				v[settings.firstview.field]=!!v[settings.firstview.field]?v[settings.firstview.field]:"unknown";
				if(v[settings.firstview.field]==settings.firstview.value){
					
					var field=findField(v);
					if(!!v.children){
						ul+='<div class="line '+(v.type=='nav_one'?'column':'column2')+'"><div class="left"><i class="icon-folder-open"></i>'+v.name+'</div><div class="right">'+right+(v.type=='nav_one'?right_add:'')+'</div><!--{'+v.id+'}--></div>';
						var subli=findChildItem(i);
						ul=ul.replace('<!--{'+v.id+'}-->',subli);
					}else{
						ul+='<div class="line '+(v.type=='nav_one'?'column':'column2')+'"><div class="left"><i class="icon-cog"></i>'+v.name+'</div><div class="right">'+right+(v.type=='nav_one'?right_add:'')+'</div></div>';
					}
				}
			});
			ul+='</div>';
			$(tree).html(ul);
		};
		var findChildItem=function(id){ //构造子列表
			var device_json=settings.data;
			
			var children=!!device_json[id].children?device_json[id].children:false;
			var ul='<div class="nav_child" style="clear:both;">';
			$.each(children,function(i,cid){
				var field=findField(device_json[cid]);
				if(!!device_json[cid].children){
					ul+='<div class="line '+(device_json[cid].type=='nav_one'?'column':'column2')+'"><div class="left"><i class="icon-folder-open"></i>'+device_json[cid].name+'</div><div class="right">'+right+(device_json[cid].type=='nav_one'?right_add:'')+'</div><!--{'+cid+'}--></div>';
					var subli=findChildItem(cid,false);
					ul=ul.replace('<!--{'+cid+'}-->',subli);
				}else{
					ul+='<div class="line '+(device_json[cid].type=='nav_one'?'column':'column2')+'"><div class="left"><i class="icon-cog"></i>'+device_json[cid].name+'</div><div class="right">'+right+(device_json[cid].type=='nav_one'?right_add:'')+'</div></div>';
				}
			});
			ul+='</div>';
			return ul;
		};
		if(settings.data){
			findParentItem();
			return tree;
		}else if(settings.url){
			$.getJSON(settings.url,function(json){
				settings.data=json;
				findParentItem();
				return tree;
			});	
		}else{
			return tree;
		}
	};
});