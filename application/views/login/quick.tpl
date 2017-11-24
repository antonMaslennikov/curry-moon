<div style="float:left; padding-top: 30px;">
<link rel="stylesheet" href="/css/registration_page.css" type="text/css" media="screen"/>

<div class="small_title" style="margin: -30px 0 0 4px;">Вход на сайт</div>

<div style="text-align:right;width:21px;margin-top:-30px;float:right;"><a href="#" onclick="tb_remove()"><img src="/images2/icons/delete.png" alt="close" /></a></div>

<div style="float:left; width:570px; margin: 50px 65px;">
<form method="post" action="/login/" id="quickLoginForm" style="margin-left:0">
	<div class="input">
		<label for="qlogin">Имя пользователя <br> <a href="/{$module}/">Зарегистрироваться</a></label>			
		<input MAXLENGTH="25" type="text" name="login" id="reglogin" tabindex="1" class="inputbox" value="" style="width: 162px;"/>
	</div>
	<div class="input">
		<label for="qpassword">Пароль <br> <a href="/registration/recover/">Забыли пароль?</a></label>			
		<input type="password" name="password" id="regpassowrd" class="inputbox" tabindex="2" value="" style="width: 162px;"/>			
	</div>
	<input type="hidden" name="HTTP_REFERER" value="{$HTTP_REFERER}" />
	<input type="submit" name="submit" value="Вход" class="submit" tabindex="3"/>
	<label class="remember" for="remember"><input type="checkbox" checked="checked" id="remember" name="remember"> Запомнить меня</label>
</form>
<div class="fbLoginBlock">
	<p>Войти на сайт используя аккаунт Facebook</p>
	<span>Используйте свой аккаунт в социальной сети Facebook чтобы создать профиль на maryjane.ru</span>
	<a href="#facebook_enter" id="fb_enter_dva">Войти через Facebook</a>
</div>	
</div>
{literal}
<script>
var fb_login3 = {
	fb_login_btn_id : 'a#fb_enter_dva',
	fb_permission: 'user_about_me,email',
	fb_check_connection : '/registration/facebook/check/',
	fb_registration : '/registration/facebook/',
	
	init : function (){		
		$(fb_login3.fb_login_btn_id).click(function(){
			FB.getLoginStatus(function(response) {
			  if (response.status) {
				// logged in and connected user, someone you know // проверяем фейсбук ID на сервере, если прийдет ОК - значит залогинили
				fb_login3.loginWifhtFb(response);
				
			  } else {
				//console.log('not loged in FB');
				var get_perm = {scope:fb_login3.fb_permission};
				FB.login(function(response){
					if (response.status) {						
						if (response.authResponse.userID) {	// Если пользователь залогинен в фейсбук и дает разрешения на доступ к своей информации на фейсбуке						
							fb_login3.fb_user_id = response.authResponse.userID;							
							fb_login3.loginWifhtFb(response);
						} else {
							alert('Пользователь не дал прав на доступ к своим данным');
						}
					}
				},get_perm);
			  }
			});		
			return false;
		});
	},
	
	/*loginWifhtFb : function (response) {
		// Проверяем заригистрирован ли пользователь в базе - если нет - отправляем на форму регистрации
		$.get(fb_login3.fb_check_connection, {fb_user_id:response.authResponse.userID}, function (r){			
			if (r == 'ok') { location.reload();}
			else {document.location.href = fb_login3.fb_registration;}
		});
	}*/
	
	loginWifhtFb : function (response) { 
		// Проверяем заригистрирован ли пользователь в базе - если нет - отправляем на форму регистрации
		//console.log('session fbuid '+response.session.uid);
		$.get(fb_login3.fb_check_connection, {fb_user_id:response.authResponse.userID}, function (r){
			//console.log('response check on server'+r);
			if (r == 'ok') { location.reload();  /*document.location.href = document.location.href+'#';*/}
			else {
					var userID = response.authResponse.userID;
					FB.api('/me', function(response) {
						$.post(fb_login3.fb_registration, { ajax: 1, bind: 1, fb_user_id: userID, email: response.email }, function(r){
							//если все ок, то перегружаем страничку с зарегистрированным пользователем
							if (r == 'ok') location.reload();
							else {
								//если пользователя нет, то регистрируем его
								$.post(fb_login3.fb_registration, 
										{ 
											ajax: 1,
											new: 1,
											fb_user_id: userID,
											fb_user_login: '',
											fb_user_email: response.email,
											fb_user_gender: response.gender,
											fb_user_fname: response.first_name,
											fb_user_lname: response.last_name
										}, function(r){
											if (r)
												r = eval('('+r+')');
											if (r && r.status == 'ok') location.reload();
											if (r && r.status == 'no' && r.next) location.htef = r.next;
										});
							}
						});
					});
					//document.location.href = fb_login3.fb_registration;
				 }
		});
	}
	
	
};

$(document).ready(function(){
	
	FB.init({appId  : '192523004126352', status : true, cookie : true, xfbml  : true });
	fb_login3.init();
	
	$("input#reglogin").focus();
	setTimeout(function(){$("input#reglogin").focus();}, 500);
});
</script>
{/literal}
</div>