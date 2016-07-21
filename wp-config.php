<?php
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
define('WP_ALLOW_MULTISITE',true);
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', true);
define('DOMAIN_CURRENT_SITE', 'superzw.com');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);
//define('WP_ALLOW_REPATR',true);
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
define('AUTH_KEY',         'd.;>X3<I$1m`?r0h(w|>e%08NqrQS<+9p@~kzsn~<MKjeu*M[RZ^c7,F-MgpNn*U');
define('SECURE_AUTH_KEY',  'Ij@X*qs$b[2Lqs)S|7[(!qu)x8%duT|y<6Yl_D6pT59!4z$NQP*]_[&@aH7};E,%');
define('LOGGED_IN_KEY',    'g8Ki6_>hLOlq <Yqy0f1]<Nc%@S4hzMYN19CDPl+ [~gknVQ;+(_vjORcbfW)?wM');
define('NONCE_KEY',        ',:kROe;jnh/f Mg$=}57y3=nH`K0d :g~Za^==`3SvlOY+|~5__-+lL^0mQsJ%yx');
define('AUTH_SALT',        '4-P&.ZBg?yPjOy*njM(%%Z5Ui_J<* :l3pmf//aMS2<ep@5~P3xErV{m!X?nvnu4');
define('SECURE_AUTH_SALT', '{x6ftloj_gu18^@j$Ej$2`kV%,EkV,_5_%)LVHL+f*Kfk3|0NEbBB#ayaW,j*u:/');
define('LOGGED_IN_SALT',   'uw{-V&?&g}(P|;e->-{B0LY6#./DjvXq-r;W7$c1]5Q,Oa.|{%h?<GK/r:9&8{KS');
define('NONCE_SALT',       '}oTl8cQkYb#U]4At+K=&lh*ua|L Qkr+31IS@02~w+{5j(sV(q~F0|aJ85$<)$sp');

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
