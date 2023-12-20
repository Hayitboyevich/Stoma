<?php

namespace common\models\rbac;

use common\models\DoctorPatient;
use common\models\Invoice;
use common\models\Reception;
use Yii;
use yii\rbac\Rule;

/**
 * Checks if authorID matches user passed via params
 */
class ViewInvoiceDetails extends Rule
{
    public $name = 'viewInvoiceDetails';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        if(Yii::$app->user->can('reception') || Yii::$app->user->can('admin') || Yii::$app->user->can('cashier') || Yii::$app->user->can('director'))
            return true;

        $invoice_id = Yii::$app->request->get('id');
        $doctor_patient = Invoice::findOne(['doctor_id' => Yii::$app->user->identity->id,'id' => $invoice_id]);
        return (bool)$doctor_patient;
    }
}