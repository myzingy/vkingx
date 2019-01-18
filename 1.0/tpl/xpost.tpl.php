<div style=" margin:0 auto; width:80%; padding-bottom:10px;">
<?php if($result):?>
<fieldset>
	<legend>目标服务器对本次 POST/GET 请求结果</legend>
	<p id="result"><?php echo $result;?></p>
</fieldset>
<?php endif;?>
<form enctype="multipart/form-data" action="" method="post">
<fieldset>
	<legend>POST URI</legend>
	<input style="width: 100%;" name="uri" value="<?=$p_uri?$p_uri:"http://my.xxx.com/updateEduInfo.action";?>"/>
</fieldset>
<fieldset>
	<legend>Herder(使用google浏览器，F12后复制header)</legend>
	<textarea style="width: 100%; height: 300px;" name="header">
<?php if($p_header):?>
<?php echo $p_header;?>
<?php else: ?>
Accept:*/*
Accept-Encoding:gzip,deflate,sdch
Accept-Language:zh-CN,zh;q=0.8
Connection:keep-alive
Content-Length:222
Content-Type:application/x-www-form-urlencoded; charset=UTF-8
Cookie:guide=1; USERTRACEID=fd63ad5a22da4d6087b118e486cdce3f; one_week_off=one_week_off; Hm_lvt_91dc55201d5fa054a868aa1e0f20332a=1377080847,1377085052,1377085348,1377086219; Hm_lpvt_91dc55201d5fa054a868aa1e0f20332a=1377332640; JSESSIONID=6C6F63622C7B88BE2FBCB480D8EEF4EC; randCode=lqC2j5wJYWc.; CNZZDATA5583512=cnzz_eid%3D504814679-1377004877-http%253A%252F%252Fmy.weke.com%26ntime%3D1377510608%26cnzz_a%3D40%26retime%3D1377510496787%26sin%3Dhttp%253A%252F%252Fzuopin.weke.com%252F14564.html%26ltime%3D1377510496787%26rtime%3D3; one_week_on=one_week_on; uid=UST6eNM0IUHPg4CNQpjqhSMelBv7paOmhTUIGh4oqIPpUk3bxM_l1l_ht1U.
Host:my.weke.com
Origin:http://my.xxx.com
Referer:http://my.xxx.com/showEduInfo.action
User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.95 Safari/537.36
X-Requested-With:XMLHttpRequest<?php endif;?></textarea>
</fieldset>
<fieldset>
	<legend>POST 数据(使用google浏览器，F12后复制data)</legend>
	<textarea style="width: 100%; height: 150px;" name="data">
<?php if($p_data):?>
<?php echo $p_data;?>
<?php else: ?>
educationInfoQuery.education:大学
educationInfoQuery.accessRightId:1
educationInfoQuery.school:556
educationInfoQuery.schoolYear:1992
educationInfoQuery.department:555
educationInfoQuery.educationInfoId:10272<?php endif;?></textarea>
</fieldset>
<input type="hidden" id="method" name="method" value="POST" />
<input type="button" value="POST 提交"/> &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="GET 提交"/>
</form>
</div>
<script type="text/javascript">
$(function(){
	$('input[type="button"]').click(function(){
		if(this.value.indexOf('POST')>-1){
			$('#method').val('POST');
		}else{
			$('#method').val('GET');
		}
		$('#result').html('...');
		$('form').trigger('submit');
	});
});
</script>