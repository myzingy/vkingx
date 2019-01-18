<?php
foreach($caishu as $arr){
	echo "<table border=1 class=\"sclass\" style='width:45%; float: left; margin-right: 5%;'><tr>";
	foreach($arr as $k=>$v){
		if($k%4==0 && $k!=0){
			echo "</tr><tr>";
		}
		echo "<td>{$v}</td>";
		
	}
	echo "</tr></table>";
}
?>
<p style="clear: both;">猜数字是一个数学概率问题，我首次看到是在某报纸上看到。</p>
<p>上面五个表的第一个数据1，2，4，8，16，看以看出前面数字的和总是比后面数字小1，如：1+2+4=7，比8小1。</p>
<p>1，2，4，8，16，之间任意组合相加正好为1~31，表中数据正好为1~31。我们猜得数据也是1~31</p>
<p>如：我们心中想的数字是15，存在与1，2，3，4四张表中，所以1+2+4+8就是你心中想的数字。</p>
<p>扩展：猜姓氏也是用的此索引数组，只是它的展示不一样，内部原理都是一样的。</p>
