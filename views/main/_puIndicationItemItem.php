<?php

/* @var $pu */

use yii\helpers\Html;
?>
<div class="sub-objects-item collapse-item no-result wrap-pu">
    <div class="sub-objects-btn collapse-btn">
        <?=$pu['FullName']?>
        <!--span class="tip" style="display:none;">Показания переданы объем <span class="result-pu"></span>кВтч</span-->
    </div>
    <div class="sub-objects-content collapse-content" style="display: none;">
        <div class="info">
            <div class="label  consumed-wrap" style="display: none">Показания сохранены объем <span><span  class="result-pu"></span> кВтч</span></div>
            <div class="notice">Срок проверки счетчика <?=$pu['VerificationYear']?>г.</div>
            <a href="#" class="btn border">История показаний</a>
        </div>
        <div class="testimony-box white-box">
            <div class="cols">
                <div class="col">
                    <?php
                    if(!empty($pu['Indications'])){
                        $indicationArr = explode(',', $pu['Indications']);
                        $outputOld = '';
                        $outputCurrent = '';

                        for ($i=0;$i<$pu['Discharge']-strlen($indicationArr[0]);$i++){
                            $outputOld .= '<input type="text" class="inputs old-num" value="0" maxlength="1" />';
                            $outputCurrent .= '<input type="text" class="inputs curr-num" maxlength="1"  />';
                        }

                        foreach(str_split($indicationArr[0]) as $n){
                            $outputOld .= '<input type="text" class="inputs old-num" value="'.$n.'" maxlength="1"  />';
                            $outputCurrent .= '<input type="text" class="inputs curr-num" maxlength="1"  />';
                        }

                    } else {
                        for ($i=0;$i<$pu['Discharge'];$i++){
                            $outputOld .= '<input type="text" class="inputs old-num" value="0" maxlength="1" />';
                            $outputCurrent .= '<input type="text" class="inputs curr-num" maxlength="1"  />';
                        }
                    }
                    ?>
                    <div class="group disable">
                        <?php if(!empty($pu['DateInd'])):?>
                        <div class="label">Начальное показание от <?=$pu['DateInd']?> </div>
                        <?php endif;?>
                        <div class="field">
                            <?=$outputOld;?>
                        </div>
                    </div>
                    <div class="group">
                        <div class="label">Введите показания от <?=date('d.m.Y')?></div>
                        <div class="field">
                            <?=$outputCurrent;?>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="ratio-value">Коэффициент трансформации: <?=$pu['KTT']?></div>
                    <div class="mounth-value consumed-wrap" style="display: none">
                        Потреблено за месяц:
                        <div class="value"><strong class="result-pu">120</strong> кВтч</div>
                        <em>(с учетом коифицентом трансформации без учёта транзитных ПУ и потерь)</em>
                    </div>
                </div>
                <div class="col">
                    <div class="wrap-error">
                        <div class="label-error" style="display: none">Не заполнено текущее показание!</div>
                    </div>
                    <div class="bts">
                        <a href="#" class="btn submit-btn left computation-pu" data-k="<?=$pu['KTT']?>">Расчитать</a>
                        <!--a href="#" class="attach-lnk"></a-->
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>