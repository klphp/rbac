<?php
/**
 * 用户接口
 * User: kl
 * DateTime: 2021/11/16 6:23 下午
 */
namespace klphp\rbac\services;

interface UserService{

    public function one($id);

    public function items();

}