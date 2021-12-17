<?php
/**
 * 自定义异常处理
 * User: www
 * Date: 18-4-2
 * Time: 上午5:20
 */
namespace klphp\rbac\components;

use Yii;
use yii\base\Component;
use yii\web\BadRequestHttpException;

class Hexception extends Component {

    //自动获取第一个错误并写入闪存
    public static function firstAlert($model){
        $error=$model->getFirstErrors();
        if($error){
            Yii::$app->getSession()->setFlash('error',$error[array_keys($error)[0]]);
        }
    }

    //自动报第一条模型错误数据为异常
    public static function first($model){
        $error=$model->getFirstErrors();
        if($error){
            throw new \Exception($error[array_keys($error)[0]]);
        }
    }

    //返回model的第一条错误信息
    public static function getFirstError($model){
        $error=$model->getFirstErrors();
        return $error[array_keys($error)[0]];
    }

    /**
     * 直接闪存一个错误信息
     */
    public static function Alert($message){
        if($message){
            Yii::$app->getSession()->setFlash('error',$message);
        }else{
            static::error('未设置错误信息');
        }
    }

    /**
     * 报一个异常
     * @param string $message
     * @throws \Exception
     */
    public static function error(string $message){
        throw new \Exception($message);
    }

    /**
     * api报错
     * User: kl
     * DateTime: 2021/9/21 1:58 上午
     * @param string $message
     * @throws BadRequestHttpException
     */
    public static function apiError($message){
        if(is_string($message)){
            throw new BadRequestHttpException($message);
        }
        if(is_array($message)){
            $response=\Yii::$app->getResponse();
            $response->setStatusCode(400);
            return $message;
        }
    }

    /**
     * 抛出HTTP异常
     * @param $message      异常信息
     * @param int $status   状态码
     * @throws \yii\web\HttpException
     */
    public static function httpError($message='您访问的页面不存在！',$status=404){
        throw new \yii\web\HttpException($status, $message);
    }

    /**
     * 无权限异常
     * @param string $message
     * @throws \yii\web\ForbiddenHttpException
     */
    public static function http403($message='您没有权限执行此操作'){
        throw new \yii\web\ForbiddenHttpException($message);
    }
}