<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "assigned_service".
 *
 * @property integer $id
 * @property string $address
 * @property integer $state
 * @property string $date
 * @property string $time
 * @property double $lat
 * @property double $lng
 * @property string $comment
 * @property string $created_date
 * @property integer $service_id
 * @property integer $user_id
 * @property integer $city_id
 * @property integer $expert_id
 * @property integer $coupon_id
 *
 * @property City $city
 * @property Coupon $coupon
 * @property Expert $expert
 * @property Service $service
 * @property User $user
 * @property AssignedServiceHasModifier[] $assignedServiceHasModifiers
 * @property CompletedService[] $completedServices
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
                [['state', 'service_id', 'user_id', 'city_id', 'expert_id', 'coupon_id'], 'integer'],
                [['date', 'time', 'created_date'], 'safe'],
                [['lat', 'lng'], 'number'],
                [['address'], 'string', 'max' => 100],
                [['comment'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'address' => 'Dirección',
            'state' => 'Activo',
            'date' => 'Fecha',
            'time' => 'Hora',
            'comment' => 'Comentario',
            'lat' => 'Lat',
            'lng' => 'Lng',
            'created_date' => 'Fecha de asignación',
            'service_id' => 'Service ID',
            'user_id' => 'User ID',
            'city_id' => 'City ID',
            'expert_id' => 'Expert ID',
            'coupon_id' => 'Coupon ID',
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

    public function getExpertName() {
        $expert = Expert::findOne(['id' => $this->expert_id]);
        return $expert->name . " " . $expert->last_name;
    }

    public function getUserName() {
        $user = User::findOne(['id' => $this->user_id]);
        return $user->first_name . " " . $user->last_name;
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
        return $this->hasOne(AssignedServiceHasModifier::className(), ['assigned_service_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompletedServices() {
        return $this->hasMany(CompletedService::className(), ['assigned_service_id' => 'id']);
    }
    /**
     * 
     * @return type
     */

    public function getPrice() {

        $price = 0;
        $service = Service::findOne(['id' => $this->service_id]);

        if ($service->tax == 0) {
            $price += $service->price;
        } else {
            $price += round($service->price + ($service->price * Yii::$app->params ['tax_percent']), -2, PHP_ROUND_HALF_UP);
        }
       
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand(Yii::$app->params ['vw_actual_service'],[':user_id' => '',':id' => $this->id]);
        $modifier_vw = $command->queryAll();
//      $modifier_vw = VwActualService::findOne(['id' => $this->id]);
        
        if (isset($modifier_vw['modifier_id']) && !empty($modifier_vw['modifier_id'])) {
            $modifier = Modifier::findOne(['id' => $modifier_vw['modifier_id']]);
            if ($modifier->tax == 0) {
                $price += $modifier->price;
            } else {
                $price += (int) round($modifier->price + ($modifier->price * Yii::$app->params ['tax_percent']), -2, PHP_ROUND_HALF_UP);
            }
        }
        return $price;
    }

    public function getTax() {
        
        $price = 0;
        $service = Service::findOne(['id' => $this->service_id]);

        if ($service->tax == 0){
            $price += $service->price;
        }
        else{
            $price += ($service->price * Yii::$app->params ['tax_percent']);
        }
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand(Yii::$app->params ['vw_actual_service'],[':user_id' => $this->user_id,':id' => $this->id]);
        $modifier_vw = $command->queryAll();
        
        if ($modifier_vw[0]['modifier_id'] != "") {
            $modifier = Modifier::findOne(['id' => $modifier_vw[0]['modifier_id']]);
            if ($modifier->tax == 0){
                $price += $modifier->price;
            } else {
                    $price += ($modifier->price * Yii::$app->params ['tax_percent']);
            }
        }
        return $price;
    }

    public function getDuration() {
        
        $duration = 0;
        $service = Service::findOne(['id' => $this->service_id]);
        $duration += $service->duration;

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand(Yii::$app->params ['vw_actual_service'],[':user_id' => '',':id' => $this->id]);
        $modifier_vw = $command->queryAll();
//      $modifier_vw = VwActualService::findOne(['id' => $this->id]);
        
        if (isset($modifier_vw['modifier_id']) && !empty($modifier_vw['modifier_id'])) {
            $modifier = Modifier::findOne(['id' => $modifier_vw['modifier_id']]);
            $duration += $modifier->duration;
        }
        return $duration;
    }

    public function getServiceName() {
        
        $name = "";
        $service = Service::findOne(['id' => $this->service_id]);
        $name .= $service->name;
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand(Yii::$app->params ['vw_actual_service'],[':user_id' => '',':id' => $this->id]);
        $modifier_vw = $command->queryAll();
//      $modifier_vw = VwActualService::findOne(['id' => $this->id]);
        
        if (isset($modifier_vw['modifier_id']) && !empty($modifier_vw['modifier_id'])) {
            $modifier = Modifier::findOne(['id' => $modifier_vw['modifier_id']]);
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
        $model = LogAssignedService::find()->select('max(attempt)')->where(['assigned_service_id' => $this->id, 'date' => $this->date, 'time' => $this->time])->scalar();
        if ($model != null)
            $num = $model;
        return $num;
    }

    public function getModifierId() {
        
        $modifier = "";
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand(Yii::$app->params ['vw_actual_service'],[':user_id' => '',':id' => $this->id]);
        $modifier_vw = $command->queryAll();
//      $modifier_vw = VwActualService::findOne(['id' => $this->id]);
        
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
