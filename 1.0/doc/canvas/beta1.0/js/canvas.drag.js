/**
*	canvasDrag
*	canvas 拖拽事件
*	canvas 元素右击事件
*	canvas 元素左击事件
**/
$.fn.canvasDrag=function(canvas,options){
	var i=0; //移动时累加参数
	var settings = jQuery.extend({
        px:0 //偏移left							
		,py:0  //偏移top
		,movefun:function(){} //移动处理函数
		,leftfun:function(){} //左键处理函数
		,rightfun:function(){} //右键处理函数							
    }, options);
	var canvasBox=this;
	canvasBox.I=''; //有对象被选中时>-1
	canvasBox.movefun=function(event){
		event=!event?document.event:event;
		canvasBox.unbind('mousemove');
		i++;
		//canvas.log(i);
		eval("settings.movefun(event,canvasBox.I);");
		setTimeout(function(){
			$(canvasBox).mousemove(canvasBox.movefun);
		},50);
	};
	canvasBox.bind('mousedown mouseup',function(event){
		
		if(event.type=='mousedown'){
			//canvas.log(event);
			var _xy=getMouseXY(event);
			canvasBox.I=canvas.isGraph({'x':_xy.x-canvas.pos.left,'y':_xy.y-canvas.pos.top});
			
			if(event.button!=2){ //左键
				canvasmouseXY={'x':_xy.x,'y':_xy.y};
				canvasBox.bind('mousemove',canvasBox.movefun);
			}
			
		}
		if(event.type=='mouseup'){
			if(i<2){
				if(event.button==2){ //右键
					eval("settings.rightfun(event,canvasBox.I);");
				}else{
					eval("settings.leftfun(event,canvasBox.I);");
				}
			}
			i=0;
			canvasBox.I='';
			canvasmouseXY={'x':0,'y':0};
			canvasBox.unbind('mousemove');
		}
		
	});
	return 	canvasBox;
};