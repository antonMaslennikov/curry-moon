<script type="text/javascript" src="/js/calendar.js"></script>
<script type="text/javascript">
	{if $active_deliveryboy_deliver_posible}
		var deliveryboy_deliver_posible = {$active_deliveryboy_deliver_posible};
	{else if $deliveryboy_deliver_posible}
		var deliveryboy_deliver_posible = {$deliveryboy_deliver_posible};
	{else}
		var deliveryboy_deliver_posible = {};
	{/if}
		
		var user_deliver_posible = '{$user_deliver_posible}';
	//var module = '{$module}';// есть в хереде
</script>	
{literal}
<script type="text/javascript">
	$(document).ready(function(){

		//появление/скрытие формы измененмя телефона
		$('#show-telefon, .box-info-p.telefon .close').unbind('click').click(function(){
			if ($(this).hasClass('disable') || $(this).parent().hasClass('disable')) return false;
			$('.box-info-p.telefon').toggle();
			return false;
		});
		$('#show-telefon-2, .box-info-p.telefon-2 .close').unbind('click').click(function(){
			if ($(this).hasClass('disable') || $(this).parent().hasClass('disable')) return false;
			$('.box-info-p.telefon-2').toggle();
			return false;
		});
		//появление/скрытие формы ихменения даты доставки
		/*
		$('#show-clock, .box-info-p.clock .close').unbind('click').click(function(){
			if ($(this).hasClass('disable') || $(this).parent().hasClass('disable')) return false;
			$('.box-info-p.clock').toggle();
			return false;
		});
	
		//календарик выбора даты доставки
		$('.help-top-menu .dostav-link .kalendarik').wCalendar({ curDate: (deliveryboy_deliver_posible?moment(deliveryboy_deliver_posible[0], 'DD/MM/YYYY'):moment()), name: 'data[kurer_date]', dates: deliveryboy_deliver_posible })
		.bind('select', function(e){
			//selectDateDelivery(e.curdate);
			$('.help-top-menu .dostav-link form input[type="submit"]').click();
		});
		
		//время доставки
		$('.help-top-menu .dostav-link select').change(function(){ $('.help-top-menu .dostav-link form input[type="submit"]').submit(); });
		
		//сабмит для доставки
		$('.help-top-menu .dostav-link form').submit(function(){
			$.post('/orderhistory/saveDeliveryTime/', $(this).serialize()).done(function(data) { if (data != 'ok') alert(data); });
			return false;
		});
		*/
		
		//телефон
		var phonevalid = function(e){ 
			if ($(this).val() == '') return true;
			if (!/^[\d\-\(\)\s]+$/.test($(this).val())) {
				var f = /[^\d\-\(\)\s]+/.exec($(this).val());
				if (f) {
					var s = $(this).val();
					for(var i = 0;i<f.length;i++)
						s = s.replace(new RegExp(f[i],'gi'),'');
					$(this).val(s);
				}
				return false;
			}
			return true;
		}
		$('.telefon input, .telefon-2 input')
		.keydown(phonevalid)
		.keyup(phonevalid)
		.change(phonevalid);
		
		$('.telefon form, .telefon-2 form, .telefon-3 form').submit(function(){
			var f = this;
			if ($(this).find('input[type=text]').val().trim() == '') { alert('Вы не ввели телефон'); return false; }
			//'+module
			$.post('/orderhistory', { 'addphone' : $(this).find('input[type=text]').val(), 'basket_id' : $(this).find('input[name=addphone_basket]').val() }, function(r){
				if (r == 'ok') {
					$(f).empty().text('Телефон добавлен');
					setInterval(function(){$('.telefon').fadeOut();}, 2000);
				} else {
					$(f).empty().text(r);
					setInterval(function(){$('.telefon').fadeOut();}, 4000);
				}
			});
			
			return false;
		});
		
		//отправим по СМС
		$('.sms_info .sms-b').click(function(){
			$('.sms_info .sms-b').removeClass('on');
			$(this).addClass('on');
			$('input[type="hidden"][name="sms_info"]')
				.val($(this).index() == 0?'on':'off')
				.attr('checked',$(this).index() == 0?'checked':null)
				.change();
		});
		
		/*перенес в main.js
		$('.menu-topp .icon-vozvrati a, .history_orders_2013 .vozvratBib').click(function(){		
			if (!$('.wrap-change').is(':visible')){
				var self= this;
				if ($(self).closest('.ypravlenie_zakazom').length>0) 
					trackUser('Order.v3','возврат', 'открытие окна возврата - в верхней полоске');
				else
					trackUser('Order.v3','возврат', 'открытие окна возврата - в истории заказов');
				
				$('.ypravlenie_zakazom').show();//если форма скрыта
				
				$.get('/orderhistory/return/', function(r){
					$('.wrap-change .body').html(r);
					$('.wrap-change .sum').html('0');
					$('.wrap-change .body .item a.v').click(function(){
						$(this).toggleClass('activ');
						if ($(this).is('.activ'))
							$(this).prev().attr('checked', 'checked');
						else
							$(this).prev().removeAttr('checked');
						
						var sum = 0;
						$('.wrap-change .item input[type=checkbox]:checked').each(function(){
							sum += parseFloat($(this).parents('.item:first').find('.price').text());
						});
						$('.wrap-change .sum').html(sum);
						if(sum>0){
							$('.wrap-change .img-change input:visible').removeAttr("disabled").removeClass('disabled').attr('title','');
							$('.wrap-change .sum').attr('title','');
							$('.refundExchangeHint .prc').text(sum);
							$('.ypravlenie_zakazom .ya-peredumal .prc').text(sum);
						}else
							$('.wrap-change .img-change input:visible').attr('disabled', 'disabled').addClass("disabled");
						return false;
					});					
					
					if ($('.wrap-change .change .body .item').length==1)
						$('.wrap-change .body .item:first-child a.v').click();
						
					if ($('.wrap-change .change .body .item').length>3)	
						$('.wrap-change .selectAll').show();
						
					$('.wrap-change .selectAll').click(function(){
						$('.wrap-change .body .item .v:not(.activ)').click();
						return false;
					});
					
					/ * перенес в main.js
					$('.wrap-change a[href="/orderhistory/return/cancel/"]').click(function(){
						trackUser('Order.v3','возврат', 'оформил и передумал');//трек аналитика
						return true;
					});* /
					
				});
				
				
				//для остального
				$('.wrap-change').css('top','-1000px');//дергание убираем				
				var scrollTo=$( ".icon-vozvrati a").offset().top;				
				$(window).scrollTop(scrollTo);
				$('.wrap-change').css({'top':scrollTo+26+'px','left':'300px'});
				

				if (!$('.wrap-change').is(':visible'))
					$('.wrap-change').slideToggle();
				
			}
			return false;
		});*/
		
		/*$(window).bind('click',function(d){ 
			debugger;//нужно переделать событие
			
			if ($(d.target).parents('.wrap-change').length>0) return;
			$('.wrap-change').slideUp(); 
		});*/
	});
