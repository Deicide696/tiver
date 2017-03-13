<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "assigned_service".
 *
 * @property string $id
 * @property string $address
 * @property string $date
 * @property string $time
 * @property double $lat
 * @property double $lng
 * @property string $comment
 * @property integer $immediate
 * @property string $service_id
 * @property string $user_id
 * @property integer $city_id
 * @property string $expert_id
 * @property integer $coupon_id
 * @property string $created_date
 * @property string $updated_date
 * @property integer $state
 *
 * @property City $city
 * @property Coupon $coupon
 * @property Expert $expert
 * @property Service $service
 * @property User $user
 * @property AssignedServiceHasModifier[] $assignedServiceHasModifiers
 * @property CompletedService[] $completedServices
 * @property Conversation[] $conversations
 */
class AssignedService extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'assigned_service';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['address', 'date', 'time', 'lat', 'lng', 'service_id', 'user_id', 'city_id', 'expert_id'], 'required'],
            [['date', 'time', 'created_date', 'updated_date'], 'safe'],
            [['lat', 'lng'], 'number'],
            [['immediate', 'service_id', 'user_id', 'city_id', 'expert_id', 'coupon_id', 'state'], 'integer'],
            [['address'], 'string', 'max' => 100],
            [['comment'], 'string', 'max' => 255],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['coupon_id'], 'exist', 'skipOnError' => true, 'targetClass' => Coupon::className(), 'targetAttribute' => ['coupon_id' => 'id']],
            [['expert_id'], 'exist', 'skipOnError' => true, 'targetClass' => Expert::className(), 'targetAttribute' => ['expert_id' => 'id']],
            [['service_id'], 'exist', 'skipOnError' => true, 'targetClass' => Service::className(), 'targetAttribute' => ['service_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'address' => Yii::t('app', 'Address'),
            'date' => Yii::t('app', 'Date service'),
            'time' => Yii::t('app', 'Time service'),
            'lat' => Yii::t('app', 'Lat'),
            'lng' => Yii::t('app', 'Lng'),
            'comment' => Yii::t('app', 'Comment'),
            'immediate' => Yii::t('app', 'Immediate'),
            'service_id' => Yii::t('app', 'Service ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'city_id' => Yii::t('app', 'City ID'),
            'expert_id' => Yii::t('app', 'Expert ID'),
            'coupon_id' => Yii::t('app', 'Code coupon'),
            'created_date' => Yii::t('app', 'Created Date'),
            'updated_date' => Yii::t('app', 'Updated Date'),
            'state' => Yii::t('app', 'Assigned'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_date', 'updated_date'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_date',
                ],
                'value' => function() { return  date ( 'Y-m-d H:i:s' );},
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity() {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoupon() {
        return $this->hasOne(Coupon::className(), ['id' => 'coupon_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpert() {
        return $this->hasOne(Expert::className(), ['id' => 'expert_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getService() {
        return $this->hasOne(Service::className(), ['id' => 'service_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssignedServiceHasModifier() {
        return $this->hasMany(AssignedServiceHasModifier::className(), ['assigned_service_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompletedServices() {
        return $this->hasMany(CompletedService::className(), ['assigned_service_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversations() {
        return $this->hasMany(Conversation::className(), ['assigned_service_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpertName() {
        $expert = Expert::findOne(['id' => $this->expert_id]);
        return $expert->name . " " . $expert->last_name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserName() {
        $user = User::findOne(['id' => $this->user_id]);
        return $user->first_name . " " . $user->last_name;
    }

    /**
     * 
     * @return type
     */
    public function getPrice() {

        $price = 0;
        $service = Service::findOne(['id' => $this->service_id]);

        if ($service->tax == 0)
        {
            $price += $service->price;
        }

        else
        {
            $price += (int) round(($service->price + ($service->price * Yii::$app->params ['tax_percent'])), -2, PHP_ROUND_HALF_UP);
        }

        $modifier_vw = VwActualService::findOne(['id' => $this->id]);

        if ($modifier_vw->modifier_id != "")
        {
            $modifier = Modifier::findOne(['id' => $modifier_vw->modifier_id]);

            if ($modifier->tax == 0)
            {
                $price += $modifier->price;
            }

            else
            {
                $price += (int) round($modifier->price + ($modifier->price * Yii::$app->params ['tax_percent']), -2, PHP_ROUND_HALF_UP);
            }
        }

        $userHasCoupons = UserHasCoupon::find()
            ->where([
                'user_id' => $this->user_id,
                'used' => 0,
                'enable' => 1])
            ->asArray()
            ->all();

        if (isset($userHasCoupons) && !empty($userHasCoupons))
        {
            foreach ($userHasCoupons as $userHasCoupon)
            {
                $model = Coupon::find()
                        ->where(['coupon.id' => $userHasCoupon['coupon_id'], 'coupon.enable' => 1])
                        ->joinWith(['couponHasCategoryServices.categoryService.service'])
                        ->joinWith(['couponHasServices.service s'])
                        ->asArray()
                        ->one();

                if (isset($model) && !empty($model))
                {
                    $day = date_parse(date('Y-m-d H:i:s'));
                    $day2 = date_parse($model['due_date']);

                    if ($day > $day2)
                    {
                        break;
                    }

                    if (!empty($model["couponHasCategoryServices"]))
                    {
//                        $category = $model["couponHasCategoryServices"][0]["categoryService"]['description'];

                        if (isset($model["couponHasCategoryServices"][0]["categoryService"]['service']) && !empty($model["couponHasCategoryServices"][0]["categoryService"]['description']))
                        {
                            foreach ($model["couponHasCategoryServices"][0]["categoryService"]['service'] as $serviceValid)
                            {
                                if ($serviceValid['id'] == $this->service_id)
                                {
                                    $priceold = $price;

                                    if ($model["quantity"] > 0)
                                    {
                                        if ($model["discount"] > 0)
                                        {
                                            $discount = ($price * $model["discount"]) / 100;
                                            $price = ($price - $discount);
                                            goto a;
                                        }
                                    }
                                }
                            }
                        }
                        a:
                    }

                    else if (!empty($model["couponHasServices"]))
                    {
                        $service = $model["couponHasServices"][0]['service']['id'];

                        if ($service == $this->service_id)
                        {
                            $priceold = $price;
                            if ($model["quantity"] > 0)
                            {
                                if ($model["discount"] > 0)
                                {
                                    $discount = ($price * $model["discount"]) / 100;
                                    $price = ($price - $discount);
                                    goto b;
                                }
                            }
                        }
                        b:
                    }
                }
            }
        }

        return $price;
    }

    public function setDiscountCoupon($price) {

        $userHasCoupons = UserHasCoupon::find()
            ->where([
                'user_id' => $this->user_id,
                'used' => 0,
                'enable' => 1])
            ->asArray()
            ->all();

        if (isset($userHasCoupons) && !empty($userHasCoupons)) {

            foreach ($userHasCoupons as $userHasCoupon) {

                $model = Coupon::find()
                        ->where(['coupon.id' => $userHasCoupon['coupon_id'], 'coupon.enable' => 1])
                        ->joinWith(['couponHasCategoryServices.categoryService.service'])
                        ->joinWith(['couponHasServices.service s'])
                        ->asArray()
                        ->one();

                if (isset($model) && !empty($model)) {

                    $day = date_parse(date('Y-m-d H:i:s'));
                    $day2 = date_parse($model['due_date']);

                    if ($day > $day2) {
                        break;
                    }
                    if (!empty($model["couponHasCategoryServices"])) {
                        $category = $model["couponHasCategoryServices"][0]["categoryService"]['description'];
                        if (isset($model["couponHasCategoryServices"][0]["categoryService"]['service']) && !empty($model["couponHasCategoryServices"][0]["categoryService"]['description'])) {
                            foreach ($model["couponHasCategoryServices"][0]["categoryService"]['service'] as $serviceValid) {
                                if ($serviceValid['id'] == $this->service_id) {
                                    $priceold = $price;
                                    if ($model["quantity"] > 0) {
                                        if ($model["discount"] > 0) {
                                            $discount = ($price * $model["discount"]) / 100;
                                            $price = ($price - $discount);
                                            $userHasCoupons2 = UserHasCoupon::find()
                                                    ->where(['user_id' => $userHasCoupon['user_id'],
                                                        'coupon_id' => $model['id']])
                                                    ->one();
                                            $userHasCoupons2->used = 1;
                                            $userHasCoupons2->enable = 0;
                                            $userHasCoupons2->update();
                                            break;
                                            goto a;
                                        }
                                    }
                                }
                            }
                        }
                        a:
                    } else if (!empty($model["couponHasServices"])) {

                        $service = $model["couponHasServices"][0]['service']['id'];
                        if ($service == $this->service_id) {
                            $priceold = $price;
                            if ($model["quantity"] > 0) {
                                if ($model["discount"] > 0) {
                                    $discount = ($price * $model["discount"]) / 100;
                                    $price = ($price - $discount);
                                    $userHasCoupons2 = UserHasCoupon::find()
                                            ->where(['user_id' => $userHasCoupon['user_id'],
                                                'coupon_id' => $model['id']])
                                            ->one();
                                    $userHasCoupons2->used = 1;
                                    $userHasCoupons2->enable = 0;
                                    $userHasCoupons2->update();
                                    break;
                                    goto b;
                                }
                            }
                        }
                        b:
                    }
                }
            }
        }

        return $price;
    }

    public function getTax()
    {
        $price = 0;
        $service = Service::findOne(['id' => $this->service_id]);

        $userHasCoupons = UserHasCoupon::find()
            ->where([
                'user_id' => $this->user_id,
                'used' => 0,
                'enable' => 1])
            ->asArray()
            ->all();

        if (isset($userHasCoupons) && !empty($userHasCoupons))
        {
            foreach ($userHasCoupons as $userHasCoupon)
            {
                $model = Coupon::find()
                    ->where(['coupon.id' => $userHasCoupon['coupon_id'], 'coupon.enable' => 1])
                    ->joinWith(['couponHasCategoryServices.categoryService.service'])
                    ->joinWith(['couponHasServices.service s'])
                    ->asArray()
                    ->one();

                if (isset($model) && !empty($model))
                {
                    $day = date_parse(date('Y-m-d H:i:s'));
                    $day2 = date_parse($model['due_date']);

                    if ($day > $day2)
                    {
                        break;
                    }

                    if (!empty($model["couponHasCategoryServices"]))
                    {
                        if (isset($model["couponHasCategoryServices"][0]["categoryService"]['service']) && !empty($model["couponHasCategoryServices"][0]["categoryService"]['description']))
                        {
                            foreach ($model["couponHasCategoryServices"][0]["categoryService"]['service'] as $serviceValid)
                            {
                                if ($serviceValid['id'] == $this->service_id)
                                {
                                    if ($model["quantity"] > 0)
                                    {
                                        if ($model["discount"] > 19)
                                        {
                                            return $price;
                                        }

                                        else
                                        {
                                            if ($service->tax == 0)
                                            {
                                                $price += $service->price;
                                            }

                                            else
                                            {
                                                $iva = $service->price * Yii::$app->params ['tax_percent'];

                                                $round_iva = (int) round(($service->price * Yii::$app->params ['tax_percent']), -2, PHP_ROUND_HALF_UP);

                                                if($round_iva > $iva)
                                                {
                                                    $extra_price = $round_iva - $iva;

                                                    $extra_iva = $extra_price * 0.16;

                                                    $price += ($service->price * Yii::$app->params ['tax_percent']) + $extra_iva;
                                                }

                                                else
                                                {
                                                    $price += ($service->price * Yii::$app->params ['tax_percent']);
                                                }

                                            }

                                            $modifier_vw = VwActualService::findOne(['id' => $this->id]);

                                            if ($modifier_vw->modifier_id != "")
                                            {
                                                $modifier = Modifier::findOne(['id' => $modifier_vw->modifier_id]);

                                                if ($modifier->tax == 0)
                                                {
                                                    $price += $modifier->price;
                                                }

                                                else
                                                {
                                                    $iva = $modifier->price * Yii::$app->params ['tax_percent'];

                                                    $round_iva = (int) round(($modifier->price * Yii::$app->params ['tax_percent']), -2, PHP_ROUND_HALF_UP);

                                                    if($round_iva > $iva)
                                                    {
                                                        $extra_price = $round_iva - $iva;

                                                        $extra_iva = $extra_price * 0.16;

                                                        $price += ($modifier->price * Yii::$app->params ['tax_percent']) + $extra_iva;
                                                    }

                                                    else
                                                    {
                                                        $price += ($modifier->price * Yii::$app->params ['tax_percent']);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        a:
                    }

                    else if (!empty($model["couponHasServices"]))
                    {
                        $service = $model["couponHasServices"][0]['service']['id'];

                        if ($service == $this->service_id)
                        {
                            if ($model["quantity"] > 0)
                            {
                                if ($model["discount"] > 19)
                                {
                                    return $price;
                                }

                                else
                                {
                                    if ($service->tax == 0)
                                    {
                                        $price += $service->price;
                                    }

                                    else
                                    {
                                        $iva = $service->price * Yii::$app->params ['tax_percent'];

                                        $round_iva = (int) round(($service->price * Yii::$app->params ['tax_percent']), -2, PHP_ROUND_HALF_UP);

                                        if($round_iva > $iva)
                                        {
                                            $extra_price = $round_iva - $iva;

                                            $extra_iva = $extra_price * 0.16;

                                            $price += ($service->price * Yii::$app->params ['tax_percent']) + $extra_iva;
                                        }

                                        else
                                        {
                                            $price += ($service->price * Yii::$app->params ['tax_percent']);
                                        }

                                    }

                                    $modifier_vw = VwActualService::findOne(['id' => $this->id]);

                                    if ($modifier_vw->modifier_id != "")
                                    {
                                        $modifier = Modifier::findOne(['id' => $modifier_vw->modifier_id]);

                                        if ($modifier->tax == 0)
                                        {
                                            $price += $modifier->price;
                                        }

                                        else
                                        {
                                            $iva = $modifier->price * Yii::$app->params ['tax_percent'];

                                            $round_iva = (int) round(($modifier->price * Yii::$app->params ['tax_percent']), -2, PHP_ROUND_HALF_UP);

                                            if($round_iva > $iva)
                                            {
                                                $extra_price = $round_iva - $iva;

                                                $extra_iva = $extra_price * 0.16;

                                                $price += ($modifier->price * Yii::$app->params ['tax_percent']) + $extra_iva;
                                            }

                                            else
                                            {
                                                $price += ($modifier->price * Yii::$app->params ['tax_percent']);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        b:
                    }
                }
            }
        }
        else
        {
            if ($service->tax == 0)
            {
                $price += $service->price;
            }

            else
            {
                $iva = $service->price * Yii::$app->params ['tax_percent'];

                $round_iva = (int) round(($service->price * Yii::$app->params ['tax_percent']), -2, PHP_ROUND_HALF_UP);

                if($round_iva > $iva)
                {
                    $extra_price = $round_iva - $iva;

                    $extra_iva = $extra_price * 0.16;

                    $price += ($service->price * Yii::$app->params ['tax_percent']) + $extra_iva;
                }

                else
                {
                    $price += ($service->price * Yii::$app->params ['tax_percent']);
                }

            }

            $modifier_vw = VwActualService::findOne(['id' => $this->id]);

            if ($modifier_vw->modifier_id != "")
            {
                $modifier = Modifier::findOne(['id' => $modifier_vw->modifier_id]);

                if ($modifier->tax == 0)
                {
                    $price += $modifier->price;
                }

                else
                {
                    $iva = $modifier->price * Yii::$app->params ['tax_percent'];

                    $round_iva = (int) round(($modifier->price * Yii::$app->params ['tax_percent']), -2, PHP_ROUND_HALF_UP);

                    if($round_iva > $iva)
                    {
                        $extra_price = $round_iva - $iva;

                        $extra_iva = $extra_price * 0.16;

                        $price += ($modifier->price * Yii::$app->params ['tax_percent']) + $extra_iva;
                    }

                    else
                    {
                        $price += ($modifier->price * Yii::$app->params ['tax_percent']);
                    }
                }
            }
        }

        return $price;

        ////////////////////////////////////////////////////

//        if ($service->tax == 0)
//        {
//            $price += $service->price;
//        }
//
//        else
//        {
//            $iva = $service->price * Yii::$app->params ['tax_percent'];
//
//            $round_iva = (int) round(($service->price * Yii::$app->params ['tax_percent']), -2, PHP_ROUND_HALF_UP);
//
//            if($round_iva > $iva)
//            {
//                $extra_price = $round_iva - $iva;
//
//                $extra_iva = $extra_price * 0.16;
//
//                $price += ($service->price * Yii::$app->params ['tax_percent']) + $extra_iva;
//            }
//
//            else
//            {
//                $price += ($service->price * Yii::$app->params ['tax_percent']);
//            }
//
//        }
//
//        $modifier_vw = VwActualService::findOne(['id' => $this->id]);
//
//        if ($modifier_vw->modifier_id != "")
//        {
//            $modifier = Modifier::findOne(['id' => $modifier_vw->modifier_id]);
//
//            if ($modifier->tax == 0)
//            {
//                $price += $modifier->price;
//            }
//
//            else
//            {
//                $iva = $modifier->price * Yii::$app->params ['tax_percent'];
//
//                $round_iva = (int) round(($modifier->price * Yii::$app->params ['tax_percent']), -2, PHP_ROUND_HALF_UP);
//
//                if($round_iva > $iva)
//                {
//                    $extra_price = $round_iva - $iva;
//
//                    $extra_iva = $extra_price * 0.16;
//
//                    $price += ($modifier->price * Yii::$app->params ['tax_percent']) + $extra_iva;
//                }
//
//                else
//                {
//                    $price += ($modifier->price * Yii::$app->params ['tax_percent']);
//                }
//            }
//        }

//        return $price;
    }

    public function getDuration() {
        
        $duration = 0;
        $service = Service::findOne(['id' => $this->service_id]);
        $duration += $service->duration;

        $modifier_vw = VwActualService::findOne(['id' => $this->id]);
        if ($modifier_vw->modifier_id != "") {
            $modifier = Modifier::findOne(['id' => $modifier_vw->modifier_id]);
            $duration += $modifier->duration;
        }
        return $duration;
    }

    public function getServiceName() {

        $name = "";
        $service = Service::findOne(['id' => $this->service_id]);
        $name .= $service->name;

        $modifier_vw = VwActualService::findOne(['id' => $this->id]);
        if ($modifier_vw->modifier_id != "") {
            $modifier = Modifier::findOne(['id' => $modifier_vw->modifier_id]);
            $name .= " - " . $modifier->name;
        }
        return $name;
    }

    public function getProcessId() {

        $script = "ps -ef | grep '" . $this->id . ".txt' | grep -v grep | awk '{print $2}'";
        return exec($script);
    }

    public function getNumAttempts() {

        $num = 0;
        $model = LogAssignedService::find()
                ->select('max(attempt)')
                ->where(['assigned_service_id' => $this->id, 'date' => $this->date, 'time' => $this->time])
                ->scalar();
        if ($model != null)
            $num = $model;
        return $num;
    }

    public function getModifierId() {

        $modifier = "";
        $modifier_vw = VwActualService::findOne(['id' => $this->id]);

        if ($modifier_vw->modifier_id != "") {
            $modifier = $modifier_vw->modifier_id;
        }
        return $modifier;
    }

    public function beforeDelete() {
        
        if (parent::beforeDelete()) {
            //Eliminamos chat del servicio

            $conv = Conversation::find()->where(['assigned_service_id' => $this->id])->all();
            foreach ($conv as $conversation) {
                Chat::deleteAll(['conversation_id' => $conversation->id]);
            }
            Conversation::deleteAll(['assigned_service_id' => $this->id]);
            return true;
        } else {
            return false;
        }
    }
}
