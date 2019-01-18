mysql group

实现 group by 有三种方式

1，使用松散（Loose）索引扫描（最好）
	
	实现松散索引扫描至少满足以下几个条件：
	1）group by 条件字段必须处于同一索引中最前面的连续位置；
	2）使用 group by 时，只能使用 MAX 和MIN 这两个聚合函数；
	3）如果引用到了该索引中 group by 条件以外的字段条件，它就必须已常量形式存在。
	
	另执行query的执行计划 explan SQL 可以在 Extra 中看到 Using index for group-by
 
2，使用紧凑（Tight）索引扫描（次之）
	
	执行query 的执行计划 explan SQL 可以在 Extra 中没看到 Using index for group-by，并不是说 mysql 的 group by 操作不是通过索引完成的，只不过是需要访问 where 条件所限点的所有索引键信息之后才能得出结果。
	
3，使用临时表扫描（最坏）
	
	当无法利用索引来实现 group by 时就不得不通过临时表来完成 group by 。
	
具体应用：

	1）尽可能让 mysql 利用索引来完成 group by 操作，当然最好是松散索引扫描的方式。在系统允许的情况下，可以通过调整索引或调整 query 的这两种方式来达到目的；
	2）当无法使用索引完成 group by 时，由于要使用临时表且需要 filesort ，所以必须要有足够的的 sort_buffer_size 供 mysql 排序使用，如果不够时会将表数据复制到磁盘进行操作，这时的分组操作性能将成数量级下降。尽量避免大结果集进行 group by。
	
	优化 group by 时如果无法利用索引且不想进行 filesort 操作，可以在整个 sql 后添加一个 null 排序（ORDER BY null）的子句。
	
distinct 的实现与优化与 group by 类似。