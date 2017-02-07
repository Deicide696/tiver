<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "service_history".
 *
 * @property integer $id
 * @property string $address
 * @property integer $state
 * @property string $date
 * @property string $time
 * @property double $lat
 * @property double $lng
 * @property string $comment
 * @property string $qualification
 * @property string $description
 * @property string $observations
 * @property integer $immediate
 * @property string $created_date
 * @property integer $expert_id
 * @property integer $cancel_reason_id
 * @property integer $problem_type_id
 * @property integer $service_id
 * @property integer $user_id
 * @property integer $coupon_id
 * @property integer $assigned_service_id
 *
 * @property TypeCancel $cancelReason
 * @property Coupon $coupon
 * @property TypeProblem $problemType
 * @property Expert $expert
 * @property Service $service
 * @property User $user
 * @property ServiceHistoryHasModifier[] $serviceHistoryHasModifiers
 * @property Modifier[] $modifiers
 * @property ServiceHistoryHasPay[] $serviceHistoryHasPays
 * @property Pay[] $pays
 */
class ServiceHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'service_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['state', 'immediate', 'expert_id', 'cancel_reason_id', 'problem_type_id', 'service_id', 'user_id', 'coupon_id', 'assigned_service_id'], 'integer'],
                [['date', 'time', 'created_date'], 'safe'],
                [['lat', 'lng'], 'number'],
                [['expert_id', 'service_id', 'user_id'], 'required'],
                [['address', 'description'], 'string', 'max' => 100],
                [['comment', 'observations'], 'string', 'max' => 255],
                [['qualification'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'address' => 'Address',
            'state' => 'State',
            'date' => 'Date',
            'time' => 'Time',
            'lat' => 'Lat',
            'lng' => 'Lng',
            'comment' => 'Comment',
            'qualification' => 'Qualification',
            'description' => 'Description',
            'observations' => 'Observations',
            'immediate' => 'Immediate',
            'created_date' => 'Created Date',
            'expert_id' => 'Expert ID',
            'cancel_reason_id' => 'Cancel Reason ID',
            'problem_type_id' => 'Problem Type ID',
            'service_id' => 'Service ID',
            'user_id' => 'User ID',
            'coupon_id' => 'Coupon ID',
            'assigned_service_id' => 'Assigned Service ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCancelReason() {
        return $this->hasOne(TypeCancel::className(), ['id' => 'cancel_reason_id']);
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
    public function getProblemType() {
        return $this->hasOne(TypeProblem::className(), ['id' => 'problem_type_id']);
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
    public function getServiceHistoryHasModifier() {
        return $this->hasMany(ServiceHistoryHasModifier::className(), ['service_history_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModifier() {
        return $this->hasMany(Modifier::className(), ['id' => 'modifier_id'])->viaTable('service_history_has_modifier', ['service_history_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServiceHistoryHasPay() {
        return $this->hasOne(ServiceHistoryHasPay::className(), ['service_history_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPay() {
        return $this->hasMany(Pay::className(), ['id' => 'pay_id'])->viaTable('service_history_has_pay', ['service_history_id' => 'id']);
    }

   public function getPrice(){
    	$value=0;
    	$service=Service::findOne(['id'=>$this->service_id]);
    	if($service->tax==0)
    	$value+=$service->price;
    	else 
    		$value+=$service->price+($service->price*Yii::$app->params ['tax_percent']);
    
    	$modifier_vw=VwServiceHistory::findOne(['id'=>$this->id]);
    	if($modifier_vw->modifier_id!=""){
    		$modifier=Modifier::findOne(['id'=>$modifier_vw->modifier_id]);
    		if($modifier->tax==0)
  			$value+=$modifier->price;
  			else 
  			$value+=$modifier->price+($modifier->price*Yii::$app->params ['tax_percent']);
  	
    	}
    
    	//var_dump($modifier);
    
    		return round($value, -2, PHP_ROUND_HALF_UP);
    	
    	 
    }

    public function getLastPay() {
        return Pay::find()
                ->joinWith('serviceHistoryHasPay')
                ->where(['service_history_id' => $this->id])
                ->orderBy(['pay.created_date' => SORT_ASC])
                ->one();
    }

    public function getDuration(){
    	$duration=0;
    	$service=Service::findOne(['id'=>$this->service_id]);
    	$duration+=$service->duration;
    
    	$modifier_vw=VwServiceHistory::findOne(['id'=>$this->id]);
    	if($modifier_vw->modifier_id!=""){
    		$modifier=Modifier::findOne(['id'=>$modifier_vw->modifier_id]);
    		$duration+=$modifier->duration;
    	}
    
    	//var_dump($modifier);
    
    	return $duration;
    
    }
    public function getServiceName(){
    	$name="";
    	$service=Service::findOne(['id'=>$this->service_id]);
    	$name.=$service->name;
    
    	$modifier_vw=VwServiceHistory::findOne(['id'=>$this->id]);
    	if($modifier_vw->modifier_id!=""){
    		$modifier=Modifier::findOne(['id'=>$modifier_vw->modifier_id]);
    		$name.=" - ".$modifier->name;
    	}
    
    	//var_dump($modifier);
    
    	return $name;
    
    }

    public function getExpertName() {
        $expert = Expert::findOne(['id' => $this->expert_id]);
        return $expert->name . " " . $expert->last_name;
    }

    public function getUserName() {
        $user = User::findOne(['id' => $this->user_id]);
        return $user->first_name . " " . $user->last_name;
    }

    public function getPayStatus() {
        $status = $this->getLastPay();
        if ($status != null) {
            return $status->state;
        } else
            return 0;
    }

    public function getPayMessage() {
        $status = $this->getLastPay();
        if ($status != null) {
            return $status->message;
        } else
            return'';
    }

}
