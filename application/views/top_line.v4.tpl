	<div class="b-header topbar-remove">	
		<div class="p-head clearfix" style="z-index:1003;height:58px">			
			{if $onHudsovet && $USER->id != 63250}
				<div style="position:absolute;z-index:1004;color:#FFF;top:70px;left:10px"><a href="/hudsovet/"  style="background-color:#FFF" class="hs">ХC: {$onHudsovet.new}</a></div>
			{/if}
						
			<a class="logo-img" title="{$L.HEADER_title}" href="/" {if $PAGE->reqUrl.0 != ''}{/if}>
				<img width="107" height="121" title="{$L.HEADER_title_2}" src="{if ($datetime.month == 11 && ($datetime.day == 23 || $datetime.day == 24 || $datetime.day == 25)) || $USER->id == 27278}/images/logoblack.gif{else}/images/logo201711.jpg{/if}"/>
			</a>		
			
			<div class="top-line {$PAGE->lang}">
				<div class="sub_cursor"></div>

				{if ($PAGE->module == 'customize' && ($PAGE->reqUrl.1 == 'index' || $PAGE->reqUrl.1 == '' || $PAGE->reqUrl.1 == 'filter' || $style_id)) || ($PAGE->module == 'stickermize') || ($PAGE->module == 'stickermize.v2')|| ($PAGE->module == 'customize.v2' && $style_id)}	
					<div id="fakeLiSubMenu" {if !$USER->authorized}class="no_authorized"{/if}>
						<h1>{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if} {if $USER->city}({$USER->city}){/if}</h1>
					</div>					
				{else}				
					<div class="top-menu">
						<a class="top-menu-shop {if ($PAGE->module == 'catalog' && !$user) || ($PAGE->module == 'catalog' && $good) || ($PAGE->module == 'tag' && $type != 'posts')}active{/if}" title="{$L.HEADER_shop}" href="{if $PAGE->url != "/catalog/"}/catalog/{else}#{/if}"></a>
						<a class="top-menu-community {if ($PAGE->module == 'catalog' && $user && !$good) || $PAGE->module == 'blog' || $PAGE->module == 'people' || $PAGE->module == 'voting' || $PAGE->module == 'gallery' || $PAGE->module == 'senddrawing.design' || ($PAGE->module == 'tag' && $type == 'posts')}active{/if}" title="{$L.HEADER_community}" href="{if $PAGE->url != "/voting/competition/main/"}/voting/competition/main/{else}#{/if}"></a>
					</div>
				{/if}
				
				<ul class="menu">
				{if $USER->authorized}
					<li class="authorised">
						<a href="/profile/{$USER->id}/"  class="mini-avatar" title="{$USER->user_login}">{$USER->avatar}</a>
						<a href="/profile/{$USER->id}/"  style="font-size:12px">{$USER->user_login}</a>
						<span id="user-menu"><img src="/images/reborn/0.gif" alt="" title="{$L.HEADER_user_menu}" /></span>
				
						{*title в теге <a>  используем для трека, что бы исключить переменные деньги для юзверов*}
						<ul id="dropdown" style="display:none">
							<li class="dropdown-arrow"><div></div></li>
							
							{if $USER->meta->goodApproved > 0}
							<li>
								<a href="/catalog/{$USER->user_login}/"  title="{$L.HEADER_works}">{$L.HEADER_works}</a>
								<a href="/senddrawing.design/"  title="{$L.HEADER_menu_senddrawing}" class="senddrawing">+</a>
							</li>
							{/if}
							
							<li><a href="/orderhistory/"   title="{$L.HEADER_orders}">{$L.HEADER_orders}</a></li>
							
							{if $myTenders > 0 || $USER->meta->mjteam == "designer"}
							<li><a href="/design/list/my/"  title="Мои задания">Мои задания на дизайн ({$myTenders})</a></li>
							{/if}
							
							{if $USER->authorized}
							<li><a href="/senddrawing.design/"  title="Прислать работу">Прислать работу</a></li>
							{/if}
							
							{if $usercash + $usercash_awaiting > 0}
								<li><a href="/payback/"  title="{$L.HEADER_cash}">{$L.HEADER_cash}: {$usercash}{if $usercash_awaiting > 0} ({$usercash_awaiting}) {/if}р.</a></li>
							{/if}
							
							{if $USER->user_bonus + $bonusWait > 0}
								<li><a href="/bonuses/"  title="{$L.HEADER_bonuses}">{$L.HEADER_bonuses}: <span {if $sharebonuses}style="font-size:14px; color:#F00"{/if} title="{$L.HEADER_bonuses}">{$USER->user_bonus}р.</span> {if $bonusWait > 0}<span title="{$L.HEADER_bonuses_wilbe_charged}">(+{$bonusWait})<span>{/if}</a></li>
							{/if}
							
							{if $USER->meta->goodApproved > 0}
							<li><a href="/stat/"  title="Продажи">Продажи</a></li>
							{/if}
							
							<li><a href="/promo/"  title="Промо">Промо</a></li>
							
							<li><a href="/selected/{$USER->id}/"  title="{$L.HEADER_ilike}">{$L.HEADER_ilike}</a></li>
							
							{*<li><a href="/blog/user/{$USER->user_login}/"  title="{$L.HEADER_blog}">{$L.HEADER_blog}</a></li>
							<li><a href="/myphoto/"  title="{$L.HEADER_photo}">{$L.HEADER_photo}</a></li>*}

							<li class="logout-line"><div></div></li>
							<li class="logout"><a href="/logout/"   title="{$L.HEADER_singout}">{$L.HEADER_singout}</a></li>
						</ul>
					</li>

					<li class="divider">|</li>

					{if $unreaded_messages == 0}
					<li class="messages">
						<a href="/messages/" >
							<img src="/images/reborn/0.gif" alt="{$L.HEADER_messages_empty}" title="{$L.HEADER_messages_empty}" />
							<span class="none">0</span>
						</a>
					</li>
					{else}
					<li class="messages messages-on">
						<a href="/messages/" >
							<img src="/images/reborn/0.gif" alt="{$L.HEADER_messages_start}" title="{$L.HEADER_messages_start} {$unreaded_messages} {$L.HEADER_messages_end}" />
							<span>{$unreaded_messages}</span>
						</a>
					</li>
					{/if}
					
					<li class="divider">|</li>
					<li class="divider"></li>
					
					<li class="selected_works">
						<a href="/selected/{$USER->id}/"  title="{$L.HEADER_ilike}">&nbsp;</a>
					</li>					
				{/if}
				
				{if !$USER->authorized}
					<li class="divider">|</li>
					<li class="tologin" style="padding:0 5px"><!--noindex-->
						<a href="#" _href="/login/" class="enter" title="{$L.HEADER_authorization}"  onclick="{if $USER->client->ismobiledevice == '1'}document.location = $(this).attr('_href');return false;{else}return qLogin.showForm();{/if}">{$L.HEADER_singin}</a>
						<img border="0" alt="F" id="topLineFBLogin" onclick="return qLogin.loginWithFB();" title="{$L.HEADER_fb_singin}" src="/images/social/fcb.gif"/>
						<a href="#" ><img border="0" alt="V" id="topLineVKLogin" title="{$L.HEADER_vk_singin}" src="/images/social/vkontakte.png" /></a>
						<a href="/registration/" class="regTopLine" >{$L.HEADER_register}</a><!--/noindex-->
					</li>
				{/if}				
				
				<li class="cart quick-basket">
					<div class="basket-bg-wrap">
						<div class="basket-sum-wrap {if $basket_sum == 0} empty-basket {/if}">
							<a id="basket-link" class="basket-link" href="#"  ></a>
							
							<a id="basket-sum" class="basket-sum" href="#"  {*if $basket_sum == 0}style="font-weight:bold;font-size:14px"{/if*}><font>{if $basket_sum == 0};-({else}{$BASKETINFO}{$L.CURRENT_CURRENCY}{/if}</font> </a>
							
							{include file="order/basket.quick.v3.tpl"}							
						</div>
					</div>
				</li>		
				
				<li class="header-contact">
					<!--noindex-->
					<div class="contactUs"><span>{$L.HEADER_contact_us}</span></div>						
					<ul id="dropdownContactUs" style="display:none">
						<li class="dropdown-arrow"><div></div></li>						
											
						<li class="notLink checkstatus">
							<font class="f">Узнать статус заказа</font><br/>
							<font class="l">введите телефон</font>
							<form action="/order.v3/checkstatus/" method="post" class="clearfix">
								<input type="text" name="search" {if $USER->authorized && $USER->user_phone}value="{$USER->user_phone}"{/if}>
								<input type="submit" value="найти">
							</form>
						</li> 						
						{*else}
						<li><a href="/order.v3/checkstatus/"  title="Проверить статус заказа" track="Проверить статус заказа"><font class="f">Проверить статус </font><br/><font class="l">заказа</font></a></li>
						{/if*}						
						
						<li><a href="/faq/"  title="{$L.HEADER_quick_answers}" track="Быстрый поиск ответов"><font class="f">{$L.HEADER_quick_answers}</font><br/><font class="l">{$L.HEADER_payment_delivery_returns}</font></a></li>
						<li><a class="thickbox" href="/feedback/?width=300&height={if $USER->authorized}465{else}565{/if}" title="{$L.HEADER_still_have_questions}?"  track="Свой вопрос, напишите нам"><font class="f">{$L.HEADER_your_question}</font><br/><font class="l">{$L.HEADER_write_to_us}</font></a></li>
						{*<li><a href="mailto:team@maryjane.ru" title="Напишите нам"  track="Напишите нам team@maryjane.ru"><font class="f">Напишите нам</font><br/><font class="l">team@maryjane.ru</font></a></li>*}
						{if $PAGE->lang=='ru'}
							
							{if $datetime.hour > 10 && $datetime.hour < 20 && $datetime.dayofweek != 6 && $datetime.dayofweek != 0}
							<li style="height:52px" class="notLink"><font class="f">Позвонить нам</font><br/><font class="f">+7 (495) 229-30-73</font><br/><font class="l">Для звонков из Москвы</font></li> 
							{/if}
								
						{/if}
						
						{if $datetime.hour < 10 || $datetime.hour >= 20 || $datetime.dayofweek == 6 || $datetime.dayofweek == 0}
							<li style="height:26px"><a  href="/#TB_inline?width=570&height=475&inlineId=callbackHint" class="thickbox" track="Перезвоните нам">Обратный звонок</a></li>
							{include file="callback.tpl"}
						{/if}	
						
						
					</ul>
					<!--/noindex-->
				</li>
				
			</ul>
			<div style="clear:both"></div>

			<div class="language">
				<!--noindex-->
				{if $PAGE->lang == 'en' && $USER->meta->mjteam}
					<span class="kurs" style="border-bottom:1px dashed orange">currency rate: 1$ = {$dollarRate}ruble(rus)</span>
				{/if}
				<a href="#" _href="{if $PAGE->lang == 'ru'}#{else}/language/ru/{/if}" {if $PAGE->lang != 'en'}class="active"{/if} title="rus" >rus</a>
				<a href="#" _href="{if $PAGE->lang == 'en'}#{else}/language/en/{/if}" {if $PAGE->lang=='en'}class="active"{/if} title="eng" >eng</a>
				<!--/noindex-->
			</div>
			
		</div>
		{*<div style="clear:both"></div>*}
	</div>
</div>