</script>

<style type="text/css">

ul{list-style: none;}
/*меню */
.breadcrumbs-menu {height:18px;float: left;margin: 7px 0 14px 5px;}
.breadcrumbs-menu li {border: 1px solid #E1E1E1;border-bottom: 0;float:left; margin:0 2px 0 0; padding: 5px 5px 0 5px;color:#9a9c9c;}
.breadcrumbs-menu li.active{background: #ffffff;}
.breadcrumbs-menu li.delim {margin: 2px 4px 0 4px;}
.breadcrumbs-menu li a {color:#9a9c9c;font-family: 'Times New Roman';
font-style: italic;font-size: 18px}
.breadcrumbs-menu li.active a{text-decoration: none;color:#000000;}
.menu .line-bot{width: 975px;border-top: 1px dotted #E1E1E1;height: 1px;clear: both;margin: 0 0 0 0px;}
/*меню помощи*/
.help-top-menu {
	height:18px;display: block;
	clear: both;position: relative;
	width: 969px;margin: 10px 2px 19px 5px;
}
.help-top-menu li {line-height: 12px;float:left; margin:0 25px 0 0; padding:0; color:#000000;position: relative;}
/*иконки*/
.icon-zakazi{	display: block;	width: 20px;	height: 20px;	background-image: url(/images/order/buttons_ypravlenie_zakazom_3.png);	background-repeat: no-repeat;	float: left;}

.icon-telefon .icon-zakazi{background-position: -5px -4px;}
.icon-telefon:hover .icon-zakazi{background-position: -5px -27px;}

.icon-clock .icon-zakazi{background-position:1px -358px;width: 28px;height:22px;margin-right: 2px;}
.icon-clock:hover .icon-zakazi{background-position: 1px -378px;}
.help-top-menu li.icon-clock.disable .icon-zakazi {background-position: -26px -358px;}

.icon-vozvrati .icon-zakazi{background-position: -5px -88px;}
.icon-vozvrati:hover .icon-zakazi{background-position: -5px -111px;}
.help-top-menu li.icon-vozvrati.disable .icon-zakazi{background-position: -33px -88px;}

.icon-anull .icon-zakazi{background-position: -5px -133px;}
.icon-anull:hover .icon-zakazi{background-position: -5px -156px;}
.help-top-menu li.icon-anull.disable .icon-zakazi{background-position: -33px -133px;}


.icon-moizakazi .icon-zakazi{background-position: 0px -229px;width: 27px;height: 27px;}
.breadcrumbs-menu li.icon-moizakazi.active .icon-zakazi,
.icon-moizakazi:hover .icon-zakazi{background-position: 0px -261px;}

.help-top-menu .icon-moizakazi-canceled .icon-zakazi{background-position:-32px -402px;width: 16px;height: 20px;    margin-right: 3px;}
.help-top-menu .icon-moizakazi-canceled.active .icon-zakazi,
.help-top-menu .icon-moizakazi-canceled:hover .icon-zakazi{background-position:-5px -402px;}
.help-top-menu .icon-moizakazi-canceled a{border-bottom: 0;text-decoration: underline;}
.help-top-menu .icon-moizakazi-canceled:hover a{text-decoration: none;}

.icon-moizakazi-canceled .icon-zakazi{background-position: -27px -401px;width: 27px;height: 27px;}
.breadcrumbs-menu li.icon-moizakazi-canceled.active .icon-zakazi,
.icon-moizakazi-canceled:hover .icon-zakazi{background-position: 0px -401px;}
 
.icon-print-o .icon-zakazi{background-position: -2px -297px;width: 27px;height: 27px;padding: 0;border: 0;}
.icon-print-o:hover .icon-zakazi{background-position: -1px -329px;}

.help-top-menu li.disable .icon-zakazi{background-position: -33px -4px;}

.help-top-menu li a {
	/*color:#000000;border-bottom: 1px dashed #000000;*/
	color:#B9B9B9;border-bottom: 1px dashed #B9B9B9;
	padding-top: 3px;	
	text-decoration: none;
	font-size: 12px;
	font-weight: bold;
	float: left;
}
.help-top-menu li.disable a {color:#EEEEEE;	border:none;cursor: default;}
.help-top-menu li a:hover{border:none;}

/*скрытая форма ввода телефона, и времени*/
.box-info-p{
	display: none;text-align: center;
	position: absolute;
	width: 205px;
	padding: 15px 13px;
	background-color: white;
	border: 2px solid #BCBCBC;
	color: #535758;
	box-shadow: 0 1px 7px rgba(0, 0, 0, 0.3);
	z-index: 50;
	top: 22px;left: 0px;
}

.box-info-p.telefon.addphone{display:block;width:582px;padding:50px 13px}
.box-info-p.telefon.addphone form{border: 2px solid #BCBCBC;  width: 378px;  padding: 18px 0;  margin: auto;}

.box-info-p i{position: absolute;width: 15px;height: 8px;left: 80px;top: -8px;
	background: url(http://www.maryjane.ru/images/icons/pointer-top.png) no-repeat scroll 0 0;}
.box-info-p .close{
	position: absolute;width: 15px;height: 15px;background: url(http://www.maryjane.ru/images/icons/delete_gray_red.gif) no-repeat scroll 0 0 transparent;top: 5px;right: 5px;}
.box-info-p .num{
	height: 17px;
	width: 107px;
	padding: 2px 0;
	font-family: Arial, Tahoma;
	font-size: 12px;
}
.box-info-p.subm{
	margin: 4px;
	cursor: pointer;
	height: 26px;
	padding: 0px 3px;
	font-family: Arial, Tahoma;
	font-size: 12px;
	color: #939393;
}
/*календарик*/
.kalendarik{width:200px;margin: 3px 0 0 16px;}
.kalendarik .day{margin-left:21px;}
.kalendarik .day .d{
	float: left;
	display: block;
	font-size: 10px;
	color: #000000;
	padding: 0 3px;
}
.kalendarik .day .d.activ{color: red;}
.kalendarik .chislo{
	width:142px;
	border: 1px solid #EFEFEF;
	height: 18px;
	padding: 2px 15px;
	position: relative;
	overflow: hidden;
}
.kalendarik .chislo .icon{
	height: 19px;
	width: 9px;
	display: block;
	float: left;
	background-image: url(/images/order/left_right.png);
	background-repeat: no-repeat;
	position: absolute;
	top: 1px;
	cursor: pointer;
}
.kalendarik .chislo .icon.left{left: 2px;background-position: 0px 0;}
.kalendarik .chislo .icon.right{right: 2px;background-position: 0px -23px;}
.kalendarik .chislo ul{float: left;overflow: hidden;width: 145px;
white-space: nowrap;}
.kalendarik .chislo ul li{line-height: 19px;margin:0px;cursor: pointer;float: left;list-style: none;white-space: nowrap;width: 20px;text-align: center;}
.kalendarik .chislo ul li.l{color: #D5D5D5;cursor: default !important;}}
/*чтото глушит вывел ниже
.kalendarik .chislo ul li.activ{color: #ffffff; background: red;}*/
.kalendarik .disabled{cursor: default !important;}
/*изменить дату*/
.help-top-menu .read-dostavka-order{clear:left;float: none;display: block;border: none;margin: 7px 0 0 0px;font-weight: normal;text-decoration: underline;}
.help-top-menu .read-dostavka-order:hover{text-decoration: none;}

/*смс*/
.menu .input-sms {margin: 6px 5px 0px 0;float: right;display: block;}
.input-sms div.sms_info{margin: 0px 10px 0px 0;float: left;/*border:1px solid #B9B9B9;*/}
/*.input-sms div.sms_info .sms-b{
width: 29px;float: left;display: block;
background-color:#EDEDED;color:#B9B9B9;text-align: center;cursor: pointer;font-size: 10px;line-height: 14px;}
.input-sms div.sms_info .sms-b.on{background-color:#000000;color:#ffffff;cursor: default;}
.input-sms div.sms_info .vert{float: left;display: block;height: 14px;border-left:1px solid #ffffff;}*/
.input-sms div.sms_info .sms-b{
	height: 16px;float: left;display: block;cursor: pointer;font-size: 0px;
	background-image: url("/images/senddrawing/btn-switch-cat.gif");
	background-repeat: no-repeat;	
}
.input-sms div.sms_info .sms-b:first-child{width: 30px;background-position: 0 -19px;}
.input-sms div.sms_info .sms-b:last-child{width: 31px;background-position: -30px 0px;}
.input-sms div.sms_info .sms-b:first-child.on{background-position:0 0px;}
.input-sms div.sms_info .sms-b:last-child.on{background-position:-30px -19px;}
</style>
{/literal}

<div class="menu menu-topp" style="margin-top:-9px">	


	<div class="tabz2">
		<a class="green" href="/orderhistory/" rel="nofollow" style="{if $PAGE->reqUrl.1 != 'canceled'}border: none;font-weight:bolder;{else}border-style:solid;{/if}margin-right: 20px;" title="{$L.CONFIRM_my_orders}">{$L.CONFIRM_my_orders}</a>
		<a class="green" href="/orderhistory/canceled/" rel="nofollow" style="{if $PAGE->reqUrl.1 == 'canceled'}border: none;font-weight:bolder{else}border-style:solid;{/if}" title="{$L.CONFIRM_my_orders}">{$L.CONFIRM_my_orders_canceled}</a>
		
		{if $PAGE->lang=='ru'}
		<div class="input-sms" style="margin-top: 16px">
			<!--input type="checkbox" name="sms_info" checked="checked" style="margin: 0;"-->
			<input type="hidden" name="sms_info" />
			<div class="sms_info">
				<span class="sms-b {if $sms_info_checked == 'TRUE'}on{/if}">{$L.CONFIRM_on}.</span>
				<!--span class="vert"></span-->
				<span class="sms-b {if !$sms_info_checked}on{/if}">{$L.CONFIRM_off}.</span>
			</div>
			<span class="left" style="line-height: 14px;color:#5a5a5a;font-size:12px">Мы будем информировать вас о состоянии заказа с помощью e-mail и sms{*$L.CONFIRM_sms_inform*}{if $PAGE->reqUrl.1 == 'confirm' && $order.user_basket_delivery_type == 'user'}<br/>Перед приездом дождитесь СМС-оповещения о готовности.{/if}</span>
		</div>
		{/if}
	</div>
	
	{*<ul class="breadcrumbs-menu">
		<li class="icon-moizakazi {if $PAGE->reqUrl.1 != 'canceled'}active{/if}">
			<span class="icon-zakazi"></span><a href="/orderhistory/" rel="nofollow" title="{$L.CONFIRM_my_orders}">{$L.CONFIRM_my_orders}</a>
		</li>
		{if $PAGE->module == 'orderhistory'}
		<li class="icon-moizakazi-canceled {if $PAGE->reqUrl.1 == 'canceled'}active{/if}">
			<span class="icon-zakazi"></span><a href="/orderhistory/canceled/" rel="nofollow" title="{$L.CONFIRM_my_orders}">{$L.CONFIRM_my_orders_canceled}</a>
		</li>
		{/if}
	</ul><div style="clear:both"></div><div class="line-bot"></div>*}
	
	<ul class="help-top-menu">
		<li class="icon-telefon {if !$active_order || $active_order.user_basket_status == 'delivered' || $active_order.user_basket_status == 'canceled'}disable{/if}">
			<span class="icon-zakazi"></span>
			<a href="#" rel="nofollow" id="show-telefon" title="{$L.CONFIRM_add_phone}">{$L.CONFIRM_add_phone}</a>
			<div class="box-info-p telefon {if $PAGE->reqUrl.2 == 'addphone'}addphone{/if}">
				<i></i><span class="close"></span>
				<form method="post" class="" action="">
					Добавить номер телефона&nbsp;&nbsp;
					<input type="hidden" name="addphone_basket" value="{$O->id}" />
					<input type="text" name="phone" value="" placeholder="{$L.CONFIRM_phone}" class="num">
					<input type="submit" value="{$L.ORDERV3_save}" class="subm" />
				</form>
			</div>			
		</li>
		<li class="icon-clock {if $active_order && $active_order.user_basket_status != 'ordered'}disable{/if}">
			<span class="icon-zakazi"></span>	
			<a href="{if $active_order.user_basket_id}/order.v3/{$active_order.user_basket_id}/{else}#{/if}" rel="nofollow" id="show-clock" title="{$L.CONFIRM_delivery_address_time}">{$L.CONFIRM_delivery_address_time}</a>
			<div class="box-info-p clock dostav-link" {if $active_order.user_basket_delivery_type == 'deliveryboy'}style="height:95px"{/if}>
				<i></i><span class="close"></span>
				<style>
				.kalendarik .chislo ul li.activ{
					color: #ffffff; 
					background: red;
				}
				</style>
				<form method="post" class="" action="">
					{if $active_order.user_basket_delivery_type == 'deliveryboy'}
					<div class="kalendarik">
						<div class="day"><span class="d">{$L.ORDERV3_d_mon}</span><span class="d">{$L.ORDERV3_d_true}</span><span class="d activ">{$L.ORDERV3_d_wed}</span><span class="d">{$L.ORDERV3_d_thu}</span><span class="d">{$L.ORDERV3_d_fri}</span><span class="d">{$L.ORDERV3_d_sat}</span><span class="d">{$L.ORDERV3_d_sum}</span></div>
						<div style="clear:left;"></div>
						<div class="chislo">
							<span class="icon left"></span>
							<ul>
								<li class="l">12</li><li class="l">13</li>
								<li class="l activ">14</li><li>15</li>
								<li>16</li><li>17</li><li>18</li><li>19</li>
							</ul>
							<span class="icon right"></span>
						</div>
					</div>
					<div style="clear:both;"></div>
					<select style=" width:90px;margin: 12px 0 0 0px;padding-left: 4px;" name="data[kurer_time]">
						<option value="с 11 до 14">с 11 до 14</option>
						<option value="с 14 до 16">с 14 до 16</option>
						<option value="с 16 до 18">с 16 до 18</option>
						<option value="с 18 до 21">с 18 до 21</option>
					</select>	
					{/if}
					<a href="/order.v3/{$active_order.user_basket_id}/" rel="nofollow" class="read-dostavka-order" title="{$L.CONFIRM_change_address}">{$L.CONFIRM_change_address}</a>
					<input type="submit" style="display:none" />
				</form>
			</div>
		</li>
		
		{if $USER->user_delivered_orders > 0}
		<li class="icon-vozvrati {if !$active_order || $active_order.user_basket_status != 'delivered'}disable{/if}">
			<span class="icon-zakazi"></span>			
			<a href="#" name="obmen" id="obmen" class="" rel="nofollow">{$L.CONFIRM_change} {if $active_order.user_basket_status == 'delivered'}({$L.CONFIRM_exchange_days} {$active_order.exchangeIsPosible} д.){/if}</a>
		</li>
		{/if}
		{if ($active_order.user_basket_status == 'ordered' || $active_order.user_basket_status == 'waiting' || 
		$active_order.user_basket_status == 'accepted') && $active_order.printed == 0}
		<li class="icon-anull" style="">
			<span class="icon-zakazi"></span>
			<a href="/orderhistory/cancel/?height=210&width=400" onclick="tb_show('Анулировать заказ от {$DETAILS.DATE}', '/orderhistory/cancel/?height=210&width=480'); return false" rel="nofollow" class="dashed">{$L.CONFIRM_cancel}</a>
		</li>
		{/if}

		{if $USER->meta->mjteam &&  $PAGE->module == 'orderhistory'}
		<li class="icon-moizakazi-canceled {if $PAGE->reqUrl.1 == 'canceled'}active{/if}" style="border-bottom: 1px dashed orange">
			<span class="icon-zakazi"></span>
			<a href="/orderhistory/canceled/" rel="nofollow" title="{$L.CONFIRM_my_orders}">{$L.CONFIRM_my_orders_canceled}</a>
		</li>
		{/if}
		
		<li class="icon-print-o" style="margin: 0;float: right;">
			<span class="icon-zakazi"></span>
			<a href="{if $basketNum}/order.v3/print/{$basketNum}/{else}#{/if}" class="dashed" rel="nofollow" target="_blank" title="{$L.CONFIRM_print}">{$L.CONFIRM_print}</a>
		</li>		
	</ul>	
</div>