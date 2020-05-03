<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_status".
 *
 * @property integer $status_id
 * @property string $status_name_th
 * @property string $status_name_en
 * @property integer $status_label_id
 *
 * @property EgsDefense[] $egsDefenses
 * @property EgsDefenseDocument[] $egsDefenseDocuments
 * @property EgsRequestDocument[] $egsRequestDocuments
 * @property EgsStatusLabel $statusLabel
 * @property EgsUserRequest[] $egsUserRequests
 */
class EgsStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_status';
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
            [['status_id', 'status_name_th', 'status_name_en', 'status_label_id'], 'required'],
            [['status_id', 'status_label_id'], 'integer'],
            [['status_name_th', 'status_name_en'], 'string', 'max' => 255],
            [['status_label_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsStatusLabel::className(), 'targetAttribute' => ['status_label_id' => 'status_label_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'status_id' => 'Status ID',
            'status_name_th' => 'Status Name Th',
            'status_name_en' => 'Status Name En',
            'status_label_id' => 'Status Label ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsDefenses()
    {
        return $this->hasMany(EgsDefense::className(), ['defense_status_id' => 'status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsDefenseDocuments()
    {
        return $this->hasMany(EgsDefenseDocument::className(), ['defense_document_status_id' => 'status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsRequestDocuments()
    {
        return $this->hasMany(EgsRequestDocument::className(), ['request_document_status_id' => 'status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusLabel()
    {
        return $this->hasOne(EgsStatusLabel::className(), ['status_label_id' => 'status_label_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsUserRequests()
    {
        return $this->hasMany(EgsUserRequest::className(), ['request_fee_status_id' => 'status_id']);
    }
}
