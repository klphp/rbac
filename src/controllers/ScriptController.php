<?php

namespace klphp\rbac\controllers;

use hi121\components\ArrayHelper;
use Yii;
use klphp\rbac\models\AuthRule;
use klphp\rbac\models\AuthRuleSearch;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use klphp\rbac\components\Hexception;
/**
 * Rbac脚本权限控制
 */
class ScriptController extends Controller
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

    /**
     * 提取规则脚本列表
     */
    private function getRules(){

        $files=[];
        if($this->module->rule!==false){
            $dir=Yii::getAlias($this->module->rule['path']);
            foreach(scandir($dir) as $file){
                if($file!='.' && $file!='..'){
                    $name=trim($file,'.php');
                    $files[$this->module->rule['namespace'].'\\'.$name]=$name;
                }
            }
        }

        return $files;
    }

    /**
     * Lists all AuthRule models.
     */
    public function actionIndex()
    {
        $auth=Yii::$app->authManager;
        $rules=$auth->getRules();

        return $this->render('index', [
            'rules' => $rules,
        ]);
    }

    /**
     * Creates a new AuthRule model.
     */
    public function actionCreate()
    {
        $rules=static::getRules();

        //数据库里的规则
        $dbRules=Yii::$app->authManager->getRules();
        if($dbRules){ $rules=array_diff($rules,array_keys($dbRules)); }

        $model = new AuthRule();
        $model->loadDefaultValues();
        $return = Yii::$app->request->referrer;
        if (Yii::$app->request->isPost) {
            try{
                $data=Yii::$app->request->post($model->formName(),false);
                if(!class_exists($data['name'])){
                    Hexception::error('类 '.$data['name'].' 没找到');
                }
                $rule=new $data['name'];
                $auth = Yii::$app->authManager;
                if($auth->add($rule)){
                    return $this->redirect(Yii::$app->request->post('return'));
                }
            }catch (\Exception $e){
                $return = Yii::$app->request->post('return');
                Hexception::Alert($e->getMessage());
            }
        }

        return $this->render('create', [
            'rules'=>$rules,
            'model' => $model,
            'return' => $return
        ]);
    }

    /**
     * Deletes an existing AuthRule model.
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(Yii::$app->request->referrer);
    }

    protected function findModel($id)
    {
        if (($model = AuthRule::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
