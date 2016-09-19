<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "assigned_service_has_modifier".
 *
 * @property integer $assigned_service_id
 * @property integer $modifier_id
 * @property integer $item_modifier_id
 *
 * @property AssignedService $assignedService
 * @property ItemModifier $itemModifier
 * @property Modifier $modifier
 */
class AssignedServiceHasModifier extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'assigned_service_has_modifier';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['assigned_service_id', 'modifier_id'], 'required'],
            [['assigned_service_id', 'modifier_id', 'item_modifier_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'assigned_service_id' => 'Assigned Service ID',
            'modifier_id' => 'Modifier ID',
            'item_modifier_id' => 'Item Modifier ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssignedService()
    {
        return $this->hasOne(AssignedService::className(), ['id' => 'assigned_service_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemModifier()
    {
        return $this->hasOne(ItemModifier::className(), ['id' => 'item_modifier_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModifier()
    {
        return $this->hasOne(Modifier::className(), ['id' => 'modifier_id']);
    }
}
