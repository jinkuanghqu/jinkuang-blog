<?php
return array(
    //'配置项'=>'配置值'
    'TAGLIB_PRE_LOAD'        => "Home\\TagLib\\Web",

    'COOKIE_SAVE_SESSION_ID' => true,

    // 'SESSION_TYPE'           => 'MYSQLI',
    'SESSION_PREFIX'         => 'home_',
    'COOKIE_PREFIX'          => 'C_HOME_',
    'PAGE_SIZE'              => '5',

    'HTML_CACHE_ON'          => false,
    'HTML_CACHE_TIME'        => 3660, // 全局静态缓存有效期（秒）
    'HTML_FILE_SUFFIX'       => '.shtml', // 设置静态缓存文件后缀
    'HTML_CACHE_RULES'       => array(
        'Category:' => array('{$_SERVER.REQUEST_URI|md5}'),
    ),

    // 多语言支持
    'LANG_SWITCH_ON'         => true,
    'LANG_AUTO_DETECT'       => true,
    'DEFAULT_LANG'           => 'en-us',
    'LANG_LIST'              => 'en-us',
    'VAR_LANGUAGE'           => 'l',
	'ENABLE_STOCK_CHECK'     => 0,
);
