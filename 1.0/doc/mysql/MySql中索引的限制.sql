MySql
	中索引的限制
 
1） MyISAM 存储引擎索引键长度的总和不能超过 1000 字节；

2） BLOB 和 TEXT 类型的列只能创建前缀索引；

3） MySql 目前不支持函数索引；

4） 使用不等于（!= 或者 <>） 的时候，MySql无法使用索引；

5） 过滤字段使用了函数运算（如 abs(column)）后，MySql 无法使用索引；

6） Join 语句中 Join 条件类型不一致的时候，MySql 无法使用索引；

7） 使用 LIKE 操作的时候如果条件以通配符开始（如‘%abc’）时，MySql 无法使用索引；

8） 使用非等值查询的时候，MySql 无法使用 Hash 索引。

Sql 语句用存在多个索引时需要手动指定索引：
	SELECT * FROM TABLE force index(index_name) WHERE ...
   