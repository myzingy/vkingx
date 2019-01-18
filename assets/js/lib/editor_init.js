/**
 * Created by zhangwen on 13-12-27.
 */
define(function(require, exports, module){
    var $ = require('jquery');
    require('wysiwyg')($);
    require('hotkey')($);
    require('upload');
    var common=require('common');
    var color_box ='<span class="color_none"><a href="javascript: void(0);" title="无颜色">无颜色</a></span>' +
                    '<span class="color_item"><a href="javascript:void(0);" title="#E53333" style="background-color: #E53333"></a></span>' +
                    '<span class="color_item"><a href="javascript:void(0);" title="#E56600" style="background-color: #E56600"></a></span>' +
                    '<span class="color_item"><a href="javascript:void(0);" title="#FF9900" style="background-color: #FF9900"></a></span>' +
                    '<span class="color_item"><a href="javascript:void(0);" title="#64451D" style="background-color: #64451D"></a></span>' +
                    '<span class="color_item"><a href="javascript:void(0);" title="#DFC5A4" style="background-color: #DFC5A4"></a></span>' +
                    '<span class="color_item"><a href="javascript:void(0);" title="#FFE500" style="background-color: #FFE500"></a></span>' +
                    '<span class="color_item"><a href="javascript:void(0);" title="#009900" style="background-color: #009900"></a></span>' +
                    '<span class="color_item"><a href="javascript:void(0);" title="#006600" style="background-color: #006600"></a></span>' +
                    '<span class="color_item"><a href="javascript:void(0);" title="#99BB00" style="background-color: #99BB00"></a></span>' +
                    '<span class="color_item"><a href="javascript:void(0);" title="#B8D100" style="background-color: #B8D100"></a></span>' +
                    '<span class="color_item"><a href="javascript:void(0);" title="#60D978" style="background-color: #60D978"></a></span>' +
                    '<span class="color_item"><a href="javascript:void(0);" title="#00D5FF" style="background-color: #00D5FF"></a></span>' +
                    '<span class="color_item"><a href="javascript:void(0);" title="#337FE5" style="background-color: #337FE5"></a></span>' +
                    '<span class="color_item"><a href="javascript:void(0);" title="#003399" style="background-color: #003399"></a></span>' +
                    '<span class="color_item"><a href="javascript:void(0);" title="#4C33E5" style="background-color: #4C33E5"></a></span>' +
                    '<span class="color_item"><a href="javascript:void(0);" title="#9933E5" style="background-color: #9933E5"></a></span>' +
                    '<span class="color_item"><a href="javascript:void(0);" title="#CC33E5" style="background-color: #CC33E5"></a></span>' +
                    '<span class="color_item"><a href="javascript:void(0);" title="#EE33EE" style="background-color: #EE33EE"></a></span>' +
                    '<span class="color_item"><a href="javascript:void(0);" title="#FFFFFF" style="background-color: #FFFFFF"></a></span>' +
                    '<span class="color_item"><a href="javascript:void(0);" title="#CCCCCC" style="background-color: #CCCCCC"></a></span>' +
                    '<span class="color_item"><a href="javascript:void(0);" title="#999999" style="background-color: #999999"></a></span>' +
                    '<span class="color_item"><a href="javascript:void(0);" title="#666666" style="background-color: #666666"></a></span>' +
                    '<span class="color_item"><a href="javascript:void(0);" title="#333333" style="background-color: #333333"></a></span>' +
                    '<span class="color_item"><a href="javascript:void(0);" title="#000000" style="background-color: #000000"></a></span>';
                    
    var base_html = '<div class="btn-toolbar" data-role="editor-toolbar" data-target="#editor"><div class="btn-group">' +
               '<a class="btn dropdown-toggle editor_font" data-toggle="dropdown" title="字体"><i class="icon-font"></i><b class="caret"></b></a><ul class="dropdown-menu"></ul></div>' +
               '<div class="btn-group">' +
               '<a class="btn dropdown-toggle" data-toggle="dropdown" title="文字颜色" style="color: #2F75EA"><i class="icon-font"></i>&nbsp;<b class="caret"></b></a>' +
               '<div class="dropdown-menu color_box select_font_color">' + color_box + '</div></div>' +
               '<div class="btn-group">' +
               '<a class="btn dropdown-toggle" data-toggle="dropdown" title="背景颜色"><i class="icon-font" style="background-color: #FFCC00;padding:2px"></i>&nbsp;<b class="caret"></b></a>' +
               '<div class="dropdown-menu color_box select_bg_color">' + color_box + '</div></div>' +
               '<div class="btn-group" data-fullscreen="hide">' + 
               '<a class="btn dropdown-toggle" data-toggle="dropdown" title="文字样式"><i class="icon-italic"></i>&nbsp;<b class="caret"></b></a>' + 
               '<ul class="dropdown-menu">' +
               '<li><a data-edit="bold" title="加粗(Ctrl/Cmd+B)"><i class="icon-bold"></i>加粗(Ctrl/Cmd+B)</a></li>' +
               '<li><a data-edit="italic" title="倾斜(Ctrl/Cmd+I)"><i class="icon-italic"></i>倾斜(Ctrl/Cmd+I)</a></li>' +
               '<li><a data-edit="strikethrough" title="删除线"><i class="icon-strikethrough"></i>删除线</a></li>' +
               '<li><a data-edit="underline" title="下划线(Ctrl/Cmd+U)"><i class="icon-underline"></i>下划线(Ctrl/Cmd+U)</a></li>' +
               '</ul></div>' + 
               '<div class="btn-group" data-fullscreen="show" style="display:none;"><a data-edit="bold" class="btn" title="加粗(Ctrl/Cmd+B)"><i class="icon-bold"></i></a>' +
               '<a data-edit="italic" class="btn" title="倾斜(Ctrl/Cmd+I)"><i class="icon-italic"></i></a>' +
               '<a data-edit="strikethrough" class="btn" title="删除线"><i class="icon-strikethrough"></i></a>' +
               '<a data-edit="underline" class="btn" title="下划线(Ctrl/Cmd+U)"><i class="icon-underline"></i></a>' + 
               '</div>' +
               '<div class="btn-group">' +
               '<a class="btn dropdown-toggle" data-toggle="dropdown" title="文字大小"><i class="icon-text-height"></i>&nbsp;<b class="caret"></b></a>' +
               '<ul class="dropdown-menu">' +
               '<li><a data-edit="fontSize 5"><font size="5">大</font></a></li>' +
               '<li><a data-edit="fontSize 3"><font size="3">中</font></a></li>' +
               '<li><a data-edit="fontSize 1"><font size="1">小</font></a></li></ul></div>' +
               '<div class="btn-group">' +
			   '<a class="btn pictureBtn fileinput-button"><i class="icon-picture"></i><input id="fileupload_editor" type="file" name="files[]" multiple=""></a>'+
               '</div>' +
			   '<div class="btn-group" data-fullscreen="hide">' +
               '<a class="btn dropdown-toggle" data-toggle="dropdown" title="文字排版"><i class="icon-align-justify"></i>&nbsp;<b class="caret"></b></a>' +
               '<ul class="dropdown-menu">' +
               '<li><a data-edit="justifyleft" title="文字居左(Ctrl/Cmd+L)"><i class="icon-align-left"></i>文字居左(Ctrl/Cmd+L)</a></li>' +
               '<li><a data-edit="justifycenter" title="文字居中(Ctrl/Cmd+E)"><i class="icon-align-center"></i>文字居中(Ctrl/Cmd+E)</a></li>' +
               '<li><a data-edit="justifyright" title="文字居右(Ctrl/Cmd+R)"><i class="icon-align-right"></i>文字居右(Ctrl/Cmd+R)</a></li>' +
               '<li><a data-edit="justifyfull" title="整理排版(Ctrl/Cmd+J)"><i class="icon-align-justify"></i>整理排版(Ctrl/Cmd+J)</a></li>' +
               '</ul></div>' +
               '<div class="btn-group" data-fullscreen="show" style="display:none;">'+
               '<a data-edit="justifyleft" class="btn" title="文字居左(Ctrl/Cmd+L)"><i class="icon-align-left"></i></a>' +
               '<a data-edit="justifycenter" class="btn" title="文字居中(Ctrl/Cmd+E)"><i class="icon-align-center"></i></a>' +
               '<a data-edit="justifyright" class="btn" title="文字居右(Ctrl/Cmd+R)"><i class="icon-align-right"></i></a>' +
               '<a data-edit="justifyfull" class="btn" title="整理排版(Ctrl/Cmd+J)"><i class="icon-align-justify"></i></a></div>'+
               '<div class="btn-group" data-fullscreen="hide">' +
               '<a class="btn dropdown-toggle" data-toggle="dropdown" title="超链接"><i class="icon-link"></i>&nbsp;<b class="caret"></b></a>' +
               '<ul class="dropdown-menu">' +
               '<li><a class="link-dialog" title="添加超链接"><i class="icon-link"></i>添加超链接</a></li>' +
               '<li><a class="phone-dialog" title="添加电话号码"><i class="icon-phone"></i>添加电话号码</a></li>' +
               '<li><a data-edit="unlink" title="删除超链接"><i class="icon-cut"></i>取消超链接</a></li></ul></div>' +
               '<div class="btn-group" data-fullscreen="show" style="display:none;">' + 
               '<a class="link-dialog btn" title="添加超链接"><i class="icon-link"></i></a>' +
               '<a class="phone-dialog btn" title="添加电话号码"><i class="icon-phone"></i></a>' +
               '<a data-edit="unlink" class="btn" title="删除超链接"><i class="icon-cut"></i></a>' +
               '</div>' +
               '<div class="btn-group" data-fullscreen="hide">' +
               '<a class="btn dropdown-toggle" data-toggle="dropdown" title="排序功能"><i class="icon-list"></i>&nbsp;<b class="caret"></b></a>' +
               '<ul class="dropdown-menu">' +
               '<li><a data-edit="insertunorderedlist" title="项目符号列表"><i class="icon-list-ul"></i>项目符号列表</a></li>' +
               '<li><a data-edit="insertorderedlist" title="数字符号列表"><i class="icon-list-ol"></i>数字符号列表</a></li>' +
               '<li><a data-edit="outdent" title="减少缩进(Shift+Tab)"><i class="icon-indent-left"></i>减少缩进(Shift+Tab)</a></li>' +
               '<li><a data-edit="indent" title="缩进(Tab)"><i class="icon-indent-right"></i>缩进(Tab)</a></li>' +
               '</ul></div>' +
               '<div class="btn-group" data-fullscreen="show" style="display:none;">' +
               '<a data-edit="insertunorderedlist" class="btn" title="项目符号列表"><i class="icon-list-ul"></i></a>' +
               '<a data-edit="insertorderedlist" class="btn" title="数字符号列表"><i class="icon-list-ol"></i></a>' +
               '<a data-edit="outdent" class="btn" title="减少缩进(Shift+Tab)"><i class="icon-indent-left"></i></a>' +
               '<a data-edit="indent" class="btn" title="缩进(Tab)"><i class="icon-indent-right"></i></a>'+
               '</div>' +
               '<div class="btn-group"><a class="btn" data-click="full-screen" title="全屏"><i class="icon-fullscreen"></i></a></div>' + 
               '<div class="btn-group"><a class="btn" data-edit="removeFormat" title="清除样式"><i class="icon-magic"></i></a></div>' + 
               
               
               '<div id="progress_editor" class="progress progress-success progress-striped" style="display: none;height: 2px;"><div class="bar"></div></div>'+
			   '</div>';

    //生成并插入编辑器方法
    var editor_insert = function(options){
        if($('#editor_cont' + options.id).length>0) return false;
        $('#' + options.id).hide();
        var new_html = base_html + '<div class="editor" style="height: ' + options.height + '; max-height: ' + options.height + '" id="edit_area_' + options.id + '"></div>';
        new_html = '<div id="editor_cont' + options.id + '">' + new_html + '</div>'
        $(new_html).insertBefore($('#' + options.id));
        return 'editor_cont' + options.id;
    };

    //初始化编辑器方法
    var editor_init = function (options) {
        var defaults = {
            id: 'textarea',
            height: '150px',
            upload_url: '',
            file_name: 'imgFile'
        };
        options = $.extend({}, defaults, options);
        var editor_id = editor_insert(options);
        $('#fileupload_editor').attr('name',options.file_name);
        if(!editor_id) {
        	$('#edit_area_' + options.id).html($('#' + options.id).val());
        	return false;
        }
        var editor = $('#' + editor_id);
        var fonts = ['Serif', 'Sans', 'Arial', 'Arial Black', 'Courier',
                'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Lucida Grande', 'Lucida Sans', 'Tahoma', 'Times',
                'Times New Roman', 'Verdana'],
            fontTarget = editor.find('.editor_font').siblings('.dropdown-menu');
        $.each(fonts, function (idx, fontName) {
            fontTarget.append($('<li><a data-edit="fontName ' + fontName +'" style="font-family:\''+ fontName +'\'">'+fontName + '</a></li>'));
        });
        editor.find('.dropdown-menu input').click(function() {return false;})
            .change(function () {$(this).parent('.dropdown-menu').siblings('.dropdown-toggle').dropdown('toggle');})
            .keydown('esc', function () {this.value='';$(this).change();});
        editor.find('[data-role=magic-overlay]').each(function () {
            var overlay = $(this), target = $(overlay.data('target'));
            overlay.css('opacity', 0).css('position', 'absolute').offset(target.offset()).width(target.outerWidth()).height(target.outerHeight());
        });
        $(document).on('keyup', '#edit_area_' + options.id, function(){
            $('#' + options.id).val($(this).html());
        });
        $(document).on('blur', '#edit_area_' + options.id, function(){
            $('#' + options.id).val($(this).html());
        });
        editor.find('#edit_area_' + options.id).html($('#' + options.id).val());
        editor.find('#edit_area_' + options.id).wysiwyg({"upload_url": options.upload_url, "id": options.id});
		editor.find('[data-click=full-screen]').click(function(){
            if(editor.hasClass('fullScreen')){
                if(self.frameElement){
                    $(self.frameElement).css({
                        'width': '100%',
                        'position': 'static',
                        'left': '0',
                        'top': '0',
                        'z-index': '999',
                        'height': '100%'
                    });
                }
                $(this).removeClass('active');
                editor.css({
                    'position': 'static',
                    'width': 'auto',
                    'height': 'auto',
                    'z-index': '999',
                    'background': 'none',
                    'padding': '0'
                });
                editor.find('#edit_area_' + options.id).css({
                    'maxHeight': '100%',
                    'maxWidth': '100%',
                    'width': '100%',
                    'height': options.height
                });
                editor.removeClass('fullScreen');
                editor.find('[data-fullscreen=show]').hide();
                editor.find('[data-fullscreen=hide]').show();
            }else{
                if(self.frameElement){
                    $(self.frameElement).css({
                        'width': '100%',
                        'position': 'fixed',
                        'left': '0',
                        'top': '0',
                        'z-index': '999',
                        'height': $(top.window.document).height() - 84
                    });
                }
                $(this).addClass('active');
                var _height = $('body').height() < $(window).height() ? $(window).height() : $('body').height();
                editor.css({
                    'position': 'fixed',
                    'top': 0,
                    'left': 0,
                    'width': '98%',
                    'height': _height - 84,
                    'z-index': '999',
                    'background': '#fff',
                    'padding': '0 1%'
                });
                editor.find('#edit_area_' + options.id).css({
                    'maxHeight': '100%',
                    'maxWidth': '100%',
                    'width': '100%',
                    'height': $(window).height() - 84 -35
                });
                editor.addClass('fullScreen');
                editor.find('[data-fullscreen=show]').show();
                editor.find('[data-fullscreen=hide]').hide();
            }
        });        
		//
        $('#fileupload_editor').fileupload({
            url:options.upload_url,
            dataType: 'json',
            autoUpload: true,
            acceptFileTypes:  /(\.|\/)(gif|jpe?g|png)$/i,
            maxNumberOfFiles : 1,
            maxFileSize: 5000000,
	        done: function (e, data) {//设置文件上传完毕事件的回调函数
	        	console.log(data);
	            if(data.result.code==200){
	            	$('#progress_editor').hide();
	            	$('#edit_area_' + options.id).focus();
                    document.execCommand('insertimage',0, data.result.data.url);
	            	$('#' + options.id).val($('#edit_area_' + options.id).html());
	            }else{
	            	alert(data.result.erro);
	            }
	        },
	        progressall: function (e, data) {//设置上传进度事件的回调函数
	            $('#progress_editor').show();
	            var progress = parseInt(data.loaded / data.total * 100, 10);
	            $('#progress_editor .bar').css(
	                'width',
	                progress + '%'
	            );
	        }
        });
    };

    exports.init = editor_init;
});