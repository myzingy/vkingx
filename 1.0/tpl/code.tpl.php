<script type="text/javascript" src="<?=site_url()?>/js/SyntaxHighlighter/shCore.js"></script>
<script type="text/javascript" src="<?=site_url()?>/js/SyntaxHighlighter/shBrushPhp.js"></script>
<link type="text/css" rel="stylesheet" href="<?=site_url()?>/css/SyntaxHighlighter/shCore.css"/>
<link type="text/css" rel="stylesheet" href="<?=site_url()?>/css/SyntaxHighlighter/shThemeDefault.css"/>
<script type="text/javascript">
    SyntaxHighlighter.config.clipboardSwf = '<?=site_url()?>/js/SyntaxHighlighter/clipboard.swf';
    SyntaxHighlighter.all();
</script>
<? if($bit_title):?>
<h2><?=$bit_title;?>&nbsp;&nbsp;<a href="<?=site_url("bit/".$per);?>">返回列表</a></h2>
<? endif;?>
<? if($info):?>
<pre class="brush:php;">
<?=$info;?>
</pre>
<? endif;?>
<pre class="brush:php;">
<?=$code;?>
</pre>
<div style="text-align:right; font-size:9px; cursor:pointer;" onclick="tipsWindown('Add New Content','',400,300)">Add New Content</div>
