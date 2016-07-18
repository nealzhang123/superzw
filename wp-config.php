<?php
/**
 * WordPress基础配置文件。
 *
 * 本文件包含以下配置选项：MySQL设置、数据库表名前缀、密钥、
 * WordPress语言设定以及ABSPATH。如需更多信息，请访问
 * {@link http://codex.wordpress.org/zh-cn:%E7%BC%96%E8%BE%91_wp-config.php
 * 编辑wp-config.php}Codex页面。MySQL设置具体信息请咨询您的空间提供商。
 *
 * 这个文件被安装程序用于自动生成wp-config.php配置文件，
 * 您可以手动复制这个文件，并重命名为“wp-config.php”，然后填入相关信息。
 *
 * @package WordPress
 */
// ob_start();
// require_once( "/var/www/html/wp-content/plugins/wp-firephp/FirePHPCore/fb.php" );

 //Added by WP-Cache Manager
//define('WP_CACHE', true); //Added by WP-Cache Manager
//define( 'WPCACHEHOME', '/app/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('WP_ALLOW_MULTISITE',true);
//define('WP_HTTP_BLOCK_EXTERNAL', true);

define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', true);
define('DOMAIN_CURRENT_SITE', 'superzw.com');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);

// ** MySQL 设置 - 具体信息来自您正在使用的主机 ** //
/** WordPress数据库的名称 */
define('DB_NAME', 'superzw');

/** MySQL数据库用户名 */
define('DB_USER', 'root');

/** MySQL数据库密码 */
define('DB_PASSWORD', 'Neal98059');

/** MySQL主机 */
define('DB_HOST', 'localhost');

/** 创建数据表时默认的文字编码 */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'R5kbN(dYbL`GKm(vyD8;O:5>`rI)ND$lo@.}!=h+9l^l(WRr$LHy_t_~1~qF4v1n');
define('SECURE_AUTH_KEY',  ' kfJsc:9!W}EzQl@1v_(cB^QU%_^_Ru~l97,]VvpBVfpj;Mjj4+jMOC9VFS#:M.x');
define('LOGGED_IN_KEY',    '1G+IbWblSdSGf?F^b*LRnl|LZ+c[(i83$Zcv+61h!IHkNj;CSA@<vBO-DG5foP,[');
define('NONCE_KEY',        '~h1(]OYnU@}lPsg{|FGxM/{!sRIom_qu5XCVJyBkI92WX*BPPS#ALmb&J)w~n>pT');
define('AUTH_SALT',        'pGG71I%f(98T+P`3q1WYx6z: Yn+wwj_NPx+vYcLIO0V(pUTSGTNkUKkE2VQ(tb|');
define('SECURE_AUTH_SALT', '9<CKsUBF+o-10+@=)3YZzaQM@+-Ea!!!Q6[[h6*>W5RHJtN;GktsjoWG7wdRX+<2');
define('LOGGED_IN_SALT',   ':d[&O+;4bU#0-[uvc(D!]? }~8j<#yEtvzB8+Y[2pjA,u6:HJfcH22aK_^k[pk=$');
define('NONCE_SALT',       '{8lSKyxShGn#wY~kO>}q)3&HB5)-6F$2[1-RnG:FgTL/|[-ML6Z@VV>m}w2*>T/U');

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
