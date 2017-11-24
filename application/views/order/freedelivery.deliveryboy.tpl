{if $basket->basketSum > 0 && $freedelivery_rest}

	<style>
		#freedelivery_deliveryboy {
			text-align:center;
			padding:10px;
			background:#eaeaea;
			margin-bottom:0px;
			font-weight:bolder;
		}
	</style>

	<p id="freedelivery_deliveryboy">
		{if $freedelivery_rest <= 0}
			Доставка экспресс-курьером по Москве для Вас  - бесплатно!
		{else}
			До бесплатной доставки экспресс-курьером по Москве осталось потратить {$freedelivery_rest} руб! <a href="/catalog/category,futbolki/new/">Продолжить покупки</a>
		{/if}
	</p>

{/if}
