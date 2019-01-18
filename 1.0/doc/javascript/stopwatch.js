var stopwatch=new Object();
stopwatch.watches=new Array(); //已注册的定时器数组

stopwatch.getWatch=function(id,startNow){//客户端代码入口
	var watch=stopwatch.watches[id];
	if(!watch){
		watch=new stopwatch.StopWatch(id);
	}
	if(startNow){
		watch.start();
	}
	return watch;
}

stopwatch.StopWatch=function(id){//对象构造函数
	this.id=id;
	stopwatch.watches[id]=this;
	this.count=0;
	this.total=0;
	this.events=new Array();
	this.objViewSpec=[
	    {name:"count",type:"simple"},
	    {name:"total",type:"simple"},
	    {name:"events",type:"array",inline:true}
	];
	$('<div id="stopwatchDebug" style="position:absolute; right:0; top:0; width:300px; background-color:#0000FF; color:#ffffff; font-size:12px;" />').appendTo('body');
}
stopwatch.StopWatch.prototype.start=function(){
	this.current=new TimedEvent();
}
stopwatch.StopWatch.prototype.stop=function(){
	if(this.current){
		this.current.stop();
		this.events.push(this.current);
		this.count++;
		this.total+=this.current.duration;
		this.current=null;
	}
}
var TimedEvent=function(){//定时事件对象构造函数
	this.start=new Date();
	this.objViewSpec=[
	    {name:"start",type:"simple"},
	    {name:"duration",type:"simple"}          
	];
}
TimedEvent.prototype.stop=function(){
	var stop=new Date();
	this.duration=stop-this.start;
}
stopwatch.report=function(){
	$('#stopwatchDebug').html('');
	for(i  in stopwatch.watches){
		//console.log("["+i+"]==>\t"+stopwatch.watches[i].count+"次,"+stopwatch.watches[i].total+"毫秒");
		$('#stopwatchDebug').prepend("<p>["+i+"]==>\t"+stopwatch.watches[i].count+"次,"+stopwatch.watches[i].total+"毫秒,约"+(stopwatch.watches[i].total/stopwatch.watches[i].count)+"毫秒/次</p>");
	}
	
}