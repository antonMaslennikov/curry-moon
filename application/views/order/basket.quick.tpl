<!-- Контент быстрой корзины -->
		{foreach from=$goods item="g"}
		<div class="q-goods qg1">
			<div class="q-name">
				<a href="{if $g.good_status != 'customize'}/catalog/{$g.user_login}/{$g.good_id}/{else}#{/if}" rel="nofollow">{$g.good_name}</a>
				<span>{$g.style_name}</span>
			</div>
			<div class="q-size">
				<span class="b">{$g.size_rus}</span>				
				<sup class="help">{if $g.style_viewsize > 0}<a href="/faq/{$g.style_viewsize}/?height=500&amp;width=600" rel="nofollow" class="help thickbox" >?</a>{else}&nbsp;{/if}</sup>
			</div>
			<div class="q-price">{$g.price} руб.</div>
			<a href="/basket/delete_good/{$g.good_id}/{$g.good_stock_id}/" class="q-delete_good" rel="nofollow">
				<img width="15" height="15" class="del-icon" src="/images/icons/delete_gray.gif" title="Удалить">
			</a>
		</div>
		{/foreach}
		
		{foreach from=$gifts item="g"}
		<div class="q-goods qg1">
			<div class="q-name">
				<a href="#"  rel="nofollow">{$g.gift_name}</a>
			</div>
			<div class="q-size">
			</div>
			<div class="q-price">{$g.priceTotal} руб.</div>
			<a href="/basket/delete_gift/{$g.gift_id}/" class="q-delete_good" rel="nofollow">
				<img width="15" height="15" class="del-icon" src="/images/icons/delete_gray.gif" title="Удалить">
			</a>
		</div>
		{/foreach}
				
		<div class="no-goods" style="{if $goods|count != 0 && $gifts|count != 0}display:none;{/if}">
			<p>Ваша корзина пока пуста!</p>
		</div>

<div class="b-calc-delivery" style="display:none">
    <a class="show-link" id="show-link" rel="nofollow" href="#!/calculate-delivery">Калькулятор доставки</a>
</div>
<div class="b-calc-form" id="calc-delivery-form" style="display:block;">
    <div class="form-title">Кальтулятор доставки</div>
    <input type="text" class="input" id="q-delivery-city-search" value="Москва" />
    <input type="hidden" class="input" id="q-delivery-city" value="Москва" />
    <div class="delivery-details" id="moscow_deliv">
        <div class="line" id="deliv_1"><b>Курьер (на след. день)</b>				<span>200 руб.</span></div>
        <div class="line" id="deliv_2"><b>Самовывоз (Москва, м. Бауманская)</b>		<span>0 руб.</span></div>
        <div class="line" id="deliv_3"><b>В метро (Кольцевая)</b>					<span>100 руб.</span></div>
    </div>

    <div class="delivery-details" id="other_deliv" style="display:none;">
        <div class="line" id="deliv_4"><b>Курьерская доставка DPD (<u></u> дней)</b>		<span>0 руб.</span></div>
        <div class="line" id="deliv_5"><b>Почта России (1-3 недели)</b>						<span>0 руб.</span></div>
    </div>
</div>


<div class="submit-basket">
    <div class="sum-bonus">
        <h3>Итого: {if $basket_sum_minus_bonuses < $basket_sum}<s>{$basket_sum}</s> {$basket_sum_minus_bonuses}{else}{$basket_sum}{/if} руб.</h3>
        <div class="you_return">Бонус за заказ: {$bonusBack} руб.</div>
    </div>
    <input id="submitQBask" type="submit" value="Оформить заказ" />
</div>
{if 1==2}
 <link rel="stylesheet" href="/css/basket-post-cards.css" type="text/css" />
<div  style="clear: both;"></div>
 <div id="basket-merchandise" class="cards-b" style="">
	  <div class="tit">
	   <a rel="nofollow" class="one" title="" href="#">Отличное дополнение к подаркам!</a>
	   <a rel="nofollow" class="tvo" title="" href="#"><span style="width: 268px;float: left;overflow: hidden;">Валентинка..................................................</span><span style="width: 50px;float: right;">39 руб.</span></a>
	   <br/>
	   <!--a rel="nofollow" class="tr" title="" href="#">/алфавит наклейки/</a-->
	  </div>
	 <div class="items-p">
		  <div class="item">
			<img title="" height="114" width="112" alt="Candy" src="/images/postcards/3.jpg">
			<a rel="nofollow" class="hid" title="" good_stocks="73307" good_id="46741" href="/catalog/ossssa/46741/category,postcards/"></a>
			<!--если ненадо ссылку то берем span-->
			<!--span class="hid"></span-->
		 </div>
		  <div class="item">
			<img title="" height="114" width="112" alt="olen" src="/images/postcards/2.jpg">
			<a rel="nofollow" class="hid" title="Alice" good_stocks="73307" good_id="46740" href="/catalog/ossssa/46740/category,postcards/"></a>
			<!--span class="hid"></span-->
		  </div>
		  <div class="item">
			<img alt="santa_olen" height="114" width="112" src="/images/postcards/1.jpg">
			<a rel="nofollow" class="hid" title="Angie" good_stocks="73307" good_id="46742" href="/catalog/ossssa/46742/category,postcards/"></a>
			<!--span class="hid"></span-->
		  </div> 
		<div  style="clear: both;"></div>
	</div> 
 </div>
{/if}
<!-- Контент быстрой корзины -->

<script>
    
	$("#show-link").click(function(event){
        $(".b-calc-delivery").hide();
        $("#calc-delivery-form").show();
        return false;
    });

    $("#calc-delivery-form").click(function(event){
        return false;
    });
		
	if (typeof qBask != 'undefined') qBask.initSearchCity();
	
</script>