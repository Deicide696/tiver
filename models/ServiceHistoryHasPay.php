<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "service_history_has_pay".
 *
 * @property integer $service_history_id
 * @property integer $pay_id
 *
 * @property Pay $pay
 * @property ServiceHistory $serviceHistory
 */
class ServiceHistoryHasPay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service_history_has_pay';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service_history_id', 'pay_id'], 'required'],
            [['service_history_id', 'pay_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'service_history_id' => 'Service History ID',
            'pay_id' => 'Pay ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPay()
    {
        return $this->hasOne(Pay::className(), ['id' => 'pay_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServiceHistory()
    {
        return $this->hasOne(ServiceHistory::className(), ['id' => 'service_history_id']);
    }
}
