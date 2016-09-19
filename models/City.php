<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "city".
 *
 * @property integer $id
 * @property string $name
 *
 * @property AssignedService[] $assignedServices
 * @property CityHasService[] $cityHasServices
 * @property User[] $users
 * @property Zone[] $zones
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Ciudad',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssignedServices()
    {
        return $this->hasMany(AssignedService::className(), ['city_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCityHasServices()
    {
        return $this->hasMany(CityHasService::className(), ['city_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['FK_id_city' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZones()
    {
        return $this->hasMany(Zone::className(), ['city_id' => 'id']);
    }
}
