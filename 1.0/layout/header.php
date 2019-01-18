<div id="logo">
<h1><a href="<?=str_replace("1.0/","index.html",$base_url)?>">vking.wang<span style="font-size:12px; font-weight:normal; padding-left:20px;">coding...</span></a></h1>
</div>

<ul class="menu">
	<li class="menusub"><a href="<?=site_url();?>">点阵汉字</a></li>
    <li class="menusub"><a>猜谜游戏</a>
    	<ul style="display:none;" class="subul">
            <li><a href="<?=site_url('caimi/num');?>">猜 数 字</a></li>
            <li><a href="<?=site_url('caimi/xing');?>">猜 姓 氏</a></li>
            <li><a href="<?=site_url('caimi/li');?>">猜迷原理</a></li>
        </ul>
    </li>
    <li class="menusub"><a>源码参考</a>
    	<ul style="display:none;" class="subul">
            <li><a href="<?=site_url('code/lattice');?>">点阵汉字</a></li>
            <li><a href="<?=site_url('code/caishu');?>">猜数代码</a></li>
        </ul>
    </li>
    <li class="menusub"><a>知识点滴</a>
    	<ul style="display:none;" class="subul">
            <?php $dir=dir_list(FF.'doc/');if($dir):foreach($dir as $k=>$v):if($v['name']=='..' || $v['name']=='.'){continue;}?>
            <li <?php if ($k==0){echo 'class="menusubx"';}?>><a href="<? echo site_url("bit/{$v['name']}");?>"><?php echo ucfirst($v['name']);?></a></li>
            <?php endforeach;endif;?>
            <!--li><a href="<?=site_url('admin');?>">上传档案</a></li-->
        </ul>
    </li>
</ul>
<div class="clear"></div>
<script type="text/javascript">
	$(function(){
		$(".menusub,.menusubx").mouseover(function(){
			$(this).children('ul').show();
		}).mouseout(function(){
			$(this).children('ul').hide();
		});
	});
</script>
