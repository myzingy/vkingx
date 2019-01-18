define(function(require, exports, module) {
    var $ = require('jquery');
    var common = require('common');
    exports.init=function(){
        common.loadhtml(location.hash?location.hash:'/tpl/work.html');
    };
});