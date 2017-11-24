<div id="feedbackcenter" style="padding:0 5px 0">
	{*<div style="padding-left:12px;line-height:12px;font-size:11px;color:#A9A9A9"><i>Среднее время ответа в рабочее время 15 минут!</i></div>*}
	<form action="#" method="post" enctype="multipart/form-data" id="feedbackform">
								
		<table border="0" width="100%" cellpadding="10" cellspacing="10" style="padding:7px 10px">
		<tr>
			<td> 
				<div style="font-size:11px;padding-bottom:3px;">Имя или Логин</div>
				<input style="width:99%;font-size:16px;" id="feedbackname" name="feedbackname" type="text" value="{$USER->user_login}" />
			</td>
		</tr>
		<tr>
			<td>
				<div style="font-size:11px;padding-bottom:2px; padding-top:5px;">E-mail</div>
				<input style="width:99%; font-size:16px" id="feedbackemail" name="feedbackemail" type="text" value="{$USER->user_email}" />
			</td>
		</tr>
		<tr>
			<td><div style="font-size:11px;padding-bottom:2px;padding-top:5px;">Тема&nbsp;сообщения</div>
				<select id="feedbacksubj" name="subj" style="width:99%;font-size:16px">
					<option value="3" {if !$selected_theme}selected="selected"{/if}>Вопрос</option>
					<option value="1">Заказ</option>
					{if $USER->id == 27278}
					<option value="12">Опт</option>
					{/if}
					<option value="4">Идея</option>
					<option value="8">Ошибка</option>
					{if $selected_theme == 10}
					<option value="10" selected="selected">Гаджет-запрос</option>
					{/if}
					<option value="7">Жалоба</option>
					<option value="14">Моя работа</option>
					{if $selected_theme == 15}
					<option value="15" selected="selected">Партнёрская программа</option>
					{/if}
					<option value="16">Почему отклонили мою работу?</option>
				</select>
			</td>
		</tr>
		<tr class="hiddenBlocks" id="hiddenBlock1" style="display:none;">
			<td>
			<div style="font-size:11px;padding-bottom:2px; padding-top:5px;">Номер&nbsp;заказа</div>
			<input style="width:99%; font-size:16px" id="feedbackbasket" name="feedbackbasket" type="text"/></td>
		</tr>
		<tr>
			<td>
			<div style="font-size:11px;padding-bottom:2px; padding-top:5px;">Сообщение</div>
			<textarea cols="10" rows="5" name="text" id="feedbacktext" style="width:99%;font-size:16px;resize: none;"></textarea></td>
		</tr>
		<tr>
			<td>
				<div style="font-size:11px;padding-bottom:2px; padding-top:5px;">Картинка</div>
				<table>
					<tr>
						<td>
							<input id="error_pict" name="error_pict" type="file" />
							<input type="hidden" name="pic" id="pic" />
							<span class="smallcomment" style="font-size:10px">JPG, 500Кb</span>
						</td>
						<td valign="top" style="padding-top:3px">
							<img src="/images/loading2.gif" id="pic_loading" width="15px" style="display: none;vertical-align: middle" />
							<em style="display: none" id="pic_success">загружен</em>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		{if !$USER->authorized}
		<tr>
			<td align="center">
				<table style="padding-top:15px;">
				<tr>
					<td><input type="text" name="keystring" id="keystring" size="2"  maxlength="5" style="height:48px;font-size:38px;font-family:Times New Roman;"></td>
					<td><div class="left" id="secimgBlock" style="height:52px;"></div></td>
					<td style="font-size:10px; line-height:9px;"><a href="#" onclick="genNewCaptcha(); return false;"  style="color:gray">Обновить картинку</a></td>
				</tr>
				</table>
				<div style="font-size:11px;padding-bottom:2px; padding-bottom:10px; text-align:left">Введите текст c картинки в нижнем регистре</div>
			</td>
		</tr>
		{/if}
		<tr>
			<td colspan="2" align="center" style="padding-top:10px;">
				<input type="hidden" name="feedbackUserId" id="feedbackUserId" value="{$USER->user_id}" />
				<input style="width:100%; height:30px;" type="submit" id="feedbacksubmit" onclick="return feedSend();" value="Отправить" />
				
				<br/><br/>
				<a class="mj_small" title="Maryajne.ru" href="/" rel="nofollow">© Maryjane.ru</a>
			</td>
		</tr>
		</table>
	</form>
</div>

{literal}
<script>
	$(document).ready(function() {
		genNewCaptcha();
		var bb = $('#error_pict'), interval;

		$.ajax_upload(bb, {
            action : '/feedback/uploadPict/',
            name : 'file',
            onSubmit : function(file, ext) {
            	// показываем картинку загрузки файла
				$('#pic_loading').show();
				$('#feedbacksubmit').attr('disabled', 'disabled');
				this.disable();
            },
            onComplete : function(file, response) {
				// убираем картинку загрузки файла
				// снова включаем кнопку
				this.enable();
				$('#feedbacksubmit').removeAttr('disabled');
				
				r = eval('(' + response + ')');

				if (r.status == 'ok') {
					// показываем что файл загружен
					$('#pic').val(r.id);
					$('#pic_loading').hide();
					$('#pic_success').show();
             	}
            }
          });
    });
</script>
{/literal}