<div class="contacts_us">
	
	<div class="left-b">
		<form action="" method="post" enctype="multipart/form-data">
			<h2>Напишите нам всё, что хочется</h2>
				
			<div class="name">
				{if !$USER->authorized || !$USER->user_name}
				<span>Имя:</span> 
				<input type="text" name="data[name]"  class="name" value="{$smarty.post.data.name}" />
				{else}
				<input type="hidden" name="data[name]"  class="name" value="{$USER->user_name}" />
				{/if}
			</div>
			<div class="email">
				{if !$USER->authorized || !$USER->user_email}
				<span>Ваш e-mail:</span> 
				<input type="text" name="data[email]"  class="email" value="{$smarty.post.data.email}" />
				{else}
				<input type="hidden" name="data[email]"  class="email" value="{$USER->user_email}" />
				{/if}
			</div>
			<div class="phone">
				{if !$USER->authorized || !$USER->user_phone}
				<span>Ваш телефон:</span>
				<input type="text" name="data[phone]" class="phone" value="{$smarty.post.data.phone}" />
				{else}
				<input type="hidden" name="data[phone]" class="phone" value="{$USER->user_phone}" />
				{/if}
			</div>
			
			<input type="hidden" id="rating" name="data[rating]" value="{if $smarty.post.data.rating}{$smarty.post.data.rating}{else}0{/if}" />
		  
			<ul class="stars">
				<li><a href="#" class="star1 {if $smarty.post.data.rating == 1}on{/if}" rat='1' title="Ужасно">Ужасно</a></li>
				<li><a href="#" class="star2 {if $smarty.post.data.rating == 2}on{/if}" rat='2' title="Плохо">Плохо</a></li>
				<li><a href="#" class="star3 {if $smarty.post.data.rating == 3}on{/if}" rat='3' title="Нормально">Нормально</a></li>
				<li><a href="#" class="star4 {if $smarty.post.data.rating == 4}on{/if}" rat='4' title="Хорошо">Хорошо</a></li>
				<li><a href="#" class="star5 {if $smarty.post.data.rating == 5}on{/if}" rat='5' title="Отлично">Отлично</a></li>
			</ul>
			<div class="syr">- выберите оценку</div>
			
			{*<div class="characters-left">осталось символов: 700</div>*}
			<div class="">
				<br/>
<textarea name="data[text]" cols="70" rows="10" class="textarea-len">
{if $smarty.post.data.text}{$smarty.post.data.text}{else}
{if $smarty.get.mailsender_618 || $smarty.get.utm_campaign == "mailsender_618"}
1. Вы легко нашли нужный товар на сайте?   
2. Было ли просто оформить заказ?    
3. Был ли вежлив менеджер, курьер?  
4. Курьер приехал вовремя? 
5. Будете рекомендовать нас друзьям? Почему? 
{else}
Пожалуйста, оцените товар, качество сервиса
- Все отлично
- Нанесение
- Ткань, пленка
- Сервис, обмен-возврат
- Курьерская служба
- Другое{/if}
{/if}
</textarea>				
			
				<p>
					<input type="file" name="pic1" />
				</p>

				{if !$smarty.get.order}
					<p>
						<table style="display: inline-block; vertical-align: middle;">
						<tr>
							<td><input type="text" name="keystring" id="keystring" maxlength="5" style="height:48px;width:100px;font-size:38px;font-family:Times New Roman;"></td>
							<td><div class="left" id="secimgBlock" style="height:52px; margin-bottom: 0;"></div></td>
							<td style="padding-left:5px;"><a href="#" onclick="genNewCaptcha(); return false;"><img src="/images/icons/arrow_refresh.png" /></a></td>
						</tr>
						</table>
					</p>
					
					{literal}
					<script>
						$(document).ready(function() {
							genNewCaptcha();
						});
					</script>
					{/literal}
				{/if}
				
				<input type="hidden" name="data[tpl]" class="tpl" value="{$tpl}" />
				<input type="hidden" value="{$order->id}" name="order" />
				<input type="submit" class="submit" value=" " style="" onclick="return checkEmail()" />
				
				{if $error}
				<p class="noaccess error">{$error}</p>
				{/if}
			</div>
		</form>
		
	</div>
	
	{if $gmailForm}
	{literal}
	<script src="https://apis.google.com/js/platform.js?onload=renderOptIn" async defer></script>
	
	<script>
	  window.renderOptIn = function() {
	    window.gapi.load('surveyoptin', function() {
	      window.gapi.surveyoptin.render(
	        {
	          "merchant_id": 100436013,
	          "order_id": "{/literal}{$order->id}{literal}",
	          "email": "{/literal}{$order->user->user_email}{literal}",
	          "delivery_country": "ru-RU",
	          "estimated_delivery_date": "{/literal}{$order->user_basket_delivery_date}{literal}"
	        });
	    });
	  }
	</script>
	{/literal}
	{/if}
	
	{include file="catalog/good.reviews.tpl"}			
	
</div>