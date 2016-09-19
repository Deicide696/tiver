<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "category_service".
 *
 * @property integer $id
 * @property string $description
 * @property boolean $status
 * @property string $icon
 */
class CategoryService extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category_service';
    }

    /**
     * @inheritdoc
     */
    public function getService(){
    	return $this->hasMany(Service::className(), ['category_service_id' => 'id']);
    }
    
    public function rules()
    {
        return [
            [['description'], 'required'],
            [['status'], 'boolean'],
            [['description'], 'string', 'max' => 100],
            [['icon'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Categoría',
            'status' => 'Activo',
            'icon' => 'Ícono en App',
        ];
    }
    
    //public $status = true;
    
}
