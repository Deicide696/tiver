<?php

use yii\db\Migration;
use yii\db\Expression;

class m161208_205942_insert_data extends Migration
{
    public function up()
    {
        ///////////////////////////// Inserts de usuario admin /////////////////////////////
        $this->insert('user', [
            'first_name' => 'Admin',
            'last_name' => 'Tiver',
            'email' => 'admin@zugartek.com',
            'password' => '$2y$13$zFiXe8TCm3WJ57ojbsN5Ge5ltBWvB/192iD885tznqmojc8Vm0r46',
            'receive_interest_info' => 0,
            'imei' => 123456789123456,
//            'FK_id_rol' => 1,
            'FK_id_gender' => 1,
            'FK_id_type_identification' => 1,
            'FK_id_city' => 1,
            'last_login' =>  new Expression('NOW()'),
            'created_date' => new Expression('NOW()'),
            'updated_date' => new Expression('NOW()'),
            'enable' => 1,
        ]);        
        ///////////////////////////// FIN Inserts de usuario admin /////////////////////////////
        ///////////////////////////// Inserts de roles /////////////////////////////
       
        $this->insert('auth_item', [
            'name' => 'super-admin',
            'type' => 1,
            'description' => 'Rol de máximo control',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'admin',
            'type' => 1,
            'description' => 'Rol para los administradores',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'expert',
            'type' => 1,
            'description' => 'Rol para los usuarios especialistas',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'user',
            'type' => 1,
            'description' => 'Rol para los usuarios normales',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        ///////////////////////////// FIN Inserts de roles /////////////////////////////
        
        ///////////////////////////// Inserts de Permisos /////////////////////////////
        $this->insert('auth_item', [
            'name' => 'create-user',
            'type' => 2,
            'description' => 'Permite crear un nuevo usuario',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'edit-user',
            'type' => 2,
            'description' => 'Permite editar un usuario existente',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'delete-user',
            'type' => 2,
            'description' => 'Permite eliminar un usuario',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'create-expert',
            'type' => 2,
            'description' => 'Permite crear un nuevo Especialista',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'edit-expert',
            'type' => 2,
            'description' => 'Permite editar un usuario Especialista',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'delete-expert',
            'type' => 2,
            'description' => 'Permite eliminar un Especialista',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'create-coupon',
            'type' => 2,
            'description' => 'Permite crear un nuevo cupón',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'edit-coupon',
            'type' => 2,
            'description' => 'Permite editar un cupón',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'delete-coupon',
            'type' => 2,
            'description' => 'Permite eliminar un cupón',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'create-service',
            'type' => 2,
            'description' => 'Permite crear un nuevo servicio',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'edit-service',
            'type' => 2,
            'description' => 'Permite editar un servicio',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'delete-service',
            'type' => 2,
            'description' => 'Permite eliminar un servicio',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'create-schedule',
            'type' => 2,
            'description' => 'Permite crear una disponibilidad',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'edit-schedule',
            'type' => 2,
            'description' => 'Permite editar una disponibilidad',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'delete-schedule',
            'type' => 2,
            'description' => 'Permite eliminar una disponibilidad',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'create-assignment-service',
            'type' => 2,
            'description' => 'Permite crear una asignación de servicio',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'edit-assignment-service',
            'type' => 2,
            'description' => 'Permite editar una asignación de servicio',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'delete-assignment-service',
            'type' => 2,
            'description' => 'Permite eliminar una asignación de servicio',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'create-address',
            'type' => 2,
            'description' => 'Permite crear una dirección',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'edit-address',
            'type' => 2,
            'description' => 'Permite editar una dirección',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'delete-address',
            'type' => 2,
            'description' => 'Permite eliminar una dirección',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'create-category',
            'type' => 2,
            'description' => 'Permite crear una categoría',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'edit-category',
            'type' => 2,
            'description' => 'Permite editar una categoría',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'delete-category',
            'type' => 2,
            'description' => 'Permite eliminar una categoría',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'create-modifier',
            'type' => 2,
            'description' => 'Permite crear una modificador',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'edit-modifier',
            'type' => 2,
            'description' => 'Permite editar una modificador',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'delete-modifier',
            'type' => 2,
            'description' => 'Permite eliminar una modificador',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'create-zone',
            'type' => 2,
            'description' => 'Permite crear una zona',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'edit-zone',
            'type' => 2,
            'description' => 'Permite editar una zona',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'delete-zone',
            'type' => 2,
            'description' => 'Permite eliminar una zona',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'create-city',
            'type' => 2,
            'description' => 'Permite crear una ciudad',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'edit-city',
            'type' => 2,
            'description' => 'Permite editar una ciudad',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'delete-city',
            'type' => 2,
            'description' => 'Permite eliminar una ciudad',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'create-id',
            'type' => 2,
            'description' => 'Permite crear una identificación',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'edit-id',
            'type' => 2,
            'description' => 'Permite editar una identificación',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('auth_item', [
            'name' => 'delete-id',
            'type' => 2,
            'description' => 'Permite eliminar una identificación',
            'rule_name' => NULL,
            'data' => NULL,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        ///////////////////////////// FIN Inserts de Permisos /////////////////////////////
        echo "Fin de la insercion de la data";
    }

    public function down()
    {
        $this->delete('user');
        $this->delete('auth_assignment');
        $this->delete('auth_item');
        $this->delete('auth_item_child');
        $this->delete('auth_rule');
        $this->delete('migration', "version = 'm161208_205942_insert_data'");
        echo "Se elimino toda la data de las tablas de Autentificacion de usuarios";

        return false;
    }
}
