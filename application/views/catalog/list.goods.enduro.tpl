{* форма для ввода контактных данных *}

<div id="enduroZakazBlock">
	<div class="downloadPrice">
		<form action="/catalog/enduro/" method="post" id="enduroZakazForm">
			
			<div class="name">
				<span>Имя:</span> 
				<input type="text" name="name" class="name" required="required" value="{$USER->user_name}" />
			</div>
			<div class="email">
				<span>Ваш e-mail:</span> 
				<input type="text" name="email"  class="email" required="required" value="{$USER->user_email}" />
			</div>
			<div class="phone">
				<span>Ваш телефон:</span>
				<input type="text" name="phone" class="phone" required="required" value="{$USER->user_phone}" />
			</div>
			
			<div class="ccomment">
				<span>Ваш Комментарий:</span>
				<textarea name="comment"></textarea>
			</div>
			
			<div style="text-align: center;margin-bottom: 20px">
				<table style="display: inline-block; vertical-align: middle;">
				<tr>
					<td><input type="text" name="keystring" id="keystring" maxlength="5" style="height:48px;width:100px;font-size:38px;font-family:Times New Roman;"></td>
					<td><div class="left" id="secimgBlock" style="height:52px; margin-bottom: 0;"></div></td>
					<td style="padding-left:5px;"><a href="#" onclick="genNewCaptcha(); return false;"><img src="/images/icons/arrow_refresh.png" /></a></td>
				</tr>
				</table>
			</div>
			
			{literal}
			<script>
				$(document).ready(function() {
					genNewCaptcha();
				});
			</script>
			{/literal}
	
			<input type="hidden" name="enduro_order_description" value="" />
			<input type="hidden" name="source" value="" />
	
			<input type="submit" class="submit" value=" "/>	
		</form>	
	</div>
	<div class="downloadPrice-successful">
		<p>Спасибо! Ваш заказ принят</p> 
		<p>В ближайшее время мы с Вами свяжемся</p>
	</div>
</div>
 
{foreach from=$models item="g"}	
	<li class="m12" im_id="{$g.id}">
		<form action="/catalog/enduro/" method="post" class="enduro-zakaz">
			<span class="list-img-wrap">
				<img title="{$g.name}" alt="{$g.name}" src="/images/moto_model/{$filters.category}/{$manufacturer}/{$g.picture}">
			</span>			

			<input type="hidden" name="description" value="Разработать дизайн наклейки на {if $filters.category=='jetski'}гидроцикл{elseif $filters.category=='atv'}квадроцикл{elseif $filters.category=='snowmobile'}снегоход{else}мотоцикл{/if}  {$manufacturer}({$filters.category}) [{$g.name} №{$g.id}]" />
			
			<input type="submit" name="zakaz" value=" " title="Заказать дизайн" class="zdiz">
		</form>
		<div class="item">{$manufacturer} {$g.name} (№{$g.id})</div>
	</li>
{/foreach}

