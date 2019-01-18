<link rel="stylesheet" type="text/css" href="<?=$base_url?>css/tab.css"/>
<script type="text/javascript" src="<?=$base_url?>js/jquery.validate.pack.js"></script>
<script type="text/javascript" src="<?=$base_url?>js/jquery.form.js"></script>
<div class="alert alert-danger alert-dismissible" role="alert">
	<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<span class="label label-important">提示</span> 查看所有选项卡，有你的姓氏请选中，然后点击 我选好了。
</div>
<a name="result"></a>
<div id="result" style="text-align:center;font-size:18px;line-height: 50%;"></div>
<form action="<?=site_url('caimi/num')?>" method="post" id="caimi" name="caimi">
<div id="tabbed_box_1" class="tabbed_box">
    <div class="tabbed_area">
        <ul class="tabs">
            <? foreach($caishu as $i=>$arr):?>
            <li><a href="javascript://" title="content_<?=$i+1?>" class="tab <?=($i==0)?"active":""?>">第<?=$i+1;?>卷</a></li>
            <? endforeach;?>
            <li ><a style="cursor:pointer;" onClick="showall(this)">显示全部</a></li>
        </ul>
        <? foreach($caishu as $i=>$arr):?>
        <div id="content_<?=$i+1?>" class="content">
        	<ul>
            	<? $tmp=999;?>
            	<? foreach($arr as $k=>$v):?>
                <li><?=$bjx[$v]?$bjx[$v]:$v;?></li>
                <? $tmp=$tmp>$v?$v:$tmp;?>
                <? endforeach;?>
                <li style="width:200px; font-size:12px; font-weight:normal; color:#FF0000;"><label><input value="<?=$tmp;?>" type="checkbox" name="snum" id="snum" class="snumclass">存在你的姓氏请选中</label></li>
			</ul>
        </div>
        <? endforeach;?>
	</div>
    <div style="text-align:center; padding-top:10px;"><input class="btn btn-success" type="submit" value="我选好了" name="submit">&nbsp;&nbsp;<input class="btn btn-default" type="button" value="重 置" onClick="xreset()"></div>
</div>
</form>
<script type="text/javascript">
var tab_i=1;
function xreset(){
	$('#caimi').resetForm();
	$('.snumclass').trigger('change');
	$('#result').html('');
}
function showall(a){
	if($(a).html()=='显示全部'){
		$(a).html('隐藏');
		$('div.content').show();
	}else{
		$(a).html('显示全部');
		$('div.content').hide();
		$($('div.content')[tab_i-1]).show();
	}
}
$(function(){
	$("a.tab").click(function () {	
		// switch all tabs off
		$(".active").removeClass("active");
		
		// switch this tab on
		$(this).addClass("active");
		
		// slide all content up
		$(".content").slideUp();
		
		// slide this content up
		var content_show = $(this).attr("title");
		tab_i=content_show.match(/[0-9]+/);
		$("#"+content_show).slideDown();
	  
	});
	$(".snumclass").change(function(){
		var id=$(this).parents("div.content").attr('id');
		var tab=$('a[title="'+id+'"]');
		if(this.checked){
			$(tab).css({'background':'url(<?=$base_url?>images/bgdate.gif)'});
		}else{
			$(tab).css({'background':'url()'});
		}
	});
	var options = { 
		type:"post",
		dataType :"html",
		url:base_url+index_page+"/caimi/xing",
		success: function(html) {
			$("#result").html(html);
			$(".snumclass").attr("name","snum");
			$("#caimi").find('input[type="submit"]').removeAttr("disabled");
			$('#caimi').resetForm();
			$('.snumclass').trigger('change');
			location.href=base_url+index_page+"/caimi/xing#result";
			return false;
		} 
	};
	//$('#latticeform').ajaxForm(options);
	$('#caimi').validate({
		rules:{
			snum:{required:true}
		},
		messages:{
			snum:{required:"请至少选择一个"}
		},
		event: "blur",
		errorElement: "em",
		submitHandler: function() {
			$(".snumclass").attr("name","snum[]");
			$('#caimi').ajaxSubmit(options);
			$("#caimi").find('input[type="submit"]').attr("disabled","disabled");
			return false;
		}
	});
	
});
</script>