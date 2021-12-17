<?php
/**
 * CLI 管理帐号注册
 */
namespace klphp\rbac\models;

use Yii;
use klphp\rbac\components\Hexception;
use yii\base\Model;
use klphp\rbac\models\User;
use yii\helpers\Console;

class AdminSignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $role;
    public $status;

    private $_auth;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => User::class, 'message' => '用户名已被占用.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email', 'message' => 'email格式不正确.'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::class, 'message' => '这个email地址已注册.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 8, 'message'=>'密码长度不能小于8位'],
            ['role','in','range'=>array_keys(User::LABEL_ROLES)],
            ['status', 'in', 'range' => [User::STATUS_ACTIVE, User::STATUS_INACTIVE, User::STATUS_DELETED]],

        ];
    }


    /**
     * 后台用户组帐号自动激活
     * @return array
     */
    private function adminGroup(){
        $group=[];
        $group[]=User::ROLE_ADMINISTRATOR;
        $group[]=User::ROLE_ADMIN;
        $group[]=User::ROLE_STAFF;
        return $group;
    }


    /**
     * cli用户注册
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            Hexception::getFirstError($this);
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->role = $this->role;
        //后台帐号自动激活
        if(in_array($user->role,static::adminGroup())){
            $user->status=User::STATUS_ACTIVE;
        }
        $user->setPassword($this->password);
        $user->generateAuthKey();

        if($user->save()){
            return true;
        }else{
            return $user->getFirstErrors();
        }

    }
}
