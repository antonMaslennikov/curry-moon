<link rel="stylesheet" href="/css/order.styles_2013.css" type="text/css" />
<script type="text/javascript" src="/js/calendar.js"></script>

{literal}
<style type="text/css">
	/*правим хедер  не выносить из файла правки хедера*/
	.p-head-cont .p-head .menu {padding-right: 45px;}
	#containerwrap #mainbody #content {width:100%!important;}
	#footer {width: 929px!important;margin-left: 40px!important;}
</style>
{/literal}

<link rel="stylesheet" href="/css/support.dev.css" type="text/css" media="screen, projection" />
<script type="text/javascript" src="/js/ajaxupload.js"></script>

{if !$MobilePageVersion}
	{include file="profile/tabz.tpl"}
{/if}

{include file="order/v3/order_top_menu.tpl"}
	
<div class="history_orders_2013">

	<div class="order-history">
		
		{foreach from=$ORDERS item="o" name="forders"}	
		<div class="{if $basketNum == $o.ID}order-select{else}orders{/if} item_v{$o.ID}">
			{*if $order.user_basket_status != 'canceled'}
				<a href="/order.v3/print/{$o.ID}/" class="print-order" rel="nofollow"target="_blank"></a>
			{/if*}
			<div class="order-title  {$o.STATUSCODE}" {if $basketNum != $o.ID}onclick="location.href='/{$module}/{$o.ID}/'"{/if}>
				<div class="{if $basketNum == $o.ID}no-icon{/if} icon min"></div>
				<div class="order-num">
					<span class="n1">№</span>
					<span class="n2">{$o.phone.1}</span>
					<span class="n3">{if $o.phone.2}{$o.phone.2}{else}{$o.ID}{/if}</span>
				</div>
				<div class="order-date">{$o.date.0} </div>
				<div class="order-timetrack">
					<span>{$o.date.1.0}:{$o.date.1.1} &nbsp;—&nbsp;</span>
					<b class="status">{$o.STATUS}</b>
				</div>
				<input type="hidden" name="id" value="{$o.ID}" />

			</div>
			
			{if $o.STATUSCODE=='delivered' && !$MobilePageVersion}
				<span class="vozvratBib" data-order="{$o.ID}"></span>
			{/if}
				
			{if $basketNum == $o.ID}
				{include file="order/history/details.tpl"}
			{/if}
			
			<div style="clear:both;"></div>
		</div>
		{/foreach}
		
		{if $PAGES}
			<div class="pagination">{$PAGES}</div>
		{/if}
		
	</div>


	<!-- Правый сайдбар -->
	<div class="right">	

		{if $order.user_basket_status != "canceled" && $order.user_basket_status != "delivered" && $order.user_basket_payment_confirm != 'true'}
			
			{include file="order/v3/pay.tpl"}
			
		{/if}
		
		{if !$MobilePageVersion}
			<div class="support-question helps-r" style="margin-top:0px;">
				<h3>{$L.ORDERHISTORY_help}?</h3>
				<ul class="help-sublist">
					<li>—&nbsp;<a class="thickbox" href="/faq/group,10/?TB_iframe=true&height=500&width=600">{$L.ORDERHISTORY_change_return}</a></li>
					<li>—&nbsp;<a class="thickbox" href="/faq/group,9/?TB_iframe=true&amp;height=500&amp;width=600">{$L.ORDERHISTORY_delivery}</a></li>
					<li>—&nbsp;<a class="thickbox" href="/faq/group,7/?TB_iframe=true&amp;height=500&amp;width=600">{$L.ORDERHISTORY_payment}</a></li>
					<li>—&nbsp;<a class="thickbox" href="/faq/98/?TB_iframe=true&amp;height=500&amp;width=600">{$L.ORDERHISTORY_change_payment}</a></li>
					<li>—&nbsp;<a class="thickbox" href="/faq/97/?TB_iframe=true&amp;height=500&amp;width=600">{$L.ORDERHISTORY_cancel}</a></li>
				</ul>
				<div class="sled">
					<div class="help-link">
						<a href="http://dpd.ru/ols/trace/" rel="nofollow" target="_blank">{$L.ORDERHISTORY_DPD_track}</a>
					</div>
					<div class="help-link">
						<a href="http://www.russianpost.ru/tracking20/" rel="nofollow" target="_blank">{$L.ORDERHISTORY_Russian_post_track}</a>
					</div>
				</div>
				
				<div style="clear:both;"></div>
			</div>
		{/if}
		
		<div class="support-question chat">
			<h3>{if $messages|count > 0}{$L.ORDERHISTORY_feedback}{else}Остался вопрос?{/if}</h3>
			<div class="items">
				<div class="item hidden" style="display:none;">
						<div class="name">{$USER->user_login}</div>
						<div class="text">
							<span class="date"></span>
							<span class="text-data"></span>
						</div>
				</div>
				{foreach from=$messages item="m"}
					<div class="item {if $m.user_from_id != $USER->user_id}in{/if}">
							<div class="name">{$m.user_login}</div>
							<div class="text">
								<span class="date">{$m.send_date}</span>
								<span class="text-data">{$m.text}</span>
							</div>
					</div>
				{/foreach}
			</div>
				<form id="chat-form" method="post">
					<textarea name="message" placeholder="{$L.ORDERHISTORY_what_we_can}?" id=""></textarea>
					<input type="hidden" name="parent" value="{$parent}" />
					<input type="hidden" name="pic" id="support_pic" />
					<div class="attach" rel="nofollow">{$L.ORDERHISTORY_attach_file}
						<div style="opacity:0;position:absolute;left:0px;top:0px;">
							<input id="supportFile" name="" type="file" />
						</div>
						<div style="display:none;position:absolute;right:20px;top:0px;" id="support_pic_name"></div>
						<img style="display:none;position:absolute;right:5px;top:0px;" src="http://www.maryjane.ru/images/loading2.gif" id="support_pic_loading" width="15px" />
					</div>				
					<div id="support_loader" style="display:none;"><img src="http://maryjane.ru/images/buttons/ajax-loader-img-2.gif" alt="loading ..." /></div>
					<div class="submit">
						<input type="submit" name="send" value="{$L.ORDERHISTORY_send}">
					</div>
				</form>
			<div style="clear:both;"></div>
		</div>
	</div>
	
	<div style="clear:both;"></div>
	
