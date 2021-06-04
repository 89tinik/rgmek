<?php
/* @var $this yii\web\View */
/* @var $result */

$this->title = 'Передать показания | ЛК РГМЭК';
?>
<div class="page-heading">
    <div class="breadcrumbs">
        <strong>Передать показания приборов учета</strong><span class="sep"></span>
        <span>Договор <span class="name-sidebar"></span></span>
    </div>
</div>

<div class="objects-items uid-d"  data-uid="<?=\Yii::$app->user->identity->id_db?>">

    <?php
    if (isset($result['Object'])){
        if (isset($result['Object']['Name'])){
            echo $this->render('_objectIndicationItem', [
                'object' => $result['Object']
            ]);
        } else {
            foreach ($result['Object'] as $arr) {
                echo $this->render('_objectIndicationItem', [
                    'object' => $arr
                ]);
            }
        }

    }
    ?>


</div>
