<?php

use yii\helpers\Html;

$this->title = 'Переход в ИСУ |  ЛК РГМЭК';
?>
<div class="page-heading">
    <div class="breadcrumbs">
        <strong>Переход в ИСУ</strong><span class="sep"></span>
        <span>Договор <?= $this->context->currentContract; ?></span>
    </div>
</div>

<style>
.isu-item .title {
    margin-bottom: 20px;
    font-weight: 600;
    font-size: 20px;
    line-height: 28px;
    color: #1C2834;
}
@media screen and (max-width: 1200px){
	.isu-item .title{
		font-size: 16px;
		line-height: 24px;
	}
	.isu-item .border-box .btn{
		display:block;
	}
}
</style>

<div class="isu-items">
    <div class="isu-item">

		<div class="border-box">
            <div class="title">Объект 001, Нежилое помещение, Островского, 34, корп.1 (Н1)</div>
            <div class="bts">
                <a href="#" class="btn small border">Переход в ИСУ 1</a>
				<a href="#" class="btn small border">Переход в ИСУ 2</a>
				<a href="#" class="btn small border">Переход в ИСУ 3</a>
                <div class="clear"></div>
            </div>
        </div>
        
        <div class="border-box">
            <div class="title">Объект 001, Нежилое помещение, Островского, 34, корп.1 (Н1)</div>
            <div class="bts">
                <a href="#" class="btn small border">Переход в ИСУ 1</a>
				<a href="#" class="btn small border">Переход в ИСУ 2</a>
				<a href="#" class="btn small border">Переход в ИСУ 3</a>
                <div class="clear"></div>
            </div>
        </div>
        
    </div>

</div>