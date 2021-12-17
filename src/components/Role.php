<?php
/**
 * 绑定用户组
 * User: kl
 * DateTime: 2021/12/10 8:55 上午
 */
namespace klphp\rbac\components;

use Yii;
use yii\base\Component;

class Role extends Component{

    const ROlE_ADMINISTRATOR='administrator';
    const ROlE_SERVICE='service';

    const ROLE_ITEMS=[
        self::ROlE_ADMINISTRATOR=>'管理员',
        self::ROlE_SERVICE=>'客服',
    ];

    /**
     * 用户提权
     * User: kl
     * DateTime: 2021/12/10 9:32 上午
     * @param int $userId
     * @param $roleName
     * @throws \Exception
     */
    public static function assign(int $userId,$roleName){
        $auth=Yii::$app->getAuthManager();
        $role = $auth->createRole($roleName);
        //清空用户所有用户组
        $auth->revokeAll($userId);
        //分配用户组
        $auth->assign($role, $userId);
    }

    /**
     * 注册时分配到默认用户组
     * User: kl
     * DateTime: 2021/12/10 9:15 上午
     */
    public static function default($event){
        $user=$event->sender;
        $roleName=$event->data['role'];
        $auth=Yii::$app->getAuthManager();
        $role = $auth->createRole($roleName);
        //清空用户所有用户组
        $auth->revokeAll($user->id);
        //分配用户组
        $auth->assign($role, $user->id);
    }
}