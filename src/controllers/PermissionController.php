<?php
/**
 * 权限配置
 */
namespace klphp\rbac\controllers;

use klphp\rbac\components\Apps;
use klphp\rbac\components\Routers;
use Yii;
use klphp\rbac\models\AuthItem;
use klphp\rbac\models\AuthItemPermissionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use klphp\rbac\components\Hexception;

class PermissionController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function getRules(){
        return array_keys(Yii::$app->authManager->getRules());
    }

    /**
     * 权限列表
     */
    public function actionIndex()
    {
        $searchModel = new AuthItemPermissionSearch();
        $searchModel->type=2;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 创建权限
     */
    public function actionCreate()
    {
        $model = new AuthItem();
        $model->loadDefaultValues();
        $return = Yii::$app->request->referrer;
        if (Yii::$app->request->isPost) {
            try{
                $auth=Yii::$app->authManager;
                $data=Yii::$app->request->post($model->formName(),[]);
                $permission = $auth->createPermission($data['name']);
                $permission->description = $data['description'];
                $permission->ruleName = $data['rule_name']===''?null:static::getRules()[$data['rule_name']];
                $permission->data = $data['data'];
                if($auth->add($permission)){
                    return $this->redirect(Yii::$app->request->post('return'));
                }
            }catch (\Exception $e){
                Hexception::Alert($e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $model,
            'return' => $return
        ]);
    }

    public function actionSetting(){

        $auth=Yii::$app->authManager;

        $permissions=$auth->getPermissions();
        if($permissions){
            $permissions=array_keys($permissions);
        }

        if(Yii::$app->request->isPost){

        }

        return $this->render('setting',[
            'permissions'=>$permissions,
            'roles'=>$roles,
        ]);
//        $perParent=$auth->getPermission('测试');
//        $perChild=$auth->getPermission('/search/node/index');
//        $auth->addChild($perParent,$perChild);
//
//
//        pre($auth->getChildren('测试')); die;

    }

    /**
     * 导入后台路由
     * @throws \Exception
     */
    public function actionImport($app='api'){

        $routeModel=new Routers();
        $appObj=new Apps();
        $routeModel->app=$appObj->createApp($app);
        try{
            $routers=$routeModel->getRouters();
            $auth=Yii::$app->authManager;
        }catch (\Exception $e){
            echo $e->getMessage()."<br/>";
            die;
        }

        if($routers){
            dump($routers);
//            foreach($routers as $router){
//                try{
//                    $permission=$auth->createPermission($router);
//                    $auth->add($permission);
//                    echo "<span style='color:cadetblue'>{$router} 写入成功！</span> <hr/>";
//                }catch (\Exception $e){
//                    echo "<span style='color:darkblue'>{$router} 已存在！</span> <hr/>";
//                }
//            }
            die;
        }

    }

    /**
     * 更新权限
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $return = Yii::$app->request->referrer;
        if ($model->load(Yii::$app->request->post())) {
            try{
                $auth=Yii::$app->authManager;
                $data=Yii::$app->request->post($model->formName(),[]);
                $permission = $auth->getPermission($id);
                $permission->description = $data['description'];
                $permission->ruleName = $data['rule_name']===''?null:static::getRules()[$data['rule_name']];
                $permission->data = $data['data'];
                if($auth->update($id,$permission)){
                    return $this->redirect(Yii::$app->request->post('return'));
                }
            }catch (\Exception $e){
                Hexception::Alert($e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
            'return' => $return
        ]);
    }

    /**
     * 删除权限
     */
    public function actionDelete($id)
    {
        try{
            $auth=Yii::$app->authManager;
            $permission = $auth->getPermission($id);
            if($auth->remove($permission)){
                return $this->redirect(Yii::$app->request->referrer);
            }
        }catch (\Exception $e){
            Hexception::Alert($e->getMessage());
        }
    }

    protected function findModel($id)
    {
        if (($model = AuthItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
