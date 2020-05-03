<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_status_label".
 *
 * @property integer $status_label_id
 * @property string $status_label_name
 *
 * @property EgsStatus[] $egsStatuses
 */
class EgsStatusLabel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_status_label';
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
            [['status_label_id', 'status_label_name'], 'required'],
            [['status_label_id'], 'integer'],
            [['status_label_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'status_label_id' => 'Status Label ID',
            'status_label_name' => 'Status Label Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsStatuses()
    {
        return $this->hasMany(EgsStatus::className(), ['status_label_id' => 'status_label_id']);
    }
}
