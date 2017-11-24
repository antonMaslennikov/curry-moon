<div class="moveSidebarToLeft enduroText clearfix">
	{if $MobilePageVersion}
		<div class="pageTitle table">
			<h1>{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}</h1>
		</div>
	{else}
		<!--catalog/enduro.zakaz-nakleek.tpl-->
		<div class="top-banners">
			<div class="pageTitle">
				<div class="f-wall"></div>		
				<div class="h1"><h1>{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}</h1></div>
			</div>
			<img src="/images/b/enduro.jpg" width="980" height="300" alt="{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}">
		</div>

		{include file="catalog/list.sidebar.tpl"}
	{/if}
	
	<div class="cont {if $PAGE->reqUrl.2 == 'kleim-nakleyki-na-mototsikl'}loading{/if}">
		{if $PAGE->reqUrl.2 == 'zakaz-nakleek'}
			Наклейки на мототехнику с индивидуальным дизайном<br>
			1. Наклейки из материалов произвоства Германия/США с усилением клеевого<br>
			слоя (не боится керхера)<br>
			Толщина наклеек 300/450 мкм<br><br>

			Кроссовая/Эндуро техника - от 10000р, <br>
			Квадроциклы спортивные - от 10000р, <br>
			Квадроциклы большие - от 15000р, <br>
			Багги - от 20 000р, <br>
			Снегоходы- от 15 000р, <br>
			Большие эндуро мотоциклы BMW, KTM - от 15 000р,<br>
			Спортивные мотоциклы мотоциклы от 15 000 тр, <br>
			Шлемы от 7000р,<br>
			Микроавтобусы/автомобили от 20 000р<br>
			Детские мотоциклы 50/65 - 7000р<br>
			Мотоциклы 85/125 - 9000р<br>
			Номерные таблички на эндуро мотоцикл от 2900 р<br><br>

			2. Наклейки на материалах Китай с ламинацией (толщина наклеек 180мкм) <br>
			Маленький ATV (Аналог Yamaha Raptor 250) 6800р<br>
			Большой ATV (Аналог CF Moto X8) 8900р<br>
			МХ/Эндуро мотоцикл 6100р<br>
			Детский мотоцикл 4500р<br>
			Пит-Байк 4500р<br>
			Номерные таблички на эндуро мотоцикл 900р<br><br>

			— Разработка дизайн первого макета от 2-5 раб дней в зависимости от
			сложности работы.<br>
			— На доработку макетов дизайна 2 итерации<br>
			— Производство 2-5 раб дней<br>
			— Доставка по Москве 250р, по области от 700р<br>
			— Услуги по приклейке с выездом на место стоянки техники 5000р<br><br>
			
			<a href="/catalog/enduro/kak-oformit-zakaz/" rel="nofollow">Как оформить заказ</a>
		{else if $PAGE->reqUrl.2 == 'kak-oformit-zakaz'}
			1. Скачать бриф в формате World. Внутри вы найдете пример заполнения.<br>
			2. Отправляем на почту mj@maryjane.ru</a><br>
			3. Уточняем, предоплата 70%<br>
			4. Разрабатываем дизайн.<br>
			5. Производим наклейки.<br>
			6. Доставляем (доставка за ваш счет)<br>
			7. После получения, вы переводите оставшиеся 30%<br><br>

			<a href="http://www.maryjane.ru/download/brief_mjmoto_ru_2_2016.doc" download="Бриф mjmoto.ru на разработку дизайна мотоцикла, квадроцикла, гидроцикла.doc" rel="nofollow">Скачать бриф</a>
		{else if $PAGE->reqUrl.2 == 'kleim-nakleyki-na-mototsikl'}
			<iframe {if $MobilePageVersion}width="320" height="180"{else}width="774" height="435"{/if} src="https://www.youtube.com/embed/D54OBhOYt4Y" frameborder="0" allowfullscreen=""></iframe>
		{/if}
	</div>
</div>

<div class="list_seotexts">{include file="catalog/list.seotexts.tpl"}</div>
