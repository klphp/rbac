<?php
/**
 * 链接权限展示控制
 * User: www
 * Date: 18-3-22
 * Time: 下午9:40
 */
namespace klphp\rbac\components;

use Yii;
use yii\base\Component;
use yii\helpers\Html;
use yii\helpers\Url;

class Helper extends Component{


    /**
     * 获得我的所有操作权限列表
     * @return array
     */
    private static function getMyPermissions(){

        $user=Yii::$app->user;
        $auth=Yii::$app->getAuthManager();
        $roles=$auth->getRolesByUser($user->identity->id);

        $permissions=[];
        foreach ($roles as $role){
            $temp=$auth->getPermissionsByRole($role->name);
            $permissions[]=array_keys($temp?$temp:[]);
            unset($temp);
        }

        $items=[];
        if($permissions){
            foreach ($permissions as $permission){
                $items=array_merge($items,$permission);
            }
            $items=array_unique($items);
        }

        if($items){
            return $items;
        }else{
            return [];
        }
    }

    /**
     * 带权限控制的A标签生成
     * @param $text
     * @param null $url
     * @param array $options
     * @return mixed
     */
    public static function a($text, $url = null, $options = [])
    {
        $user=Yii::$app->user;
        $urlManager=\Yii::$app->urlManager;
        $urlManager->setScriptUrl(false);
        $urlManager->enablePrettyUrl=true;

        if(static::filter( Url::to([$url[0]]) ) ){
            return Html::a($text, $url, $options);
        }else{
            return '';
        }
    }


    /**
     * 菜单
     * @param $routes
     */
    static public function menu($routes){

        if(Yii::$app->user->isGuest){
            return [];
        }

        //如果是超级管理员则跳过判断
        $user=Yii::$app->user;
        if($user->identityClass::isAdministrator($user->identity)){
            return $routes;
        }

        $menus=[];
        if($routes){
            $menus=array_filter(array_map(function($route){
                return static::filterMenu($route);
            },$routes));
        }else{
            $menus=$routes;
        }

        return $menus;
    }


    /**
     * 过滤菜单
     * @param $routes
     */
    private function filterMenu($route){

        //非URL菜单保留
        if(!isset($route['url'])){
            return $route;
        }

        //没有子菜单项直接进入过滤流程
        if(!isset($route['items'])){
            if(static::filter($route['url'])){
                return $route;
            }
        }else{
            //有子菜单的项
            $menus=$route;
            unset($menus['items']);
            $menus['items']=array_filter(array_map(function($child){
                if( isset($child['items']) && !empty($child['items']) ){
                    $child['items']=static::filterMenu($child['items']);
                    return $child;
                }else{
                    if( isset($child['items']) && empty($child['items']) ){
                        unset($child['items']);
                    }
                    if(static::filterMenu($child)){
                        return $child;
                    }
                }
            },$route['items']));

            if($menus['items']){
                return $menus;
            }
        }

    }

    /**
     * 判断指定路由是否有权限
     */
    private static function filter($route){

        if($route=='#'){
            return true;
        }else{

            $user=Yii::$app->user;
//            if($user->identityClass::isAdministrator($user->identity)){
//                return true;
//            }

            //规范化当前URI
            $uri='';
            if(is_array($route)){ $uri=ltrim($route[0],'/'); }
            if(is_string($route)){ $uri=ltrim($route,'/'); }

            //如果当前路由带有附加规则，优先检测
            $auth=Yii::$app->getAuthManager();
            $permission=$auth->getPermission($uri);
            if($permission->ruleName){
                //如果有附加规则优先触发禁止访问权限
                if (Yii::$app->user->can($uri)) {
                    return true;
                }else{
                    return false;
                }
            }

            //生成星号路由列表
            $routes=static::createRouteArray($uri);

            //所在用户组所有权限列表
            $permissions=static::getMyPermissions();

            if($routes){
                foreach($routes as $route){
                    if(in_array($route,$permissions)){
                        return true;
                    }
                }
            }
        }

        return false;

    }


    /**
     * 根据指定的route返回所有需要判断的route数组
     * @param $route
     */
    private static function createRouteArray($route){
        $routeList=[];
        //判断是否是模型
        if(Yii::$app->getModule($route)){
            if(strpos($route,'/')!==false){
                $routeList=static::createArray($route,true);
            }else{
                $routeList[]=$route.'/*';
            }
        }else{
            if(strpos($route,'/')!==false){
                $routeList=static::createArray($route);
            }else{
                $routeList[]=$route.'/*';
            }
        }
        return $routeList;
    }


    /**
     * 如
     * site/index 返回site/*
     * site/default/index 返回site/*,site/default/*
     * @param $route
     * @param bool $module
     * @param $i           //初始循环次数
     * @param array $routes//是否是模型，
     * @return array
     */
    private static function createArray($route,$module=false,$i=0,&$routes=[]){
        $tempArray=explode('/',$route);
        if($module===false){array_pop($tempArray);}
        $uri='';
        foreach($tempArray as $k => $arr){
            if($k==0){
                $routes[]=$route;
            }
            if($k<$i){
                $uri.=$arr.'/';
            }
            if($k==$i){
                $uri.=$arr.'/*';
                $routes[]=$uri;
            }
            if($k>$i){
                static::createArray($route,$module,$i+1,$routes);
            }
        }
        $routes=array_unique($routes);
        return $routes;
    }

}