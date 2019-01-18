以下内容选自《 MySql 性能调优与架构设计》

	第六章 影响 MySql Server 性能的相关因素 --查询

需求：取出某个 group （假设 id 为 100） 下的用户编号（id），用户昵称（ nick_name） 用户性别（sexuality） 用户签名（sign）和用户生日（birthday），并按照加入组的时间（user_group.gmt_creat）进行倒叙排列，取出前20个。
解决方案一：
	
	SELECT id,nick_name
	FROM user,user_group
	WHERE user_group.group_id = 1 AND user_group.user_id = user.id
	LIMIT 100,20;
	
解决方案二：

	SELECT user.id,user.nick_name
	FROM (
		SELECT user_id
		FROM user_group
		WHERE user_group.group_id = 1
		ORDER BY gmt_create DESC
		LIMIT 100,20 ) t,user
	WHERE t.user_id = user.id
	
查看执行计划 ：
	
	EXPLAIN SQL 语句 1
	EXPLAIN SQL 语句 2
	
查看 SQL执行的 profiling 信息：

	打开 profiling功能
		
		SET PROFILING = 1
		
	执行 SQL 语句 1
	执行 SQL 语句 2
	
	查看 profiling 基本信息：
		
		SHOW PROFILES
		
	查看 SQL 语句的详细信息：
		
		SHOW PROFILE CPU,BLOCK IO FOR QUERY 1;
		SHOW PROFILE CPU,BLOCK IO FOR QUERY 2;
		
	IO 消耗在 Sorting result 处体现
	
2010.12.7 by vking
