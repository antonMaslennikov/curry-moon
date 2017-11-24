{if (($module == 'profile' && $USER->user_id == $userId) || $PAGE->module == 'bonuses'|| $PAGE->module == 'payback' || $PAGE->module == 'stat' || $PAGE->module == 'catalog' || $PAGE->module == 'catalog.dev' || $PAGE->module == 'promo'|| $PAGE->module == 'mynotees'|| $PAGE->module == 'selected') && !$filters.category}
	<div class="tabz2 notborderdev">

	{if ($PAGE->module == 'selected' && $selected_authors > 0) || $type == 'authors'}
		<a style="{if $module == 'selected' && $type == 'authors'}border: none;font-weight:bolder{else}border-style:solid;{/if}" href="{if $type != 'authors'}/selected/{$userInfo.user_id}/authors/{else}#{/if}" rel="nofollow" class="green" title="Избранные авторы">Избранные авторы</a>
	{/if}
	
	{if $PAGE->module != 'selected'}
		
		{if $module == 'bonuses' || $PAGE->module == 'payback' || ($PAGE->module == 'stat' && $type == 'personalbill')}
			{if $usercash > 0}
			<a style="margin-right: 20px;{if $module == 'stat' && $type == 'personalbill'}border: none;font-weight:bolder{else}border-style: solid;{/if}" class="green" rel="nofollow" href="/stat/personalbill/">Личный счёт</a>
			{/if} 
			<a style="margin-right: 20px;{if $module == 'bonuses'}border: none;font-weight:bolder{else}border-style: solid;{/if}" class="green" rel="nofollow" href="/bonuses/">Мои бонусы</a> 
		{elseif $module == 'profile'}
			<a style="margin-right: 20px;border-style: solid;" rel="nofollow" href="/editprofile/" title="Редактировать данные" class="green">Редактировать данные...</a>
		{/if}
		
		{if $module == 'catalog' || $module == 'catalog.dev'}
			<a style="margin-right: 15px;" class="green thickbox" href="/#TB_inline?width=514&height=280&inlineId=change-form" title="" rel="nofollow">Изменить заголовок</a>
		
			{if $USER->meta->mjteam == 'super-admin'}
				<a style="margin-right:15px;color:orange" href="/{$module}/{$user.user_login}/disabled/?width=514&height=680" rel="nofollow" class="green thickbox" title="Отключённые работы"><span>Отключённые работы</span></a>
			{/if}				
		{/if}
		
		
		{if $module == 'catalog' || $module == 'catalog.dev' || $PAGE->module == 'mynotees'}
			<a style="{if $module == 'mynotees'}border: none;font-weight:bolder{else}border-style:solid;{/if}" class="green" rel="nofollow" href="/mynotees/{$userId}/">Надписи</a>
		{/if}

		{if $PAGE->module == 'promo'}
			<a class="green" style="{if $PAGE->module == 'promo' && ($PAGE->reqUrl.1 == 'stat' || $PAGE->reqUrl.1 == 'partners')}border: none;font-weight:bolder{else}border-style:solid;{/if}" rel="nofollow" href="/promo/partners/{$USER->id}/">Статистика</a> 		
		{/if}
		
		{if $PAGE->module == 'stat' && $type != 'promo' && $type != 'personalbill'}
			<a class="green" style="{if $PAGE->module == 'stat' && $type == ''}border: none;font-weight:bolder{else}border-style:solid;{/if}" rel="nofollow" href="/stat/">Статистика</a>
		{/if}
		
		{if $PAGE->module == 'promo' && ($PAGE->reqUrl.1 == '' || $PAGE->reqUrl.1 == 'banners' || $PAGE->reqUrl.1 == 'export' || $PAGE->reqUrl.1 == 'landings' || $PAGE->reqUrl.1 == 'forms' || $PAGE->reqUrl.1 == 'api' || $PAGE->reqUrl.1 == 'coupons' || $PAGE->reqUrl.1 == 'createCoupon' || $PAGE->reqUrl.1 ==  'tariff' || $PAGE->reqUrl.1 == 'partners')}
			<a class="green" style="margin: 0 15px;{if $PAGE->module == 'promo' && $PAGE->reqUrl.1 == 'tariff'}border: none;font-weight:bolder{else}border-style:solid;{/if}" rel="nofollow" href="/promo/tariff/">Тарифы</a>
		{/if}
		
		{if $PAGE->module == 'promo'}
			{*<a class="green" style="margin: 0 10px;{if $PAGE->module == 'promo' && ($PAGE->reqUrl.1 == "partners" || $PAGE->reqUrl.1 == "tariff")}border: none;font-weight:bolder{else}border-style:solid;{/if}" rel="nofollow" href="/promo/partners/">Финансы</a>*}
		{/if}
		
		{if $usercash + $usercash_awaiting > 0}	
			<a style="margin: 0 20px;{if $PAGE->module == 'payback'}border: none;font-weight:bolder{else}border-style:solid;{/if}" class="green" rel="nofollow" href="/payback/">Вывод денежных средств</a>
		{/if}			
		
		
		{if $usercash + $usercash_awaiting > 0}	
			{if $PAGE->module != 'bonuses' && $PAGE->module != 'promo' && ($PAGE->module != 'stat' && $type != 'promo')}
				<a style="margin-right: 20px;border-style: solid;" class="green" rel="nofollow" href="/contract/">Договор</a>
			{/if}
		{/if}
		
		{if $PAGE->module == 'promo' || ($PAGE->module == 'stat' && $type == 'promo')}
			<a href="/promo/" class="green" style="margin: 0 15px;{if $PAGE->module == 'promo' && ($PAGE->reqUrl.1 == '' || $PAGE->reqUrl.1 == 'banners' || $PAGE->reqUrl.1 == 'export' || $PAGE->reqUrl.1 == 'landings' || $PAGE->reqUrl.1 == 'forms' || $PAGE->reqUrl.1 == 'api' || $PAGE->reqUrl.1 == 'coupons' || $PAGE->reqUrl.1 == 'createCoupon')}border: none;font-weight:bolder{else}border-style:solid;{/if}">Инструменты</a>
			<a class="green" style="margin: 0 15px;{if $PAGE->module == 'promo' && $PAGE->reqUrl.1 == 'faq'}border: none;font-weight:bolder{else}border-style:solid;{/if}" rel="nofollow" href="/promo/faq/">Помощь</a>
		{/if}			
		
		{if $PAGE->module == 'promo' && ($PAGE->reqUrl.1 == '' || $PAGE->reqUrl.1 == 'banners' || $PAGE->reqUrl.1 == 'export' || $PAGE->reqUrl.1 == 'landings' || $PAGE->reqUrl.1 == 'forms' || $PAGE->reqUrl.1 == 'api' || $PAGE->reqUrl.1 == 'coupons' || $PAGE->reqUrl.1 == 'createCoupon' || $PAGE->reqUrl.1 ==  'tariff' || $PAGE->reqUrl.1 == 'partners')}
		{if $USER->meta->mjteam == 'super-admin'}
		<a class="green" style="margin: 0 15px;border-bottom:1px dashed orange;{if $PAGE->module == 'promo' && $PAGE->reqUrl.1 == 'partners'}font-weight:bolder{else}{/if}" rel="nofollow" href="/promo/partners/">Партнёры</a>
		{/if}
		{/if}
		
		{if $USER->authorized}
			<div class="stata-email-send right clearfix {if $USER->meta->saleReport != 'no'}activ{/if}">
				{*<input type="checkbox" name="email_info" class="left"/>
				<div class="email_info">
					<span class="email-b first {if $sms_info_checked == 'TRUE'}on{/if}" title="выкл"></span>
					<span class="email-b last {if !$sms_info_checked}on{/if}" title="выкл"></span>
				</div>*}
				<div class="hint-what">
					<span>{if $USER->meta->saleReport == 'no'}Не получать сводку продаж{else}Получать сводку продаж {if $USER->meta->saleReport == 'everyday'}ежедневно{elseif $USER->meta->saleReport == 'everyweek'}еженедельно{elseif $USER->meta->saleReport == 'everymonth'}ежемесячно{/if}{/if}</span>
					<ul style="display:none">
						<li class="dropdown-arrow"><div></div></li>						
						<li {if $USER->meta->saleReport == 'everyday'}class="on"{/if}><a href="/editprofile/saleReport/everyday/" text="ежедневно">Ежедневно</a></li>
						<li {if $USER->meta->saleReport == 'everyweek'}class="on"{/if}><a href="/editprofile/saleReport/everyweek/" text="еженедельно">Еженедельно</a></li>
						<li {if $USER->meta->saleReport == 'everymonth'}class="on"{/if}><a href="/editprofile/saleReport/everymonth/" text="ежемесячно">Ежемесячно</a></li>
						{* if $USER->meta->saleReport != 'no' *}
						<li class="no {if $USER->meta->saleReport == 'no'}on{/if}"><a href="/editprofile/saleReport/no/">Не получать</a></li>
						{* /if *}
					</ul>
				</div>	
			</div>
			
			{literal}
			<script>
				$('.stata-email-send').find('a').parent('li').click(function() {
					var self = this;
					if (!$(self).hasClass('on')) {
						$('.stata-email-send').find('li').removeClass('on');
						var href = $(self).children('a').attr('href');
						$.get(href);
						
						
						if(href.indexOf('no')>=0){
							$(self).closest('.stata-email-send').removeClass('activ');
							$(self).closest('.stata-email-send').find('.hint-what > span').text('Не получать сводку продаж');
						}else{
							$(self).closest('.stata-email-send').addClass('activ');
							$(self).closest('.stata-email-send').find('.hint-what > span').text('Получать сводку продаж '+$(self).children('a').attr('text'));
						}
						
						$(self).addClass('on');
					}
					
					return false;
				})
			</script>
			{/literal}	
		{/if}
		
		
		
		{if $PAGE->module != 'bonuses' && $PAGE->module != 'promo' && $PAGE->module != 'payback'}
			{if $PAGE->module == 'stat' && $type == 'promo'} 
			{else}				
				<a style="margin-top:14px;float:right;line-height:16px; {if $PAGE->module == 'stat' && $type == 'salemore'}border:none;font-weight:bolder{else}border-style: solid;{/if}" rel="nofollow"  href="/stat/salemore/" class="green">Как продавать больше</a> {* /faq/135/?height=550&width=600 *}
				<div style="clear:both"></div>
			{/if}
		{/if}
		
	{/if}
	
