define(function(require, exports, module){
	return (function($){  
		var errorDeviceId=!!errorDeviceId?errorDeviceId:null;
		$.fn.treeview = function(options){                
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
				//错误item ID 数组格式
				,errorid:errorDeviceId?errorDeviceId:null
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
			var findParentItem=function(){ //构造父级列表
				var ul='<ul class="nav nav-list">';
				$.each(settings.data,function(i,v){
					v[settings.firstview.field]=!!v[settings.firstview.field]?v[settings.firstview.field]:"unknown";
					if(v[settings.firstview.field]==settings.firstview.value){
						
						var field=findField(v);
						if(!!v.children){
							ul+='<li><div class="hitarea expandable"><i class="icon-folder-open"></i><a style="font-weight: bold;text-decoration:none;" mid="'+v.id+'" '+field+'>'+v.name+'</a></div><!--{'+v.id+'}--></li>';
							var subli=findChildItem(i);
							ul=ul.replace('<!--{'+v.id+'}-->',subli);
						}else{
							ul+='<li><a mid="'+v.id+'" '+field+'><i class="icon-cog"></i>'+v.name+'</a></li>';
						}
					}
					
				});
				ul+='</ul>';
				$(tree).html(ul);
			};
			var findChildItem=function(id){ //构造子列表
				var device_json=settings.data;
				
				var children=!!device_json[id].children?device_json[id].children:false;
				var ul='<ul class="nav nav-list">';
				$.each(children,function(i,cid){
					var field=findField(device_json[cid]);
					if(!!device_json[cid].children){
						ul+='<li><div class="hitarea expandable"><i class="icon-folder-open"></i><a mid="'+device_json[cid].id+'" '+field+'>'+device_json[cid].name+'</a></div><!--{'+cid+'}--></li>';
						var subli=findChildItem(cid,false);
						ul=ul.replace('<!--{'+cid+'}-->',subli);
					}else{
						ul+='<li><a mid="'+device_json[cid].id+'" '+field+'><i class="icon-cog"></i>'+device_json[cid].name+'</a></li>';
					}
				});
				ul+='</ul>';
				return ul;
			};
			
			
			var treeview_init=function(){
		    
		    
		    // If the object dosn't have the treeview style assign it
		    if(!tree.hasClass('treeview'))
		    {
		        tree.addClass('treeview');
		    }    
		     
		    // First hide/show tree nodes
		    $('li',tree).each(
		        function()
		        {
		            // Subbranch
		            subbranch = $('ul:first', this);
		            
		            // If there is a valid cookie value, set it to that
					
		            /*if($.cookie(settings.cookie_name+$(this).attr('id')) == 'block')
		                subbranch.show();
		            else
		                subbranch.hide();*/
		            // Now see if this item has an open tag, if so override all parent settings
		            // and show this item
		            if($(this).hasClass('open'))
		            {
		                $(this).parents('ul').show();
		                $(this).removeClass('open');
		                subbranch.show();
		            }
		        }
		    );
		    
		    // Now add the +/- signs
		    $('li',tree).each(
		        function()
		        {
		            // Subbranch
		            subbranch = $('ul:first', this);
		            
		            if (subbranch.size() != 0) {    
		                // Add node icon
		                //$(this).find('span:first').addClass(settings.nodeClass);
		                
		                // Add expandable/collapsable hit areas
		                $(this).prepend('<div class="hitarea"></div>');
		                if (subbranch.eq(0).css('display') == 'none') {
		                    $(this).find('div').addClass(settings.expandableClass);
		                } else {
							$(this).find('div').addClass(settings.collapsableClass);
		                }                
		            } 
		            else
		            {
		                // Show leaf icon
		                //$(this).find('span:first').addClass(settings.leafClass);
		            }
		        }
		    );    
		        
		    // When a hit area is clicked, toggle the subbranch
		    $('div.hitarea',tree).click(
		        function()
		        {
		            var parent = $(this).parent();
		            var subtree = $('ul:first',parent);
		            
		            // Store change
		            if(subtree.css('display')=='none')
		                //$.cookie(settings.cookie_name+parent.attr('id'), 'block',{path: '/'});
						 subbranch.show();
		            else
						 subbranch.hide();
		                //$.cookie(settings.cookie_name+parent.attr('id'), 'none',{path: '/'});
		            
		            // Animate the subtree
		            subtree.animate(settings.animate,settings.speed);            
		            
		            // Toggle +/-
		            $(this).toggleClass(settings.expandableClass).toggleClass(settings.collapsableClass);
		        }
		    );
			$('a',tree).click(
				function()
		        {
		           $("li",tree).removeClass("active");
					$(this).parent('li').addClass("active");
					//if(settings.isError){$(this).addClass(settings.errorClass);}
					if(!!settings.click){
						eval("var __fun="+settings.click);
						__fun(this);
					}
		        }			  
			);
			
		    return tree;
			};//treeview_init END
			
			var openItem=function(){ //展开树
				if(!settings.openid && !settings.errorid) return;
				if(typeof settings.openid!="object"){
					settings.openid={mid:settings.openid};	
					settings.errorid={mid:settings.errorid};	
				}
				
				if(!!settings.errorid.mid){ //错误id
					var xmid=settings.errorid.mid;
					$.each(xmid,function(i,v){
						$('a[mid="'+v+'"]').addClass(settings.errorClass).parents("ul").show();
						$('a[mid="'+v+'"]').parents("ul").prev().children().addClass(settings.errorClass);				 
					});
				}
				if(!!settings.openid.mid){ //展开id
					$('a[mid="'+settings.openid.mid+'"]').parents("ul").show();
					$('a[mid="'+settings.openid.mid+'"]',tree).trigger('click');
					//滚动至可视区域
					var _oh=$(tree).height();
					var _uh=$('a[mid="'+settings.openid.mid+'"]').offset();
					$(tree).scrollTop(_uh.top-_oh);
				}
			};
			if(settings.data){
				findParentItem();
				treeview_init();
				openItem();
				return tree;
			}else if(settings.url){
				$.getJSON(settings.url,function(json){
					settings.data=json;
					findParentItem();
					treeview_init();
					openItem();
					return tree;
				});	
			}else{
				return treeview_init();
			}
		};
	});
});