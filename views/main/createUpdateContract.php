<?php

use app\models\DraftContract;
use yii\helpers\Html;

/* @var $draftContract app\models\DraftContract */
/* @var $draftDelContract app\models\DraftDelContract */
/* @var $draftGhagContract app\models\DraftGhagContract */

?>

<div class="main-index">

    <h1>Выберите действие</h1>

    <div class="buttons">
        <?= Html::a(
            DraftContract::TITLE.
            ($draftContract ? '(черновик)' : ''),
            $draftContract ? ['draft-contract/update', 'id' => $draftContract->id] : ['draft-contract/create'],
            ['class' => 'btn  border contract-btn']
        ) ?>
        <?= Html::a(
            'Сформировать соглашение об изменении цены действующего контракта (договора)'.
            ($draftContract1 ? '(черновик)' : ''),
            $draftContract1 ? ['draft-contract/update', 'id' => $draftContract->id] : ['draft-contract/create'],
            ['class' => 'btn  border contract-btn']
        ) ?>
        <?= Html::a(
            ' Сформировать соглашение о расторжении действующего контракта (договора)'.
            ($draftContract2 ? '(черновик)' : ''),
            $draftContract3 ? ['draft-contract/update', 'id' => $draftContract->id] : ['draft-contract/create'],
            ['class' => 'btn  border contract-btn']
        ) ?>


    </div>

</div>
