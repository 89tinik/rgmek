<?php
/* @var $this yii\web\View */
/* @var $result */

$this->title = 'Действующие договоры, объекты, приборы учета | ЛК РГМЭК';
?>

<div class="page-heading">
    <div class="breadcrumbs">
        <strong>Действующие объекты и приборы учета</strong><span class="sep"></span>
        <span><?= (isset($result['Object']['NameContracts'])) ? $result['Object']['NameContracts'] :  $result['Object'][0]['NameContracts']; ?></span>
    </div>
</div>

<div class="contracts-items">

    <?php
    if (isset($result['Object']['Name'])) {
        echo $this->render('_objectItem', [
            'object' => $result['Object']
        ]);
    } else {
        foreach ($result['Object'] as $arr) {
            echo $this->render('_objectItem', [
                'object' => $arr
            ]);
        }
    }
    ?>

</div>
