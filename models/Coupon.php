<?php

namespace app\models;

use yii\db\Expression;
use Yii;

/**
 * This is the model class for table "coupon".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property integer $enable
 * @property integer $used
 * @property integer $type_coupon_id
 * @property integer $discount
 * @property integer $amount
 * @property string $created_date
 * @property string $updated_date
 * @property string $due_date
 *
 * @property AssignedService[] $assignedServices
 * @property TypeCoupon $typeCoupon
 * @property CouponHasCategoryService[] $couponHasCategoryServices
 * @property CategoryService[] $categoryServices
 * @property CouponHasService[] $couponHasServices
 * @property Service[] $services
 * @property ServiceHistory[] $serviceHistories
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
            [['enable', 'used', 'type_coupon_id', 'discount', 'amount'], 'integer'],
            [['type_coupon_id'], 'required'],
            [['created_date', 'updated_date', 'due_date'], 'safe'],
            [['name', 'code'], 'string', 'max' => 45],
            [['code'], 'unique', 'message' => 'Este codigo de Cupón ya se encuentra en nuestros registros.'],
            [['type_coupon_id'], 'exist', 'skipOnError' => true, 'targetClass' => TypeCoupon::className(), 'targetAttribute' => ['type_coupon_id' => 'id']],
        ];
    }
    
    public function behaviors()
    {
       return [           
           'timestamp' => [
               'class' => 'yii\behaviors\TimestampBehavior',
               'attributes' => [
                   \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_date'],                   
               ],
               'value' => new Expression('NOW()'),
           ],
           'timestamp' => [
               'class' => 'yii\behaviors\TimestampBehavior',
               'attributes' => [
                   \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_date'],                   
               ],
               'value' => new Expression('NOW()'),
           ],
           'activeBehavior' => [
               'class' => 'yii\behaviors\AttributeBehavior',
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'enable',
                ],
                'value' => 1,
           ],
           'activeBehavior' => [
               'class' => 'yii\behaviors\AttributeBehavior',
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'used',
                ],
                'value' => 0,
           ],
       ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Nombre',
            'code' => 'Codigo de Cupón',
            'enable' => 'Activo',
            'used' => 'Usado',
            'type_coupon_id' => 'Tipo de Cupón',
            'discount' => 'Porcentaje de Descuento',
            'amount' => 'Monto',
            'created_date' => 'Fecha de Creación',
            'updated_date' => 'Fecha de Actualización',
            'due_date' => 'Fecha de Vencimiento',
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
    public function getCouponHasCategoryServices()
    {
        return $this->hasMany(CouponHasCategoryService::className(), ['coupon_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryServices()
    {
        return $this->hasMany(CategoryService::className(), ['id' => 'category_service_id'])->viaTable('coupon_has_category_service', ['coupon_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCouponHasServices()
    {
        return $this->hasMany(CouponHasService::className(), ['coupon_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServices()
    {
        return $this->hasMany(Service::className(), ['id' => 'service_id'])->viaTable('coupon_has_service', ['coupon_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServiceHistories()
    {
        return $this->hasMany(ServiceHistory::className(), ['coupon_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHasCoupons()
    {
        return $this->hasMany(UserHasCoupon::className(), ['coupon_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('user_has_coupon', ['coupon_id' => 'id']);
    }
}
