{if $module != 'foto-na-futbolku'}
<div class="footer-plashka">
	{if $module == 'stat'}	
		<img src="/images/banners/senddrawing_icon_best2.gif" alt="" title="" width="980" height="58" border="0"/>
	{else}
		{if $module == 'basket' || $module == 'order.v3'}
			<img src="/images/banners/icon_best2a.gif" alt="Вы рисуете мы продаем. Лучшие авторы. Конкурс 15000 руб. Нам 11 лет Магазин №1. Доставка 24 часа. Высшее качество. Доставка 365 дней" title="" width="980" height="74" border="0">			
		{else}
			<img src="/images/banners/icon_best2.gif" alt="Вы рисуете мы продаем. Лучшие авторы. Конкурс 15000 руб. Нам 11 лет Магазин №1. Доставка 24 часа. Высшее качество. Доставка 365 дней" title="" width="980" height="74" border="0">
			<a style="position:absolute;top:4px;left:5px;width:140px;height:60px" href="/voting/competition/main/" rel="nofollow" title="Дизайн конкурсы - каждые две недели 15000 рублей за лучшую работу"></a>
			<a style="position:absolute;top:4px;left:155px;width:125px;height:60px" href="/people/designers/" rel="nofollow" title="Художники и Дизайнеры"></a>
			<a style="position:absolute;top:6px;left:293px;width:125px;height:60px" href="/voting/" rel="nofollow" title="Дизайн конкурс — каждые две недели 15000 рублей за лучшую работу"></a>	
			<a style="position:absolute;top:7px;right:138px;width:125px;height:60px" href="/faq/#delivery" rel="nofollow" title="Доставка 24 часа"></a>
			<a style="position:absolute;top:7px;right:3px;width:125px;height:60px" href="/faq/group/10/" rel="nofollow" title="Возврат 365 дней."></a>
		{/if}
	{/if}
</div>
{/if}

{if $module=="customize" || $module=="stickermize"}	
<div class="bottom_menu_level_2 {$PAGE->lang}" style="border-top:1px solid #b5b8b8;border-bottom:0;
margin-bottom:0;">
	<ul>
		<li>{if $module == 'faq'}{$L.FOOTER_menu_help}{else}<a href="/faq/" rel="nofollow" title="{$L.FOOTER_menu_help}">{$L.FOOTER_menu_help}</a>{/if}</li>
		<li><a href="/faq/#order" rel="nofollow" title="{$L.FOOTER_menu_order}">{$L.FOOTER_menu_order}</a></li>
		<li><a href="/faq/#delivery" rel="nofollow" title="{$L.FOOTER_menu_delivery}">{$L.FOOTER_menu_delivery}</a></li>
		<li style="margin-right:0;">{if $module == 'contacts'}{$L.FOOTER_contacts}{else}<a href="/about/" rel="nofollow" title="{$L.FOOTER_contacts}">{$L.FOOTER_contacts}</a>{/if}</li>		
		<div style="clear: both;"></div>
	</ul>		
</div>
{/if}


<div class="basket-footer" style="width:980px;padding:10px 0 0px;border-top: 1px solid #b5b8b8;color:#6b7172;line-height:20px;font-size:12px;margin:0px auto;position:relative;">
	{if $module=="customize" ||$module=="stickermize"}	
		<a rel="nofollow" class="logo-link" title="{$L.FOOTER_slogan_0}" href="http://www.maryjane.ru/" style="float:left;width:121px;height:21px;margin:0 5px 10px 0;"><img border="0" alt="{$L.FOOTER_slogan_0}" src="/images/logo_mj_small.gif"></a>
		<div style="float:left;margin:-5px 10px 0 25px;">
			<img border="0" alt="16" width="31" height="31" src="/images/16plus.png"/>	
		</div>
		<a rel="nofollow" title="{$L.FOOTER_rules_of_using}" href="/agreement/" style="margin-top:4px;color:#CCCCCC;display:block;float:left;">{$L.FOOTER_rules_of_using}</a>						
		<a rel="nofollow" title="" href="/privacy-policy/" style="margin-top:4px;color:#CCCCCC;margin-left:5px;float:left;">(en)</a>	

		<div class="social_black" style="float:right;">
			<a class="so-fb" target="_blank" href="https://www.facebook.com/maryjaneisonmybrain" title="Facebook" rel="nofollow"></a>
			<a class="so-vk" target="_blank" href="http://vk.com/club1797113" title="ВКонтакте" rel="nofollow"></a>
			{*<a class="so-tw" target="_blank" href="https://twitter.com/maryjaneru" title="twitter" rel="nofollow"></a>
			<a class="so-gg" target="_blank" href="https://plus.google.com/110170198345311125008/" title="google" rel="nofollow"></a>*}
			<a class="so-in" target="_blank" href="http://instagram.com/maryjane_ru" title="Instagram" rel="nofollow"></a>
			<div style="clear:both;"></div>
		</div>
		<div style="clear:both;"></div>
	{/if}
	<div class="footer-text" style="float: left;">
	&copy;2003-{$datetime.year} {$L.FOOTER_slogan_1}<br/>
	{$L.FOOTER_slogan}
	</div>
	<div style="clear:both;"></div>
</div>	
