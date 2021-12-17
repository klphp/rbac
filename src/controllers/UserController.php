<?php
/**
 *
 * User: kl
 * DateTime: 2021/11/16 9:27 下午
 */

namespace klphp\rbac\controllers;

use klphp\rbac\models\Assign;
use Yii;
use yii\di\Container;
use yii\web\Controller;
use yii\web\Response;

class UserController extends Controller
{

    public function getRoles()
    {
        return Yii::$app->authManager->getRoles();
    }

    public function getRoleKeys()
    {
        $roles = $this->getRoles();
        if ($roles) {
            $roleKeys = array_keys($roles);
        } else {
            $roleKeys = [];
        }
        return $roleKeys;
    }

    public function actionIndex()
    {

        $roleKeys=$this->getRoleKeys();
        $searchModel = Yii::$container->get('klphp\rbac\userSearch');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        \Yii::$app->session->setFlash('return', Yii::$app->request->getHostInfo() . Yii::$app->request->url);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'roleKeys'=>$roleKeys,
        ]);
    }

    public function actionModal(){
        if(Yii::$app->request->isAjax){

            $roleKeys=$this->getRoleKeys();

            $model=new Assign();
            $model->id=Yii::$app->request->get('id');
            $auth = Yii::$app->authManager;
            $userRoles=$auth->getRolesByUser($model->id);
            if($userRoles){
                $userRole=array_keys($userRoles)[0];
                $model->role=array_search($userRole,$roleKeys);
            }

            if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post())){
                Yii::$app->response->format=Response::FORMAT_JSON;
                if(is_numeric($model->role)){
                    $roleName=$roleKeys[$model->role];
                    $role = $auth->createRole($roleName);
                    $auth->revokeAll($model->id);
                    $auth->assign($role, $model->id);
                    return [
                        'uid'=>$model->id,
                        'role'=>$roleName,
                        'status'=>true
                    ];
                }else{
                    $auth->revokeAll($model->id);
                    return ['status'=>false];
                }
            }

            return $this->renderPartial('modal',[
                'model'=>$model,
                'roleKeys'=>$roleKeys
            ]);
        }
    }

    public function actionAssign()
    {
        $auth=Yii::$app->getAuthManager();
        $items=$auth->getUserIdsByRole('member');
        dump($items);
    }
}