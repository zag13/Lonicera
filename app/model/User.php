<?php
/**
 * Created by PhpStorm
 * User: ZS
 * Date: 2020/7/6
 * Time: 8:44 下午
 */

namespace app\model;

//use Lonicera\core\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';
    public $timestamps = false;

//    public $username;
//    public $password;
}