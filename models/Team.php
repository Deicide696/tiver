<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "team".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $enable
 * @property string $created_date
 * @property string $updated_date
 */
class Team extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'team';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['enable'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
            [['name'], 'string', 'max' => 45],
            [['name'], 'unique', 'message' => 'Este grupo ya se encuentra en nuestros registros, cree uno nuevo o actívelo  si ya existe (Super-admin).'],
            [['description'], 'string', 'max' => 200],
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
            'description' => Yii::t('app', 'Description'),
            'enable' => Yii::t('app', 'Enable'),
            'created_date' => Yii::t('app', 'Created Date'),
            'updated_date' => Yii::t('app', 'Updated Date'),
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
       ];
    }
}
