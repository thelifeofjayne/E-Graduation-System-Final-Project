<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_action_type".
 *
 * @property integer $action_type_id
 * @property string $action_type_name_th
 * @property string $actiont_type_name_en
 *
 * @property EgsAction[] $egsActions
 */
class EgsActionType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_action_type';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_egs');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['action_type_id', 'action_type_name_th', 'actiont_type_name_en'], 'required'],
            [['action_type_id'], 'integer'],
            [['action_type_name_th', 'actiont_type_name_en'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'action_type_id' => 'Action Type ID',
            'action_type_name_th' => 'Action Type Name Th',
            'actiont_type_name_en' => 'Actiont Type Name En',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsActions()
    {
        return $this->hasMany(EgsAction::className(), ['action_type_id' => 'action_type_id']);
    }
}
