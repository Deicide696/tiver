<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_has_coupon".
 *
 * @property integer $user_id
 * @property integer $coupon_id
 *
 * @property Coupon $coupon
 * @property User $user
 */
class UserHasCoupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_has_coupon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'coupon_id'], 'required'],
            [['user_id', 'coupon_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'coupon_id' => 'Coupon ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoupon()
    {
        return $this->hasOne(Coupon::className(), ['id' => 'coupon_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
