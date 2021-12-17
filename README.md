yii2-rbac
=========

## 依赖

> 后台模板 yii2-adminlte
> 组件 kartik-v/yii2-widget-activeform

安装
------------

```
composer require --prefer-dist klphp/yii2-rbac "*"
```

## 配置

main.php 

```php
//组件
'authManager' => [
            'class' => 'yii\rbac\DbManager',                //使用数据库RBAC
            'itemTable' => 'auth_item',                     //权限列表
            'assignmentTable' => 'auth_assignment',         //权限分配表
            'itemChildTable' => 'auth_item_child',          //权限父子关联表
            'defaultRoles' => ['member'],                   //默认角色
        ],

//模块
'modules'=>[
        'rbac'=>[
            'class'=>klphp\rbac\Module::class,
            'rule'=>[
                'path'=>'@common/rules',				//规则路径
                'namespace'=>'\\common\\rules',			//规则命名空间
            ],
            'userSearchModel'=>frontend\models\search\MemberSearch::class,	//用户搜索model
        ],
    ],


    'as access' => [
        'class' => klphp\rbac\components\AuthFilter::class,
        //放行路由
        'allowActions' => [
            'site/login',
            'site/captcha'
        ]
    ],
```

#### 数据表迁移

klphp\rbac\migrations

'klphp\rbac\userSearch'=>\frontend\models\search\MemberSearch::class,

