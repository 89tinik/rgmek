<?php
if (isset($result['Act'])) {
    if (isset($result['Act']['Number'])) {
        echo $this->render('_aktppItem', [
            'act' => $result['Act']
        ]);
    } else {
        foreach ($result['Act'] as $arr) {
            echo $this->render('_aktppItem', [
                'act' => $arr
            ]);
        }
    }
} else {
    echo '<li><h3>За выбранный период документы отсутствуют.</h3></li>';
}