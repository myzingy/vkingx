<style type="text/css">
.taobao{ float:left; border:1px #777777 solid; padding:2px; margin-right:5px; margin-bottom:5px;}
.taobao img{  width:72px; height:72px;  border:1px #888888 solid; padding:1px;}
.taobao div{ padding-top:3px; width:75px; height:28px; overflow:hidden;}
</style>
<div>
八字的强弱不能决定命运的好坏，喜神即位，即可补八字五行之不济。您的喜神是<?=$keyword?>，所以姓名第二，三个字可以取<?=$keyword?>属性字。同时我们为您推荐了以下<?=$keyword?>属性物品
</div>
<?php
for($i=0;$i<4;$i++){
	if(!$info[$i]) break;
	if($i%2==0 && $i!=0) echo "</tr><tr>";
	echo "<div class=\"taobao\"><a href=\"{$info[$i]['url']}\" target=\"_blank\" title=\"{$info[$i]['title']}({$info[$i]['price']})元\"><img src=\"".site_url()."/images/taobao/{$info[$i]['img']}\" border=\"0\" alt=\"{$info[$i]['title']}({$info[$i]['price']})元\"><div>{$info[$i]['title']}({$info[$i]['price']})元</div></a></div>";
}
?>