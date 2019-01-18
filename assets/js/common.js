define(function(require, exports, module){
    var $ = require('jquery');
    require('bootstrap');
    require('treeview')($);
    require('form');
    require('jquery-ui');
    require('localStorage');
    require('tpl');
    var common=require('common');
    var data;
    
    $("#form_one").click(function(){
		  $("#form_check").hide();
		});
   
    var loadhtml=function(a){
        var url='';
        if(typeof a=='string'){
            url=a;
        }else{
            url=a.href;
        }
    	url=url.replace(/[^#]*#/g,'');
        if(!url) return false;
        $.get('assets/'+url,function(html){
			$('#layout_right').html(html);
			return false;
		});
		return false;
    };

    var openContentDialog=function(id){
    	id=typeof id=='undefined'?'':id;
        common.dialog({
    		title:'内容'
            ,isShowOkBut:true
            ,okButName:'确定'
            ,url:'tpl/model/users1_form.html?id='+id
            ,id:'users_form_div'
            ,pageDataFun:function(){
            	seajs.use('users_form',function(users_form){
			        users_form.init();
			    });
            }
            ,callback:function(obj){
				$('#users_form').trigger('submit');
			}
    	});
    };
    
    exports.init=function(){
    	$(function(){
    		$(document).on('click','.loadhtml',function(){
    			loadhtml(this);
    		});
    		
    		$.ajaxSetup({
	    		type:'POST'
				,"beforeSend":function(XMLHttpRequest, settings){
					XMLHttpRequest.setRequestHeader('If-Modified-Since', '0');
					if(settings.url.indexOf('html?')>0){
						$.localStorage('ajax-param',settings.url);
					}
					//console.log(settings);
					var token=$.localStorage('token');
					if(!token) return;
					if(settings.type=='GET'){
						if(settings.url.indexOf('?')>0){
							settings.url+='&token='+token;
						}else{
							settings.url+='?token='+token;
						}
						return;
					}
					if(typeof settings.data=='undefined'){
						settings.data=new FormData();
					}
					if(typeof settings.data=='object'){
						settings.data.append('token',token);
					}else{
						settings.data += '&token='+token;
					}
				}
				,"complete":function(res){
					if(typeof res.responseJSON=='object'){
						if(res.responseJSON.code==-1){
							var formcallback=function(json,self){
								var submit_btn=self.next().find('.btn');
								submit_btn.prop('disabled', false);
                        		submit_btn.removeClass('disabled');
                        		submit_btn.find('span').text('登录');
								if(json.code!=200){
        							alert(json.message);
        							
        							return;
        						}
        						$.localStorage('token',json.data.token);
        						location.reload();
        						self.dialog('close');
							};
							form_dialog({
								title : '请登录',
								content:$('#login_tpl').html(),
					            url: exports.cgi('admin','login'),
					            width: 300,
					            form_id:'login',
					            but_cancel:{'text':'取消','show':false},
            					but_submit:{'text':'登录','show':true},
            					callback:formcallback,
            					
							});
							return false;
						}else{
                            if(res.responseJSON.code!=200){
                                exports.error(res.responseJSON.message);
                            }
                            exports._formcallback(res.responseJSON);
                        }
					}
					
				}
			});
			//绑定站点切换事件
			$('#siteListNavDom').on('click','li',function(){
				var that=$(this);
				if(that.attr('class')=='active') return;
				$.ajax({
		    		url:exports.cgi('api/act','switchSite/'+that.attr('siteid'))
		    		,dataType:'json'
		    		,success:function(json){
		    			if(json.status==0){
		    				$('#siteListNavDom').find('li').removeClass('active');
		    				that.attr('class','active');
		    				
		    				$('#treeview').find('li.active>a').trigger('click');
		    				//$('#layout_right').html('');
		    			}
		    		}
		    	});
			});
			//getUserInfo();
			//getSiteList();
			exports.height=$(window).height()-100;
			$('.mini-layout').css("height",exports.height);
			//ajax提交表单方法
			$(document).on('submit','form',function(e){
				var that=$(this);
				var submit_btn = that.find('button[type="submit"]');
				var old_text = submit_btn.text();
				that.ajaxSubmit({
					dataType: 'json',
                    beforeSend: function(XMLHttpRequest, settings){
                    	XMLHttpRequest.setRequestHeader('If-Modified-Since', '0');
                    	if(typeof settings.data=='undefined'){
							settings.data=new FormData();
						}
						if(typeof settings.data=='object'){
							settings.data.append('token',$.localStorage('token'));
						}else{
							settings.data += '&token='+$.localStorage('token');
						}
                        submit_btn.prop('disabled', true);
                        submit_btn.addClass('disabled');
                        submit_btn.text('提交中...');
                    },
                    success: function(data){
                        submit_btn.prop('disabled', false);
                        submit_btn.removeClass('disabled');
                        submit_btn.text(old_text);
                        exports._formcallback(data,that);
                    },
                    error: function(){
                        submit_btn.prop('disabled', false);
                        submit_btn.removeClass('disabled');
                        submit_btn.text(old_text);
                        exports._formcallback({code:10000,message:'网络错误，请重试'});
                    }
				});
				//禁止提交
				e.preventDefault();
			});
    	});
    };
    
    //ajax提交浮动层
    var alert_float = function(content){
        var html = $('<div class="alert_float label label-success">' + content + '</div>').hide();
        $('body').append(html);
        html.fadeIn();
        var window_width = $(window).width();
        var html_left = window_width / 2 - html.width();
        html.css('left', html_left);
        setTimeout(function(){
            html.fadeOut(function(){
                html.remove();
            });
        }, 3000);
    };
    //弹出确认层
    var dialog = function(options,hideid){
    	var defaults = {
            content: ''
            ,callback: null
            ,title:'信息提示框'
            ,isShowOkBut:false
            ,okButName:'继续'
            ,url:null
            ,id:''
            ,width:0
            ,height:0
            ,pageDataFun:null
        };
        hideid=typeof hideid=='undefined'?'':hideid;
        if(options=='hide'){
    		$('#__dialog_'+hideid).modal('hide');
    		return false;
    	}
    	options = $.extend({}, defaults, options);
        options.id='#__dialog_'+options.id;
    	
    	var cf=$(options.id);
    	
        if(!options.content){
        	$.ajax({
        		async:false
        		,url:options.url
        		,dataType:'html'
        		,success:function(html){
        			options.content=html;
        		}
        	});
        }
    	if(!cf.get(0)){
            var style=(options.width>0?('width:'+options.width+'px;'):'');
    		var html='<div class="modal" style="'+style+'" id="'+options.id.replace('#','')+'"><div class="modal-header"><a class="close" data-dismiss="modal">×</a><h3 class="__dialog_title">'+options.title+'</h3></div><div class="modal-body __dialog_content">'+options.content+'</div>'
    		+'<div class="modal-footer"><a href="#" class="btn btn-primary __dialog_butok">继续</a>&nbsp;<a href="#" data-dismiss="modal">关闭</a></div></div>';
    		$(html).appendTo('body');
    	}
    	$('.__dialog_title',options.id).html(options.title);
    	$('.__dialog_content',options.id).html(options.content);
    	$('.__dialog_butok',options.id).html(options.okButName).hide();
    	$('.__dialog_butok',options.id).unbind('click');
    	if(options.isShowOkBut){
    		$('.__dialog_butok',options.id).show();
    		if(options.callback){
    			$('.__dialog_butok',options.id).click(function(){
		        	options.callback($(options.id));
		        });
    		}
    	}else{
    		$(options.id).hide();
    	}
        
        $(options.id).modal({
            backdrop:false,
		    keyboard:false,
		    show:true
        });
        $(options.id).show();
        if(options.pageDataFun){
        	options.pageDataFun($(options.id));
        }
			
    };
    //图片上传弹出框
    var img_upload = function(options){
        var defaults = {
            file_name: 'imgFile',
            upload_url: 'login'
        }
        options = $.extend({}, defaults, options);
        var form_id = Math.floor(Math.random() * ( 100000000 + 1));
        var html =  '<div class="img_upload_form"><form method="post" id="' + form_id + '" enctype="multipart/form-data" action="' + options.upload_url + '"><input readonly="readonly" style="vertical-align: top; float:left;" type="text" id="upload_text"/><div style="float: left;width: 88px;overflow: hidden;position: relative"><button type="button" style="margin-left: 5px;" class="btn">选择图片' +
                    '</button><input type="file" style="position: absolute; top: 0;right:0;opacity: 0;filter: alpha(opacity=0);" name="' + options.file_name + '" id="' + options.file_name + '"/></div></form></div>';
        var imgDialog = $(html).dialog({
            autoOpen: false,
            width: 330,
            modal: true,
            title: '上传图片',
            resizable: false,
            buttons: {
                "上传": function () {
                    var _self = $(this);
                    var _self_btn = _self.parents('.ui-dialog').find('.ui-dialog-buttonset button').eq(0);
                    $('#' + form_id).ajaxSubmit({
                        dataType: 'JSON',
                        async: false,
                        beforeSend: function(){
                            _self_btn.addClass('disabled').addClass('btn');
                            _self_btn.text('上传中...');
                        },
                        success: function(data){
                            if(parseInt(data.error) === 0){
                                window.alert('错误');
                                _self.dialog('close');
                            }else{
                                if(options.success && options.success !== undefined){
                                    options.success();
                                }else{
                                    alert('上传成功' + data.img_src);
                                }
                            }
                        },
                        error: function(){
                            alert('网络错误,请重试');
                            _self_btn.removeClass('disabled').removeClass('btn');
                            _self_btn.text('上传');
                        }
                    });
                },
                "取消": function () {
                    $(this).dialog('close');
                }
            },
            close: function(){
                $(this).remove();
            }
        });
        imgDialog.dialog('open');
        $(document).on('change', '#' + form_id + ' #' + options.file_name, function(){
            var local_img = $(this).val();
            $('#' + form_id + ' #upload_text').val(local_img);
        });
    };
    //form弹出层
    var form_dialog = function(options){
        var defaults = {
            content : '请确定！',
            title : '提示',
            url: '',
            width: 300,
            form_id:'',
            but_cancel:{'text':'取消','show':true},
            but_submit:{'text':'提交','show':true},
            callback:exports._formcallback,
        };
        options = $.extend({}, defaults, options);
        var form_id = options.form_id?options.form_id:Math.floor(Math.random() * ( 100000000 + 1));
        if($('#form_dialog_'+ form_id).length>0){
        	return;
        }
        if(options.url && options.url !== undefined){
            options.content = '<div class="form_dialog"><form id="form_dialog_' + form_id + '" action="' + options.url + '" method="post">' + options.content + '</form></div>';
        }else{
            options.content = '<div class="form_dialog" id="form_dialog_' + form_id + '">' + options.content + '</div>';
        }
        var buttons={};
        buttons[options.but_submit['text']]=function(){
            var _self = $(this);
            var _self_btn = _self.parents('.ui-dialog').find('.ui-dialog-buttonset button').eq(0);
            if(options.url && options.url !== undefined){
                $('#form_dialog_' + form_id).ajaxSubmit({
                    async: false,
                    dataType: 'JSON',
                    beforeSend: function(){
                        _self_btn.addClass('disabled').addClass('btn');
                        _self_btn.find('span').text('提交中...');
                    },
                    success: function(data){
                    	if(options.callback && options.callback !== undefined){
                            options.callback(data, _self);
                        }else{
                            alert_float('提交成功！');
                            _self.dialog('close');
                        }
                    },
                    error: function(){
                        alert('网络错误,请重试');
                        _self_btn.removeClass('disabled').removeClass('btn');
                        _self_btn.text('上传');
                    }
                });
            }else{
                if(options.callback && options.callback !== undefined){
                    options.callback($(this));
                }else{
                    $(this).close();
                }
            }
        };
        if(options.but_cancel['show']){
        	buttons[options.but_cancel['text']]=function(){$(this).dialog('close');};
        }
        var formDialog = $(options.content).dialog({
            autoOpen: false,
            width: options.width,
            modal: true,
            title: options.title,
            resizable: false,
            buttons: buttons,
            close: function(){
                $(this).remove();
            }
        });
        formDialog.dialog('open');
        $('.ui-dialog').zIndex(9999);
    };
    exports.img_upload = img_upload;
    exports.dialog = dialog;
    exports.alert=function(content){
    	dialog({"title":"提示信息","content":content,"isShowOkBut":false});
    };
    exports.confirm=function(content,callback){
        dialog({
            "title":"确认信息",
            "content":content,
            "isShowOkBut":true,
            "callback":typeof callback=='undefined'?function(){dialog('hide');}:callback
        });
    };
    exports.alert_float = alert_float;
    exports.form_dialog = form_dialog;
    exports._formcallback=function(res){alert('需要在具体model中实现 common._formcallback 方法');};
    exports.cgi=function(m,f){
    	var baes_url=location.href.replace(/.*\.html/ig,'');
    	return baes_url+m+'/'+f;
    };
    exports.error=function(msg,style){
    	style=typeof style=='undefined'?'danger':style;
    	var css='alert alert-'+style;
    	$('#postmsg').html(msg).attr('class',css).show(0,function(){
            setTimeout(function(){$('#postmsg').hide()},3000);
        });
    };
    exports.i=function(key,islocalStorage){
    	islocalStorage=typeof islocalStorage=='undefined'?false:islocalStorage;
    	var url=islocalStorage?$.localStorage('ajax-param'):location.href;
    	var reg=new RegExp(key+'=([^&]+)');
    	var data=url.match(reg);
    	if(data) return data[1];
    	return "";
    };
    exports.loadhtml=loadhtml;
});