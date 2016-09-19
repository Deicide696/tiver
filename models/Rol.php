<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rol".
 *
 * @property integer $id
 * @property string $name
 *
 * @property Expert[] $experts
 * @property User[] $users
 */
class Rol extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rol';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Rol',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExperts()
    {
        return $this->hasMany(Expert::className(), ['rol_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['FK_id_rol' => 'id']);
    }
}
