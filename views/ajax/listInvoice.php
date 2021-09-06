<?php
if (isset($result['Invoice'])) {
    if (isset($result['Invoice']['FullName'])) {
        echo $this->render('_invoiceItem', [
            'account' => $result['Invoice']
        ]);
    } else {
        foreach ($result['Invoice'] as $arr) {
            echo $this->render('_invoiceItem', [
                'account' => $arr
            ]);
        }
    }
} else {
    echo '<li><h3>За выбранный период документы отсутствуют.</h3></li>';
}