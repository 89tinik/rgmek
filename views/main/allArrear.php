<?php
/* @var $this yii\web\View */
/* @var $result */


if (isset($result['FullName'])) {
    echo $this->render('_invoiceItem', [
        'invoice' => $result['Contract']
    ]);
} else {
    foreach ($result as $arr) {
        echo $this->render('_invoiceItem', [
            'invoice' => $arr
        ]);
    }
}


