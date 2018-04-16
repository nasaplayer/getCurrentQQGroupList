CREATE DATABASE myQQ DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci; 

USE myQQ;

CREATE TABLE qqMember(
	id int(11) NOT NULL AUTO_INCREMENT,
	card VARCHAR(100) COMMENT '群名片',
	flag INT,
	g INT COMMENT '性别|0-男 1-女 255-未知',
	join_time INT(11),
	last_speak_time INT(11),
	lv JSON ,
	level INT,
	point INT,
	nick VARCHAR(200) COMMENT '昵称',
	qage INT COMMENT 'Q龄',
	role INT COMMENT '角色[0-群主 1-管理员 -群友]',
	tags CHAR(10),
	uin BIGINT COMMENT 'QQ号',,
	gc BIGINT COMMENT '群号',
	
	join_date DATETIME,
	last_speak_date DATETIME,
	
	add_time DATETIME,
	update_time DATETIME,
	PRIMARY KEY(id)
)DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE qqGroup(
	gc BIGINT COMMENT '群号',
	gn VARCHAR(100) COMMENT '群名',
	owner BIGINT COMMENT '群主QQ号',
	
	count INT(11),
	max_count INT(11) COMMENT '群人数上限',
	search_count INT ,
	svr_time INT(11) COMMENT '查询时间',
	
	vecsize INT COMMENT '页数',
	create_time DATETIME,
	add_time DATETIME,
	update_time DATETIME,
	PRIMARY KEY(gc)
)DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;


INSERT INTO qqMember(lv) VALUE('{"level":1,"point":0}');

SELECT SUM(search_count) FROM qqGroup;
+-------------------+
| SUM(search_count) |
+-------------------+
| 60165             |
+-------------------+

/* 查看 */
 SELECT COUNT(*) FROM qqMember;
+----------+
| COUNT(*) |
+----------+
|    70726 |
+----------+

9927403

SELECT gc,
COUNT(*),
COUNT(gc) AS 'gc_total'
FROM qqMember 
GROUP BY gc
ORDER BY gc_total DESC
LIMIT 0,10;
+-----------+----------+----------+
| gc        | COUNT(*) | gc_total |
+-----------+----------+----------+
|   9927403 |     7841 |     7841 |
|  35291327 |     7802 |     7802 |

+-----------+----------+----------+
| gc        | COUNT(*) | gc_total |
+-----------+----------+----------+
|   9927403 |     7840 |     7840 |
|  35291327 |     7802 |     7802 |

/* 35291327 */
12 -1
3895 -2

/* 9927403- php六星 */
3920-2
SELECT COUNT(*)
FROM qqMember 
WHERE gc = 9927403
GROUP BY uin
HAVING COUNT(*)=2;




/* Incorrect string value: '\xF0\x9F...' for column 'XXX' at row 1 */
https://blog.csdn.net/fhzaitian/article/details/53168551




