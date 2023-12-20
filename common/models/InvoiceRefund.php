<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "invoice_refund".
 *
 * @property int $id
 * @property int|null $invoice_id
 * @property int|null $requested_user_id
 * @property int|null $approved_or_declined_user_id
 * @property string|null $approved_or_declined_at
 * @property string|null $approved_or_declined_comment
 * @property string|null $status
 * @property string|null $comments
 * @property string|null $created_at
 */
class InvoiceRefund extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoice_refund';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invoice_id', 'requested_user_id', 'approved_or_declined_user_id'], 'integer'],
            [['approved_or_declined_at', 'created_at'], 'safe'],
            [['approved_or_declined_comment', 'status', 'comments'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'invoice_id' => 'Invoice ID',
            'requested_user_id' => 'Requested User ID',
            'approved_or_declined_user_id' => 'Approved Or Declined User ID',
            'approved_or_declined_at' => 'Approved Or Declined At',
            'approved_or_declined_comment' => 'Approved Or Declined Comment',
            'status' => 'Status',
            'comments' => 'Comments',
            'created_at' => 'Created At',
        ];
    }
}
