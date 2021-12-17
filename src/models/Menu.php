<?php

namespace klphp\rbac\models;

use yii\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property string $name
 * @property int $parent
 * @property string $route
 * @property int $order
 * @property resource $data
 *
 * @property Menu $parent0
 * @property Menu[] $menus
 */
class Menu extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'unique'],
            [['parent', 'order'], 'integer'],
            ['parent','ruleParent'],
            [['data'], 'string'],
            [['name'], 'string', 'max' => 128],
            [['route'], 'string', 'max' => 255],
            [['parent'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::className(), 'targetAttribute' => ['parent' => 'id']],
        ];
    }

    public function ruleParent($insert,$attribute){
        if(!$insert){
            if($this->id==$this->parent){
                $this->addError($attribute,'父级不能选择自己');
            }
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '菜单名',
            'parent' => '父级',
            'route' => '路由',
            'order' => '排序',
            'data' => '数据',
        ];
    }

    /*
     * 获取菜单
     */
    public static function items(){
        $data=static::find()->orderBy('order asc')->asArray()->all();

        if($data){
            $menus=[];
            foreach($data as $item){
                if($item['parent']==null){
                    $menus[]=static::itemFormat($item);
                }
            }
            foreach($menus as $k => $menu){
                $menus[$k]['items']=static::ListToTree($data,$menu['key'],'key','parent','items');
            }

            return $menus;
        }else{
            return [];
        }
    }

    /**
     * 采用递归将数据列表转换成树
     * @param  array $dataArr 数据列表
     * @param  integer $rootId 根节点ID
     * @param  string $pkName 主键
     * @param  string $pIdName 父节点名称
     * @param  string $childName 子节点名称
     * @return array  转换后的树
     */
    public static function ListToTree($dataArr, $rootId, $pkName = 'id', $pIdName = 'pid', $childName = 'children')
    {
        $arr = [];
        foreach ($dataArr as $sorData) {
            if ($sorData[$pIdName] == $rootId) {
                $sorData=self::itemFormat($sorData);
                $sorData[$childName] = static::ListToTree($dataArr, $sorData[$pkName]);
                $arr[] = $sorData;
            }
        }

        return $arr;
    }


    /**
     * 格式化菜单
     * @param $item
     * @return array
     */
    public static function itemFormat($item){
        $arr=[];
        $arr['key']=$item['id'];
        $arr['label']=$item['name'];
        $arr['icon']=$item['data'];
        $arr['url']=$item['route']?$item['route']:'#';
        return $arr;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent0()
    {
        return $this->hasOne(Menu::className(), ['id' => 'parent']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenus()
    {
        return $this->hasMany(Menu::class(), ['parent' => 'id']);
    }

    /**
     * 父级选择菜单
     */
    public static function getSelectItems(){
        $model=static::find()->select(['id','name'])->all();
        return ArrayHelper::map($model,'id','name');
    }
}
