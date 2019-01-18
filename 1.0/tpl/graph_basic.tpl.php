<script type="text/javascript" src="<?=$base_url?>js/jquery.validate.pack.js"></script>
<script type="text/javascript" src="<?=$base_url?>js/jquery.form.js"></script>
<img src="<?=get_url($image);?>" id="imagecreate" />
<div style="border:#CCCCCC solid 1px; padding:5px;">图片地址：<input value="<?=site_url('graph/1.0/b_500_9_1.0_1.0_1_x.png')?>" size="80" id="imgaddr" style=" background-color:#111111; color:#FF0000; border:#333333 1px solid"></div>
<div id="xdiv">
<div style="float:right; border:#FFFFFF 1px solid; padding:0 3px 0 3px; cursor:pointer" onClick="$('#latticeform').toggle();$('#formlist').toggle();setXY();">X</div>
<div class="clear"></div>
<div id="formlist" style="display:none; width:15px;">点阵汉字设置</div>
	<form action="<?=$base_url.$index_page;?>/graph/basic" method="post" id="latticeform">
    	
    
        <fieldset>
        <legend>图片尺寸</legend>
        <table border="0" >
        	<tr>
            	<td>图片尺寸<input value="400" name="img_size" id="img_size" size="3"></td>
                <td>外接圆直径<input value="400" name="cr" id="cr" size="3"></td>
            </tr>
            </table>
         <table border="0" >
        	<tr>
            	<td>图片类型</td>
                <td>
                <select id="snum" name="snum">
                	<option value="3">正三角</option>
                    <option value="4">正方形</option>
                    <option value="5">五边形</option>
                    <option value="6">六边形</option>
                    <option value="7">七边形</option>
                    <option value="8">八边形</option>
                    <option value="9">九边形</option>
                    <option value="10">十边形</option>
                </select>
                </td>
            </tr>
            <tr>
            	<td>图片扩展</td>
                <td><input type="checkbox" name="img_ext" id="img_ext" value="1" checked />选中启用
                </td>
            </tr>
            </table>
            <table border="0" >
        	<tr>
            	<td>横向偏移<input value="1.0" name="cc_x" id="cc_x" size="3"></td>
                <td>纵向偏移<input value="1.0" name="cc_y" id="cc_y" size="3"><input value="随机" onClick="randgo()" type="button"></td>
            </tr>
            </table>
            <table border="0" >
            <tr>
            	<td>连线方式</td>
                <td><input type="radio" name="draw" id="draw" value="line" />线条
                <input type="radio" name="draw" id="draw" value="polygon" checked>多边形
                <input type="radio" name="draw" id="draw" value="circle" />圆形
                </td>
            </tr>
            
        </table>
        </fieldset>
        <div style="text-align:center; padding-top:10px;"><input type="submit" value="提交">&nbsp;&nbsp;<input value="重置" type="reset"></div>
    </form>
</div>
<script type="text/javascript">
$(function(){
	setXY();
	$(window).scroll(setXY); 
	$(window).resize(setXY);
	var options = { 
		type:"post",
		dataType :"html",
		url:base_url+index_page+"/graph/basic",
		success: function(html) {
			$("#imagecreate").attr('src',html);
			$("#imgaddr").val("<?=site_url('graph/1.0/b_')?>"+$('#img_size').val()+'_'+$('#snum').val()+'_'+$('#cc_x').val()+'_'+$('#cc_y').val()+'_'+$('input[name="draw"][@checked]').val()+'_x.png');
			$("#latticeform").find('input[type="submit"]').removeAttr("disabled");
			return false;
		} 
	};
	//$('#latticeform').ajaxForm(options);
	$('#latticeform').validate({
		rules:{
			img_size:{required:true,range:[100,500],digits:true},
			cr:{required:true,range:[50,500],digits:true},
			cc_x:{required:true,number:true,range:[-20,20]},
			cc_y:{required:true,number:true,range:[-20,20]}
		},
		messages:{
			img_size:{required:"必须填写",digits:"必须为整数",range:$.validator.format("不能小于{0}或大于{1}")},
			cr:{required:"必须填写",digits:"必须为整数",range:$.validator.format("不能小于{0}或大于{1}")},
			cc_x:{required:"必须填写",number:"必须为数字",rangelength:$.validator.format("不能小于{0}或大于{1}")},
			cc_y:{required:"必须填写",number:"必须为数字",rangelength:$.validator.format("不能小于{0}或大于{1}")}
		},
		event: "blur",
		errorElement: "em",
		submitHandler: function() {
			$('#latticeform').ajaxSubmit(options);
			$("#latticeform").find('input[type="submit"]').attr("disabled","disabled");
			return false;
		}
	}); 
});
function setXY(){
	if($("#xdiv").css('width')=="15px"){
		var W=($(window).width()-25)+"px";
	}else{
		var W=($(window).width()-285)+"px";
	}
	var H=(document.documentElement.scrollTop+document.documentElement.clientHeight-($(window).height()/2+200))+"px";
	$("#xdiv").css({left:W,top:H}).show();
}
function randgo(){
	var x=Math.random()*10;
	var y=Math.random()*10;
	$("#cc_x").val(x.toString().substr(0,4));
	$("#cc_y").val(y.toString().substr(0,4));
	$('#latticeform').trigger('submit');
}
</script>