<link type="text/css" rel="stylesheet" href="<?=site_url()?>/css/jquery.atteeeeention.css"/>
<style type="text/css">
	.item {max-width: 300px;max-height: 300px; padding: 10px; border: 1px solid #333; margin-bottom: 10px;}
</style>
<?
$txt_arr=array(
	'txt','php','css','js','xml','c','h','ini','conf'
);
$img_arr=array(
	'jpg','jpeg','bmp','gif','png'
);
$htm_arr=array(
	'html','htm'
);
$img_list=array();
?>
<ul style="position:relative;">
<? if($list):foreach($list as $v):?>
	<? if($v['name']=='..'){continue;}?>
	<? if($v['name']=='.'):?>
		<ol style="position:absolute;left:0px; top:-10px;"><?=$last?"[{$last}]&nbsp;&nbsp;":"";?><a href="<?=site_url("bit/".$per);?>">返回上层</a></ol>
	<? else:?>
        <? if(in_array($v['mini_type'],$img_arr)) {array_push($img_list,$v);continue;}?>
        <li style="line-height:2em; font-size:14px;list-style:decimal; width:900px;">
        <? if($v['type']=='file'):?>
            <? if(in_array($v['mini_type'],$txt_arr)):?>
                <font color="#009900" style="font-size:10px">[file]</font> <a href="<?=site_url("bit_view/{$now}/".urlencode($v['name']));?>" style="color:#33FF33; "><?=$v['name']?></a><span class="rtime"><?=date('Y-m-d H:i',$v['time'])?></span>
                
            <? elseif(in_array($v['mini_type'],$img_arr)):?>
            	<font color="#990099" style="font-size:10px">[img]</font> <a href="<?=site_url();?>doc/<?=$now?>/<?=urlencode($v['name'])?>" target="_blank" style="color:#990099; "><?=$v['name']?></a><span class="rtime"><?=date('Y-m-d H:i',$v['time'])?></span>
                <br class="clear" />
                <img src="<?=site_url();?>doc/<?=$now?>/<?=urlencode($v['name'])?>" height="100" style="max-width:400px;" />
            <? elseif(in_array($v['mini_type'],$htm_arr)):?>
                <font color="#990099" style="font-size:10px">[html]</font> <a href="<?=site_url();?>doc/<?=$now?>/<?=urlencode($v['name'])?>" target="_blank" style="color:#990099; "><?=$v['name']?></a> [<a href="<?=site_url("bit_view/{$now}/".urlencode($v['name']));?>">源码</a>]<span class="rtime"><?=date('Y-m-d H:i',$v['time'])?></span>
            <? else:?>
            	<font color="#990000" style="font-size:10px">[<?=$v['mini_type']?$v['mini_type']:"NoN"?>]</font> <a href="<?=site_url();?>doc/<?=$now?>/<?=urlencode($v['name'])?>" target="_blank" style="color:#990099; ">[下载] <?=$v['name']?></a><span class="rtime"><?=date('Y-m-d H:i',$v['time'])?></span>    
            <? endif;?>
            
        <? else:?>
        	<font color="#990000" style="font-size:10px">[dir]</font> <a href="<?=site_url("bit/{$now}/".urlencode($v['name']));?>" style="color:#3333FF; "><?=$v['name']?></a> 
        <? endif;?>
        </li>
    <? endif;?>	
<? endforeach;endif;?>
</ul>
<?php if(!empty($img_list)):?>
<button id="gallery_toggle" style="font-size: 14px;">查看图片资源</button>
<div id="gallery_div" style="display:none; margin-top: 10px;">
<div id="gallery" >
  <? foreach($img_list as $v):?>
  <a href="<?=site_url();?>doc/<?=$now?>/<?=urlencode($v['name'])?>" target="_blank">
    <img class="item" src="<?=site_url();?>doc/<?=$now?>/<?=urlencode($v['name'])?>" />
  </a>
  <? endforeach;?>
</div>
</div>
<?php endif;?>
<script src="<?=site_url()?>js/masonry.pkgd.min.js"></script>
<script type="text/javascript">
	$(function(){
		
		$('#gallery_toggle').click(function(){
			$('#gallery_div').toggle();
			var $container = $('#gallery');
			// initialize
			$container.masonry({
			  columnWidth: 10,
			  itemSelector: '.item'
			});
		});
	});
</script>