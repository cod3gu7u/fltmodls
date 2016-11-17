<?php

namespace app\models;

use Yii;

class GLPosting extends Posting
{
    const RETURN_JOURNAL_TYPE_ID = 3; // Return Journal
    const SALES_JOURNAL_TYPE_ID = 4; // Sales Journal
    const INVOICE_TRNX_TYPE_ID = 2; // Invoice
    const RETURN_TRNX_TYPE_ID = 3; // Return
    const QUANTITY = 1; // Default quantity
    const POSTING_STATUS = 0; // Unposted journal
    const ASSET_TYPE = 2; // Pula account
    const INSTOCK = 2;
    const VEHICLE_RESERVED_STATUS_ID = 5;
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';


    /**
    * Create a new batch each day.
    * @return int batch_id
    */
    public function createBatch($batch_prefix = '')
    {
        // Today's batch
        $batch_name = $batch_prefix . date('Ymd') . '-' . \Yii::$app->user->identity->id;
        $batch = Batch::findOne(['batch_name' => $batch_name]);
        if($batch === null){
            $batch = new Batch();
            $batch->loadDefaultValues();
            $batch->batch_name = $batch_name;
            $batch->batch_date = date('Y-m-d');
            $batch->save();
        }
        return $batch->batch_id;
    }

    /**
    * Create a new journal
    * @param [$batch_id, $journal_type_id]
    * @return Journal
    * @throws NotFoundHttpException
    */
    public function createJournal($params)
    {
        if($params !== null){
            $journal = new Journal();
            $journal->loadDefaultValues();
            $journal->batch_id = $params['batch_id'];
            $journal->journal_type_id = $params['journal_type_id'];
            $journal->save();
            return $journal->journal_id;
        }else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
    *
    */
    public function getDebitCreditAccounts($sales_trnx_type)
    {
        //get current debit and credit account
        $sales_transaction_type = SalesTransactionType::findOne([
            'sales_transaction_type_id'=>$sales_trnx_type]);
        if($sales_transaction_type !== null){
            return $sales_transaction_type;
        }else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
    * Populate Journal
    * $sales_transaction_type_id debit_account_id/credit_account_id
    * @param [$journal_id. $trnx_type_id, $amount]
    * @return boolean success
    */
    public function populateJournal($params)
    {
        if($params){
            $posting = new Posting();
            $posting->loadDefaultValues();
            $posting->journal_id = $params['journal_id'];
            $posting->account_id = $params['account_id'];
            $posting->quantity = Posting::QUANTITY;
            $posting->unit_amount = $params['unit_amount'];
            $posting->total_amount = ($params['unit_amount'] * Posting::QUANTITY);
            $posting->posting_status = Posting::POSTING_STATUS;
            $posting->asset_type_id = Posting::ASSET_TYPE;
            $posting->accounting_period_id = $params['accounting_period_id'];
            $posting->journal_owner_id = $params['create_user_id'];
            return $posting->save();
        }else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}