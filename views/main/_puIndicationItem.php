<?php

/* @var $pu */

/* @var $onePU */
/* @var $model*/

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="sub-objects-item collapse-item no-result wrap-pu <?= ($onePU) ? 'active' : '' ?>"
     data-id="<?= $pu['UIDTU'] ?>" data-puid="<?= $pu['UIDPU'] ?>" data-k="<?= $pu['KTT'] ?>">
    <div class="sub-objects-btn collapse-btn">
        <?= $pu['FullName'] ?>
        <!--span class="tip" style="display:none;">Показания переданы объем <span class="result-pu"></span>кВтч</span-->
    </div>
    <div class="sub-objects-content collapse-content" style="display: <?= ($onePU) ? 'block' : 'none' ?>;">
        <div class="info">
            <div class="label  consumed-wrap" style="display: none">Показание сохранено, объем <span><span
                            class="result-pu"></span> кВтч</span></div>
            <div class="notice">Срок поверки счетчика <?= $pu['VerificationYear'] ?>г.</div>
            <a href="#" class="btn border">История показаний</a>
        </div>
        <div class="testimony-box white-box">
            <div class="cols">
                <div class="col">
                    <?php
                    //                    if(!empty($pu['Indications'])){
                    //                        $indicationArr = explode(',', $pu['Indications']);
                    //                        $outputOld = '';
                    //                        $outputCurrent = '';
                    //
                    //                        for ($i=0;$i<$pu['Discharge']-strlen($indicationArr[0]);$i++){
                    //                            $outputOld .= '<input type="text" class="inputs old-num" value="0" maxlength="1" />';
                    //                            $outputCurrent .= '<input type="text" class="inputs curr-num" maxlength="1"  inputmode="numeric"/>';
                    //                        }
                    //
                    //                        foreach(str_split($indicationArr[0]) as $n){
                    //                            $outputOld .= '<input type="text" class="inputs old-num" value="'.$n.'" maxlength="1"  />';
                    //                            $outputCurrent .= '<input type="text" class="inputs curr-num" maxlength="1"   inputmode="numeric"/>';
                    //                        }
                    //
                    //                    } else {
                    //                        for ($i=0;$i<$pu['Discharge'];$i++){
                    //                            $outputOld .= '<input type="text" class="inputs old-num" value="0" maxlength="1" />';
                    //                            $outputCurrent .= '<input type="text" class="inputs curr-num" maxlength="1"   inputmode="numeric"/>';
                    //                        }
                    //                    }
                    //                    ?>
                    <?php

                    //$pu['Discharge']=8;
                    if (!empty($pu['Indications'])) {
                        $indicationArr = explode(',', $pu['Indications']);
                        $oldValue = '';
                        for ($i = 0; $i < $pu['Discharge'] - strlen($indicationArr[0]); $i++) {
                            $outputOld .= '0';
                        }
                        $outputOld .= $indicationArr[0];
                    }

                    ?>


                    <div class="group disable">
                        <?php if (!empty($pu['DateInd'])): ?>
                            <div class="label">Начальное показание от <?= $pu['DateInd'] ?> </div>
                        <?php endif; ?>
                        <div class="field co-<?= $pu['Discharge'] ?>">
                            <input type="text" maxlength="<?= $pu['Discharge'] ?>" value="<?= $outputOld ?>"
                                   class="old-val indication">
                        </div>
                    </div>
                    <div class="group">
                        <div class="label">Введите показание от <?= date('d.m.Y') ?></div>
                        <div class="field co-<?= $pu['Discharge'] ?>">
                            <div class="wrap-error">
                                <div class="label-error" style="display: none">Не заполнено текущее показание!</div>
                            </div>
                            <input type="text" maxlength="<?= $pu['Discharge'] ?>" value="" class="curr-val indication">
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="ratio-value">Коэффициент трансформации: <?= $pu['KTT'] ?></div>
                    <div class="mounth-value consumed-wrap" style="display: none">
                        Потреблено за месяц:
                        <div class="value"><strong class="result-pu">120</strong> кВтч</div>
                        <em>(С учётом коэффициента трансформации, без учёта ПУ и потерь)</em>
                    </div>
                </div>
                <div class="col">

                    <div class="bts attach-form">
                        <!--                        <a href="#" class="btn submit-btn left computation-pu">Расчитать</a>-->
                        <!--a href="#" class="attach-lnk"></a-->




                            <label class="btn  left computation-pu" for="attachform-photo">Фото
                                счётчика</label>

                            <label class="btn  left computation-pu <?=($pu['DisplayHV'] == 'Нет' ? 'disabled' : '')?>" for="attachform-time">Почасовые
                                объемы</label>
                            <button type="submit" class="btn submit-btn left computation-pu">Отправить</button>
                            <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>