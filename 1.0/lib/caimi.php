<?php if(!defined("FF"))die('FF');
in(FF."include/caishu.class.php");
$caishu=new caishu();
if($FF[0]=="li"){
	$data['title']="猜数字原理";
	$data['keyword']="猜数字原理";
	$data['caishu']=$caishu->show_index();
	$data['view']='caishu';
	
}elseif($FF[0]=="xing"){
	if($_POST){
		$num="出错了";
		if(!!$_POST['snum']){
			$num=array_sum($_POST['snum']);
		}
		$bjx=get_bjx();
		in(FF."include/lattice.class.php");
		$ps=new lattice();
		$ps->set(array(
			'valstr'=>$bjx[$num],
			'font_color_1'=>'☆',
			'font_color_2'=>'★',
		));
		die($ps->change());
	}
	$data['title']="猜姓氏";
	$data['keyword']="猜姓氏";
	$data['caishu']=$caishu->rand_index(8);
	$data['bjx']=get_bjx();
	$data['view']='caishu_xing';
}else{
	if($_POST){
		$num="出错了";
		if(!!$_POST['snum']){
			$num=array_sum($_POST['snum']);
		}
		die("你心中想的数字是：".$num);
	}
	$data['title']="猜数字";
	$data['keyword']="猜数字";
	$data['caishu']=$caishu->show_index(6);
	$data['view']='caishu_num';
}
load_layout();
@$C->display(720,3);
function get_bjx(){
	$bjx_str="赵钱孙李周吴郑王冯陈褚卫蒋沈韩杨朱秦尤许何吕施张 孔曹严华金魏陶姜戚谢邹喻柏水窦章云苏潘葛奚范彭郎 鲁韦昌马苗凤花方俞任袁柳酆鲍史唐费廉岑薛雷贺倪汤 滕殷罗毕郝邬安常乐于时傅皮卞齐康伍余元卜顾孟平黄 和穆萧尹姚邵湛汪祁毛禹狄米贝明臧计伏成戴谈宋茅庞 熊纪舒屈项祝董梁杜阮蓝闵席季麻强贾路娄危江童颜郭 梅盛林刁钟徐邱骆高夏蔡田樊胡凌霍虞万支柯昝管卢莫 经房裘缪干解应宗丁宣贲邓郁单杭洪包诸左石崔吉钮龚 程嵇邢滑裴陆荣翁荀羊於惠甄麴家封芮羿储靳汲邴糜松 井段富巫乌焦巴弓牧隗山谷车侯宓蓬全郗班仰秋仲伊宫 宁仇栾暴甘钭厉戎祖武符刘景詹束龙叶幸司韶郜黎蓟薄 印宿白怀蒲邰从鄂索咸籍赖卓蔺屠蒙池乔阴郁胥能苍双 闻莘党翟谭贡劳逄姬申扶堵冉宰郦雍郗璩桑桂濮牛寿通 边扈燕冀郏浦尚农温别庄晏柴瞿阎充慕连茹习宦艾鱼容 向古易慎戈廖庾终暨居衡步都耿满弘匡国文寇广禄阙东 欧殳沃利蔚越夔隆师巩库聂晁勾敖融冷訾辛阚那简饶空 曾母沙乜养鞠须丰巢关蒯相查後荆红游竺权逯盖益桓公";
	$bjx_str=str_replace(" ",'',$bjx_str);
	$bjx_arr=str_split($bjx_str,3);//生成数组
	array_unshift($bjx_arr,"");//在下标为0处插入""
	return $bjx_arr;
}
?>