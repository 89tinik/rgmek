<?php
   /*use yii\helpers\Html;*/
   
   $this->title = 'Технологическое присоединение |  ЛК РГМЭК';?>
<div class="page-heading">
   <div class="breadcrumbs">
      <strong>Технологическое присоединение</strong><span class="sep"></span>
      <span>Договор <?= $this->context->currentContract; ?></span><span style="color:red;"> (соглашение к договору на стадии заключения)</span>
   </div>
</div>
<style>
   .tehpris p{
		color:#111;
   }
   .tehpris ul li{
       color:#111;
   }
   .tehpris table tbody tr td{
       padding:10px;
       color:#111;
   }
   .tehpris table tbody tr:nth-child(odd){
		background: #e9ebf5;
	}
	.tehpris table tbody tr:nth-child(even){
		background: #cfd5ea;
	}
   @media screen and (max-width: 1200px){
       .tehpris .sub-objects-items{
           margin:0;
       }
       .tehpris .objects-more{
           display:block;
       }
       .tehpris table tbody tr td{
           float:left;
       }
   }
</style>
<div class="tehpris">
   <p>Порядок заключения договора энергоснабжения до завершения процедуры технологического присоединения описан в разделе <a href="https://www.rgmek.ru/business-clients/contracts.html">Как заключить / изменить договор</a></p>
   <p>В личном кабинете размещаются документы по заявкам юридических лиц и индивидуальных предпринимателей поданным после 01.07.2022,  на технологическое присоединение к электрическим сетям энергопринимающих устройств, максимальная мощность которых не превышает 150 кВт, а категория надежности – не выше второй (<a href="">п.39 (3) Основных положений функционирования розничных рынков электрической энергии (утв. Постановлением Правительства РФ от 04.05.2012 г.№442)</a>)</p>
   <div class="objects-item wrap-object <?=($one)?'open':''?>">
      <div class="objects-head">
         <div class="name"><a href="#">Объект 001, Нежилое помещение, Островского, 34, корп.1 (Н1)</a></div>
         <div class="info">(Заявка на технологическое присоединение №________от _________)</div>
      </div>
      <div class="objects-body" style="display: <?=($one)?'block':'none'?>;">
         <div class="sub-objects-items collapse-items">
            <table>
				<tbody>
					<tr>
						<td>01.07.2022</td>
						<td>Проект договора энергоснабжения (купли – продажи (поставки) электрической энергии (мощности) / Соглашение</td>
						<td>icon</td>
						<td><button class="btn full">Скачать</button></td>
					</tr>
					<tr>
						<td>01.07.2022</td>
						<td>Проект договора энергоснабжения (купли – продажи (поставки) электрической энергии (мощности) / Соглашение</td>
						<td>icon</td>
						<td><button class="btn full">Скачать</button></td>
					</tr>
					<tr>
						<td>01.07.2022</td>
						<td>Проект договора энергоснабжения (купли – продажи (поставки) электрической энергии (мощности) / Соглашение</td>
						<td>icon</td>
						<td><button class="btn full">Скачать</button></td>
					</tr>
					<tr>
						<td>01.07.2022</td>
						<td>Проект договора энергоснабжения (купли – продажи (поставки) электрической энергии (мощности) / Соглашение</td>
						<td>icon</td>
						<td><button class="btn full">Скачать</button></td>
					</tr>
				</tbody>
			</table>
			
			<p>Скачайте документ, подпишите своей электронно-цифровой подписью, направьте нам одним из способов:</p>
			<ul>
				<li>через сервис «<a href="https://lk.rgmek.ru/inner/fos">Написать обращение</a>» Личного кабинета;</li>
				<li>посредством электронного документооборота СБИС, Диадок.</li>
			</ul>
			<p>Также подписанный договор (соглашение) в бумажном виде можно вернуть по адресу: г. Рязань, ул. Радищева, д. 61, каб. 1</p>
			<p>Договор (соглашение) считается заключенным с даты составления акта об осуществлении технологического присоединения (уведомления об обеспечении сетевой организацией возможности присоединения к электрическим сетям) <a href="#">подробнее</a></p>
         </div>
      </div>
      <div class="objects-more">
         <a href="#" class="more-link" data-text-open="Развернуть" data-text-close="Свернуть">
         <span><?=($one)?'Свернуть':'Развернуть'?></span>
         </a>
      </div>
   </div>
</div>