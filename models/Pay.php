<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pay".
 *
 * @property integer $id
 * @property integer $state
 * @property integer $value
 * @property string $message
 * @property string $hash
 * @property string $created_date
 *
 * @property ServiceHistoryHasPay[] $serviceHistoryHasPays
 * @property ServiceHistory[] $serviceHistories
 */
class Pay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pay';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['state', 'value'], 'integer'],
            [['hash'], 'required'],
            [['created_date'], 'safe'],
            [['message', 'hash'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'state' => 'Pagado',
            'value' => 'Valor',
            'message' => 'Mensaje TPaga',
            'hash' => 'Hash',
            'created_date' => 'Created Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServiceHistoryHasPay()
    {
        return $this->hasMany(ServiceHistoryHasPay::className(), ['pay_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServiceHistories()
    {
        return $this->hasMany(ServiceHistory::className(), ['id' => 'service_history_id'])->viaTable('service_history_has_pay', ['pay_id' => 'id']);
    }
}
