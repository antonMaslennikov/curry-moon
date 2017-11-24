<form method="post" id="rememberMe-form" action="/ajax/rememberMe/">
	<div id="rm-form">
		<p>Оставьте свой e-mail и мы пришлём Вам товары которые Вам понравились.</p>
		<input type="text" name="email" placeholder="email" required="required" />
		<input type="submit" value="подтвердить" />
	</div>
	<div id="rm-success" style="display: none">
		Спасибо. Мы пришлём Вам товары, которые Вам понравились
	</div>
</form>

{literal}
<script>
	
	$('#rememberMe-form').submit(function() {
		
		var f= this;
		
		var email = $(this).find('input[name=email]').val(); 
		
		email = email.replace(/^\s+|\s+$/g, '');
	
		if (!(/^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@([a-z0-9][a-z0-9\-]*[a-z0-9]\.)+[a-z]{2,4}$/i).test(email))
		{
			alert('То что Вы ввели не похоже на адрес электронной почты');
			return false;
		}
		
		$.post($(this).attr('action'), $(this).serialize(), function(r) {
			
			$(f).children('#rm-form').hide();
			$(f).children('#rm-success').show();
			
		});
		
		return false;
	});
	
</script>
{/literal}