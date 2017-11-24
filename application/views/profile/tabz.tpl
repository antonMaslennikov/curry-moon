<div class="tabz clearfix">
	<!--noindex-->
	
	<a href="{if $PAGE->module == 'profile' || $PAGE->module == 'editprofile'}#{else}/profile/{$userId}/{/if}" class="{if $PAGE->module == 'profile' || $PAGE->module == 'editprofile'}active{/if}" rel="nofollow">Профиль</a>
    
    {if $userInfo.goodsCount > 0 || $USER->id == $userId}
	<a href="/catalog/{if $PAGE->module == 'orderhistory'}{$USER->user_login}{else}{$userInfo.user_login}{/if}/" class="{if $PAGE->module == 'portfolio' || $PAGE->module == 'catalog' || $PAGE->module == 'catalog.dev' || $PAGE->module == 'mynotees'}active{/if}" rel="nofollow">Мои Работы</a>
	{/if}
	
	{if $USER->id == $userId}
		<a href="/orderhistory/" class="{if $PAGE->module == 'orderhistory'}active{/if}" rel="nofollow">Мои заказы</a>
		<a href="/stat/personalbill/" class="{if $PAGE->module == 'bonuses' || $PAGE->module == 'payback' || ($PAGE->module == 'stat' && $PAGE->reqUrl.1 == 'personalbill')}active{/if}" rel="nofollow">{if $cash > 0}Финансы{else}Финансы{/if}</a>
	{/if}
	
	{if $USER->id == $userId}
		<a rel="nofollow" {if $PAGE->module == 'stat' && $type == ''}class="active"{/if}href="/stat/">Продажи</a> 
    	<a rel="nofollow" {if $PAGE->module == 'stat' && $type == 'promo' || $PAGE->module == 'promo'}class="active"{/if} href="/promo/">Парнерская программа</a>
	{/if}
	
    {if $userInfo.selectedCount > 0 || $USER->id == $userId}
	<a href="/selected/{$userId}/" class="{if $PAGE->module == 'selected'}active{/if}" rel="nofollow">Мне нравится</a>
	{/if}
    
	{if $userInfo.postCount > 0 || $USER->id == $userId}
    <a href="/blog/user/{$userId}/" class="{if $PAGE->module == 'blog'}active{/if}" rel="nofollow">Мои Посты</a>
		{if $drafts > 0 && $PAGE->module == 'blog' && ($USER->id == $user_id || $USER->id == 27278 || $USER->id == 6199)}
			<a href="/{$PAGE->module}/user/{$user.user_login}/drafts/" class="faketabz" rel="nofollow">Черновики ({$drafts})</a>
		{/if}	
    {/if}
    
	
	{if $userInfo.pictCount >= 3 || $USER->id == $userId}
	<a href="/myphoto/{$userId}/" class="{if $PAGE->module == 'myphoto'}active{/if}" rel="nofollow">Мои Фото</a>
	{/if}
	
    <!--/noindex-->
</div>

{include file="profile/tabz.secondlevel.tpl"}