</div>

{if $PAGE->module == 'promo' && ($PAGE->reqUrl.1 == '' || $PAGE->reqUrl.1 == 'stat' || $PAGE->reqUrl.1 == 'banners' || $PAGE->reqUrl.1 == 'export' || $PAGE->reqUrl.1 == 'landings' || $PAGE->reqUrl.1 == 'forms' || $PAGE->reqUrl.1 == 'api' || $PAGE->reqUrl.1 == 'coupons' || $PAGE->reqUrl.1 == 'createCoupon' || $PAGE->reqUrl.1 ==  'tariff' || $PAGE->reqUrl.1 == 'partners')}
<div class="tabz2 notborderdev">
	
	
		{if $PAGE->reqUrl.1 == 'stat' || $PAGE->reqUrl.1 == 'partners'}
			
			{if $USER->authorized}
				<a class="green" style="margin-right:15px;{if $PAGE->module == 'promo' && $PAGE->reqUrl.1 == 'stat'}border: none;font-weight:bolder{else}border-style:solid;{/if}" rel="nofollow" href="/promo/stat/">Промо-ссылки</a>
			{/if}
		
		{else}
		
			{if $PAGE->reqUrl.1 != 'partners' && $PAGE->reqUrl.1 != 'tariff'}
				<a class="green" style="{if $PAGE->module == 'promo' && $PAGE->reqUrl.1 == 'banners'}border:none;font-weight:bolder{else}border-style:solid;{/if}" rel="nofollow" href="/promo/banners/">Баннеры</a>
				<a class="green" style="margin-left: 20px;margin-right: 15px;{if $PAGE->module == 'promo' && $PAGE->reqUrl.1 == 'export'}border: none;font-weight:bolder{else}border-style:solid;{/if}" rel="nofollow" href="/promo/export/">Товарная выгрузка</a>
				<a class="green" style="margin: 0 15px;{if $PAGE->module == 'promo' && $PAGE->reqUrl.1 == 'landings'}border:none;font-weight:bolder{else}border-style:solid;{/if}" rel="nofollow" href="/promo/landings/">Посадочные страницы</a>
				<a class="green" style="margin: 0 15px;{if $PAGE->module == 'promo' && $PAGE->reqUrl.1 == 'forms'}border: none;font-weight:bolder{else}border-style:solid;{/if}" rel="nofollow" href="/promo/forms/">Формы</a>
				<a class="green" style="margin: 0 15px;{if $PAGE->module == 'promo' && $PAGE->reqUrl.1 == 'api'}border: none;font-weight:bolder{else}border-style:solid;{/if}" rel="nofollow" href="/promo/api/">API</a>
				<a class="green" style="margin: 0 15px;{if $PAGE->module == 'promo' && ($PAGE->reqUrl.1 == 'coupons' || $PAGE->reqUrl.1 == 'createCoupon')}border: none;font-weight:bolder{else}border-style:solid;{/if}" rel="nofollow" href="/promo/coupons/">Купоны</a>
				<a class="green" style="margin: 0 15px;border-style:solid;" rel="nofollow" href="/catalog/selected/{$USER->user_login}/">Избранное</a>
			{/if}
			
		{/if}
	
	
</div>
{/if}		       

{/if}