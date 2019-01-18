/**
 * Created by Administrator on 13-10-28.
 */
if(typeof window.console=='undefined'){window.console={log:function(){},error:function(){}}}
seajs.config({
    alias: {
        //jquery & jquery lib
        'jquery': 			'lib/jquery.js',
        //'jquery-ui': 		'lib/jquery-ui.custom.min.js',
        'jquery-ui':        'lib/jquery-ui-1.9.2.custom.min.js',
        'bootstrap' : 		'lib/bootstrap.min.js',
        'form': 	 		'lib/jquery.form.js',
        'xtable': 	 		'lib/jquery.xtable.js',
        'placeholder': 		'lib/placeholder',
        'treeview' : 		'lib/jquery.treeview.js',
        'xheditor' : 		'lib/xheditor/xheditor-1.1.14-zh-cn.min.js',
        'treenav' : 		'lib/jquery.treenav.js',
        'treediy' : 		'lib/jquery.treediy.js',
        
        'wysiwyg':          'lib/bootstrap-wysiwyg.js',
        'hotkey' :          'lib/jquery.hotkeys.js',
        'editor' :          'lib/editor_init.js',
        'tagsinput':        'lib/jquery.tagsinput.min.js',
        'localStorage':     'lib/jquery.localStorage.js',
        'tpl':     			'lib/jquery.tpl.js',
        'page':     		'lib/jquery.page.js',
        'table':     		'lib/jquery.table.js',

        'common' : 			'common.min.js',
    },
    charset: 'utf-8'
});

seajs.use('common', function(common){
	common.init();
});