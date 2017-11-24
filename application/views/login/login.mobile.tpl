{* Шаблон не используется , все в login!!!!!!!!!!!*}


<link rel="stylesheet" href="/css/registration_page.css" type="text/css" media="screen"/>
<script type="text/javascript" src="/js/registration.js"></script>
{literal}
	<style type="text/css">
		.close-btn, .small_title{display:none}
		
		
		
		/**скрываем лишнее под мобильных*/
		.ismobiledevice #wrapper {width: 320px;}
		.ismobiledevice .b-header,
		.ismobiledevice .your-link,
		.ismobiledevice .fast-red-deliv,
		.ismobiledevice .ypravlenie_zakazom,
		.ismobiledevice .need-hepl,
		.ismobiledevice .bottom_menu,
		.ismobiledevice .bottom_menu_level_2,
		.ismobiledevice .bottom_menu_dealer{display: none;}
	</style>
{/literal}

<script type="text/javascript">
{if $USER->user_id == 27278 || $USER->user_id == 6199 || $USER->user_id == 86455}
	window.dev = true;
{else}
	window.dev = false;
{/if}
window.currentuser = { user_id: {$USER->user_id}-0 };
window.module = '{$module}';

window.link={ link: '{$link}', page: '{$page}' };
</script>


<a style="margin:0 0 0 10px;" href="/" rel="nofollow">
	<img width="121" height="21" title="{$L.HEADER_title}" src="/images/logo_mj_small.gif"/>
</a>	
			
{*<p class="ifYouNew" style="text-align: center;padding:45px 0 60px;border-bottom:0px solid #EFEFEF;font-size:30px;font-weight: bold;">Если Вы новый пользователь, <a href="/registration/" rel="nofollow">зарегистрируйтесь</a>.</p>*}
<div class="loginSimple">
	{if $error}
	<div class="serverErr">
		{$error.ERROR}
	</div>
	{/if}
	{include file="login/quick2.tpl"}
</div>
