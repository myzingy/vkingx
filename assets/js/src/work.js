define(function(require, exports, module) {
    var $ = require('jquery');
    var common = require('common');
    require('tpl');
    var history_tpl= $('#history_tpl').html();
    var history_tpl_list= $('#history_tpl_list').html();
    $.tpl.history_tpl_list=function(r){
        return $.tpl.html(history_tpl_list,r.list);
    };
    $.tpl.history_tpl_more=function(r){
        return $.tpl.html('<p>{info}</p>',r.more);
    };
    exports.init=function(action){
        $("#history").on('click','.year>h2>a',function(e){
            console.log(this);
            e.preventDefault();
            $(this).parents(".year").toggleClass("close");
        });
        action=action||'getWorks';
        $.getJSON(common.cgi('cgi/tp3/index.php','apido/'+action));
        var display=function(json){
            if(json.data){
                $.tpl.html(history_tpl,json.data,function(html){
                    $('#history').html(html);
                });
                $(".main .year .list").each(function(e, target){
                    var $target=  $(target),
                        $ul = $target.find("ul");
                    $target.height($ul.outerHeight()), $ul.css("position", "absolute");
                });
            }
        };
        common._formcallback=function(json){
            display(json);
        };
    };
});