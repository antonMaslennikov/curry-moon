<div>
	<div>
		<form action="/{$module}/cancel/" method="post" id="cancelForm">
			<p>
				{$L.ORDERHISTORY_reason}:<span class="error">*</span><br />
				<select name="reason" id="reason" style="width:99%">
					<!--option value="">Выберите причину</option-->					
					<option value="my" style="background:#ccc">{$L.ORDERHISTORY_personareason}</option>
					<option value="Изменил решение о покупке">{$L.ORDERHISTORY_reason_1}</option>
					<option value="Не воспользовался скидкой">{$L.ORDERHISTORY_reason_2}</option>
					<option value="Не устраивает срок выполнения заказа">{$L.ORDERHISTORY_reason_3}</option>
					<option value="Товар был заказан ошибочно">{$L.ORDERHISTORY_reason_4}</option>
					<option value="Не получается оплатить">{$L.ORDERHISTORY_reason_5}</option>
					{if $USER->meta->mjteam && $USER->meta->mjteam != 'fired'}
					<option value="6">Тестовый заказ{*$L.ORDERHISTORY_reason_6*}</option>
					{/if}
				</select>
			</p>
			
			<p>
				{$L.ORDERHISTORY_personareason}:<br />
				<input type="text" name="my_reason" id="my_reason" value="" style="width:98%" />
			</p>
			
			<br />
			
			<p style="text-align:center">
				<input type="submit" name="submit" value="{$L.ORDERHISTORY_confirm}" class="button" onclick="if ($('#reason').val() == '') { alert('{$L.ORDERHISTORY_reason_is_empty}'); return false; }" />
			</p>
		</form>
	</div>
	
	{literal}
	<script>
		$('select[name=reason]').change(function(){
			$('#my_reason').attr('disabled', 'disabled'); 
			if ($(this).val() == 'my') 
				$('#my_reason').removeAttr('disabled');
		})
		
		$('#cancelForm').submit(function() {
			
			var f = $(this);
			
			if ($('select[name=reason]').val() == 'my' && $('#my_reason').val().length == 0)
			{
				alert('Пожалуйста, выберите одну из причин аннулирования или укажите свою.');
				return false;
			}
			
			if ($('select[name=reason]').val() == 'my' && $('#my_reason').val().length < 7)
			{
				alert('Пожалуйста, напишите чуть больше, почему Вы решили аннулировать заказ. Спасибо.');
				return false;
			}
			
			$.post($(f).attr('action'), {'reason' : $('select[name=reason]').val(),  'my_reason' : $('#my_reason').val(), 'submit' : 1}, function(r){
			
				if (r == 'error')
					alert('При аннулировании заказа произошла ошибка');
				else if (r == 'alert')
					$(f).html('Ваш заказ аннулирован!<br /> Деньги могут быть возвращены удобным Вам способом - Вебмани, ЯД, для карт (будут возвращены на карту в течении 7-ми дней)').css('text-align','center').css('padding-top', '30px');
				else
					location.reload();
					//location.href='/orderhistory/';
			});
			
			return false;
		})
	</script>
	
	<style>
		#cancelForm .error {
			color:red
		}
	</style>
	{/literal}
</div>