</div>

{literal}
<script type="text/javascript">

function changeSize(id, size)
{
	$.get('/ajax/changePositionSize/', {'id' : id, 'size' : size});
}	

$(document).ready(function() {

	//$.scrollTo( ".order-select.item_v{/literal}{$basketNum}{literal}", 200);
	$.scrollTo(".menu-topp .line-bot", 200);
	
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
	
	function addAction() {
		//появление редактора размера
		$('.order-item .size').unbind('hover').bind('hover',function(){
			$(this).parents('.order-item').find('.reader').css('visibility','visible');
		});
		//скрытие редактора размера
		$('.order-item').unbind('mouseleave').bind('mouseleave',function(){
			$('.order-item .reader').css('visibility','hidden');
		});
	}
	addAction();
	
	
	/*
	$('.info a').click(function(){
		$(this).parent
	});
	*/
	/*
	var disabled_opacity = '0.4';
	var id = 0;

	if (document.location.hash != '') {
		id = document.location.hash.substr(3);
	} else if (id = $.cookie('orderhistory_id')) {
		id = $.cookie('orderhistory_id');
	}

	if (id > 0 && $('#order-details-' + id).length) {
		$(".orders .order-details").hide();
		
		$.get('/{/literal}{$module}{literal}/' + id + '/', {'ajax' : true}, function(r) {
			$(".orders .order-title").css({opacity:disabled_opacity}).addClass('hidy');
			$('.orders #order-details-' + id).prev('.order-title').removeClass('hidy').css({opacity:'1'}).addClass('open').prev('orders').addClass('order-select');
			$('.orders #order-details-' + id).html(r).animate({height:'show'});
			addAction();
		});
	}

	// Открывание и закрывание ордеров
	$(".orders .order-title").click(function(){			
		var id = $(this).children('input:hidden').val();
		
		$.cookie('orderhistory_id', id);
		document.location = document.location.href.replace(document.location.hash, '') + '#!/'+id; 
		
		$(".orders .order-title").removeClass('open').css({opacity:disabled_opacity}).addClass('hidy');
		$(".orders").removeClass('order-select');
		$(".orders .order-details").animate({height:'hide'}, function(){ $(this).hide(); });
		
		if ($(this).next(".order-details").css("display") != 'none') {
			$(this).next(".order-details").animate({height:'hide'});
			$(this).removeClass('open');
			$(".orders .order-title").css({opacity:'1'}).removeClass('hidy');
		} else {
			$(".orders .order-details").hide();
			$(".orders .order-title").css({opacity:disabled_opacity}).addClass('hidy');
			//$(".orders .order-title")
			$(this).addClass('open').removeClass('hidy').css({opacity:'1'});
			var dt = this;
			
			$.get('/{/literal}{$module}{literal}/' + id + '/', {'ajax' : true}, function(r) {
				addAction();
				$(dt).next(".order-details").html(r).animate({height:'show'});
			});
		}
		return false;
	});*/


	//Остался вопрос?!
	if ($('#supportFile').length>0)
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
			var row = $('.support-question.chat .items .item.hidden').clone();
			$(row).find('.text-data').text(m);
			
			var dt = (new Date());
			var d = dt.getDate() + ' ';
			switch (dt.getMonth()+1) {
				case 1: d+='января';break;
				case 2: d+='февраля';break;
				case 3: d+='марта';break;
				case 4: d+='апреля';break;
				case 5: d+='мая';break;
				case 6: d+='июня';break;
				case 7: d+='июля';break;
				case 8: d+='августа';break;
				case 9: d+='сентября';break;
				case 10: d+='октября';break;
				case 11: d+='ноября';break;
				case 12: d+='декабря';break;
			}
			d += ' ' + dt.getFullYear() + ' ' + dt.getHours() + ':' + dt.getMinutes();
			
			$(row).find('.date').text(d);
			//$(row).prependTo('.support-question.chat .items').show();
			$(row).appendTo('.support-question.chat .items').show();
			
			$('.support-question.chat .items').scrollTop($('.support-question.chat .items')[0].scrollHeight);
			
			$(b).removeAttr('disabled').show();
			$('#support_loader').hide();
		});
		
		return false;
	});
	
	//кнопка "изменить" для размера
	$('.order-item.order-details .reader1').click(function(){
		$(this).parent().find('.change-size').show();
		$(this).hide();
		return false;
	});

	//скрываем размер после выбора
	$('.order-item.order-details .change-size').bind('change', function(){
		$(this).parent().find('.size span').html($(this).find('option:selected').text());
		$(this).hide();
	});
	
	$('.order-item.order-details .size, .order-item.order-details .reader1')
	.mouseover(function(){ if (window.changesizetime) clearTimeout(window.changesizetime);delete window.changesizetime; $(this).parent().find('.reader1').show(); })
	.mouseout(function(){ var self = $(this); window.changesizetime = setTimeout(function() { self.parent().find('.reader1').hide(); },100); });


	$('input[name=sms_info]').change(function(){
		$.get('/ajax/order_sms_info/', {'sms_info' : $(this).attr('checked')}, function() {

		});
	});
	
	//календарик выбора даты доставки
	$('.kalendarik')
	.wCalendar({ curDate: (deliveryboy_deliver_posible?moment(deliveryboy_deliver_posible[0], 'DD/MM/YYYY'):moment()), name: 'data[kurer_date]', dates: deliveryboy_deliver_posible })
	.bind('select', function(e){
		//selectDateDelivery(e.curdate);
	});
	
	
	//изменение доставки
	$('.block-delivery .title').hover(function(){
		if (typeof timeouthide != 'undefined') { clearTimeout(timeouthide); delete timeouthide; }
		$('.block-delivery a.change').show();
	},
	function(){
		timeouthide = setTimeout(function() { $('.block-delivery a.change').hide(); }, 200);
	});
	$('.block-delivery a.change').hover(function(){ if (typeof timeouthide != 'undefined') { clearTimeout(timeouthide); delete timeouthide; } });
});

</script>
{/literal}

