<link rel="stylesheet" type="text/css" href="<?=$base_url?>css/jquery.gradient.css" />
<style type="text/css">
	.openbq{width:20px;height:20px; margin:3px 0px 0px 5px; cursor:pointer;}
	.qqimages{ cursor:pointer; border:1px solid #333333; margin:1px; width:24px; height:24px;}
	.button{ padding:10px; border:1px solid #FF0000; background-color:#CCCCCC; text-decoration:none; color:#FF0000; margin-right:20px;}
</style>
<script type="text/javascript" src="<?=$base_url?>js/jquery.validate.pack.js"></script>
<script type="text/javascript" src="<?=$base_url?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?=$base_url?>js/jquery.gradient.color.js"></script>
<div id="lattice" style="width:100%; overflow:auto;line-height: 100%;">
<?
echo $lattice;
?>
</div>

<div id="xdiv" class="xdiv_close">
	<div class="close_settting" onClick="setXY();">#</div>
	<div id="formlist">点阵汉字设置</div>

    <form action="<?=$base_url.$index_page;?>/lattice" method="post" id="latticeform" style="display:none;" class="form-horizontal">


		<input type="hidden" value="1" name="line_num" id="line_num" size="3" />
		<fieldset>
        
        <legend>必要设置</legend>
			<div class="input-prepend">
				<span class="add-on">转换字串</span>
				<input type="text" style="width:250px;" class="span12" value="静俏俏" name="valstr" id="valstr" />
			</div>
			<table width="100%">
				<tr>
					<td>套用样式</td>
					<td>选择字体</td>
				</tr>
				<tr>
					<td width="50%"><select id="autostyle" multiple="multiple" style="width:100%;">
							<option value="1">十字绣①</option>
							<option value="2">金钱绣②</option>
							<option value="3">浮雕绣③</option>
							<option value="4">QQ聊天</option>
						</select></td>
					<td width="50%"><select name="ffid" multiple="multiple" style="width:100%;">
							<? foreach($ff_arr as $k=>$v):?>
								<option value="<?=$k?>" <? if ($k=='24K'):?>selected="selected"<? endif;?> ><?=$v['name']?></option>
							<? endforeach;?>
						</select></td>
				</tr>
			</table>
        </fieldset>
        <fieldset>
        <legend>前景设置</legend>
        <table border="0" >
        	<tr>
            	<td>填充字符</td>
                <td><input name="font_color_1" id="font_color_1" type="text" value="" size="3" /><image src="images/qq/wx.gif" alt="点击选择" class="openbq" rel="font_color_1" style="display:none;"  />
                </td>
            </tr>
            <tr>
            	<td>色彩类型</td>
                <td><input name="style_type_1" type="radio" value="0">渐变颜色
                <input name="style_type_1" type="radio" value="1">纯色
                <input name="style_type_1" type="radio" value="2">随机颜色
                </td>
            </tr>
            <tr>
            	<td>填充颜色</td>
                <td><input name="style_color_1" id="style_color_1" type="text" value="" size="8" class="style_color">
                </td>
            </tr>
            
        </table>
        </fieldset>
        <fieldset>
        <legend>背景设置</legend>
        <table border="0" >
        	<tr>
            	<td>填充字符</td>
                <td><input name="font_color_2" id="font_color_2" type="text" value="" size="3"/><image src="images/qq/wx.gif" alt="点击选择" class="openbq" rel="font_color_2" style="display:none;" />
                </td>
            </tr>
            <tr>
            	<td>色彩类型</td>
                <td><input name="style_type_2" type="radio" value="0">渐变颜色
                <input name="style_type_2" type="radio" value="1">纯色
                <input name="style_type_2" type="radio" value="2">随机颜色
                </td>
            </tr>
            <tr>
            	<td>填充颜色</td>
                <td><input name="style_color_2" id="style_color_2" type="text" value="" size="8" class="style_color">
                </td>
            </tr>
        </table>
        </fieldset>
        <div style="text-align:center; padding-top:10px;"><input type="submit" class="btn btn-primary" value="提交">&nbsp;&nbsp;
			<input value="重置" type="reset" class="btn"></div>
    </form>
</div>
<script type="text/javascript">
$(function(){
	var options = {
		type:"post",
		dataType :"html",
		url:base_url+index_page+"/lattice",
		success: function(html) {
			setXY();
			$("#lattice").html(html);
			if($('#autostyle').val()==4){
				html=html.replace(/<font color="#[a-z0-9]+"><img src="images\/qq/ig,'');
				html=html.replace(/\.gif" width="24" height="24" \/><\/font>/ig,'');
				html=html.replace(/<br \/>/ig,'\n');
			}
			$("#lattice_code").val(html);
			$("#latticeform").find('input[type="submit"]').removeAttr("disabled");
			return false;
		} 
	};
	//$('#latticeform').ajaxForm(options);
	$('#latticeform').validate({
		rules:{
			valstr:{required:true},
			line_num:{required:true,digits:true,range:[1,5]},
			font_color_1:{rangelength:[1,6]},
			font_color_2:{rangelength:[1,6]}
		},
		messages:{
			valstr:{required:"必须填写"},
			line_num:{required:"必须填写",digits:"必须为整数",range:$.validator.format("不能小于{0}或大于{1}")},
			font_color_1:{rangelength:$.validator.format("字串长度不能小于{0}或大于{1}")},
			font_color_2:{rangelength:$.validator.format("字串长度不能小于{0}或大于{1}")}
		},
		event: "blur",
		errorElement: "em",
		submitHandler: function() {
			$('#latticeform').ajaxSubmit(options);
			$("#latticeform").find('input[type="submit"]').attr("disabled","disabled");
			return false;
		}
	});
	//color
	$('.style_color').gradientColorPicker( {
		showHexBox:true,
		bindOn:'click',
		cssPrefix: 'big_',
		gradient: {                
			count: 5,
			useSingleColors:true,
			fadeTo:'left',
			colors: ['000000', 'FFFFFF', 'FF0000', '00FF00',  '0000FF','FFFF00','FF00FF','00FFFF']
		}     
	});
	//autostyle
	var styletext={};
	$("#autostyle").change(function(){
		
		if(!$(this).val()){
			$("#latticeform").trigger('reset');
			return;
		}
		//global
		styletext['line_num']=1;
		$('#line_num,#font_color_1,#font_color_2').removeAttr('readonly');
		$('input[name^="font_color"]').css({color:'#000000','background-image':'none','text-indent':'0px'});
		$('input[name^="style_"]').parents('tr').show();
		$('.openbq').hide();
		switch($(this).val()){
			case "1":
				styletext['font_color_1']="╋";
				styletext['style_color_1']="#FF0000";
				styletext['style_type_1']=1;
				styletext['font_color_2']="╋";
				styletext['style_color_2']="#FFFFFF";
				styletext['style_type_2']=0;
			break;
			case "2":
				styletext['font_color_1']="⊙";
				styletext['style_color_1']="#FFFF00";
				styletext['style_type_1']=1;
				styletext['font_color_2']="╋";
				styletext['style_color_2']="#009900";
				styletext['style_type_2']=0;
			break;
			case "3":
				styletext['font_color_1']="┓";
				styletext['style_color_1']="#FF0000";
				styletext['style_type_1']=1;
				styletext['font_color_2']="━";
				styletext['style_color_2']="#FFFF00";
				styletext['style_type_2']=1;
			break;
			case "4":
				styletext['valstr']='★';
				styletext['line_num']=1;
				styletext['font_color_1']="/xin";
				styletext['font_color_2']="/shuai";
				$('input[name^="font_color"]').css({
					color:'#EBEBE4',
					'text-indent':'30px',
					'background-position':'0 -4px', 
					'background-repeat':'no-repeat'
				});
				$('input[name="font_color_1"]').css('background-image','url(images/qq'+styletext['font_color_1']+'.gif)');
				$('input[name="font_color_2"]').css('background-image','url(images/qq'+styletext['font_color_2']+'.gif)');
				$('.openbq').show();
				$('select[name="ffid"]').val(12);
				$('#line_num,#font_color_1,#font_color_2').attr('readonly',true);
				$('input[name^="style_"]').parents('tr').hide();
			break;
		}
		for(var i in styletext){
			if(i=='style_type_1' || i=='style_type_2'){
				$('input[name="'+i+'"]').eq(styletext[i]).attr('checked','checked');
			}else{
				$("#"+i).val(styletext[i]);
				if(i=='style_color_1' || i=='style_color_2'){
					$("#"+i).next().css({'background-color':styletext[i]});	
				}
			}
			
		}
	});
	//QQ
	$('.openbq').click(function(){
		var font_color=$(this).attr('rel');
		tipsWindown((font_color=='font_color_1'?'前景设置':'背景设置')+'--单击选择表情','id:qqbq',400,250,false,0,0);
		$('.qqimages').unbind('click').bind('click',function(){
			var v=this.src.match(/(\/[a-z]+)\.gif$/i)
			//alert(font_color+" "+v[1]);
			$("#"+font_color).val(v[1]).css('background-image','url(images/qq'+v[1]+'.gif)');
		});
		
	});
	
	$("#autostyle").trigger('change');
});

function setXY(){
	$("#xdiv").toggleClass('xdiv_open');
	if($("#xdiv").hasClass('xdiv_open')){
		$('#latticeform,#autostylediv').show();
		$('#formlist').hide();
	}else{
		$('#latticeform,#autostylediv').hide();
		$('#formlist').show();
	}
}
</script>