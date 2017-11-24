{if $loginForm}
	{literal}
		<style type="text/css">
			.error a {color:#2F76BD}
			.close-btn, .small_title{display:none}
			.q-login-form-v2 .form-wrap {margin:50px 35px 5px!important}
		</style>		
	{/literal}
	
	<link rel="stylesheet" href="/css/registration_page.css" type="text/css" media="screen"/>
	<script type="text/javascript" src="/js/registration.v2.js"></script>
	
	{if $USER->client->ismobiledevice == '1' && $USER->client->istablet == 0}
		<a style="margin:0 0 0 10px;" href="/" rel="nofollow">
			<img width="121" height="21" title="{$L.HEADER_title}" src="/images/logo_mj_small.gif"/>
		</a>
	{/if}
		<p class="ifYouNew">Если Вы новый пользователь, пожалуйста, <a href="#" _href="/registration/" onclick="document.location = $(this).attr('_href');return false;" rel="nofollow">зарегистрируйтесь</a>.<a class="registration-cha-lang" href="#" _href="/language/{if $PAGE->lang == 'ru'}en{else}ru{/if}/" onclick="document.location = $(this).attr('_href');return false;" rel="nofollow" title="{if $PAGE->lang == 'ru'}Switch to English{else}Перейти на Русский{/if}">{if $PAGE->lang == 'ru'}Switch to English{else}Перейти на Русский{/if}</a></p>

	{if $error.ERROR}
		<div class="loginServerErr">
			{$error.ERROR}
		</div>
	{/if}
		
	<div class="loginSimple">
		
		{include file="login/quick2.tpl"}
	</div>
{/if}
