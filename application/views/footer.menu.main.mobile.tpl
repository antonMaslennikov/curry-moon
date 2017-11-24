<div class="menu-mainPageBottom">
	{if $module!='blog'}
	<div class="social_black">
		<a class="so-fb" target="_blank" href="/goto/?target=https://www.facebook.com/maryjaneisonmybrain" title="Facebook" rel="nofollow"></a>
		<a class="so-vk" target="_blank" href="/goto/?target=http://vk.com/club1797113" title="ВКонтакте" rel="nofollow"></a>
		{*<a class="so-tw" target="_blank" href="https://twitter.com/maryjaneru" title="twitter" rel="nofollow"></a>
		<a class="so-gg" target="_blank" href="https://plus.google.com/110170198345311125008/" title="google" rel="nofollow"></a>*}
		<a class="so-in" target="_blank" href="/goto/?target=http://instagram.com/maryjane_ru" title="Instagram" rel="nofollow"></a>
		<div style="clear:both;"></div>
	</div>
	{/if}
	<ul>						
		<li><a href="/about/" rel="nofollow" title="{$L.FOOTER_contacts}">{$L.FOOTER_contacts}</a></li>
		{if $module != 'voting'}
		<li><a href="/voting/last/" title="{$L.FOOTER_menu_voting}" rel="nofollow">{$L.FOOTER_menu_voting}</a></li>
		{/if}
		<li><a href="/blog/" rel="nofollow" title="{$L.FOOTER_menu_blog}">{$L.FOOTER_menu_blog}</a></li>		
		<li><a href="/catalog/category,futbolki/new/" rel="nofollow" title="">Каталог</a></li>		
		<li><a href="/faq/#delivery" rel="nofollow" title="{$L.FOOTER_menu_delivery}">{$L.FOOTER_menu_delivery}</a></li>
		<li><a href="/faq/" rel="nofollow" title="{$L.FOOTER_menu_help}">{$L.FOOTER_menu_help}</a></li>
	</ul>

	<a href="" rel="nofollow" class="SwitchToStandardVersion">Переключить сайт в стандартную версию</a>

	<div class="description">
		<p>&copy;2003-{$datetime.year} {$L.FOOTER_slogan_2}</p>
		<p>{$L.FOOTER_copyrights}</p>		
	</div>
</div>