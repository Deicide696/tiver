<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "service".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $price
 * @property integer $tax
 * @property string $icon
 * @property integer $duration
 * @property integer $status
 * @property integer $category_service_id
 *
 * @property AssignedService[] $assignedServices
 * @property CityHasService[] $cityHasServices
 * @property CouponHasService[] $couponHasServices
 * @property Coupon[] $coupons
 * @property ExpertHasService[] $expertHasServices
 * @property Expert[] $experts
 * @property Price[] $prices
 * @property CategoryService $categoryService
 * @property ServiceHasCode[] $serviceHasCodes
 * @property Code[] $codes
 * @property ServiceHasModifier[] $serviceHasModifiers
 * @property Modifier[] $modifiers
 * @property ServiceHistory[] $serviceHistories
 */
class Service extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'price', 'tax', 'icon', 'category_service_id'], 'required'],
            [['price', 'tax', 'duration', 'status', 'category_service_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
        	[['description'], 'string', 'max' => 255],
            [['icon'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Servicio',
            'price' => 'Precio',
            'tax' => 'I.V.A.',
        		   'status' => 'Activo',
            'duration' => 'Duración (mins)',
        		'description' => 'Descripción',
            'category_service_id' => 'Categoría',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssignedService()
    {
        return $this->hasMany(AssignedService::className(), ['service_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCityHasService()
    {
        return $this->hasMany(CityHasService::className(), ['service_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCouponHasService()
    {
        return $this->hasMany(CouponHasService::className(), ['service_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoupon()
    {
        return $this->hasMany(Coupon::className(), ['id' => 'coupon_id'])->viaTable('coupon_has_service', ['service_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpertHasService()
    {
        return $this->hasMany(ExpertHasService::className(), ['service_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpert()
    {
        return $this->hasMany(Expert::className(), ['id' => 'expert_id'])->viaTable('expert_has_service', ['service_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrice()
    {
        return $this->hasMany(Price::className(), ['service_id' => 'id']);
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
    public function getServiceHasCode()
    {
        return $this->hasMany(ServiceHasCode::className(), ['service_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCode()
    {
        return $this->hasMany(Code::className(), ['id' => 'code_id'])->viaTable('service_has_code', ['service_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServiceHasModifier()
    {
        return $this->hasMany(ServiceHasModifier::className(), ['service_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModifier()
    {
        return $this->hasMany(Modifier::className(), ['id' => 'modifier_id'])->viaTable('service_has_modifier', ['service_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServiceHistorie()
    {
        return $this->hasMany(ServiceHistory::className(), ['service_id' => 'id']);
    }
}
