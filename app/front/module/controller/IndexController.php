<?php
/**
 * Created by PhpStorm
 * User: ZS
 * Date: 2020/7/1
 * Time: 10:42 下午
 */

namespace app\front\module\controller;

//require_once _SYS_PATH . 'core/Controller.php';

use app\model\User;
use library\Container\Container;
use Lonicera\core\Controller;
use Lonicera\core\DB;

class IndexController extends Controller
{
    public function _before_()
    {
        echo 'before操作' . PHP_EOL;
    }

    public function indexAction()
    {
        phpinfo();
    }

    public function hiAction()
    {
//        require_once _SYS_PATH . 'core/DB.php';
        $db = DB::getInstance($GLOBALS['_config']['db']);
        $ret = $db->query('select * from tp_one');

//        $db2 = new DB($GLOBALS['_config']['db']);
//        $ret2 = $db2->query('select * from tp_one');
        var_dump($ret);
        echo 'hiAction';

    }

    public function userAction()
    {
//        require_once _SYS_PATH . 'core/Model.php';
//        require_once _APP . 'model/User.php';
        $user = new User();
        $user->username = 'zzz';
        $user->password = mt_rand(1000, 9999);
        $user->save();
    }

    public function viewAction()
    {
        $this->assign('age', 223);
        $this->display();
    }

    public function userConAction()
    {
        $user = new User();
        $user->username = 'uuu';
        $user->password = mt_rand(1000, 9999);
        $container = new Container();
        $container->set('user', $user);
        $userBean = $container->user;
        $container->callback('user', 'save');
        $container->inject('user', 'method1', function () use ($userBean) {
//            $userBean->save();
            for ($i = 0; $i < 6; $i++) {
                echo $i;
            }
        });
//        $container->callback('user','method1');
    }
}
 