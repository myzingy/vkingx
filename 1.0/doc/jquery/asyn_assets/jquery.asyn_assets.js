/**
 * 功能实现同步/异步加载资源（css 和 js）
 * 
 * 你需要修改 assets/css/ 和 assets/js/ 改为你的资源路径
 * 
 * 使用方式为2中
 * 
 * 1）同步
 * $.include(['jquery.treeview.js','jquery.treeview.css']);
 * 
 * 2）异步
 * $.footerInclude({
 *			'jquery.form.js':null //无依赖请指定为 null			
 *			,	'jquery.validate.min.js':['jquery.validate-ext.js'] //加载jquery.validate-ext前会先加载jquery.validate
 *	});	
 * 
 * 没有演示，只能通过 firefox 的 debug 来看效果。
 * 
 * */
$.extend({
	loadAssetsed:new Object, 
	include:function(assname){ 
		if(typeof assname=="string"){
			assname=[assname];
		}
		$.each(assname,function(i,v){
			var tv=v.replace("/","_");
			if(!!$.loadAssetsed[tv]) return;
			var type=v.match(/\.([a-z]{2,4})$/);
			var isCSS= (type[1]=='css') ? true : false;
			var tag = isCSS ? "link" : "script";
			var attr = isCSS ? " type='text/css' rel='stylesheet' " : " language='javascript' type='text/javascript' ";
			var link = (isCSS ? "href" : "src") + "='assets/" + type[1] +"/"+ v + "'";
			if(isCSS){
				document.write("<" + tag + attr + link + "/>");
			}else{
				document.write("<" + tag + attr + link + "></" + tag + ">");
			}
			$.loadAssetsed[tv]=true;
		});
	},
	footerInclude:function(assname){ 
		
		$.each(assname,function(v,ext){ 
			var tv=v.replace("/","_");
			if(!!$.loadAssetsed[tv]) return;
			var type=v.match(/\.([a-z]{2,4})$/);
			var isCSS= (type[1]=='css') ? true : false;
			if(isCSS){
				$('<link href="assets/css/'+v+'" />').appendTo("head").attr({type:'text/css',rel:'stylesheet'});
				$.loadAssetsed[tv]=true;
			}else{
				$.getScript('assets/js/'+v,function(){
					$.loadAssetsed[tv]=true;
					if(!!ext){
						$.each(ext,function(m,js){
							var tv=js.replace("/","_");
							$.getScript('assets/js/'+js,function(){
								$.loadAssetsed[tv]=true;									 
							});
						});
					}
				});
			}
			
		});
	}
});