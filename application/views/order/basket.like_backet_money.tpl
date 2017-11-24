{* ещё пример http://www.pixelcom.crimea.ua/facebook-api.html *}
	<script src="http://connect.facebook.net/ru_RU/all.js"></script>
	<script src="http://vkontakte.ru/js/api/openapi.js" type="text/javascript"></script>
	<style>
		#message, #messageVK { color: #3B5998; text-align:right; float:right; }
		.social { width: 10px; float: right; margin-top: 10px; margin-left: 10px; margin-right:6px; clear:both; }
		.fb-social, .vk-social { /* padding-right: 9px;*/ }
		.vk-social { top:-4px;position:relative; }
		.social img { width:14px; }
	</style>
<!-- h1>Заголовок</h1 --> 
<!-- img src="/images/catalog/logo1_201207.jpg" /--> 

<div class="fb-social facebook">
	<div id="fb-root"></div>
	<!-- div class="fb-like" data-send="true" data-width="450" data-show-faces="true"></div -->
	<div class="social fb facebook">
		<a href="#" id="fb_login" title="facebook" onclick="trackUser('Order.v3', 'Поделитесь с друзьями и получите скидку 100 руб', $(this).attr('title'));">
			<img src="/images/social/fcb.gif" style="margin:0;" />
		</a>
		<a href="#" id="fb_wall"  style="display:none;">
			<img src="/images/social/fcb.gif" style="margin:0;" />
		</a>
	</div>
	<p id="message">{$L.BASKET_share_ftiends}</p>
	<div style="clear:both;"></div>
</div>

{if $USER->user_id == 86455 || $USER->user_id == 105091}
<div class="vk-social vk" style="border-bottom: 1px dashed orange;">
		<div class="social vk">
			<a href="#" id="vk_login" title="vkontakte" onclick="trackUser('Order.v3', 'Поделитесь с друзьями и получите скидку 100 руб', $(this).attr('title'));">
				<img src="/images/social/vkontakte.gif" style="margin:0;" />
			</a>
			<a href="#" id="vk_wall"  style="display:none;">
				<img src="/images/social/vkontakte.gif" style="margin:0;" />
			</a>
		</div>
		<p id="messageVK">{$L.BASKET_share_ftiends}</p>	
	<div style="clear:both;"></div>
</div>
{/if}

{literal}
<script type="text/javascript" src="/js/like.bonus.js" ></script>
<script type="text/javascript">
    initLikeBonusBasket();
</script>
{/literal}
