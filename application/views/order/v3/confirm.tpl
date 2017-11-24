<!--/templates/order/order.dev.tpl-->
<link rel="stylesheet" href="/css/support.dev.css" type="text/css" media="screen, projection" />

{literal}
	<style type="text/css">
	.sub-basket form input.bbutton, .sub-basket form input.button {margin-left:120px;font-size: 25px !important}
	/*шапка подгонка*/
	.p-head-cont .p-head .menu {padding-right: 21px;}
</style>
{/literal}

<script type="text/javascript" src="/js/ajaxupload.js"></script>
<script type="text/javascript">

	/**
	 * SUBMIT feedback FORM
	 */
	function feedSend() {
		$('.question input[type=submit]').attr('disabled', 'disabled');

		var error = "";
		
		var name 		= $("#feedbackname").val();
		var email		= $("#feedbackemail").val();
		var text		= $("#feedbacktext").val();
		var error_pict  = $("#pic").val();
						
		if (name == "")		{ error += "Empty field Name<br />"; $("#feedbackname").css("border","2px solid red");  }
		if (email == "")	{ error += "Empty field Email<br />"; $("#feedbackemail").css("border","2px solid red"); }
		if (text == "")		{ error += "Empty field Text<br/>"; $("#feedbacktext").css("border","2px solid red");  }		
		
		if (error.length != 0) { $('.question input[type=submit]').removeAttr('disabled'); return false; }
		else
		{
			$.post("/feedback/saveNew/", { name:name, email:email, text:text, error_pict : error_pict },
			function (data) {
				var pos         = data.indexOf(":");
				var state       = data.substring(0, pos);
				var message     = data.substring(pos+1);

				if (state == "err") {
					if (message == 1) alert('пустое имя');
					if (message == 2) alert('нет email');
					if (message == 3) alert('не введён текст');
				}
				else {
					
					var fid = message;
					
					if (fid == 0)
						alert('Извините, но при сохранении произошла ошибка');
					/*else
						if (subj == 1 || subj == 2) {
							// update basket id
							var basket = $("#feedbackbasket").val();
							$.post("/feedback/updateBasket/" + fid, { basket:basket });
						}*/
					
					$("#feedbacktext").val('');
					$("#pic").val('');
					$('#pic_name').html('');
					alert("Спасибо! Ваше сообщение отправлено.");
				}

				$('.question input[type=submit]').removeAttr('disabled');
			});
		}
		return false;
	}
    

	
	
	$(document).ready(function(){
		
		{if $active_order.user_basket_id}
			if (sessionStorage.getItem('confirmRefresh')!={$active_order.user_basket_id}){
				trackUser('Order.v3','переход в confirm',(typeof sbjs!='undefined'?sbjs.get.current.src:''));//трек гугл аналитик
				sessionStorage.setItem('confirmRefresh', {$active_order.user_basket_id});
			}
		{/if}
		
		$('input[placeholder],select[placeholder],textarea[placeholder]').
		focus(function(){
			var pl = $(this).attr('placeholder');
			if (!$(this).attr('_placeholder'))
				$(this).attr('_placeholder', (pl?pl:''))
			$(this).attr('placeholder','').css('font-style', 'normal');
		}).blur(function(){
			var pl = $(this).attr('_placeholder');
			$(this).attr('placeholder', pl)
			if ($.trim($(this).val()) == '')
				$(this).css('font-style', 'italic');
		}).each(function(){
			$(this).blur();
		});
		
		//рaспечатать
		$('.print-paper').click(function(){
			window.print();
		});
		
		$('.payment_method a').click(function(){
			$('.payAssist').submit();
			return false;
		});

		$('input[name=sms_info]').change(function(){
			$.get('/ajax/order_sms_info/', {ldelim}'basket_id' : '{$order.user_basket_id}', 'sms_info' : $(this).attr('checked'){rdelim}, function() {
			});
		});		
		
		$('#feedbackform').submit(function(){			
			feedSend();
			return false;
		});
		
		$('.read-payment').click(function(){
			$('#type-payment').remove();
			//$(this).prev().remove();
			$('#form-type-payment').show();
			//$(this).next().show();
			$(this).css('visibility','hidden');
			//$(this).remove();
		});
		
		if ($('#error_pict').length > 0)
		$.ajax_upload('#error_pict', {
				action : '/feedback/uploadPict/',
				name : 'file',
				onSubmit : function(file, ext) {
					// показываем картинку загрузки файла
					$('#pic_loading').show();
					$('.support-question input[type=submit]').attr('disabled', 'disabled');
					this.disable();
				},
				onComplete : function(file, response) { 
					// убираем картинку загрузки файла
					// снова включаем кнопку
					this.enable();
					$('.support-question input[type=submit]').removeAttr('disabled');
					
					r = eval('(' + response + ')');

					if (r.status == 'ok') {
						// показываем что файл загружен
						$('#pic').val(r.id);						
						$('#pic_name').html(file).show();
					}
					
					if (r.status == 'error') 
						alert(r.message);
					
					$('#pic_loading').hide();
				 },
			  });

		$.ajax_upload('#supportFile', {
				action : '/feedback/uploadPict/',
				name : 'file',
				onSubmit : function(file, ext) {
					// показываем картинку загрузки файла
					$('#chat-form #support_pic_loading').show();
					$('.support-question input[type=submit]').attr('disabled', 'disabled');
					this.disable();
				},
				onComplete : function(file, response) { 
					// убираем картинку загрузки файла
					// снова включаем кнопку
					this.enable();
					$('.support-question input[type=submit]').removeAttr('disabled');
					
					r = eval('(' + response + ')');

					if (r.status == 'ok') {
						// показываем что файл загружен
						$('#support_pic').val(r.id);
						$('#support_pic_name').html(file).show();
					}
					
					if (r.status == 'error') 
						alert(r.message);
					
					$('#chat-form #support_pic_loading').hide();
				 },
			  });
			  
		
		$('#chat-form textarea').focus(function(){
			if ($('#chat-form textarea').val() == $('#chat-form textarea').attr('caption'))
				$('#chat-form textarea').val('');
		});
		
		$('#chat-form textarea').blur(function(){
			if ($('#chat-form textarea').val() == '')
				$('#chat-form textarea').val($('#chat-form textarea').attr('caption'));
		})
		

		$('#chat-form').submit(function() {
			if ($('#chat-form textarea').val().length == 0) {
				return false;
			}
		});
		
		$('#chat-form input:submit').click(function(){
			
			var b = this;
			var u = 10;
			var m = $('#chat-form textarea').val();
			var fid = $('#support_pic').val();
			var p = $('#chat-form input[name=parent]').val();
			
			if (m.length == 0) {
				return false;
			}
			
			$(this).attr('disabled', 'disabled').hide();
			$('#chat-form #support_loader').show();
			 
			$.post('/messenger/', { 'task' : 'send', 'attach': fid, 'text' : m, 'user_id' : u, 'parent' : p }, function(r) {				
				$('.support-question.chat textarea').val('');
				$('#support_pic_name').html('');
				$('#support_pic').val('');
				var row = $('.support-question.chat .items .item.hidden:first').clone().removeClass('hidden');
				$(row).find('.text-data').text(m);
				$(row).prependTo('.support-question.chat .items').show();
				
				$(b).removeAttr('disabled').show();
				$('#support_loader').hide();
			});
			
			return false;
		});
		
	});
