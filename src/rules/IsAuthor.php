<?php
/**
 * 文章编辑权限
 * User: www
 * Date: 18-7-6
 * Time: 上午12:31
 */
namespace klphp\rbac\rules;

use yii\rbac\Rule;
use Yii;

class IsAuthor extends Rule{

    const NAME='isAuthor';

    public $name='isAuthor';

    public $description='数据所属权检测';

    /**
     * @param string|integer $userId 用户 ID.
     * @param Item $item 该规则相关的角色或者权限
     * @param array $params 传给 ManagerInterface::checkAccess() 的参数
     * @return boolean 代表该规则相关的角色或者权限是否被允许
     */
    public function execute($userId, $item, $params)
    {
//        $user=Yii::$app->user;
//        if($user->identityClass::isAdministrator($user->identity)){
//            return true;
//        }

        return isset($params['model']) ? $params['model']->$params['userField'] == $userId : false;
    }

}