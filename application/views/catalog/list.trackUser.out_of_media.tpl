{*Не найдено ни одной работы для данной категории носителей*}
{if $SEARCH || $user}
	{literal}
		<script>
			$(document).ready(function(){
				//trackUser('Сообщения','не найдено товаров',window.link.link+' ('+window.link.prevlink+')');//трек гугл аналитик	
				//trackUser('Сообщения','не найдено товаров','{/literal}{$link} ({$HTTP_REFERER}{literal})');//трек гугл аналитик					
				trackUser('Сообщения','не найдено товаров',window.location.href);//трек гугл аналитик	
			 });
		</script>
	{/literal}
{else}
	{literal}
		<script>
			$(document).ready(function(){
				//trackUser('Закончился носитель', window.link.link, window.link.prevlink);//трек гугл аналитик
				//trackUser('Сообщения','закончился носитель',window.link.link+' ('+window.link.prevlink+')');//трек гугл аналитик	
				trackUser('Сообщения','закончился носитель',window.location.href+' ('+window.link.prevlink+')');//трек гугл аналитик	
				//trackUser('Сообщения','закончился носитель','{/literal}{$link} ({$HTTP_REFERER}{literal})');//трек гугл аналитик
			 });
		</script>
	{/literal}
{/if}