</script>

{include file="order/v3/order_top_menu.tpl"}

<div class="confirm clearfix">
	<!--верхнее меню-->

	<div class="left">	
		<div class="bg">	
			<div class="wolna top"></div>
			
			<div class="y_zakaz_oform">{$L.CONFIRM_your_order}</div>
			<div class="your-tel2">
				<span>{$L.CONFIRM_your_phone} {$order.phone_1}</span>&nbsp;<b class="b">{$order.phone_2}</b>
			</div>			
			<div class="number-order_y2">{$L.CONFIRM_n_order}</div>		
			<div class="order-number2">{$order.phone_2}</div>
			
			{*if $order.user_basket_delivery_type == 'user'}
				<div class="wait-sms">Перед приездом дождитесь СМС-оповещения о готовности.</div>
			{/if*}
			
			{if $author_thanks|count > 0 && !$MobilePageVersion}
			<div class="senkY">{$L.CONFIRM_you_support}.</div>
			<div class="authors clearfix">
			
				{foreach from=$author_thanks key="user_id" item="a"}
				<div class="itm">	
					<div class="avat">
						<img src="{$a.user_avatar}" alt="" title="">
					</div>
					<div class="text">
						<a class="name" rel="nofollow" title="{$a.user_login}" href="/catalog/{$a.user_login}/">{$a.user_login}</a>
						{if $a.user_city_name}
						<span class="city">{$a.user_city_name}</span>
						{/if}
						<div class="rub">Автор получит {$a.author_payment} руб.</div>
					</div>
					<div class="help_author"><a href="/help_author/{$user_id}/?good_id={$a.good_id}&width=685&height=415" class="thickbox" rel="nofollow">{$L.CONFIRM_support_author}</a></div>	
					<div style="clear:both"></div>
				</div>
				{/foreach}
			</div>
			{/if}
			
			<!--div class="or-number" style="visibility:hidden;">
				<span>или 3651941</span>
				<a class="help-popup-btn help-popup-gray" href="http://www.maryjane.ru/faq/141/?TB_iframe=true&amp;height=500&amp;width=600" rel="nofollow">?</a>
			</div-->
			<div class="wolna bottom"></div>
		</div>
		
		<br><br>
		<div class="thanks-order2">{$L.CONFIRM_thank_you_for}!</div>
		<div class="social">
			{literal}
			<!-- ВКОНТАКТЕ --><noindex >
			<div style="width:83px;float: left;margin-left:10px;">
				<!-- Put this script tag to the <head> of your page -->
				<script type="text/javascript" src="http://vk.com/js/api/share.js?11" charset="windows-1251"></script>

				<!-- Put this script tag to the place, where the Share button will be -->
				<script type="text/javascript"><!--
				document.write(VK.Share.button('{/literal}http://www.maryjane.ru/{literal}',{type: "round", text: "+"}));
				--></script>
			</div></noindex >
			
			
			<!-- FACEBOOK -->	
			<div style="float: left;">			
				<div class="fb-like" style="display: inline-block;" data-href="http://www.maryjane.ru/" data-send="false" data-layout="button_count" data-width="450" data-show-faces="true"></div>
				<div id="fb-root" style="display:none;"></div>
				<script>(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				  js = d.createElement(s); js.id = id;
				  js.src = "//connect.facebook.net/en_EN/all.js#xfbml=1&appId=192523004126352";
				  fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));</script>
			</div>
			{/literal}
			
			{*
				
			<!-- twitter -->
			<div style="width:113px;float:left;margin-left:5px">
				<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-url="{/literal}http://www.maryjane.ru/{literal}" rel="nofollow">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
			</div>
			
			<!-- Google Разместите этот тег в теге head или непосредственно перед закрывающим тегом body -->
			<div style="float:left">	
				<script type="text/javascript" src="https://apis.google.com/js/plusone.js">{lang: 'ru'}</script><!-- Разместите этот тег в том месте, где должна отображаться кнопка +1 --><g:plusone size="medium" href="{/literal}http://www.maryjane.ru/{literal}"></g:plusone>
			</div>
			*}
			
		</div>
		<div style="clear:both"></div>
		
		{*<br/><br/>
		<div class="history_orders_2013">
			<div class="order-history">
				<div class="order-select" style="min-height:70px;width:581px">
					{include file="order/history/details.tpl"}
					<div style="clear:both;"></div>
				</div>
			</div>
			<div style="clear:both"></div>
		</div>*}
	</div>
	
	<div class="right">	
		{include file="order/v3/pay.tpl"}
		
		<div class="social_black clearfix" style="margin: 15px auto 0">
			<a class="so-fb" target="_blank" href="/goto/?target=https://www.facebook.com/maryjaneisonmybrain" title="Facebook" rel="nofollow"></a>
			<a class="so-vk" target="_blank" href="/goto/?target=http://vk.com/club1797113" title="ВКонтакте" rel="nofollow"></a>
			{*<a class="so-tw" target="_blank" href="https://twitter.com/maryjaneru" title="twitter" rel="nofollow"></a>
			<a class="so-gg" target="_blank" href="https://plus.google.com/110170198345311125008/" title="google" rel="nofollow"></a>*}
			<a class="so-in" target="_blank" href="/goto/?target=http://instagram.com/maryjane_ru" title="Instagram" rel="nofollow"></a>
		</div>
				
		<div class="support-question chat clearfix">
			<h3>{$L.CONFIRM_feedback}</h3>
			<div class="items">
			<div class="item hidden" style="display:none;">
				<div class="name">{$USER->user_login}</div>
				<div class="text">
					<span class="date"></span>
					<span class="text-data"></span>
				</div>
			</div>
			{*foreach from=$messages item="m"}
				<div class="item {if $m.user_from_id != $USER->user_id}in{/if}">
					<div class="name">{$m.user_login}</div>
					<div class="text">
						<span class="date">{$m.send_date}</span>
						{$m.text}
					</div>
				</div>
			{/foreach*}
			</div>
			<form id="chat-form" method="post">
				<textarea name="message" placeholder="{$L.CONFIRM_what_we_can}?" id=""></textarea>
				<input type="hidden" name="parent" value="{$parent}" />
				<input type="hidden" name="pic" id="support_pic" />
				<div class="attach" rel="nofollow">{$L.CONFIRM_attach_file}
					<div style="opacity:0;position:absolute;left:0px;top:0px;">
						<input id="supportFile" name="" type="file" />
					</div>
					<div style="display:none;position:absolute;right:20px;top:0" id="support_pic_name"></div>
					<img style="display:none;position:absolute;right:5px;top:0" src="http://www.maryjane.ru/images/loading2.gif" id="support_pic_loading" width="15px" />
				</div>				
				<div id="support_loader" style="display:none;"><img src="http://maryjane.ru/images/buttons/ajax-loader-img-2.gif" alt="loading ..." /></div>
				<div class="submit">
					<input type="submit" name="send" value="{$L.CONFIRM_send}">
				</div>
			</form>
		</div>
	</div>
</div>