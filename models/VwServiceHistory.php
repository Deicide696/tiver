<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_service_history".
 *
 * @property integer $id
 * @property string $date
 * @property string $time
 * @property string $user_id
 * @property string $address
 * @property string $name
 * @property string $last_name
 * @property string $service_id
 * @property integer $status
 * @property string $expert_id
 * @property string $phone
 * @property double $lat
 * @property double $lng
 * @property string $comment
 * @property string $modifier_id
 * @property string $category_id
 */
class VwServiceHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vw_service_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'service_id', 'status', 'expert_id', 'phone', 'modifier_id', 'category_id'], 'integer'],
            [['date', 'time'], 'safe'],
            [['user_id', 'name', 'last_name', 'service_id', 'expert_id', 'phone'], 'required'],
            [['lat', 'lng'], 'number'],
            [['address', 'name', 'last_name'], 'string', 'max' => 100],
            [['comment'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'date' => Yii::t('app', 'Date'),
            'time' => Yii::t('app', 'Time'),
            'user_id' => Yii::t('app', 'User ID'),
            'address' => Yii::t('app', 'Address'),
            'name' => Yii::t('app', 'Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'service_id' => Yii::t('app', 'Service ID'),
            'status' => Yii::t('app', 'Status'),
            'expert_id' => Yii::t('app', 'Expert ID'),
            'phone' => Yii::t('app', 'Phone'),
            'lat' => Yii::t('app', 'Lat'),
            'lng' => Yii::t('app', 'Lng'),
            'comment' => Yii::t('app', 'Comment'),
            'modifier_id' => Yii::t('app', 'Modifier ID'),
            'category_id' => Yii::t('app', 'Category ID'),
        ];
    }
}
