<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "type_coupon".
 *
 * @property integer $id
 * @property string $description
 *
 * @property Coupon[] $coupons
 */
class TypeCoupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'type_coupon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoupons()
    {
        return $this->hasMany(Coupon::className(), ['type_coupon_id' => 'id']);
    }
}
