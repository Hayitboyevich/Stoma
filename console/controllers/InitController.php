<?php

namespace console\controllers;


use common\models\Config;
use common\models\User;
use Yii;
use yii\console\Controller;

/**
 * AccessController implements the CRUD actions for Access model.
 */
class InitController extends Controller
{
    function actionCreateAdmin(){
        $user = User::findOne(['username' => 'admin']);
        $pass = '@dmin!23';
        if(!$user){
            $user = new User();
            $user->username = 'admin';
            $user->auth_key = \Yii::$app->security->generateRandomString();
            $user->password_hash = \Yii::$app->security->generatePasswordHash($pass);
            $user->email = 'admin@localhost';
            $user->status = 10;
        }
        else{
            $user->password_hash = \Yii::$app->security->generatePasswordHash($pass);
        }
        $user->role = 'admin';

        $user->assignRole('admin');


        if($user->save()){
            echo "username: admin\r\n";
            echo "password: {$pass}\r\n";
        }
        else{
            echo "error";
        }
    }

    function actionCreateDefaultConfigs(){
        foreach(Yii::$app->params['default_params'] AS $key => $value){
            $config = new Config();
            $config->key = $key;
            $config->value = $value;
            $config->save();
        }
    }
}
