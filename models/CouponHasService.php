<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "coupon_has_service".
 *
 * @property integer $coupon_id
 * @property integer $service_id
 *
 * @property Coupon $coupon
 * @property Service $service
 */
class CouponHasService extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coupon_has_service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coupon_id', 'service_id'], 'required'],
            [['coupon_id', 'service_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coupon_id' => 'Coupon ID',
            'service_id' => 'Service ID',
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
    public function getService()
    {
        return $this->hasOne(Service::className(), ['id' => 'service_id']);
    }
}
