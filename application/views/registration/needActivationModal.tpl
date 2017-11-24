<p>
	{if $USER->user_activation == 'awaiting'}
		Для продолжения Вам необходимо подтвердить свой Email, так как вы ещё не сделали этого.
	{else}
		<span style="color:green">На указанный при регистрации ящик было отправлено письмо с активацией</span>
	{/if}
</p>

{if $USER->user_activation == 'failed'}
	<p>
		<span style="color:red">Отправка активационного письма для данного аккаунта более невозможна. Попытки исчерпаны</span>
	</p>
{else}
	<p>
		Если вы ещё не получали письмо с активацией, Вы можете отправить его на Ваш Email снова
	</p>
	<p>
		<form action="/registration/resend/">
			<input type="submit" value="Отправить" />
		</form>
	</p>
{/if}