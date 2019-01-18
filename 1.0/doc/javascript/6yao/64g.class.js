Array.prototype.indexOf=function(value){for(var i=0,l=this.length;i<l;i++)if(this[i]==value)return i;return -1;};
function g(){
	this.G_10=-1;
	this.G_2='';
	this.G_ARR=[];
}
g.prototype.s2b=function(){
	i=(this.G_10).toString('2');
	var l=i.length;
	for(var p=6;p>l;p--){
		i='0'+i;
	}
	return i;
}
g.prototype.selectG=function(x){
	var r=(x%33);
	r=r==0?33:r;
	if(this.G_ARR.indexOf(r)==-1){
		this.G_ARR.push(r);
		return true;
	}
	return false;
}
g.prototype.claerG=function(x){
	this.G_ARR.length=0;
}
g.prototype.randG=function(){
	var br=parseInt(Math.random()*64);
	var r=(r%33);
	r=r==0?33:r;
	if(this.G_ARR.indexOf(r)==-1){
		this.G_10=br;
		this.G_2=this.s2b();
		return;
	}
	this.randG();
}
g.prototype.getHtml=function(){
	var x=this.G_2.split('');
	var html='<div class="g_show">';
	for(var i=0;i<x.length;i++){
		html+='<div class="yao '+(x[i]==0?'yin':'yang')+'"></div>';
	}
	html+='</div>';
	return html;
	//$(html).appendTo('#gua6');
	//console.log(x);
}
g.prototype.playCP=function(){
	var html='<tr>';
	html+='<td>第'+($(".blue",'#zhu').length+1)+'注</td>';
	this.G_ARR.sort(function(a,b){return a-b});
	for(var i=0;i<this.G_ARR.length;i++){
		html+='<td class="red">'+(this.G_ARR[i]>9?this.G_ARR[i]:('0'+this.G_ARR[i]))+'</td>';
	}
	html+='<td class="blue">&nbsp;</td>';
	html+='</tr>';
	$(html).appendTo('#zhu');
}