{literal}
	
	<style>
		#enduroZakazBlock {display:none}
		
		.downloadPrice { padding: 41px 0 0 33px;}
		
		.downloadPrice .phone,
		.downloadPrice .email,
		.downloadPrice .name, 
		.downloadPrice .ccomment {margin-bottom: 25px;font-size: 13px;clear: both;}
		
		.downloadPrice .email span,
		.downloadPrice .phone span, 
		.downloadPrice .name span, 
		.downloadPrice .ccomment span{display: block;padding-bottom: 7px;font-size: 16px;width: 300px;font-weight: bold;}
		
		.downloadPrice div input[type="text"] {background: url(/images/buttons/btn-required-star.gif) no-repeat right top;border: 1px solid #A9A9A9;width: 492px;padding-left:5px;height: 34px;margin: 0;}
		.downloadPrice div textarea {border: 1px solid #A9A9A9;width: 492px;padding-left:5px;height: 64px;margin: 0;}
		
		.downloadPrice .submit{background-image: url('/dealer/img/2zakazat_ras4et.gif');background-position: 0px 0px!important;margin:10px 0 0 120px;text-decoration: none;display: inline-block;width: 257px;height: 52px;cursor: pointer;background-position: 0px 0px;background-repeat: no-repeat;border: 0!important;margin-top:0}
		
		.downloadPrice .wpz{font-size: 16px;font-weight: bold;text-align: center;text-align: left;line-height: 16px;margin-bottom: 10px;}
		
		.downloadPrice-successful{display:none;text-align: left; line-height: 23px; margin-bottom: 10px;    font-size: 18px;    font-weight: bold;    color: #333333;    width: 100%;  height: 70px;    margin: auto;    margin-top: 150px;}
		.downloadPrice-successful p {text-align: center;}
	</style>
{/literal}
	
{if $MobilePageVersion} 
{literal}
	<style>
		.downloadPrice {padding: 5px 5px 0;}
		
		.downloadPrice div input[type="text"], .downloadPrice div textarea {width: 266px;}
		
		.downloadPrice .submit {margin: 0px 0 0 23px;}
		
		.downloadPrice .phone, 
		.downloadPrice .email, 
		.downloadPrice .name, 
		.downloadPrice .ccoment {margin-bottom:10px;}
	</style>
{/literal}
{/if}

{literal}	
	<script>
	$(document).ready(function(){
		
		$('form.enduro-zakaz').submit(function(){
			
			$('input[name=enduro_order_description]').val($(this).find('input[name=description]').val());
			
			$('#enduroZakazBlock > .downloadPrice').show();
			$('#enduroZakazBlock > .downloadPrice-successful').hide();
			
			/*
			if (authorized) {
				
				if ($('.downloadPrice input[name="email"]').val().length > 0 && $('.downloadPrice input[name="phone"]').val().length > 0) 
				{
					$('.downloadPrice form').submit();
					
					$('#enduroZakazBlock > .downloadPrice').hide();
					$('#enduroZakazBlock > .downloadPrice-successful').show();
				}
			}
			*/
			{/literal}
			
			tb_show('', '/#TB_inline?width={if !$MobilePageVersion}570{else}290{/if}&height={if !$MobilePageVersion}557{else}400{/if}&inlineId=enduroZakazBlock');
			{literal}
			return false;
			
		});
		
		$('.downloadPrice form').submit(function(){
			
			var email = $('.downloadPrice input[name="email"]').val();
			if (email.length == 0){
				alert('e-mail - обязательное поле');
				$('.downloadPrice').show();
				return false;
			}
	
			var phone = $('.downloadPrice input[name="phone"]').val();
			if (phone.length == 0){
				alert('Телефон - обязательное поле');
				$('.downloadPrice').show();
				return false;
			}
			
			if (phone.length < 6){
				alert('Пожалуйста укажите корректный Телефон для связи');
				$('.downloadPrice').show();
				return false;
			}
			
			var name = $('.downloadPrice input[name="name"]').val();
			if (name.length == 0){
				alert('Имя - обязательное поле');
				$('.downloadPrice').show();
				return false;
			}
			
			email = email.replace(/^\s+|\s+$/g, '');
			
			if (!(/^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@([a-z0-9][a-z0-9\-]*[a-z0-9]\.)+[a-z]{2,4}$/i).test(email)){
				alert('То что Вы ввели не похоже на адрес электронной почты');
				$('.downloadPrice').show();
				return false;
			}
			
			if(typeof sbjs!='undefined')
				$('.downloadPrice input[name="source"]').val(sbjs.get.current.src + (sbjs.get.current.cmp && sbjs.get.current.cmp != '(none)' ? '-' + sbjs.get.current.cmp : '') + (sbjs.get.current.cnt && sbjs.get.current.cnt != '(none)' ? '-' + sbjs.get.current.cnt : '') + (sbjs.get.current.trm && sbjs.get.current.trm != '(none)' ? '-' + sbjs.get.current.trm : ''));
			
			$.post($(this).attr('action'), $(this).serialize() , function (data) {	
				$('.downloadPrice').hide();
				$('.downloadPrice-successful').show();
				trackUser('enduro','страница мотонаклеек', 'заказ-мотонаклеек');
			});
			
			return false;
			
		});
		
	});
	</script>
	
{/literal}