{if isset($TOPmessage) || ($USER->authorized && ($USER->user_is_fake == 'true' || $USER->user_email == ''))}
<div class="vk_step2 {if $USER->authorized && ($USER->user_is_fake == 'true' || $USER->user_email == '') && $PAGE->module == 'voting'}top_line_red_message{/if}">
	<div class="d">
		<div class="wr">
			{if not isset($TOPmessage) && $USER->authorized && ($USER->user_is_fake == 'true' || $USER->user_email == '')}
			<span class="s">
				{if $PAGE->module == 'voting' && $good}
					Чтобы Ваш голос был учтён, добавьте и подтвердите e-mail
				{else}
					{if $USER->user_email != ''}Подтвердите{else}Укажите{/if} свой e-mail, чтобы стать членом клуба Maryjane.ru:
				{/if}
			</span>
			{else}
			<span class="s">{$TOPmessage}</span>
			{/if}
			<a rel="nofollow" href="/editprofile/" class="button_podtverdit"></a>
			
			<div style="clear:both"></div>
		</div>
	</div>
</div>
{/if}