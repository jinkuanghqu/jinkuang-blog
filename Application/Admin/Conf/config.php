<?php
return array(
    'DEFAULT_CONTROLLER'   => 'Index', // 默认控制器名称
    'DEFAULT_ACTION'       => 'index', // 默认操作名称
    'LAYOUT_NAME'          => 'layout',
    'LAYOUT_ON'            => false,
    'PAGE_SIZE'            => 20,
    // 'DEFAULT_THEME' => "AdminTpl", //默认主题

    // 'USER_AUTH_MODEL' => 'admin' //验证模型
    'USER_AUTH_ON'         => true,
    'USER_AUTH_TYPE'       => 2,
    'USER_AUTH_KEY'        => 'admin.id',
    'NOT_AUTH_MODULE'      => 'default',
    'USER_AUTH_GATEWAY'    => '/Admin/default/login',
    'USER_AUTH_NO_ACCESS'  => '/Admin/default/noAccess',
    'AUTH_ROLE_MODEL'      => 'Role',
    'AUTH_ADMIN_MODEL'     => 'Admin',
    'AUTH_AUTH_RULE_MODEL' => 'AuthRule',

    'GOODS_PER_PAGE'       => 10,
    'PRODUCT_PER_PAGE'     => 10,

    // 多语言支持
    'LANG_SWITCH_ON'       => true,
    'LANG_AUTO_DETECT'     => true,
    'DEFAULT_LANG'         => 'zh-cn',
    'LANG_LIST'            => 'zh-cn',
    'VAR_LANGUAGE'         => 'l',
);
