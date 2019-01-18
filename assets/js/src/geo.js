define(function(require, exports, module) {
    var $ = require('jquery');
    var common = require('common');
    var log=function($1,$2){
        $('#info').append('<div style="word-break:break-all;width:98%;margin: 10px auto;">'+JSON.stringify($1)+'=>'+JSON.stringify($2)+"</div>");
    };
    exports.init=function(){
        $('.header_footer').hide();
        $('.content').css('padding-top',0);
    };
    exports.init_amap=function(){
        log("init amap","start...");
        var onComplete=function(data){log("amap::position",data);};
        var onError=function(data){log("amap::position::error",data);};
        var mapObj = new AMap.Map('iCenter',{
            resizeEnable: true,
            zoom: 15
        });
        var geolocation;
        mapObj.plugin(["AMap.ToolBar"],function(){
            //加载工具条
            var tool = new AMap.ToolBar();
            mapObj.addControl(tool);
        });
        mapObj.plugin('AMap.Geolocation', function () {
            geolocation = new AMap.Geolocation({
                enableHighAccuracy: true,//是否使用高精度定位，默认:true
                timeout: 10000,          //超过10秒后停止定位，默认：无穷大
                maximumAge: 0,           //定位结果缓存0毫秒，默认：0
                convert: true,           //自动偏移坐标，偏移后的坐标为高德坐标，默认：true
                showButton: true,        //显示定位按钮，默认：true
                buttonPosition: 'LB',    //定位按钮停靠位置，默认：'LB'，左下角
                buttonOffset: new AMap.Pixel(10, 20),//定位按钮与设置的停靠位置的偏移量，默认：Pixel(10, 20)
                showMarker: true,        //定位成功后在定位到的位置显示点标记，默认：true
                showCircle: true,        //定位成功后用圆圈表示定位精度范围，默认：true
                panToLocation: true,     //定位成功后将定位到的位置作为地图中心点，默认：true
                //zoomToAccuracy:true      //定位成功后调整地图视野范围使定位位置及精度范围视野内可见，默认：false
            });
            mapObj.addControl(geolocation);
            geolocation.getCurrentPosition();
            AMap.event.addListener(geolocation, 'complete', onComplete);//返回定位信息
            AMap.event.addListener(geolocation, 'error', onError);      //返回定位出错信息
        });

        if (navigator.geolocation) {
            var marker;
            var updateMarker=function(pos) {
                // 自定义点标记内容
                //var markerContent = document.createElement("div");

                // 点标记中的图标
                //var markerImg = document.createElement("img");
                //markerImg.className = "markerlnglat";
                //markerImg.src = "http://webapi.amap.com/theme/v1.3/markers/n/mark_r.png";
                //markerContent.appendChild(markerImg);

                // 点标记中的文本
                var img=$('<img src="https://webapi.amap.com/theme/v1.3/markers/n/mark_r.png">');
                var txt=$('<div style="background: #933;color:#fff;">Hi，我是火星坐标<br>误差大吗？</div>');
                var markerContent=$('<div></div>').append(img).append(txt);

                marker.setContent(markerContent[0]); //更新点标记内容
                marker.setPosition([pos.lng, pos.lat]); //更新点标记位置
            };
            navigator.geolocation.getCurrentPosition(function(position){
                var posi=position.coords.longitude+","+position.coords.latitude;
                log("h5::location",{lat:position.coords.latitude,lng:position.coords.longitude});
                marker = new AMap.Marker({
                    position: posi.split(','),
                    title: posi
                });
                marker.setMap(mapObj);
                var pos=geoTransform(position.coords.latitude,position.coords.longitude);
                log("gps转火星坐标",pos.lng+','+pos.lat);
                setTimeout(function(){
                    log("火星坐标","goto...");
                    updateMarker(pos);
                    mapObj.panTo([pos.lng, pos.lat]);
                },1000);
            },function(data){
                log("error",data);
            },{
                // 指示浏览器获取高精度的位置，默认为false
                enableHighAccuracy: true,
                // 指定获取地理位置的超时时间，默认不限时，单位为毫秒
                timeout: 5000,
                // 最长有效期，在重复获取地理位置时，此参数指定多久再次获取位置。
                maximumAge: 300
            });
        }
        else {
            log("h5::location::error","浏览器不支持定位.");
        }
    };
    exports.init_qqmap=function() {
        log("init qqmap","start...");
        var showPosition = function (position) {
            log("position",position);
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            //调用地图命名空间中的转换接口   type的可选值为 1:gps经纬度，2:搜狗经纬度，3:百度经纬度，4:mapbar经纬度，5:google经纬度，6:搜狗墨卡托
            qq.maps.convertor.translate(new qq.maps.LatLng(lat, lng), 1, function (res) {
                //取出经纬度并且赋值
                latlng = res[0];

                var map = new qq.maps.Map(document.getElementById("container"), {
                    center: latlng,
                    zoom: 13
                });
                //设置marker标记
                var marker = new qq.maps.Marker({
                    map: map,
                    position: latlng
                });
            });
        };
        //判断是否支持 获取本地位置
        log("init qqmap ...",navigator.geolocation);
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(data){
                log("geolocation",data);
                showPosition(data);
            },function(data){
                log("error",data);
            },{
                // 指示浏览器获取高精度的位置，默认为false
                enableHighAccuracy: true,
                // 指定获取地理位置的超时时间，默认不限时，单位为毫秒
                timeout: 5000,
                // 最长有效期，在重复获取地理位置时，此参数指定多久再次获取位置。
                maximumAge: 300
            });
        }
        else {
            $('#container').html("浏览器不支持定位.");
        }
    }
    /////////////////////////////
    var pi = 3.14159265358979324
    var a = 6378245.0
    var ee = 0.00669342162296594323

    function geoTransform(wgLat, wgLng) {
        var transCoords = new Array()
        if (regionOfChina(wgLat, wgLng)) {
            transCoords['lat'] = wgLat
            transCoords['lng'] = wgLng
            return transCoords
        }
        var dLat = transformLat(wgLng - 105.0, wgLat - 35.0)
        var dLng = transformLng(wgLng - 105.0, wgLat - 35.0)
        var radLat = wgLat / 180.0 * pi
        var magic = Math.sin(radLat)
        magic = 1 - ee * magic * magic
        var sqrtMagic = Math.sqrt(magic)
        dLat = (dLat * 180.0) / ((a * (1 - ee)) / (magic * sqrtMagic) * pi)
        dLng = (dLng * 180.0) / (a / sqrtMagic * Math.cos(radLat) * pi)
        transCoords['lat'] = wgLat + dLat
        transCoords['lng'] = wgLng + dLng
        return transCoords
    }

    function regionOfChina(lat, lng) {
        if (lng < 72.004 || lng > 137.8347) {
            return true
        } else if (lat < 0.8293 || lat > 55.8271) {
            return true
        } else {
            return false
        }
    }

    function transformLat(x, y) {
        var ret = -100.0 + 2.0 * x + 3.0 * y + 0.2 * y * y + 0.1 * x * y + 0.2 * Math.sqrt(Math.abs(x));
        ret += (20.0 * Math.sin(6.0 * x * pi) + 20.0 * Math.sin(2.0 * x * pi)) * 2.0 / 3.0;
        ret += (20.0 * Math.sin(y * pi) + 40.0 * Math.sin(y / 3.0 * pi)) * 2.0 / 3.0;
        ret += (160.0 * Math.sin(y / 12.0 * pi) + 320 * Math.sin(y * pi / 30.0)) * 2.0 / 3.0;
        return ret;
    }

    function transformLng(x, y) {
        var ret = 300.0 + x + 2.0 * y + 0.1 * x * x + 0.1 * x * y + 0.1 * Math.sqrt(Math.abs(x));
        ret += (20.0 * Math.sin(6.0 * x * pi) + 20.0 * Math.sin(2.0 * x * pi)) * 2.0 / 3.0;
        ret += (20.0 * Math.sin(x * pi) + 40.0 * Math.sin(x / 3.0 * pi)) * 2.0 / 3.0;
        ret += (150.0 * Math.sin(x / 12.0 * pi) + 300.0 * Math.sin(x / 30.0 * pi)) * 2.0 / 3.0;
        return ret;
    }
});