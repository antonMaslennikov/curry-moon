<script type="text/javascript">
	{if $USER->authorized}
		var authorized = true;
	{else}
		var authorized = false;
	{/if}
	
	{if $USER->client->ismobiledevice == '1'}	
		var ismobiledevice = true;
	{else}
		var ismobiledevice = false;
	{/if}
	
	{if $MobilePageVersion}
		var MobilePageVersion = true;
	{else}
		var MobilePageVersion = false;
	{/if}	

	{if $USER->user_id == 27278 || $USER->user_id == 6199}
		window.dev = true;
	{else}
		window.dev = false;
	{/if}
	
	window.currentuser = { user_id: {$USER->user_id}-0 };
	
	window.bonusBackPercent ={$bonusBackPercent};//% от кол-ва заказов	
	
	window.module = '{$PAGE->module}';
	window.link={ link: '{$link}', page: '{$page}', prevlink: '{$HTTP_REFERER}'};
	window.CURRENT_CURRENCY = '{$L.CURRENT_CURRENCY}';
	
	VK_APP_ID = {$VK_APP_ID};
</script>
<!--{* {$link} -  в сумках не срабатывает, альтернатива {$CURRENT_URL} *} -->