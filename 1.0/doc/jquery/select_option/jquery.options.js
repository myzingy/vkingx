//select option 控件
$.fn.option=function(options){
	var settings = jQuery.extend({
        url:null,							//json url
		data:null,							//json data
		field:{value:'id',text:'name'},		//数据字段
		nofield:false,                      //为true时则根据数据键值填充
		selectedValue:null,					//选中值
		disabled:false,						//是否使控件不可用，默认可用
		multiple:false,						//是否可以多选
		size:1								//控件高度
    }, options);
	var sel=this;
	var optionAdd=function(){
		
		if(settings.data){
			if(settings.nofield){
				$.each(settings.data,function(i,v){
					sel.append('<option value='+i+(i==settings.selectedValue?' selected="selected"':'')+'>'+v+'</option>');					
				});
			}else{
				$.each(settings.data,function(i,v){
					if(typeof v[settings.field.text]=="undefined") return;
					sel.append('<option value='+v[settings.field.value]+(v[settings.field.value]==settings.selectedValue?' selected="selected"':'')+'>'+v[settings.field.text]+'</option>');					 
				});
			}
		}
		
	};
	//控件初始化
	if(settings.disabled) sel.attr({disabled:true});
	if(settings.multiple){
		sel.attr({multiple:settings.multiple,size:settings.size});
	}
	//控件初始化
	if(settings.url){
		sel.attr({disabled:true});
		$.getJSON(settings.url,function(json){
			settings.data=json;
			optionAdd();
			if(!settings.disabled) sel.removeAttr('disabled');
			return sel;
		});	
	}else if(settings.data){
		optionAdd();
		return sel;
	}else if(!!settings.selectedValue){ //仅用于选中option
		sel.children('option[value="'+settings.selectedValue+'"]').attr('selected',true);
		return sel;
	}else if(!!settings.length){ //删除其余option
		sel.children().each(function(i){
			if(i>(settings.length-1)){
				$(this).remove();
			}
		});
		return sel;
	}
};