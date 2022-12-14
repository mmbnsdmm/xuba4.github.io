<?php
$basePath = dirname(__DIR__);
$config = [
    'id' => YII_APP_ID,
    'basePath' => $basePath,
    'name' => "admin",
    'controllerNamespace' => 'admin\controllers',
    'bootstrap' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-admin',
        ],
        /*'assetManager' => [
            'forceCopy' => YII_ENV == 'dev' ? true : false,
            'linkAssets' => YII_ENV == 'dev' ? true : false,
        ],*/
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'user' => [
            'class' => \common\components\User::class,
            'identityClass' => \common\models\db\User::class,
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-admin', 'httpOnly' => true],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'itemTable' => "{{%admin_auth_item}}",
            'itemChildTable' => "{{%admin_auth_item_child}}",
            'assignmentTable' => "{{%admin_auth_assignment}}",
            'ruleTable' => "{{%admin_auth_rule}}",
        ],
    ],
    'modules' => [
        \kartik\grid\Module::MODULE =>  [
            'class' => \kartik\grid\Module::class,
        ],
        'rbac' => [
            'class' => \mdm\admin\Module::class,
//            'mainLayout' => "@admin/views/layouts/main.php",
//            'layout' => 'left-menu',
            'menus' => [
                /*'assignment' => [
                    'label' => '权限分配', // change label
                ],*/
                'user' => false,
                'assignment' => false,
            ],
            'controllerMap' => [
                'assignment' => [
                    'class' => mdm\admin\controllers\AssignmentController::class,
                    'usernameField' => 'username',
                    'extraColumns' => [
                        'id',
                        [
                            'attribute' => 'email',
                            'label' => '邮箱',
                            'value' => function($model, $key, $index, $column) {
                                return $model->email;
                            },
                        ],
                    ],
                    'searchClass' => \mdm\admin\models\searchs\User::class,
                ],
            ],
        ],
        'system' => \admin\modules\system\SystemModule::class,
        'log' => \admin\modules\log\LogModule::class,
        'user' => \admin\modules\user\UserModule::class,
        'ucenter' => \admin\modules\ucenter\UcenterModule::class,
        'app' => \admin\modules\app\AppModule::class,
        'article' => \admin\modules\article\ArticleModule::class,
    ],
    'as access' => [
        'class' => \mdm\admin\components\AccessControl::class,
        'allowActions' => [
            'debug/*','gii/*', 'site/*', 'admin/*'
        ],
    ],
    'params' => [
        'mdm.admin.configs' => [
            'defaultUserStatus' => \common\models\db\User::STATUS_ACTIVE, // 0 = inactive, 10 = active
            'menuTable' => "{{%admin_menu}}",
        ]
    ],
];
$_urlManagers = require(Yii::getAlias('@common/config/_urlManagers.php'));
$config['components']['urlManager'] = $_urlManagers['urlManagerAdmin'];
return $config;