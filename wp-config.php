<?php
//__APP__POS CC__DEV,CC__TEST,CC__LINE
define('__APP__POS',$_SERVER['__APP__POS']?$_SERVER['__APP__POS']:'CC__LINE');
if(__APP__POS=='CC__LINE') {
	$dbname = "sypvosexxlvSpTkbNTcW";
	$ip = getenv('HTTP_BAE_ENV_ADDR_SQL_IP');
	$port = getenv('HTTP_BAE_ENV_ADDR_SQL_PORT');
	$host = $ip . ":" . $port;
	$user = '12bfb98106494987b7fe60381e6e5a7c';//getenv('HTTP_BAE_ENV_AK');
	$pass = '0517cdb626d64dd19608a0723ba9c4e6';//getenv('HTTP_BAE_ENV_SK');
}else{
	$dbname = "vking.me";
	$ip = 'localhost';
	$port = 3306;
	$host = $ip . ":" . $port;
	$user = 'root';//getenv('HTTP_BAE_ENV_AK');
	$pass = '';//getenv('HTTP_BAE_ENV_SK');
}
/**
 * WordPress基础配置文件。
 *
 * 这个文件被安装程序用于自动生成wp-config.php配置文件，
 * 您可以不使用网站，您需要手动复制这个文件，
 * 并重命名为“wp-config.php”，然后填入相关信息。
 *
 * 本文件包含以下配置选项：
 *
 * * MySQL设置
 * * 密钥
 * * 数据库表名前缀
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/zh-cn:%E7%BC%96%E8%BE%91_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL 设置 - 具体信息来自您正在使用的主机 ** //
/** WordPress数据库的名称 */
define('DB_NAME', $dbname);

/** MySQL数据库用户名 */
define('DB_USER', $user);

/** MySQL数据库密码 */
define('DB_PASSWORD', $pass);

/** MySQL主机 */
define('DB_HOST', $host);

/** 创建数据表时默认的文字编码 */
define('DB_CHARSET', 'utf8');

/** 数据库整理类型。如不确定请勿更改 */
define('DB_COLLATE', '');

/**#@+
 * 身份认证密钥与盐。
 *
 * 修改为任意独一无二的字串！
 * 或者直接访问{@link https://api.wordpress.org/secret-key/1.1/salt/
 * WordPress.org密钥生成服务}
 * 任何修改都会导致所有cookies失效，所有用户将必须重新登录。
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '{P{VcQzhwuU}g`Nr9du7@s>N<JlNA!6Vq?sX>z?|*fIFo)t^7F*!@6 U?QJWGoKj');
define('SECURE_AUTH_KEY',  '??Om4<5H|8qTEByCO(B1Lh*8hVFq2^;EEN.u=H&(HC*t3<Q&0&(I,)T|ZJ PG/CS');
define('LOGGED_IN_KEY',    'Ab2S>&,R`wt{!}OyjM+v9J-()ELNfnT @r4`pNI1R0xx0O8#|D&_;04Ofr_YlZ|S');
define('NONCE_KEY',        'EwclCb ST=p%c`XC^tF5a{/m6SeG~xxLSKJf?:kG-Q*%<iUl:|,W{wmH7U?3+6KD');
define('AUTH_SALT',        'n!PKWX`e98&T<;aYqre,|xo|={p50XJqfL[;.wuH,Dl]C79DZgl$YbxxKu_*eGu6');
define('SECURE_AUTH_SALT', 'qw68s/iQRW7|tZ,C$T<k~ex:_v2Mt*4$T2V[u=Z=suM7xT?!K:-8Z@U5c_s3 %B~');
define('LOGGED_IN_SALT',   '^7(_h=%j^TgwOwQ[|Me)-uNV?:PmVD0[)rx6[n2D3|9(WNE$ue]g<[FGL>-4L)6%');
define('NONCE_SALT',       '&X7v#_-<*uMBxbhh|TWt#X1Cq#NHC;-1_=}s:9b${B:9V~j0eeVwD.ya#}TiVql_');

/**#@-*/

/**
 * WordPress数据表前缀。
 *
 * 如果您有在同一数据库内安装多个WordPress的需求，请为每个WordPress设置
 * 不同的数据表前缀。前缀名只能为数字、字母加下划线。
 */
$table_prefix  = 'wp_';

/**
 * 开发者专用：WordPress调试模式。
 *
 * 将这个值改为true，WordPress将显示所有用于开发的提示。
 * 强烈建议插件开发者在开发环境中启用WP_DEBUG。
 *
 * 要获取其他能用于调试的信息，请访问Codex。
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/**
 * zh_CN本地化设置：启用ICP备案号显示
 *
 * 可在设置→常规中修改。
 * 如需禁用，请移除或注释掉本行。
 */
define('WP_ZH_CN_ICP_NUM', true);

/* 好了！请不要再继续编辑。请保存本文件。使用愉快！ */

/** WordPress目录的绝对路径。 */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** 设置WordPress变量和包含文件。 */
require_once(ABSPATH . 'wp-settings.php');
