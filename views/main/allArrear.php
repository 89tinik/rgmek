<?php
/* @var $this yii\web\View */
/* @var $result */


if (isset($result['FullName'])) {
    echo $this->render('_invoiceItem', [
        'invoice' => $result['Account']
    ]);
} else {
    foreach ($result as $arr) {
        echo $this->render('_invoiceItem', [
            'invoice' => $arr
        ]);
    }
}


