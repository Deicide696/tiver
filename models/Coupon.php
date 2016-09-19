<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "coupon".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property integer $enable
 * @property integer $used
 * @property string $created_date
 * @property string $updated_date
 * @property integer $type_coupon_id
 *
 * @property AssignedService[] $assignedServices
 * @property TypeCoupon $typeCoupon
 * @property CouponHasCategoryService[] $couponHasCategoryServices
 * @property CategoryService[] $categoryServices
 * @property CouponHasService[] $couponHasServices
 * @property Service[] $services
 * @property UserHasCoupon[] $userHasCoupons
 * @property User[] $users
 */
class Coupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coupon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['enable', 'used', 'type_coupon_id'], 'integer'],
          //  [['created_date', 'updated_date'], 'safe'],
            [['type_coupon_id'], 'required'],
            [['name', 'code'], 'string', 'max' => 45],
            [['code'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Descripción',
            'code' => 'Cupón',
            'enable' => 'Activo',
            'used' => 'Usado',
            'created_date' => 'Fecha creación',
            'updated_date' => 'Fecha actualización',
            'type_coupon_id' => 'Tipo de cupón',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssignedServices()
    {
        return $this->hasMany(AssignedService::className(), ['coupon_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypeCoupon()
    {
        return $this->hasOne(TypeCoupon::className(), ['id' => 'type_coupon_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCouponHasCategoryService()
    {
        return $this->hasMany(CouponHasCategoryService::className(), ['coupon_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryService()
    {
        return $this->hasMany(CategoryService::className(), ['id' => 'category_service_id'])->viaTable('coupon_has_category_service', ['coupon_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCouponHasService()
    {
        return $this->hasMany(CouponHasService::className(), ['coupon_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getService()
    {
        return $this->hasMany(Service::className(), ['id' => 'service_id'])->viaTable('coupon_has_service', ['coupon_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHasCoupon()
    {
        return $this->hasMany(UserHasCoupon::className(), ['coupon_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('user_has_coupon', ['coupon_id' => 'id']);
    }
}
