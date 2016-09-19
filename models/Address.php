<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property string $address
 * @property string $tower_apartment
 * @property string $custom_address
 * @property double $lat
 * @property double $lng
 * @property integer $type_housing_id
 *
 * @property TypeHousing $typeHousing
 * @property UserHasAddress[] $userHasAddresses
 * @property User[] $users
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lat', 'lng'], 'number'],
            [['type_housing_id'], 'required'],
            [['type_housing_id'], 'integer'],
            [['address'], 'string', 'max' => 255],
            [['tower_apartment'], 'string', 'max' => 45],
            [['custom_address'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address' => 'Dirección',
            'tower_apartment' => 'Complemento',
            'custom_address' => 'Nombre personalizado',
            'lat' => 'Lat',
            'lng' => 'Lng',
            'type_housing_id' => 'Tipo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypeHousing()
    {
        return $this->hasOne(TypeHousing::className(), ['id' => 'type_housing_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHasAddress()
    {
        return $this->hasOne(UserHasAddress::className(), ['address_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('user_has_address', ['address_id' => 'id']);
    }
}
