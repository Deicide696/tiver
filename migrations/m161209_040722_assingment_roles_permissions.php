<?php

use yii\db\Migration;
use app\models\User;

class m161209_040722_assingment_roles_permissions extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;
         ///////////////////////////// Inserts de asigancion de rol admin /////////////////////////////
        $user = User::find()->where(['email' => 'admin@zugartek.com', 'enable' => 1])->asArray()->one();
//        echo "Hola: ".var_dump($user["id"]);
        $auth->assign($auth->getRole('super-admin'), $user['id']);
        ///////////////////////////// FIN Inserts de asigancion de rol admin /////////////////////////////
      
        /////////////////////////////// Asiganción de permisos ////////////////////////////   
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('create-address'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('create-assignment-service'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('create-category'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('create-city'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('create-coupon'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('create-expert'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('create-id'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('create-modifier'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('create-schedule'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('create-service'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('create-user'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('create-zone'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('delete-address'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('delete-assignment-service'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('delete-category'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('delete-city'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('delete-coupon'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('delete-expert'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('delete-id'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('delete-modifier'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('delete-schedule'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('delete-service'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('delete-user'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('delete-zone'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('edit-address'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('edit-assignment-service'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('edit-category'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('edit-city'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('edit-coupon'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('edit-expert'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('edit-id'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('edit-modifier'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('edit-schedule'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('edit-service'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('edit-user'));
        $auth->addChild($auth->getRole('super-admin'), $auth->getPermission('edit-zone'));
        ///////////////////////////// FIN Asiganción de permisos /////////////////////////////
        echo "Asignación de roles y permisos exitosa";
    }

    public function down()
    {
        echo "m161209_040722_assingment_roles_permissions cannot be reverted.\n";

        return false;
    }
}
