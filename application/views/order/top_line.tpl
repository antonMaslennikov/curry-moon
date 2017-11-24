<div class="p-head-cont">
	<div class="p-head">
		{if $step == 'confirm' || $module == 'basket'}
		<a href="/">
		{/if}
		<img src="/images/new/logo.png"  height="43" width="241" title="Футболки Maryjane.ru"/>
		{if $step == 'confirm' || $module == 'basket'}
		</a>
		{/if}
		
		{if $USER->authorized}
		<span class="promo-text">Магазин футболок №1. Нам 10 лет!</span>
		{/if}
		
		<ul class="menu">

		{if $USER->authorized}

		<li class="authorised">
			<!--a href="http://www.maryjane.ru/profile/"--><strong>{$USER->user_login}</strong>
			<!--span id="user-menu"><img src="/images/reborn/0.gif" alt="" title="Меню пользователя" /></span-->
			{literal}
			<script type="text/javascript">
				function toggleUMenu(event){
					$("#dropdown").toggle("normal");
					$("#dropdown").click(function(e) {
						e.stopPropagation();
					});
					event.stopPropagation();
				}
				menupointer = document.getElementById("user-menu");
				if (menupointer) menupointer.onclick = toggleUMenu;				
			</script>
			{/literal}
			<ul id="dropdown" style="display: none;">
				<li><a href="http://www.maryjane.ru/profile/">Профиль</a></li>
				{if $options_link}
				<li>
					<a href="http://printshop.maryjane.ru/options/" style="font-weight:bold">Мой Принтшоп</a>
				</li>
				{/if}
				
				<li><a href="http://www.maryjane.ru/orderhistory/"  style="font-weight:bold">Мои Заказы</a></li>
                <li><a href="http://www.maryjane.ru/selected/">Избранное</a></li>
				
				<li><a href="http://www.maryjane.ru/voting_personal/notvote/">Новое на голосовании</a></li>
				<li class="withadd">
					<a href="http://www.maryjane.ru/senddrawing/" class="add">+ Добавить</a>
					<a href="http://www.maryjane.ru/portfolio/">Мои работы</a>
				</li>
				<li class="withadd">
					<a href="http://www.maryjane.ru/blog/new/" class="add">+ Добавить</a>
					<a href="http://www.maryjane.ru/blog/">Мои посты</a>
				</li>
				<li class="withadd">
					<a href="http://www.maryjane.ru/sendphoto/" class="add">+ Добавить</a>
					<a href="http://www.maryjane.ru/myphoto/">Мои фото</a>
				</li>
				<li class="withadd">
					<a href="http://notees.maryjane.ru/" class="add">+ Добавить</a>
					<a href="http://notees.maryjane.ru/">Мои слоганы</a>
				</li>

				<li class="rating">
					<span style="font-size:14px;">{$USER->user_raiting}/{$USER->user_carma}</span>
					Рейтинг/карма
				</li>
				
				<li class="logout">
					<a href="/logout/"  rel="nofollow">Выйти</a>
				</li>
			</ul>
		</li>
        <li class="my-bonuses" style="visibility:hidden; width:0;"> 
			<div class="my-bonuses-box">
				<div class="top">
					<span class="on_your_account">На вашем счету.....{$USER->user_bonus} руб.</span>
					<sup class="help"><a href="/faq/112/?TB_iframe=true&amp;height=500&amp;width=600" rel="nofollow" class="thickbox help">?</a></sup>					
				</div>
				<div class="bottom">
					Будет начислено...{$bonusBack} руб.
				</div>				
			</div>
		</li>					
		
		{else}
				
		<li><a href="http://www.maryjane.ru/bonuses/"  style="font-weight:bold"  rel="nofollow">Мои Бонусы</a></li>
		<li class="divider">|</li>
			<li class="tologin" style="padding-left:10px;">				
				<a href="/login/" class="enter" title="Авторизация" onclick="return qLogin.showForm();" rel="nofollow">Войди</a>  
				<img border="0" alt="F" id="topLineFBLogin" onclick="return qLogin.loginWithFB();" title="Войти на Maryjane.ru с помощью Facebook-аккаунта" src="/images/social/fcb.gif">
				<a href="http://www.maryjane.ru/registration/" class="regTopLine" rel="nofollow">Зарегистрируйся</a>
			</li>
		{/if}
		
		<!-- Новая Быстрая корзина в топлайне -->
		<li class="cart quick-basket">
		<script type="text/javascript" src="/js/jquery.cookie.min.js"></script>
		<script type="text/javascript" src="/js/jquery.autocomplete.pack.js"></script>
		<script type="text/javascript" src="/js/basket.quick_v1.js"></script>		
				
		<div class="basket-bg-wrap">
			<div class="basket-sum-wrap {if $basket_sum == 0} empty-basket {/if}">
				{if $basket_sum == 0}
				<a id="basket-sum" class="basket-sum" href="/basket/" style="font-weight:bold; font-size:14px">{if $step == 'confirm'} ;-) {else} ;-( {/if}</a>
				{else}
				<a id="basket-sum" class="basket-sum" href="/basket/">{$basket_sum} руб.</a>
				{/if}
				
				{if $USER->user_id == 9820 || $USER->user_id == 86455 || $USER->user_id == 27278 || $USER->user_id == 6199}
							
					<link rel="stylesheet" href="/css/basket.quick.css" type="text/css" />
					{literal}
					<style type="text/css">
					.wrap-basket {top: 37px;}
					</style>
					{/literal}
					
					
					<div class="wrap-basket" id="b-qbasket-conteiner" style="display:none;">
						<div class="basket">
							<div class="goods-wrap" id="goods-wrap"></div>
							<div style="clear:both;"></div>
						</div>
					</div>
				
				{else}
					<div class="b-qbasket-conteiner i-border-shadow4" id="b-qbasket-conteiner" style="display:none;"> <i></i>
						<div class="q-head">	<a href="#close-popup" class="xq-close" id="xq-close"></a></div>
						<div class="goods-wrap" id="goods-wrap">
						
						{include file="order/basket.quick.tpl"}
				
						</div>
					</div>
				{/if}
			</div>
		</div>
		{literal}
		<script type="text/javascript">
			// скрипты для быстрой корзины
			//1. Показать коризну по клику (ховеру)
			//2. Убарть корзину чез 10 секунд или по клику
			//3. Удалить товар - показать надпись - корзина пуста
			$(document).ready(function(){
				$("#q-delivery-city-search").autocomplete('/ajax/?action=city_autocomplit').result( function(event, item) {
					$('#q-delivery-city').val(item[1]);
										
					$.get('http://www.maryjane.ru/ajax/?action=city_autocomplit&q='+item[0]+'&limit=150&timestamp=1331197226273', function(res){
						var delivery_res = res.split('|');
						var price1 = (delivery_res[1]!='')?delivery_res[1]:'0';
						var price2 = (delivery_res[2]!='')?delivery_res[2]:'0';
						var price3 = (delivery_res[3]!='')?delivery_res[3]:'0';
						
						var price4	= (delivery_res[4])?delivery_res[4]:'0';		// Новосибирск|1384|472|2|200
						
						if (item[1] == 1) { // это Москва...
							$("#moscow_deliv #deliv_1 span").text(price2+' руб.');
							//$("#moscow_deliv #deliv_2 span").text(price2+' руб.');
							//$("#moscow_deliv #deliv_3 span").text(price3+' руб.');
							
							$("#moscow_deliv").show();
							$("#other_deliv").hide();
						} else {
							if (price2 > 0) {
								$("#deliv_4 b u").text(price3);
								$("#deliv_4 span").text(price2+' руб.');
								$("#deliv_4").show();
							} else {
								$("#deliv_4").hide();
							}
							
							$("#deliv_5 span").text(price4+' руб.');
							
							$("#moscow_deliv").hide();
							$("#other_deliv").show();
						}

						try {
							$.cookie("basket-city-delivery", item[1], { expires: 7, path: '/' } );
						} catch(e) {}
												
					});
				});
				
				qBask.init();
			});
		</script>
		{/literal}
		</li>
		<!-- Новая Быстрая корзина в топлайне Конец -->
		
		<li class="help_">
			<!--a href="http://www.maryjane.ru/faq/" style=" ">Помощь?</a-->&nbsp;
		</li>
	</ul>
	</div>
	
	{if $ERROR}
	
	<style>.headermenu {ldelim}  margin: 40px 0; {rdelim}</style>
	
	<div class="error" style="background: none repeat scroll 0 0 #FFDFDF; color: red;  margin-top: -11px; padding: 12px 15px; text-align:center">
		{$ERROR.text}
		
		{if $ERROR.num == 1}
			Неверный логин или пароль. <a href="/registration/recover/?login={$login}" style="color:red;margin-left:15px" rel="nofollow">Отправить пароль на почту</a>
		{elseif $ERROR.num == 2}
			Ваш профиль заблокирован.
		{elseif $ERROR.num == 3}
			Пароль не можен быть пустым
		{/if}
	
	</div>
	
	<br clear="all" />
	
	{/if}
	
</div>
<!--noindex--><div id="fb-root"></div><!--/noindex-->


{if $roulette_prize_0}	
	<div class="b-yellow-alert">
	<span>
		Поздравляем! Вы выиграли <a href="/sale/">бесплатную футболку</a>.
	</span>
	<a href="#close-alert" class="close-popup" id="close-popup-alert">x</a>
	</div>
{elseif $roulette_prize_1}
	<div class="b-yellow-alert">
	<span>
	Поздравляем! Вы выиграли 200 рублей. Ваш код {$certification_password} Введите его в <a href="/basket/">корзине</a>
	</span>
	<a href="#close-alert" class="close-popup" id="close-popup-alert">x</a>
	</div>
{elseif $roulette_prize_2}
	<div class="b-yellow-alert">   
	<span>		
	Поздравляем! Вы выиграли 123 рубля. Ваш код {$certification_password} Введите его в <a href="/basket/">корзине</a>
	</span>
	<a href="#close-alert" class="close-popup" id="close-popup-alert">x</a>
	</div>
{/if}

{literal}
<script> 
  window.fbAsyncInit = function() {FB.init({appId: '192523004126352', status:true, cookie:true, xfbml: true });};
        (function(d){
           var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
           js = d.createElement('script'); js.id = id; js.async = true;
           js.src = "//connect.facebook.net/en_US/all.js";
           d.getElementsByTagName('head')[0].appendChild(js);
         }(document));
</script>


<script type="text/javascript">
$(document).ready(function(){

	//FB.init({appId  : '192523004126352', status : true, cookie : true, xfbml  : true });

	var Category = 'Гл.выпадающее меню';
	$("#dropdown li a").click(function(){
		var link_text = $(this).text();
		trackUser(Category, link_text, '');
		var link_href = $(this).attr("href");
		setTimeout(function(){document.location.href = link_href;}, 140);
		return false;
	});
	
	// Инициализация входа через фейсбук
	//fbTopLogin.init();
});
</script>
{/literal}