<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_submit_type".
 *
 * @property integer $submit_type_id
 * @property string $submit_type_name_th
 * @property string $submit_type_name_en
 *
 * @property EgsDocument[] $egsDocuments
 */
class EgsSubmitType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_submit_type';
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
            [['submit_type_id', 'submit_type_name_th', 'submit_type_name_en'], 'required'],
            [['submit_type_id'], 'integer'],
            [['submit_type_name_th', 'submit_type_name_en'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'submit_type_id' => 'Submit Type ID',
            'submit_type_name_th' => 'Submit Type Name Th',
            'submit_type_name_en' => 'Submit Type Name En',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsDocuments()
    {
        return $this->hasMany(EgsDocument::className(), ['submit_type_id' => 'submit_type_id']);
    }
}
