<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=$title;?> vking.wang</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?=$keyword?>" />
<meta name="description" content="<?=$description?$description:$keyword?>" />
<meta name="format-detection" content="telephone=no" />
<meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0, user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="white" />
<link rel="stylesheet" type="text/css" href="<?=str_replace("1.0/","",$base_url)?>assets/css/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="<?=str_replace("1.0/","",$base_url)?>assets/css/bootstrap-theme.min.css"/>
<link rel="stylesheet" type="text/css" href="<?=str_replace("1.0/","",$base_url)?>assets/css/main.min.css"/>
<script type="text/javascript">
var base_url="<?=$base_url?>";
var index_page="<?=$index_page?>";
</script>
    <script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
    <script>jQuery.browser={};(function(){jQuery.browser.msie=false; jQuery.browser.version=0;if(navigator.userAgent.match(/MSIE ([0-9]+)./)){ jQuery.browser.msie=true;jQuery.browser.version=RegExp.$1;}})();</script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body style="background-color: #000;">
<div id="main">
<?php in(FF."layout/header.php");?>
</div>
<div id="main">
<? in(FF."layout/right.php");?>
</div>
<div class="line2"></div>
<div id="main">
<? in(FF."layout/footer.php");?>
</div>
</body>
</html>
