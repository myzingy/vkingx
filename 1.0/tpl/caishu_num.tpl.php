<script type="text/javascript" src="<?=$base_url?>js/jquery.validate.pack.js"></script>
<script type="text/javascript" src="<?=$base_url?>js/jquery.form.js"></script>
<div class="alert alert-danger" role="alert">
	<span class="label label-important">提示</span> 你心中想一个下面表格存在的数字，然后把存在你所想数字的所有表格选中，点击 我选好了。
</div>
<form action="<?=site_url('caimi/num')?>" method="post" id="caimi" name="caimi">

<?php
foreach($caishu as $arr){
	echo "<table border=1 class=\"sclass\"><tr>";
	foreach($arr as $k=>$v){
		if($k%6==0 && $k!=0){
			echo "</tr><tr>";
		}
		echo "<td>{$v}</td>";
		if($k==31){
			echo "<td colspan=4><label><input value=\"{$arr[0]}\" type=\"checkbox\" name=\"snum\" id=\"snum\" class=\"snumclass\">存在</label></td>";
		}
		
	}
	echo "</tr></table>";
}
?>
	<div style="margin: 20px 0 80px;">
	<button type="submit" class="btn btn-primary btn-lg btn-block">我选好了</button>
	<a href="javascript://" onclick="xreset()">重 置</a>
	</div>
</form>
<div id="result" style="text-align:center; padding:20px; font-size:20px; color:#FF0000; font-weight:bold;"></div>
<script type="text/javascript">
function xreset(){
	$('#caimi').resetForm();
	$('.snumclass').trigger('change');
}
$(function(){
	$(".snumclass").change(function(){
		if(this.checked){
			$(this).parents("table.sclass").css({color:'red','boder-color':'red'});
		}else{
			$(this).parents("table.sclass").css({color:'#FFFFFF','boder-color':'#FFFFFF'});
		}
	});
	var options = { 
		type:"post",
		dataType :"html",
		url:base_url+index_page+"/caimi/num",
		success: function(html) {
			$("#result").html(html);
			$(".snumclass").attr("name","snum");
			$("#caimi").find('button').removeAttr("disabled");
			return false;
		} 
	};
	//$('#latticeform').ajaxForm(options);
	$('#caimi').validate({
		rules:{
			snum:{required:true}
		},
		messages:{
			snum:{required:"请至少选择一个表格"}
		},
		event: "blur",
		errorElement: "em",
		submitHandler: function() {
			$(".snumclass").attr("name","snum[]");
			$('#caimi').ajaxSubmit(options);
			$("#caimi").find('button').attr("disabled","disabled");
			return false;
		}
	});
});
</script>