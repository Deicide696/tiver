<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "coupon_has_category_service".
 *
 * @property integer $coupon_id
 * @property integer $category_service_id
 *
 * @property CategoryService $categoryService
 * @property Coupon $coupon
 */
class CouponHasCategoryService extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coupon_has_category_service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coupon_id', 'category_service_id'], 'required'],
            [['coupon_id', 'category_service_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coupon_id' => 'Coupon ID',
            'category_service_id' => 'Categoria de Servicio',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryService()
    {
        return $this->hasOne(CategoryService::className(), ['id' => 'category_service_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoupon()
    {
        return $this->hasOne(Coupon::className(), ['id' => 'coupon_id']);
    }
}
