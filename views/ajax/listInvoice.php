<?php
if (isset($result['Account'])) {
    if (isset($result['Account']['FullName'])) {
        echo $this->render('_invoiceItem', [
            'account' => $result['Account']
        ]);
    } else {
        foreach ($result['Account'] as $arr) {
            echo $this->render('_invoiceItem', [
                'account' => $arr
            ]);
        }
    }
} else {
    echo '<li><h3>За выбранный период документы отсутствуют.</h3></li>';
}