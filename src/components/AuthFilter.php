<?php
namespace klphp\rbac\components;

use yii\web\ForbiddenHttpException;
use yii\base\Module;
use Yii;
use yii\web\User;
use yii\di\Instance;
/**
 * Access Control Filter (ACF) is a simple authorization method that is best used by applications that only need some simple access control.
 * As its name indicates, ACF is an action filter that can be attached to a controller or a module as a behavior.
 * ACF will check a set of access rules to make sure the current user can access the requested action.
 *
 * To use AccessControl, declare it in the application config as behavior.
 * For example.
 *
 * ```
 * 'as access' => [
 *     'class' => 'klphp\rbac\components\AuthFilter',
 *     'allowActions' => ['site/login', 'site/error']
 * ]
 * ```
 *
 * @property User $user
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class AuthFilter extends \yii\base\ActionFilter
{
    /**
     * @var User User for check access.
     */
    private $_user = 'user';
    /**
     * @var array List of action that not need to check access.
     */
    public $allowActions = [];


    /**
     * Get user
     * @return User
     */
    public function getUser()
    {
        if (!$this->_user instanceof User) {
            $this->_user = Instance::ensure($this->_user, User::className());
        }
        return $this->_user;
    }
    /**
     * Set user
     * @param User|string $user
     */
    public function setUser($user)
    {
        $this->_user = $user;
    }
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        //控制器组件
        $obj = $action->controller;

        //1\星号路径之放行列表检测
        do {
            $route=$obj->getUniqueId().'/*';
            //如果当前路由存在于放行列表中，或用户拥有访问权限则允许访问
            if (in_array($route,$this->allowActions)) {
                return true;
            }
            $obj = $obj->module;
        } while ($obj !== null);

        //2\完整路径之放行列表检测
        if (in_array($action->getUniqueId(),$this->allowActions)) {
            return true;
        }

        //用户组件
        $user = $this->getUser();

        //3\登陆用户进行检测排查，否则一律过滤
        if(!$user->isGuest){

            //3.1超级管理员直接放行
            if ($user->can('*')) {
                return true;
            }

            //3.2\完整的URL路径判断
            $auth=Yii::$app->getAuthManager();
            $permission=$auth->getPermission($action->getUniqueId());

            if($permission->ruleName){
                //如果有附加规则优先触发禁止访问权限
                if ($user->can($action->getUniqueId())) {
                    return true;
                }else{
                    $this->denyAccess($user);
                }
            }else{
                if ($user->can($action->getUniqueId())) {
                    return true;
                }
            }

            //3.3\*号通配权限检测
            //控制器组件
            $obj = $action->controller;
            do {
                $route=$obj->getUniqueId().'/*';
                //如果当前路由存在于放行列表中，或用户拥有访问权限则允许访问
                if ($user->can($route)) {
                    return true;
                }
                $obj = $obj->module;
            } while ($obj !== null);

            //3.4 方法名权限检测
            $actionName = $action->id;
            if ($user->can($actionName)) {
                return true;
            }
        }

        //上面未检测通过的，直接拒绝访问
        $this->denyAccess($user);

    }

    /**
     * 如果未登陆则禁止访问
     * @param $user
     */
    protected function denyAccess($user)
    {
        if ($user->getIsGuest()) {
            //将用户浏览器重定向到登录页
            $user->loginRequired();
        } else {
            throw new ForbiddenHttpException('您没有权限执行此操作.');
        }
    }

    /**
     * 初始方法
     * @inheritdoc
     */
    protected function isActive($action)
    {

        //当前页路由
        $uniqueId = $action->getUniqueId();

        //错误页
        if ($uniqueId === Yii::$app->getErrorHandler()->errorAction) {
            return false;
        }

        //当前用户
        $user = $this->getUser();

        //游客
        if($user->getIsGuest())
        {
            $loginUrl = null;
            if(is_array($user->loginUrl) && isset($user->loginUrl[0])){
                $loginUrl = $user->loginUrl[0];
            }else if(is_string($user->loginUrl)){
                $loginUrl = $user->loginUrl;
            }
            if(!is_null($loginUrl) && trim($loginUrl,'/') === $uniqueId)
            {
                return false;
            }
        }

        //模块
        if ($this->owner instanceof Module) {
            // convert action uniqueId into an ID relative to the module
            $mid = $this->owner->getUniqueId();
            $id = $uniqueId;
            if ($mid !== '' && strpos($id, $mid . '/') === 0) {
                $id = substr($id, strlen($mid) + 1);
            }
        } else {
            $id = $action->id;
        }

        //判断路由放行列表
        foreach ($this->allowActions as $route) {
            if (substr($route, -1) === '*') {
                $route = rtrim($route, "*");
                if ($route === '' || strpos($id, $route) === 0) {
                    return false;
                }
            } else {
                if ($id === $route) {
                    return false;
                }
            }
        }

        if ($action->controller->hasMethod('allowAction') && in_array($action->id, $action->controller->allowAction())) {
            return false;
        }
        return true;
    }
}