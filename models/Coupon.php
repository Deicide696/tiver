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
 * @property integer $type_coupon_id
 * @property integer $discount
 * @property integer $amount
 * @property integer $quantity
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
 * @property CouponHasTeam[] $couponHasTeams
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
            [['enable', 'used', 'type_coupon_id', 'discount', 'amount', 'quantity'], 'integer'],
            [['code','type_coupon_id', 'due_date'], 'required'],
            [['created_date', 'updated_date', 'due_date'], 'safe'],
            [['name', 'code'], 'string', 'max' => 45],
            [['code'], 'unique'],
            [['type_coupon_id'], 'exist', 'skipOnError' => true, 'targetClass' => TypeCoupon::className(), 'targetAttribute' => ['type_coupon_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'code' => Yii::t('app', 'Code'),
            'enable' => Yii::t('app', 'Enable'),
            'used' => Yii::t('app', 'Used'),
            'type_coupon_id' => Yii::t('app', 'Type Coupon ID'),
            'discount' => Yii::t('app', 'Discount'),
            'amount' => Yii::t('app', 'Amount'),
            'quantity' => Yii::t('app', 'Quantity'),
            'created_date' => Yii::t('app', 'Created Date'),
            'updated_date' => Yii::t('app', 'Updated Date'),
            'due_date' => Yii::t('app', 'Due Date'),
        ];
    }
    
    public function behaviors()
    {
       return [           
           'timestamp' => [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT =>  ['created_date', 'updated_date'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_date',
                ],
                'value' => function() { return  date ( 'Y-m-d H:i:s' );},
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
    public function getCouponHasTeams()
    {
        return $this->hasMany(CouponHasTeam::className(), ['coupon_id' => 'id']);
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
