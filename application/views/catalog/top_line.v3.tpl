<script type="text/javascript"> 
{* if $USER->user_id == 86455 || $USER->user_id == 27278 || $USER->user_id == 105091 *}
{if $USER->user_id == 27278 || $USER->user_id == 6199 || $USER->user_id == 105091 || $USER->user_id == 86455}
	window.dev = true;
{else}
	window.dev = false;
{/if}
</script>
	
{if $USER->user_id == 105091 || $USER->user_id == 27278 || $USER->user_id == 6199 || $USER->user_id == 63250}
	<link rel="stylesheet" href="/css/catalog/top-line-catalog_v2.css" type="text/css" />
{else}
	<link rel="stylesheet" href="/css/catalog/top-line-catalog.css" type="text/css" />
{/if}

	<!-- Тут будет Хеадер -->
	<div class="b-header topbar-remove">
	
		<div class="p-head" style="z-index:1003;height:58px;">
			{if $onHudsovet}
			<div style=" position:absolute;z-index:1004;color:#FFF; top:70px; left:10px;" ><a href="/hudsovet/" rel="nofollow" style="background-color:#FFF" class="hs">ХC: {$onHudsovet.new}</a></div>
			{/if}
			
			<!--a class="logo-img" title="Главная - Футболки Maryjane.ru" href="/" rel="nofollow"><img width="111" height="79" title="Футболки Maryjane.ru" src="/images/main-2013-8march/logo_8march.png">
			<div style="height: 47px;width:111px;visibility: hidden;"></div></a-->
			<!--a class="logo-img" style="display: block;width: 111px;" title="Главная - Футболки Maryjane.ru" href="/" rel="nofollow"><img width="107" height="109" title="Футболки Maryjane.ru" src="/images/main-2013-5/logo.png">
			<div style="height: 17px;width:93px;visibility: hidden;"></div></a-->
			<!--a class="logo-img" style="display: block;width: 111px;" title="Главная - Футболки Maryjane.ru" href="/" rel="nofollow"><img width="110" height="113" title="Футболки Maryjane.ru" src="/images/main-2013-7/logo.jpg">
			<div style="height: 16px;width:105px;visibility: hidden;"></div></a-->
			<a class="logo-img" style="display: block;width: 111px;" title="{$L.HEADER_title}" href="/" rel="nofollow"><img width="93" height="105" title="{$L.HEADER_title_2}" src="/images/main-2013-12/logo.png">
			<div style="height: 22px;width:111px;visibility: hidden;"></div></a>
					
			<div class="top-line {$PAGE->lang}">
				<div class="sub_cursor"></div>
				<div class="top-menu">
					<a rel="nofollow" class="top-menu-shop {if $module == 'catalog' || ($module == 'tag' && $good_id)}active{/if}" title="{$L.HEADER_shop}" href="/catalog/"></a>
					<a rel="nofollow" class="top-menu-community {if $module == 'blog' || $module == 'voting' || $module == 'gallery' || $module == 'senddrawing.design'}active{/if}" title="{$L.HEADER_community}" href="/voting/competition/main/"></a>
				</div>
				<ul class="menu">
				{if $USER->authorized}
					<li class="authorised">
						<a href="/profile/{$USER->user_id}/" rel="nofollow" style="">{$USER->user_login}</a>
						<span id="user-menu"><img src="/images/reborn/0.gif" alt="" title="{$L.HEADER_user_menu}" /></span>
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
							if (menupointer)
								menupointer.onclick = toggleUMenu;
							//console.log(menupointer);
						</script>
						{/literal}
						<ul id="dropdown" style="display: none; top:22px;">
							<li class="dropdown-arrow"><div></div></li>
							{if $cash > 0}
								<li><a href="/payback/" onclick="window.open(this.href, 'displayWindow', 'width=700,height=780,status=no,toolbar=no,menubar=no'); return false;" rel="nofollow" title="{$L.HEADER_cash}">{$L.HEADER_cash}: {$cash}р.</a>
							{/if}
							{if $USER->user_bonus + $bonusWait > 0}
								<li><a href="/bonuses/" rel="nofollow">{$L.HEADER_bonuses}: <span {if $sharebonuses}style="font-size:14px; color:#F00"{/if} title="{$L.HEADER_bonuses}">{$USER->user_bonus}р.</span> {if $bonusWait > 0}<span title="{$L.HEADER_bonuses_wilbe_charged}">(+{$bonusWait})<span>{/if}</a></li>
							{/if}
							{if $options_link}
							<li><a href="/catalog/{$USER->user_login}/" rel="nofollow">{$L.HEADER_works}</a></li>
							{/if}
							{if $options_link}
							<li><a href="/catalog/{$USER->user_login}/" rel="nofollow">Мой Принтшоп</a></li>
							{/if}
							<li><a href="/orderhistory/"  rel="nofollow">{$L.HEADER_orders}</a></li>
							<li><a href="/selected/{$USER->user_id}/" rel="nofollow">{$L.HEADER_ilike}</a></li>
							<li><a href="/blog/{$USER->user_id}/" rel="nofollow">{$L.HEADER_blog}</a></li>
							<li><a href="/myphoto/" rel="nofollow">{$L.HEADER_photo}</a></li>

							<li class="logout-line"><div></div></li>
							<li class="logout">
								<a href="/logout/" rel="nofollow">{$L.HEADER_singout}</a>
							</li>
						</ul>
					</li>

					<li class="divider">|</li>
					{* if $USER->user_bonus > 0}
					<li class="bonuses">
                    
                    <a href="/bonuses/"  style="font-weight:bold;text-decoration:none" rel="nofollow">{if $USER->user_bonus > 0} <span {if $sharebonuses}style="font-size:14px; color:#F00"{/if}>{$USER->user_bonus}р.</span> {if $bonusWait > 0}(+{$bonusWait}){/if} {else}{/if}</a></li>
					<li class="divider">|</li>
					{/if *}

					{if $unreaded_messages == 0}
					<li class="messages">
						<a href="/messages/"  rel="nofollow">
							<img src="/images/reborn/0.gif" alt="" title="{$L.HEADER_messages_empty}" />
							<span class="none">0</span>
						</a>
					</li>
					{else}
					<li class="messages messages-on">
						<a href="/messages/" rel="nofollow">
							<img src="/images/reborn/0.gif" alt="" title="{$L.HEADER_messages_start} {$unreaded_messages} {$L.HEADER_messages_end}" />
							<span>{$unreaded_messages}</span>
						</a>
					</li>
					{/if}
					<li class="divider">|</li>


					{* if $onHudsovet}
					<li><nobr><a href="/hudsovet/" rel="nofollow">ХС:&nbsp;{$onHudsovet.new}</a></nobr></li>
					<li class="divider"></li>
					{/if *}
				{/if}

			{if !$USER->authorized}
				{* <li><a href="/bonuses/"  style="font-weight:bold" rel="nofollow">{if $USER->user_bonus > 0}Бонусы: {$USER->user_bonus} {if $bonusWait > 0}(+{$bonusWait}){/if} р.{else}Мои бонусы{/if}</a></li> *}
				<li class="divider">|</li>
				<li class="tologin" style="padding:0 5px;">
					<a href="/login/" class="enter" title="{$L.HEADER_authorization}" onclick="return qLogin.showForm();" rel="nofollow">{$L.HEADER_singin}</a>
					<img border="0" alt="F" id="topLineFBLogin" onclick="return qLogin.loginWithFB();" title="{$L.HEADER_fb_singin}" src="/images/social/fcb.gif">
					<a href="#" rel="nofollow"><img border="0" alt="V" id="topLineVKLogin" title="{$L.HEADER_vk_singin}" src="/images/social/vkontakte.png" /></a>
					<a href="/registration/" class="regTopLine" rel="nofollow">{$L.HEADER_register}</a>
				</li>
			{/if}

			<!-- Новая Быстрая корзина в топлайне -->
			<li class="cart quick-basket" style="margin-top: -9px !important;">
			<script type="text/javascript" src="/js/jquery.cookie.min.js"></script>
			<script type="text/javascript" src="/js/jquery.autocomplete.pack.js"></script>
			<script type="text/javascript" src="/js/basket.quick_v1.js"></script>

			<div class="basket-bg-wrap">
				<div class="basket-sum-wrap {if $BASKETINFO eq 0} empty-basket {/if}">
					<a id="basket-link" class="basket-link" href="/basket/" rel="nofollow" ></a>
					{if $BASKETINFO eq 0}
					<a id="basket-sum" class="basket-sum" href="/basket/" rel="nofollow" style="font-weight:bold; font-size:14px">{if $step == 'confirm'} ;-) {else} ;-( {/if}</a>
					{else}
					<a id="basket-sum" class="basket-sum" href="/basket/" rel="nofollow">{$BASKETINFO} руб.</a>
					{/if}
					
					{* if $USER->user_id == 27278 || $USER->user_id == 86455 || $USER->user_id == 6199 || $USER->user_id == 105091 || $USER->user_id == 63250 *}
							
						<link rel="stylesheet" href="/css/basket.quick.css" type="text/css" />
						
						<div class="wrap-basket" id="b-qbasket-conteiner" style="display:none;">
							<div style="width: 0px;height: 0px !important;position: absolute;top: -10px;left: 50%;margin-left: -10px !important;border-color: transparent transparent #EFEFEF transparent;border-style: solid;border-width: 11px 11px;"></div>
							<div class="basket">
								<div class="goods-wrap" id="goods-wrap"></div>
								<div style="clear:both;"></div>
							</div>
						</div>
					
					{* else}

						<div class="b-qbasket-conteiner i-border-shadow4" id="b-qbasket-conteiner" style="display:none;"> <i></i>
							<div class="q-head">	<a href="#close-popup" rel="nofollow" class="xq-close" id="xq-close"></a></div>
							<div class="goods-wrap" id="goods-wrap">
							</div>
						</div>
					{/if *}
				</div>
			</div>
			<script type="text/javascript">
				VK_APP_ID = {$VK_APP_ID};
				IE = /(msie) ([\w.]+)/.test(navigator.userAgent.toLowerCase());
				
				{literal}
				// скрипты для быстрой корзины
				//1. Показать коризну по клику (ховеру)
				//2. Убарть корзину чез 10 секунд или по клику
				//3. Удалить товар - показать надпись - корзина пуста
				$(document).ready(function(){

					//бегунок выделенного
					var sm = $('.b-header .sub-menu a.active');
					if (sm.length > 0) {
						sm.css('width', 'auto');
						$('.b-header .sub_cursor').css('left', (sm.position().left+(sm.width()/2)-20)+'px');
						sm.css('width', '');
					}

					// автоматом установить ширину топ-меню для хрома
					var menu_width = 0;
					$(".menu").children('li').each(function(i, key){
						menu_width += $(this).width()+$(this).css('marginRight').match("\\d*")*1 +$(this).css('marginLeft').match("\\d*")*1 + 5;
					});
					//$(".menu").width(menu_width);

					qBask.init();
					
					$('#topLineFBLogin').attr('onclick','').click(function() { 
						window.open('http://www.maryjane.ru/login/fb/',(IE?'':'Вход через Facebook'),'location,width=800,height=400');
						//return false;
					});
					
					$('#topLineVKLogin').click(function(){ 
						window.open('http://api.vkontakte.ru/oauth/authorize?client_id='+VK_APP_ID+'&scope=&redirect_uri=http://www.maryjane.ru/login/vk/&response_type=code&display=popup',(IE?'':'Вход через Вконтакте'),'location,width=400,height=300');
						//return false;
					});
				{/literal}
					
				});
			</script>
			</li>
			<!-- Новая Быстрая корзина в топлайне Конец -->
				<li class="help_">
					<a rel="nofollow" style="" href="/faq/">{$L.HEADER_help}</a>
				</li>

				<li class="header-contact">
					<div class="phone"><span>{$contact_phone}</span></div>					
					{* <div class="skype"><span>maryjane_ru</span></div> *}
				</li>

			</ul>
			{if $USER->user_id == 27278 || $USER->user_id == 105091 || $USER->user_id == 6199 || $USER->user_id == 63250 || $USER->user_id == 86455}
				<div class="language">
					<a href="#" {if $PAGE->lang!='en'}class="active"{/if} title="rus" rel="nofollow">rus</a>
					<a href="{if $PAGE->lang!='en'}/en{$smarty.server.REQUEST_URI}{else}#{/if}" {if $PAGE->lang=='en'}class="active"{/if} title="en" rel="nofollow">en</a>
				</div>
			{/if}
		</div>
	</div>
</div>