<?php
if (isset($result['Penalty'])) {
    if (isset($result['Penalty']['Number'])) {
        echo $this->render('_invoiceItem', [
            'penalty' => $result['Penalty']
        ]);
    } else {
        foreach ($result['Penalty'] as $arr) {
            echo $this->render('_penaltyItem', [
                'penalty' => $arr
            ]);
        }
    }
} else {
    echo '<li><h3>За выбранный период документы отсутствуют.</h3></li>';
}