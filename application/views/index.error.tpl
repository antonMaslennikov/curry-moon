{if isset($ERROR)}
<div class="TopError">
	<div class="error">
		{$ERROR.text}

		{if $ERROR.num == 1}
			{literal}<script>
				$(document).ready(function(){trackUser('Регистрация/Авторизации','ошибки в роз.верхней полоске','Неверный логин или пароль');});
			</script>{/literal}
			Неверный логин или пароль. <a href="/registration/recover/?login={$login}" style="color:red;margin-left:15px" rel="nofollow">Отправить пароль на почту</a>
		{elseif $ERROR.num == 2}
			{literal}<script>
				$(document).ready(function(){trackUser('Регистрация/Авторизации','ошибки в роз.верхней полоске','Ваш профиль заблокирован');});
			</script>{/literal}			
			Ваш профиль заблокирован.
		{elseif $ERROR.num == 3}
			{literal}<script>
				$(document).ready(function(){trackUser('Регистрация/Авторизации','ошибки в роз.верхней полоске','Пароль не можен быть пустым');});
			</script>{/literal}		
			Пароль не можен быть пустым
		{elseif $ERROR.num == 10}
			{literal}<script>
				$(document).ready(function(){trackUser('Регистрация/Авторизации','ошибки в роз.верхней полоске','Ваш аккаунт не подтверждён. Укажите Ваш email');});
			</script>{/literal}
			<form method="post" action="/editprofile/changeEmail">
				Ваш аккаунт не подтверждён. Укажите Ваш email 
				<input type="text" value="" style="height:20px; width:170px; " name="email">
				<input type="submit" value="ввести" name="submit" />
			</form>
		{/if}
		
	</div>
</div>
{/if}