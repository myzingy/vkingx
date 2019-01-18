/* http://github.com/mindmup/bootstrap-wysiwyg */
/*global jQuery, $, FileReader*/
/*jslint browser:true*/
define(function(require, exports, module){
    return function(jQuery){
        var common = require('common');
        (function ($) {
        'use strict';
        var readFileIntoDataUrl = function (fileInfo) {
            var loader = $.Deferred(),
                fReader = new FileReader();
            fReader.onload = function (e) {
                loader.resolve(e.target.result);
            };
            fReader.onerror = loader.reject;
            fReader.onprogress = loader.notify;
            fReader.readAsDataURL(fileInfo);
            return loader.promise();
        };
        $.fn.cleanHtml = function () {
            var html = $(this).html();
            return html && html.replace(/(<br>|\s|<div><br><\/div>|&nbsp;)*$/, '');
        };
        $.fn.wysiwyg = function (userOptions) {
            var editor = this,
                selectedRange,
                options,
                toolbarBtnSelector,
                updateToolbar = function () {
                    if (options.activeToolbarClass) {
                        $(options.toolbarSelector).find(toolbarBtnSelector).each(function () {
                            var command = $(this).data(options.commandRole);
                            /*console.log(document.queryCommandState);
                            if (document.queryCommandState(command)) {
                                $(this).addClass(options.activeToolbarClass);
                            } else {
                                $(this).removeClass(options.activeToolbarClass);
                            }*/
                        });
                    }
                },
                execCommand = function (commandWithArgs, valueArg) {
                    var commandArr = commandWithArgs.split(' '),
                        command = commandArr.shift(),
                        args = commandArr.join(' ') + (valueArg || '');
                    document.execCommand(command, 0, args);
                    updateToolbar();
                },
                bindHotkeys = function (hotKeys) {
                    $.each(hotKeys, function (hotkey, command) {
                        editor.keydown(hotkey, function (e) {
                            if (editor.attr('contenteditable') && editor.is(':visible')) {
                                e.preventDefault();
                                e.stopPropagation();
                                execCommand(command);
                            }
                        }).keyup(hotkey, function (e) {
                            if (editor.attr('contenteditable') && editor.is(':visible')) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        });
                    });
                },
                getCurrentRange = function () {
                    var sel = window.getSelection();
                    if (sel.getRangeAt && sel.rangeCount) {
                        return sel.getRangeAt(0);
                    }
                },
                saveSelection = function () {
                    selectedRange = getCurrentRange();
                },
                restoreSelection = function () {
                    var selection = window.getSelection();
                    if (selectedRange) {
                        try {
                            selection.removeAllRanges();
                        } catch (ex) {
                            document.body.createTextRange().select();
                            document.selection.empty();
                        }
                        selection.addRange(selectedRange);
                    }
                },
                insertFiles = function (files) {
                    editor.focus();
                    document.execCommand('insertimage',0, files);
                },
                markSelection = function (input, color) {
                    restoreSelection();
                    if (document.queryCommandSupported('hiliteColor')) {
                        document.execCommand('hiliteColor', 0, color || 'transparent');
                    }
                    saveSelection();
                    input.data(options.selectionMarker, color);
                },
                bindToolbar = function (toolbar, options) {
                    toolbar.find(toolbarBtnSelector).click(function () {
                        restoreSelection();
                        editor.focus();
                        execCommand($(this).data(options.commandRole));
                        saveSelection();
                    });
                    toolbar.find('[data-toggle=dropdown]').click(restoreSelection);
                    toolbar.find('input[type=text][data-' + options.commandRole + ']').on('webkitspeechchange change', function () {
                        var newValue = this.value; /* ugly but prevents fake double-calls due to selection restoration */
                        this.value = '';
                        restoreSelection();
                        if (newValue) {
                            editor.focus();
                            execCommand($(this).data(options.commandRole), newValue);
                        }
                        saveSelection();
                    }).on('focus', function () {
                        var input = $(this);
                        if (!input.data(options.selectionMarker)) {
                            markSelection(input, options.selectionColor);
                            input.focus();
                        }
                    }).on('blur', function () {
                        var input = $(this);
                        if (input.data(options.selectionMarker)) {
                            markSelection(input, false);
                        }
                    });
                    /*toolbar.find('input[type=file][data-' + options.commandRole + ']').change(function () {
                        restoreSelection();
                        if (this.type === 'file' && this.files && this.files.length > 0) {
                            insertFiles(this.files);
                        }
                        saveSelection();
                        this.value = '';
                    });
                    editor.prev().find('.pictureBtn').on('click',function(){
                        common.img_upload({
                            upload_url: options.upload_url,
                            success: function(data){
                                restoreSelection();
                                insertFiles(data.img_src);
                                saveSelection();
                                $('#' + options.id).val($('#edit_area_' + options.id).html());
                            }
                        });
                    });
                    */
                    //增加超链
                    editor.prev().find('.link-dialog').on('click', function(){
                        common.form_dialog({
                            width:600,
                            content: '<div class="row-fluid control-group" id="add_url_dialog"><label class="control-label" for="menu_title">请输入合法的URL(http://开头)</label><input class="span11" placeholder="http://" type="text" value="http://" id="add_url"></div>',
                            form_rules: function(){
                                var reg_text = /^http[s]?:\/\/.*/;
                                if(reg_text.test($('#add_url').val())){
                                    return true;
                                }else{
                                    $('#add_url_dialog').addClass('error');
                                    return false;
                                }
                            },
                            callback: function(obj){
                                var newValue = $('#add_url').val();
                                restoreSelection();
                                if (newValue) {
                                    editor.focus();
                                    if(selectedRange.toString() === ''){
                                        var s=window.getSelection(),
                                            r=s.getRangeAt(0);
                                        var nnode = document.createElement('a');
                                        nnode.href = newValue;
                                        r.surroundContents(nnode);
                                        nnode.innerHTML = newValue;
                                    }else{
                                        execCommand('createLink', newValue);
                                    }
                                }
                                saveSelection();
                                obj.dialog('close');
                            }
                        });
                    });
                    //增加电话超链
                    editor.prev().find('.phone-dialog').on('click', function(){
                        common.form_dialog({
                        	width:600,
                            content: '<div class="row-fluid control-group" id="add_phone_dialog"><label class="control-label" for="menu_title">请输入您的电话号码（只能为数字）</label><input class="span11" placeholder="02988888888" type="text" value="" id="add_phone"></div>',
                            form_rules: function(){
                                var reg_text = /^([+-]?)\d*\.?\d+$/;
                                if(reg_text.test($('#add_phone').val())){
                                    return true;
                                }else{
                                    $('#add_phone_dialog').addClass('error');
                                    return false;
                                }
                            },
                            callback: function(obj){
                                var newValue = 'tel:' + $('#add_phone').val();
                                restoreSelection();
                                if (newValue) {
                                    editor.focus();
                                    if(selectedRange.toString() === ''){
                                        var s=window.getSelection(),
                                            r=s.getRangeAt(0);
                                        var nnode = document.createElement('a');
                                        nnode.href = newValue;
                                        r.surroundContents(nnode);
                                        nnode.innerHTML = newValue.replace('tel:', '');
                                    }else{
                                        execCommand('createLink', newValue);
                                    }
                                }
                                saveSelection();
                                if(window.getSelection){
                                    var selection = window.getSelection();
                                    if (selection.rangeCount) {
                                        if(selection.toString().indexOf('tel:') > -1){
                                            var f = selection.toString().replace('tel:', '');
                                            var range = selection.getRangeAt(0);
                                            range.deleteContents();
                                            range.insertNode(document.createTextNode(f));
                                        }
                                    }
                                }
                                
                                obj.dialog('close');
                            }
                        });
                    });
                    //变换字体颜色
                    editor.parent().find('.select_font_color span').on('click', function(){
                        if($(this).hasClass('color_none')){
                            var color = 'rgb(51, 51, 51)';
                        }else{
                            var color = $(this).find('a').css('background-color');
                        }
                        console.log(color);
                        restoreSelection();
                        editor.focus();
                        execCommand('ForeColor', color);
                        saveSelection();
                    });
                    //设置背景颜色
                    editor.parent().find('.select_bg_color span').on('click', function(){
                        if($(this).hasClass('color_none')){
                            var color = 'rgb(255, 255, 255)';
                        }else{
                            var color = $(this).find('a').css('background-color');
                        }
                        restoreSelection();
                        editor.focus();
                        execCommand('BackColor', color);
                        saveSelection();
                    });
                },
                initFileDrops = function () {
                    editor.on('dragenter dragover', false)
                        .on('drop', function (e) {
                            var dataTransfer = e.originalEvent.dataTransfer;
                            e.stopPropagation();
                            e.preventDefault();
                            if (dataTransfer && dataTransfer.files && dataTransfer.files.length > 0) {
                                insertFiles(dataTransfer.files);
                            }
                        });
                };
            options = $.extend({}, $.fn.wysiwyg.defaults, userOptions);
            toolbarBtnSelector = 'a[data-' + options.commandRole + '],button[data-' + options.commandRole + '],input[type=button][data-' + options.commandRole + ']';
            bindHotkeys(options.hotKeys);
            if (options.dragAndDropImages) {
                initFileDrops();
            }
            bindToolbar($(options.toolbarSelector), options);
            editor.attr('contenteditable', true)
                .on('mouseup keyup mouseout', function () {
                    saveSelection();
                    updateToolbar();
                });
            editor.on('paste', function(e){
                var el = $(this);
                setTimeout(function() {
                    var text = $(el).html();
                    //text=text.replace(/position/ig,'vb_position');
                    text=text.replace(/<?style|<script|<link|class=|id=/ig,function($a){
                        console.log($a);
                        return "vb_fix"+$a;
                    });
                    $(el).html(text);
                }, 100);
            })
            $(window).bind('touchend', function (e) {
                var isInside = (editor.is(e.target) || editor.has(e.target).length > 0),
                    currentRange = getCurrentRange(),
                    clear = currentRange && (currentRange.startContainer === currentRange.endContainer && currentRange.startOffset === currentRange.endOffset);
                if (!clear || isInside) {
                    saveSelection();
                    updateToolbar();
                }
            });
            return this;
        };
        $.fn.wysiwyg.defaults = {
            hotKeys: {
                'ctrl+b meta+b': 'bold',
                'ctrl+i meta+i': 'italic',
                'ctrl+u meta+u': 'underline',
                'ctrl+z meta+z': 'undo',
                'ctrl+y meta+y meta+shift+z': 'redo',
                'ctrl+l meta+l': 'justifyleft',
                'ctrl+r meta+r': 'justifyright',
                'ctrl+e meta+e': 'justifycenter',
                'ctrl+j meta+j': 'justifyfull',
                'shift+tab': 'outdent',
                'tab': 'indent'
            },
            toolbarSelector: '[data-role=editor-toolbar]',
            commandRole: 'edit',
            activeToolbarClass: 'btn-info',
            selectionMarker: 'edit-focus-marker',
            selectionColor: 'darkgrey',
            dragAndDropImages: true,
            fileUploadError: function (reason, detail) { console.log("File upload error", reason, detail); }
        };
    }(window.jQuery));
};
});