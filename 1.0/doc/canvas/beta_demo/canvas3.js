// JavaScript Document
function canvas(id){
	this.cvs=document.getElementById(id);
	this.ctx=this.cvs.getContext('2d');
	this.width=this.ctx.canvas.clientWidth;
	this.height=this.ctx.canvas.clientHeight; 
	this.pos=$(this.cvs).position(); //位置
	this.play=[]; //动作
	this.translate={'x':0,'y':0}; // 画布位移因子
	this.scale={'x':1,'y':1}; // 画布缩放因子
	this.mouseXY={'x':0,'y':0};// 鼠标位置
	this.isCanvas=!!document.createElement('canvas').getContext;
	/*
	this.settings={
		strokeStyle:"#000000",
		lineWidth:1,
		lineCap :'butt', //butt | round  |square 线头样式
		lineJoin : 'miter', //round, bevel miter 两线连接样式
		fillStyle:"#FFFFFF"		
	};
	*/
	return this;
}
/*赋值*/
canvas.prototype.set=function(options){
	this.add({'type':'set','data':options});
	for(var i in options){
		eval("this.ctx."+i+"='"+options[i]+"';");
	}
	return this;
};
/*监听事件*/
canvas.prototype.addEvent = function(c, a, b) {
	var d = function(f) {
		if (!f) {
			var f = window.event;
		}
		b(f);
	};
	if (window.addEventListener) {
		c.addEventListener(a, d, false);

	} else {
		c.attachEvent("on" + a, d);
	}
};
/***************************************
	以下为封装 canvas 绘图方法
****************************************/
/*
	Circle
	返回数组 index	
*/
canvas.prototype.circle=function(x,y,r,fill){
	this.ctx.beginPath();
	this.ctx.arc(x,y,r,0,Math.PI*2,true);
	this.ctx.stroke();
	this.ctx.closePath();
	if(!!fill){
		this.ctx.fill();
	}
	return this.add({'type':'circle','data':{'x':x,'y':y,'r':r,'fill':fill}});
};
/*	
	rect
	返回数组 index
*/
canvas.prototype.rect=function(x,y,width,height,fill){
	if(!!fill){
		this.ctx.fillRect(x,y,width,height);
	}else{
		this.ctx.strokeRect(x,y,width,height);
	}
	return this.add({'type':'rect','data':{'x':x,'y':y,'width':width,'height':height,'fill':fill}});
};
/*
	image
	返回数组 index 
*/
canvas.prototype.image=function(imageURL,x,y,width,height){
	width=!width?0:width;
	height=!height?0:height;
	var ctx=this.ctx;
	if(typeof imageURL == "string"){
		var img=new Image();
		img.src=imageURL;
	}else{
		var img=imageURL;
		if(!!img.title){
			this.ctx.font='bold '+parseInt(12*this.scale.x)+'px sans-serif';
			var tx=x;
			var ty=y;
			if(!this.isCanvas){
				tx+=this.translate.x;
				ty+=this.translate.y;
			}
			this.ctx.fillText(img.title, tx, ty+height+10,false,{'font':this.ctx.font,'color':this.ctx.fillStyle});
		}
	}
	if(!!img.complete){
		this.ctx.drawImage(img,x,y,width,height);
	}else{
		setTimeout(function(){ctx.drawImage(img,x,y,width,height);},200);
	}
	return this.add({'type':'image','data':{'imageURL':img,'x':x,'y':y,'width':width,'height':height}});
};
/*
	line
	返回数组 index
*/
canvas.prototype.line=function(x1,y1,x2,y2){
	this.ctx.beginPath();
	this.ctx.moveTo(x1,y1);
	this.ctx.lineTo(x2,y2);
	this.ctx.stroke();
	this.ctx.closePath();
	return this.add({'type':'line','data':{'x1':x1,'y1':y1,'x2':x2,'y2':y2}});	
};
/*
	text
	返回数组 index
*/
canvas.prototype.text=function(string,x,y){
	return this.add({'type':'text','data':{'string':string,'x':x,'y':y}});	
};
/***************************************
	以上为封装 canvas 绘图方法
****************************************/
canvas.prototype.clear=function(){
	this.ctx.clearRect(0,0,this.width,this.height);
};
canvas.prototype.add=function(obj){
	this.play.push(obj);
	return this.play.length-1;
};
canvas.prototype.isGraph=function(point,index){//位移处理，并返回位移前的对象
	var play=this.play;
	var i=!index?0:index;
	for(i;i<play.length;i++){
		var type=play[i].type;
		var data=play[i].data;
		var pianyi_x=this.translate.x*this.scale.x;
		var pianyi_y=this.translate.y*this.scale.y;
		switch(type){
			case 'circle' :
				var x=(data.x+this.translate.x)-point.x;
				var y=(data.y+this.translate.y)-point.y;
				var r=Math.sqrt(Math.pow(x,2)+Math.pow(y,2));
				//this.log("I:"+i+",data.x:"+data.x+",point.x:"+point.x+',this.translate.x:'+this.translate.x+',x='+x);
				if(r<=data.r || !!index){
					this.play[i].data.x=point.x-this.translate.x;
					this.play[i].data.y=point.y-this.translate.y;
					return i;
					
				}
			break;
			case 'rect' :
				var x2=(data.x+data.width);
				var y2=(data.y+data.height);
				if( (point.x-this.translate.x >= data.x && point.x-this.translate.x <= x2 &&
				    point.y-this.translate.y >= data.y && point.y-this.translate.y <= y2) || !!index ){
					this.play[i].data.x=	(point.x-data.width/2-this.translate.x);
					this.play[i].data.y=	(point.y-data.height/2-this.translate.y);
					return i;
				}
			break;
			case 'image' :
				var x2=data.x+data.width;
				var y2=data.y+data.height;
				if( (point.x-this.translate.x >= data.x && point.x-this.translate.x <= x2 &&
				    point.y-this.translate.y >= data.y && point.y-this.translate.y <= y2) || !!index ){
					this.play[i].data.x=	point.x-data.width/2-this.translate.x;
					this.play[i].data.y=	point.y-data.height/2-this.translate.y;
					return i;
				}
			break;
			default :
				//this.log('error data!');
		}	
	}
	return false;
};
canvas.prototype.setGraphXY=function(point,index){
	switch(this.play[index].type){
		case 'line' :
			if(!!point.x1) this.play[index].data.x1=point.x1;
			if(!!point.y1) this.play[index].data.y1=point.y1;
			if(!!point.x2) this.play[index].data.x2=point.x2;
			if(!!point.y2) this.play[index].data.y2=point.y2;
		break;
		default:
			this.play[i].data.x=point.x;
			this.play[i].data.y=point.y;
	}
};
canvas.prototype.moveGraph=function(point,index){
	return this.isGraph(point,index);	
};
canvas.prototype._moveCanvas=function(x,y){
	this.translate={'x':this.translate.x+x,'y':this.translate.y+y};
	//this.log(this.translate,'translate');
	this.ctx.translate(x,y);
};
canvas.prototype.moveCanvas=function(x,y){
	var _mx=(this.mouseXY.x>0)?(x-this.mouseXY.x):0;
	var _my=(this.mouseXY.y>0)?(y-this.mouseXY.y):0;
	this.mouseXY={'x':x,'y':y};
	//this.log("mx:"+_mx+",my:"+_my+"x:"+x+",y:"+y);
	if(_mx!=0 || _my!=0){
		this._moveCanvas(_mx,_my);
	}
};
canvas.prototype.scaleCanvas=function(x,y){
	for(var i=0;i<this.play.length;i++){
		switch(this.play[i].type){	
			case 'circle' :
				this.play[i].data.x*=x;
				this.play[i].data.y*=y;
				this.play[i].data.r*=x;
			break;
			case 'rect' :
				this.play[i].data.x*=x;
				this.play[i].data.y*=y;
				this.play[i].data.width*=x;
				this.play[i].data.height*=y;
			break;
			case 'image' :
				this.play[i].data.x*=x;
				this.play[i].data.y*=y;
				this.play[i].data.width*=x;
				this.play[i].data.height*=y;
			break;
			case 'line' :
				this.play[i].data.x1*=x;
				this.play[i].data.y1*=y;
				this.play[i].data.x2*=x;
				this.play[i].data.y2*=y;
			break;
		}
	}
	sx=x-1;
	sy=y-1;
	this.scale={'x':this.scale.x+sx,'y':this.scale.y+sy};
	//this.log(this.scale);
	//this.ctx.scale(x,y);
};
canvas.prototype.display=function(){
	//this.log(this.play,'this_play');
	var play=this.play;
	this.play=[];
	for(var i=0;i<play.length;i++){
		var type=play[i].type;
		var data=play[i].data
		switch(type){
			case 'set' :
				this.set(data);
			break;
			case 'circle' :
				this.circle(data.x,data.y,data.r,data.fill);
			break;
			case 'rect' :
				this.rect(data.x,data.y,data.width,data.height,data.fill);
			break;
			case 'image' :
				this.image(data.imageURL,data.x,data.y,data.width,data.height);
			break;
			case 'line' :
				this.line(data.x1,data.y1,data.x2,data.y2);
			break;
			default :
				//canvas.prototype.log('error play data!');
		}	
	}	
};
canvas.prototype.log=function(obj,groupname){
	if(!$.browser.mozilla) return;
	if(typeof console == 'undefined') return;
	groupname=!groupname?'':groupname;
	if(typeof obj == 'object'){
		if(!obj.length){
			console.group("log group %s", groupname);
		}else{
			console.group("log group %s,length: %d", groupname,obj.length);
		}
		console.log("%o",obj);
		console.groupEnd();
		return;
	}
	console.log(groupname+"%s",obj);
};
function getMouseXY(e){ //返回鼠标在页面上的位置{x,y}
	var d = {}, x, y;
	if( self.innerHeight ) {
		d.pageYOffset = self.pageYOffset;
		d.pageXOffset = self.pageXOffset;
		d.innerHeight = self.innerHeight;
		d.innerWidth = self.innerWidth;
	} else if( document.documentElement &&
		document.documentElement.clientHeight ) {
		d.pageYOffset = document.documentElement.scrollTop;
		d.pageXOffset = document.documentElement.scrollLeft;
		d.innerHeight = document.documentElement.clientHeight;
		d.innerWidth = document.documentElement.clientWidth;
	} else if( document.body ) {
		d.pageYOffset = document.body.scrollTop;
		d.pageXOffset = document.body.scrollLeft;
		d.innerHeight = document.body.clientHeight;
		d.innerWidth = document.body.clientWidth;
	}
	(e.pageX) ? x = e.pageX : x = e.clientX + d.scrollLeft;
	(e.pageY) ? y = e.pageY : y = e.clientY + d.scrollTop;
	return {'x':x,'y':y};
}