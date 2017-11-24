{if $USER->client->ismobiledevice == '1' && $USER->client->istablet == 0}
	<a style="margin:0 0 0 10px;" href="/" rel="nofollow">
		<img width="121" height="21" title="{$L.HEADER_title}" src="/images/logo_mj_small.gif"/>
	</a>
{/if}

{if $action == "needActivation"}

<div class="activationBox">
	<h3>Активация аккаунта</h3>
	
	{if $error && !$byEmail && !$byPhone}
        <div class="error" style="clear:both;color:red;background: #fce0e0;padding:10px">{$error}</div>
    {/if}
	
	{if $byEmail} {* активация по мылу *}

        <p>
            На указаный почтовый адрес <span class="uemail">{$U.user_email}</span> выслано письмо с активацией. <br/>
            Перейдите по ссылке, указанной в письме, что-бы закончить регистрацию.
        </p>

        <form id="email_for_activation" style="margin: 40px 0 0 100px;">
            <label for="uemail">Не получили код активации?</label>
            <input type="text" id="uemail" name="uemail" value="{$U.user_email}" /><input style="margin: 0 0 0 35px;" type="submit" id="activSubmit" value="Отправить повторно" />
            <span class="error_sml">Отправка активации не чаще чем 1 раз в 5 минут</span>
        </form>

    {elseif $byPhone} {* активация по телефону *}

        <p>
            На указаный телефонный номер <span class="uemail"><b>{$U.user_phone}</b></span> выслано смс с кодом активации. <br/>
            Введите его в поле ниже
        </p>

        {if $error == 1}
            <div class="error" style="clear:both;margin-top:160px">Вы ввели не правильный код активации</div>
        {/if}

        <form id="email_for_activation" action="/{$module}/activate/?userid={$U.user_id}" method="post">
            <label for="uemail">введите код активации</label>
            <input type="text" name="smskey" /><input type="submit" id="activSubmit" value="Активировать" />
        </form>

    {/if}
	
</div>

{literal}
<script type="text/javascript">
	// скрипт скрывает подсказку при входе в поле инпута и востанавливает ее, если поле пустое
	var empty_val = 'Введите ваш e-mail';
	$("#uemail").focus(function(){
		if ($(this).val() == empty_val) {$(this).val('').css({color:'#000'})}
	}).blur(function(){
		if ($(this).val() == '') {$(this).val(empty_val).css({color:'#8e8e8e'})}
	}).keyup(function(){
			if (!isValidEmail($(this).val())) $('#activSubmit').attr('disabled','disabled');
			else $('#activSubmit').removeAttr('disabled');
	}).change(function(){
			if (!isValidEmail($(this).val())) $('#activSubmit').attr('disabled','disabled');
			else $('#activSubmit').removeAttr('disabled');
	}).change();
</script>
{/literal}


{elseif $action == "after_activation"}


<div class="activationBoxAfter">
	<h3>Поздравляем!<br/>Регистрация завершена</h3>

	<form id="quickLoginForm">
		<div class="input">
			<label for="qlogin">Имя пользователя <br> <a href="/{$module}/">Зарегистрироваться</a></label>
			<input type="text" value="{$login}" name="qlogin" id="qlogin">
		</div>
		<div class="input">
			<label for="qpassword">Пароль <br> <a href="/login/">Забыли пароль?</a></label>
			<input type="password" value="" name="qpassword" id="qpassword">
		</div>
		<input type="submit" value="Вход" class="submit">
		<label class="remember" for="remember"><input type="checkbox" checked="checked" id="remember" name="remember"> Запомнить меня</label>
	</form>
	
</div>


{elseif $action == "multipleEmailsAct"}

	<h1 class="pagetitle error">Ошибка!</h1>
	В базе обнаружены несколько регистраций с данного E-mail. К сожалению активация не может быть произведена. Вам придется написать письмо на адрес <a href="mailto:team@maryjane.ru">team@maryjane.ru</a> для ручной активации. <a class="showFeedback thickbox" href="/feedback/?height=550&width=300" title="Остался вопрос?" rel="nofollow"><span><img src="/images2/icons/ask_small.gif" width="15" height="18" border="0" alt="Помощь?" /></span></a>

{elseif $action == "recover"}


	<div class="activationBox recoveryBox">
        <h3>Восстановить пароль</h3>
        <p style="display:none">
            На указаный почтовый адрес {* <span class="uemail">{$EMAIL}</span> *} будет выслано письмо с паролем. <br/>
        </p>

        {if $byEmail}
        
            <div style="color:green">Инструкция по восстановлению пароля успешно выслана</div>
            <br />
            <div>Обратите внимание на то, что код для восстановления пароля будет действовать только в течение этих суток. По прошествии этого времени код станет недействительным, и Вам придется повторно отправить заявку на изменение пароля. </div>
            
        {elseif $byPhone} {* активация по телефону *}

            <p style="border-top:0;padding-top:0">
				На номер <span class="uemail"><b>{$TO}</b>, в течение десяти минут придет SMS-сообщение с кодом, который необходимо ввести в форму ниже
            </p>

            {if $error == 1}
                <div class="error" style="clear:both;margin-top:160px">Вы ввели не правильный код</div>
            {/if}

            <form id="phone_for_recover" action="/{$module}/recover/?to={$TO}" method="post" style="margin: 28px 185px 5px;">
                <input style="margin-left: 5px;" type="text" name="code" id="phonecode" value="введите код подтверждения" /><input style="margin: 5px 0 0 35px;" type="submit" id="activSubmit" value="Подтвердить" />
            </form>

        {/if}

        {if $error}
            <div class="error_sml" style="padding-bottom:15px;font-size: 12px">{$error.text}</div>
            
            {if $not_found}
            <script>
            	if (confirm($('.error_sml').text())) 
            	{
            		location.href = '/{$module}/?email={$TO}';
            	};
            </script>
            {/if}
        {/if}

        {if $byEmail || $byPhone}
        <div style="border-bottom:0;padding-bottom: 0;margin-right: 10px;"><a href="/{$module}/recover/#{$TO}" class="dashed">отправить ещё раз</a></div>
        {/if}

		<div {if $byEmail || $byPhone}style="display: none;" {/if}>Пожалуйста, укажите Логин или e-mail, который Вы использовали для входа на сайт.<br /> Если Вы не помните этих данных, укажите телефон</div>

        <form id="email_for_activation" method="post" {if $byEmail || $byPhone}style="display: none;" {/if}>
            <input class="uEmail" type="text" id="uemail" name="to" value="{$to}" placeholder="Логин, Email или телефон" />
            <input class="activSubmit" type="submit" id="activSubmit" value="Продолжить" />
            
            {if $recover_try > 1}
            <br clear="all" /><br />
            <table style="padding-top:15px;">
			<tr>
				<td><input type="text" name="keystring" id="key" size="2"  maxlength="5" style="height:38px;width:98px;font-size:38px;font-family:Times New Roman;"></td>
				<td><div class="left" id="secimg" style="height:52px;"></div></td>
				<td style="font-size:10px; line-height:9px;padding-left: 10px"><a href="#" onclick="getCapcha(); return false;"  style="color:gray">Обновить<br />картинку</a></td>
			</tr>
			</table>
			<div style="font-size:11px;padding-bottom:2px; padding-bottom:10px; text-align:left">Введите текст c картинки в нижнем регистре</div>
			
			<script>
				function getCapcha()
				{
					$.get("/ajax/genCaptcha/", function(data){
						$("#secimg").empty().append(data);
					});
				}
				
				getCapcha();
			</script>
			{/if}
        </form>


    </div>

    {literal}
    <script type="text/javascript">
        // скрипт скрывает подсказку при входе в поле инпута и востанавливает ее, если поле пустое
        var empty_val = 'Логин, Email или телефон';
        var code_empty_val = $('#phonecode').val();
        
        $("#uemail").focus(function(){
            if ($(this).val() == empty_val) {$(this).val('').css({color:'#000'})}
        }).blur(function(){
            if ($(this).val() == '') {$(this).val(empty_val).css({color:'#8e8e8e'})}
        });

        $('#email_for_activation').submit(function(){
            var email = $('#uemail').val();
            if (email == empty_val || email.length == 0)
                return false;
        });
        
        $('#phone_for_recover').submit(function(){
            var email = $('#uphone').val();
            if (email == empty_val || email.length == 0)
                return false;
        });
        
        $("#phonecode").focus(function(){
            if ($(this).val() == code_empty_val) {$(this).val('').css({color:'#000'})}
        }).blur(function(){
            if ($(this).val() == '') {$(this).val(code_empty_val).css({color:'#8e8e8e'})}
        });
        
        $(document).ready(function(){
        	var to = window.location.hash.replace('#', '');
        	if (to.length > 0)
        		$('#uemail').val(to);
        });
    </script>
    {/literal}

{elseif $action == "chPassword"}


<div class="activationBox recoveryBox">
		<h3>Сменить пароль от профиля "<b>{$user.user_login}</b>"</h3>
		
		{if $success}
		<div style="color:green">{$success}</div>
		{/if}
		
		{if $error}
		<div class="error_sml">{$error.text}</div>
		{/if}
		
		<form id="email_for_activation" method="post" action="/{$module}/recover/?code={$code}">
			<label>
				Новый пароль<br />
				<input class="uEmail" type="password" id="uemail" name="p1" value="" />
			</label>
			<div style="clear:both;padding-top:10px">
				<label>
					Повторите пароль<br />
					<input class="uEmail" type="password" id="uemail" name="p2" value="" /><br/>
					<input class="activSubmit" type="submit" id="activSubmit" value="сменить пароль" name="chPassword" />
				</label>
				
			</div>
		</form>
			
			
	</div>
	

{elseif $action == "forgotPasswordForm"}

<h1 class="pagetitle">Восстановление пароля</h1>
      <form method="POST">
<h5>Ведите адрес на который была произведена регистрация:<sup><span class="required">*</span></sup></h5>
<input type="text" class="inputbox" name="email" id="regemail" value="">
<span id="emailCheck" style="display:none;"><img src="/images2/loading2.gif"></span>
<h5 class="error" id="emailError" style="display:none;"></h5>
<h6 class="smallcomment">Указывайте действующий E-mail — на него Вам будет выслан код активации.</h6>

<input type="hidden" name="submit" value="true">
<h3><input type="submit" name="submit" value="Восстановить пароль"></h3>
</form>

{elseif $action == "forgotPasswordSent"}

<h1 class="pagetitle">Восстановление пароля</h1>
Логин и пароль для входа были высланы Вам на E-mail: <strong class="error">{$EMAIL}</strong></td>


{elseif $action == "resendForm"}


<form method="POST">
<h1 class="pagetitle">Повторный запрос активации</h1>
<h5>Ведите адрес на который была произведена регистрация:<sup><span class="required">*</span></sup></h5>
<input type="text" class="inputbox" name="email" id="regemail" value="">
<h6 class="smallcomment">Указывайте действующий E-mail — на него Вам будет выслан код активации.</h6>

<input type="hidden" name="submit" value="true">
<h3><input type="submit" name="submit" value="Запросить активацию"></h3>
</form>


{elseif $action == "multipleEmails"}


<h1 class="pagetitle error">Ошибка!</h1>
В базе обнаружены несколько регастраций с данного E-mail. К сожалению пароль не может быть восстановлен автоматически. Вам придется написать письмо на адрес <a href="mailto:info@maryjane.ru">info@maryjane.ru</a> для ручной активации. <a class="showFeedback thickbox" href="/feedback/?height=550&width=300" title="Остался вопрос?" rel="nofollow"><span><img src="/images2/icons/ask_small.gif" width="15" height="18" border="0" alt="Помощь?" /></span></a>


{elseif $action == "emailNotFound"}


<h1 class="pagetitle error">Ошибка!</h1>
Даный E-mail в базе данных не найден. Если Вы новый пользователь, пожалуйста, <a href="/{$module}/">зарегистрируйтесь</a> <a class="showFeedback thickbox" href="/feedback/?height=550&width=300" title="Остался вопрос?" rel="nofollow"><span><img src="/images2/icons/ask_small.gif" width="15" height="18" border="0" alt="Помощь?" /></span></a>


{elseif $action == "loginNeedActivation"}


<h1 class="pagetitle error">Необходима активация. <a class="showFeedback thickbox" href="/feedback/?height=550&width=300" title="Остался вопрос?" rel="nofollow"><span><img src="/images2/icons/ask_small.gif" width="15" height="18" border="0" alt="Помощь?" /></span></a></h1>
Ваш логин зарегистрирован, но не активен. <br/>
Если Вам не пришло активационное письмо перейдите по <a href="?step=2&sub=5">этой ссылке</a> для повторного запроса.


{elseif $action == "quick"}


<!-- Стили для форм регистрации, востановления пароля и других стилей -->
<div id="fb-root"></div>
{literal}
<script>
  window.fbAsyncInit = function(){FB.init({appId  : '192523004126352', status : true, cookie : true, xfbml  : true });};
  (function() {
    var fbe = document.createElement('script');
    fbe.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
    fbe.async = true;
    document.getElementById('fb-root').appendChild(fbe);
  }());
</script>
{/literal}

<div id="TB_title"><div class="small_title">Вход на сайт</div>
<div style="text-align: right;"><a onclick="tb_remove()" href="#"><img alt="close" src="http://www.maryjane.ru/images/icons/delete.png"></a></div>
</div>
<div class="qickBlock">
	<form action="/{$module}/quick/" method="post" id="quickRegForm" onsubmit="return checkReg();">
		<div class="error"></div>
		<div class="input">
			<label for="quickRegEmail">Введите Ваш Email </label>
			<input type="text" size="30" name="email" id="quickRegEmail" class="required_input" />
			<input type="submit" id="submitbtn" value="Продолжить" /><br />
			<input type="hidden" name="next" value="{$next}" />
		</div>
		<label for="news" class="newsSubscribe"><input type="checkbox" name="news" id="news" checked="checked" /> подписаться на новости</label>
	</form>
</div>

<form id="quickLoginForm" action="/login/" method="post" >
	<div class="input">
		<label for="qlogin">Имя пользователя <br/> <a href="/{$module}/">Зарегистрироваться</a></label>
		<input type="text" id="qlogin" name="login" value="" tabindex="1" />		
	</div>
	<div class="input">
		<label for="qpassword">Пароль <br/> <a href="/login/">Забыли пароль?</a></label>
		<input type="password" id="qpassword" name="password" size="20" value="" tabindex="2" />		
	</div>
	<input type="submit" name="submit" id="Login" class="submit" value="Вход" tabindex="3" />
	<label for="remember" class="remember"><input type="checkbox" name="remember" id="remember" checked="checked" /> Запомнить меня</label>
</form>

<div class="fbLoginBlock">
	<p>Войти на сайт используя аккаунт Facebook</p>
	<span>Используйте свой аккаунт в социальной сети Facebook чтобы создать профиль на maryjane.ru</span>
	
	<a id="fb_enter" href="#facebook_enter">Войти через Facebook</a>
	
</div>

{literal}
<script type="text/javascript">
	function checkReg()
	{
		var email = $('#quickRegEmail').val();
		$('.error').html('');
		if (email.length == 0) {$('.error').html("Ошибка! E-mail не указан.").show(); return false;}	
		if (!isValidEmail(email)) {$('.error').html("То, что Вы ввели, не похоже на адрес электронной почты.").show(); return false;}		
	}
	
//######################  для Входа через ФЕйсбук
var fb_login = {
	name: 'fb_login',
	btn_fb_enter : '#fb_enter',
	fb_connect_link: '/ajax/facebook/connect/',
	fb_permission: 'user_about_me,email',
	fb_user_id : '',
	fb_reg_url : '/{$module}/facebook/',
	fb_check_connection : '/{$module}/facebook/check/',
	
	init: function () {
		this.fb_login_init();
	},
	
	fb_login_init : function () {
		
		var get_perm = {perms:fb_login.fb_permission};
		$(fb_login.btn_fb_enter).click(function(){
			FB.login(function(response){
				if (response.session) {
					if (response.perms) {	// Если пользователь залогинен в фейсбук и дает разрешения на доступ к своей информации на фейсбуке
					
						fb_login.fb_user_id = response.session.uid;
						// Проверяем заригистрирован ли пользователь в базе - если нет - отправляем на форму регистрации
						$.get(fb_login.fb_check_connection, {fb_user_id:response.session.uid}, function (r){							
							if (r == 'ok') { document.location.href = '/order/';}
							else {fb_login.getFBUserData();}
						});						
					} else {
						alert('Пользователь не дал прав на доступ к своим данным');
					}
				}
			},get_perm);
			return false;
		});
	},
	
	getFBUserData : function () {
		FB.api('/me', function(response) {
		  $("#quickRegEmail").val(response.email);
		  document.location.href = fb_login.fb_reg_url;	// redirect to facebook registration form
		});
    }
};
//##########################	
	$(document).ready(function(){
		$("input#qlogin").focus();
		fb_login.init();
		$("form#quickRegForm").submit(function(){
			return true;
		});
	});
</script>
{/literal}


{elseif $action == "merge"}
<!-- слияние нескольких аккаунтов, зарегистрированных на один email -->
<style>
#content{ldelim}width:100%;{rdelim}
</style>

<div class="comebackBox">
	<h3>
		С возвращением!	<br/>
		Нужно объединить ваши аккаунты, выберите один из них:
	</h3>

	<p>Выберите одну из учетных записей, в нее, будут объединены заказы, бонусы и работы.</p>
	<ul class="accountsList" style="display:none">
		{foreach from=$user item="u"}
		<li onclick="return show_password_form('{$u.user_id}', this);">
			<div class="uPic">{$u.user_avatar}</div>
			<i>{$u.user_login}</i>
			<p class="top">тел.: {$u.user_phone} <br/>Заказов: {$u.orders_count} / Бонусов: {$u.user_bonus} руб. / Работ: {$u.goods_count}</p>
			<p class="detail">Зарегистрирован: {$u.user_register_date}<br/>
				{if $u.last_order}
				Последний заказ: {$u.last_order.date}</p>
				{/if}
		</li>
		{/foreach}
	</ul>

	<!--<div class="darckBorder"> -->
	{foreach from=$user item="u"}
	<div class="selectAccount password-forms" id="password-{$u.user_id}" style="display:_none; height:100px; overflow:hidden; cursor:pointer;">
		<div class="mainAcc">
			<div class="uPic">{$u.user_avatar}</div>
			<i>{$u.user_login}</i>
			<p class="top">тел.: {$u.user_phone} <br/>Заказов: {$u.orders_count} / Бонусов: {$u.user_bonus} руб. / Работ: {$u.goods_count}</p>
			<p class="detail">Зарегистрирован: {$u.user_register_date}<br/>
				{if $u.last_order}
				Последний заказ: {$u.last_order.date}
				{/if}
			</p>
			<div>
				<h4>Код отправлен на {$email}</h4>
				<form class="comebackForm" id="comebackForm{$u.user_id}" action="/{$module}/merge/confirm/">
					<label for="comeback-pass">Введите код</label>
					<input type="text" class="comeback-pass" name="code" >
					<input type="hidden" name="key" value="{$key}" />
					<input type="hidden" name="next" value="{$next}" />
					<input type="hidden" name="user_id" class="comeback-user" value="{$u.user_id}" />
					<input type="hidden" name="phone" value="{if $smarty.get.phone}1{/if}" />
					<input type="submit" class="submitbtn" name="submit" value="Объединить" /> <!--onclick="return check_confirm($(this).parent());"-->
					<span class="error_sml">Неверный пароль</span>
				</form>
			</div>
		</div>
	</div>
	{/foreach}
	<!--</div> -->
</div>

{literal}
<style>
  .password-forms {border:1px solid #fff;padding:0;}
  .password-forms:hover {border:1px solid #00A851;}
  .selectedAccount {border: 4px solid #545454!important; height:auto!important;}
</style>

<script>
function show_password_form(id, obj) 
{
	$('.error_sml').hide();
	
	$('.accountsList > li').removeClass('selected');
	$(obj).addClass('selected');
	$('.password-forms').hide();
	$('#password-' + id).show();
	
	if (!$(obj).hasClass('sended')) 
	{
		// отправка письма с паролем и с ссылкой для автоматического перехода
		$.post('/{/literal}{$module}{literal}/merge/?key={/literal}{$key}{if $smarty.get.phone}&phone=1{/if}{literal}', {'select': 1, 'id' : id}, function (r) {
			$(obj).addClass('sended');
		});
	}
}
	
var forms = {
	
	currentForm: '',

	init: function () {
		this.initSelectAcc();
		this.initSubmitForm();		
	},
	// Когда пользователь выбирает один из аккаунтов - мы открываем форму 
	// и отправляем на прикрепленный имейл пароль 
	initSelectAcc : function () {
		$(".password-forms").click(function () {
			$('.error_sml').hide();
			$('.password-forms').removeClass('selectedAccount');
			$(this).addClass('selectedAccount');
			
			forms.currentForm = this;
			
			if (!$(forms.currentForm).hasClass('sended')) {
				var id = $(forms.currentForm).find('.comebackForm').children("input.comeback-user").val();
				$.post('/{/literal}{$module}{literal}/merge/?key={/literal}{$key}{if $smarty.get.phone}&phone=1{/if}{literal}', {'select': 1, 'id' : id}, function (r) {
					$(forms.currentForm).addClass('sended');
				});
			}
		
		});
	
	},
	
	initSubmitForm : function () {
		$("form.comebackForm").submit(function(){		// перед сабмитом формы объединения аккаунтов, сначаса делаем проверку через ajax-правильно ли юзер
														// ввел пароль к даному логину, а потом сабмитим, что-бы объединить аккаунты.
			//console.log("submit FORM "+$(this).attr("id"));
			if ($(this).hasClass("canSubmit")) {				
				//console.log("submit true");
				return true;
				exit();
			} else {
				forms.cur_form = $(this);
				var pass = $(forms.cur_form).children('.comeback-pass').val();
				var user = $(forms.cur_form).children('.comeback-user').val();
				if (pass.length > 0) {
					$.get('/{/literal}{$module}{literal}/merge/check_confirm/', {'password' : pass, 'user_id' : user}, function (r) {
						if (r == 'true') {
							$('.error_sml').hide();
							$(forms.cur_form).addClass("canSubmit").unbind("submit");							
							//console.log($(forms.cur_form).attr("id"));
							//$(forms.cur_form).submit();
							$("form#"+$(forms.cur_form).attr("id")+" input[type=submit]").click();							
							return true;
						} else {
							$('.error_sml').show();
						}
					});
				} else {
					$('.error_sml').show();					
				}
				console.log("submit false");
				return false;
			}
		});
	}
};	

$(document).ready(function(){
	forms.init();
	
	$(".comebackBox ul li").hover(
		function(){$(this).addClass("hover")},
		function(){$(this).removeClass("hover")}
	);
});
</script>
{/literal}

{elseif $action == "comeback"}

	<div class="comebackBox comebackBoxOne actionComeback">
		
		<h3>С возвращением! <br />Однажды вы уже регистрировались на этот e-mail <strong>{$u.email}</strong></h3>

		<div class="comebackAcc clearfix">
			<div class="mainAcc clearfix">
				<div class="uPic">{$u.user_avatar}</div>
				<i>{$u.user_login}</i>
				<p class="top">{*if $u.user_phone}тел.: {$u.user_phone}<br/>{/if*}{if $u.orders_count>0}Заказов: {$u.orders_count}{/if} {if $u.user_bonus>0}/ Бонусов: {$u.user_bonus} руб.{/if}  {if $u.goods_count>0}/ Работ: {$u.goods_count}{/if} </p>
				<p class="detail">Зарегистрирован: {$u.user_register_date}<br/>
					{if $u.last_order}
					Последний заказ: {$u.last_order.date}
					{/if}
				</p>
			</div>

			<form id="comebackFx3" class="comebackForm clearfix" action="/{$module}/comeback/confirm/">			
				<label for="comeback-pass">Введите пароль</label>
				<input type="password" class="comeback-pass" name="password" >
				<input type="hidden" name="next" value="{$next}" />
				<input type="hidden" name="user_id" class="comeback-user" value="{$u.user_id}" />
				<input type="hidden" name="social" value="{$social}" />
				<input type="hidden" name="social_user_id" value="{$social_user_id}" />
				<input type="submit" style="margin-top: 2px;" class="submitbtn" name="submit" value="Вход" />
				<span class="error_sml">Неверный пароль</span>
			</form>			
			<p class="or">или</p>			
			<p class="ag"><a href="#" class="dashed" onclick="return resendPassword('{$u.user_id}', this);">Отправить инструкцию</a> по восстановлению пароля на {$email}</p>			
		</div>		
	</div>

	{literal}
	<script>
		function resendPassword(id, o){
			$(o).hide();
			
			$.post('/{/literal}{$module}{literal}/comeback/resend/', {'user_id' : id}, function(r) {
				$(o).addClass('yes').show();
			});
		}

	var forms = {
		
		currentForm: '',
		formID : 'form#comebackFx3',
		
		init: function () {
			this.initSubmitForm();		
		},
		
		initSubmitForm : function () {
			$(this.formID).submit(function(){		// перед сабмитом формы объединения аккаунтов, сначаса делаем проверку через ajax-правильно ли юзер
															// ввел пароль к даному логину, а потом сабмитим, что-бы объединить аккаунты.
				if ($(this).hasClass("canSubmit")) {				
					//console.log("submit true");
					return true;
				} else {
					forms.cur_form = $(this);
					var pass = $(forms.cur_form).children('.comeback-pass').val();
					var user = $(forms.cur_form).children('.comeback-user').val();
					if (pass.length > 0) {
						$.get('/{/literal}{$module}{literal}/comeback/check_confirm/', {'password' : pass, 'user_id' : user}, function (r) {
							if (r == 'true') {
								$('.error_sml').hide();
								$(forms.cur_form).addClass("canSubmit").unbind("submit");							
								//console.log($(forms.cur_form).attr("id"));
								//$(forms.cur_form).submit();
								$("form#"+$(forms.cur_form).attr("id")+" input[type=submit]").click();							
								return true;
							} else {
								$('.error_sml').show();
							}
						});
					} else {
						$('.error_sml').show();					
					}
					//console.log("submit false");
					return false;
				}
			});
		}
	};	

	$(document).ready(function(){
		forms.init();
	});	
		
	</script>
	{/literal}


{elseif $action == "phonecomeback"}


<div class="comebackBox comebackBoxOne" style="padding: 65px 72px 80px 70px">
	
	<h3>С возвращением!<br />Однажды вы уже регистрировались на этот телефон</h3>

	<div class="comebackAcc">
		<div class="mainAcc" style="margin-left: 50px;">
			<div class="uPic">{$u.user_avatar}</div>
			<i>{$u.user_login}</i>
			<p class="top">тел.: {$u.user_phone} <br/>Заказов: {$u.orders_count} / Бонусов: {$u.user_bonus} руб. / Работ: {$u.goods_count}</p>
			<p class="detail">Зарегистрирован: {$u.user_register_date}<br/>
				{if $u.last_order}
				Последний заказ: {$u.last_order.date}
				{/if}
			</p>
		</div>
			<p class="ag">Отправить код для смены пароля на {$phone} <a href="#" class="dashed" onclick="return resendPassword('{$u.user_id}', this);">ещё раз</a>.<br /><small style="float: right;font-size: 10px;margin-right: 120px;" id="waitError">Отправка не чаще чем 1 раз в минуту</small></p>
			
			<form id="comebackFx3" class="comebackForm" method="post" action="/{$module}/phonecomeback/confirm/" style="margin-left:4px;width:390px;">
				<label style="margin-top:4px;width:140px;text-align:right;padding:4px 20px 0 0;" for="user_id">Введите код</label>
				<input type="text" class="comeback-pass" name="code" >
				<input type="hidden" name="next" value="{$next}" />
				<input type="hidden" name="user_id" class="comeback-user" value="{$u.user_id}" />				
				

				<label style="margin-top:10px;clear:left;width:140px;text-align:right;padding:4px 20px 0 0;display:none" for="password" class="label-password">Введите новый пароль</label>
				<input type="password" name="password" style="width:100px;margin-top:8px;display:none" placeholder="Новый пароль" />
				
				<input style="margin-top: 2px;" type="submit" class="submitbtn" name="submit" value="Вход" />	
				<span class="error_sml" style="padding-left:180px;">Неверный код</span>			
			</form>
		
	</div>
	
</div>

{literal}
<style>
  .comebackBoxOne h3 {  font-weight: normal;  text-align: center;  }
  .comebackBoxOne .mainAcc {width:380px;}
  .comebackBoxOne .mainAcc p {width: 270px;}

  .comebackAcc {  position: relative;  width: 420px;  margin: 15px auto;  }
  .comebackAcc .userInfo {  border-bottom: 1px solid #afafaf;  }
  .comebackAcc p.ag {float:left;text-transform: normal;width:100%;padding:0 0 10px;text-align:left;margin: 0;}
  .comebackAcc p.ag a {color: #00A851;}

  .disabled {  background-color: #808080 !important;  border-color: #808080 !important;  }
</style>

<script>

	function resendPassword(id, o){
		$(o).hide();
		
		$.post('/{/literal}{$module}{literal}/phonecomeback/resend/', {'user_id' : id}, function(r) {
			$(o).show();
			
			if (r == 'wait')
				$('#waitError').css('color', 'red');
		});
	}

var forms = {
	
	currentForm: '',
	formID : 'form#comebackFx3',
	
	init: function () {
		this.initSubmitForm();		
	},
	
	initSubmitForm : function () {
		
		$(this.formID).submit(function(){		// перед сабмитом формы объединения аккаунтов, сначаса делаем проверку через ajax-правильно ли юзер
														// ввел пароль к даному логину, а потом сабмитим, что-бы объединить аккаунты.
			if ($(this).hasClass("canSubmit")) {				
				//console.log("submit true");
				return true;
			} else {
				forms.cur_form = $(this);
				var code = $(forms.cur_form).children('.comeback-pass').val();
				var user = $(forms.cur_form).children('.comeback-user').val();
				if (code.length > 0) {
					$.get('/{/literal}{$module}{literal}/phonecomeback/check_confirm/', {'code' : code, 'user_id' : user}, function (r) {
						if (r == 'true') {
							
							var pass = $(forms.cur_form).find('input[name=password]').val();
					
							if (pass.length > 0)
							{
								//$(forms.cur_form).unbind('submit').submit();
								$(forms.cur_form).addClass("canSubmit").unbind("submit");							
								$("form#"+$(forms.cur_form).attr("id")+" input[type=submit]").click();	
							}
							else
							{
								$(forms.cur_form).find('input[type=password],label.label-password').show();
								$(forms.cur_form).addClass('passwordSHOW');//для наведения красоты
								$(forms.cur_form).find('input[type=submit]').val('Сохранить');
							}
							
							$('.error_sml').hide();
							
							//$(forms.cur_form).addClass("canSubmit");
							//$(forms.cur_form).addClass("canSubmit").unbind("submit");							
							//$("form#"+$(forms.cur_form).attr("id")+" input[type=submit]").click();							
							return true;
						} else {
							$('.error_sml').show();
							$(forms.cur_form).find('input[type=password],label.label-password').hide();
							$(forms.cur_form).removeClass('passwordSHOW');//для наведения красоты
						}
					});
				} else {
					$('.error_sml').show();					
				}
				//console.log("submit false");
				return false;
			}
		});
	}
};	

$(document).ready(function(){
	forms.init();
});	
	
</script>
{/literal}

{elseif $action == "phonemerge"}

	{* слияние нескольких аккаунтов с одним телефоном *}
	
	<div class="comebackBox">
	
		<h3>
			С возвращением!	<br/>
			Нужно объединить ваши аккаунты, выберите один из них:
		</h3>

		<p>
			Выберите одну из учетных записей, в нее, будут объединены заказы, бонусы и работы.
			<br />Отправка сообщений не чаще чем раз в 5 минут
		</p>
		
		<ul class="accountsList" style="display:none">
			{foreach from=$user item="u"}
			<li onclick="return show_password_form('{$u.user_id}', this);">
				<div class="uPic">{$u.user_avatar}</div>
				<i>{$u.user_login}</i>
				<p class="top">тел.: {$u.user_phone} <br/>Заказов: {$u.orders_count} / Бонусов: {$u.user_bonus} руб. / Работ: {$u.goods_count}</p>
				<p class="detail">Зарегистрирован: {$u.user_register_date}<br/>
					{if $u.last_order}
					Последний заказ: {$u.last_order.date}</p>
					{/if}
			</li>
			{/foreach}
		</ul>

		{foreach from=$user item="u"}
		<div class="selectAccount password-forms" id="password-{$u.user_id}" style="display:_none; height:100px; overflow:hidden; cursor:pointer;">
			<div class="mainAcc">
				<div class="uPic">{$u.user_avatar}</div>
				<i>{$u.user_login}</i>
				<p class="top">тел.: {$u.user_phone} <br/>Заказов: {$u.orders_count} / Бонусов: {$u.user_bonus} руб. / Работ: {$u.goods_count}</p>
				<p class="detail">Зарегистрирован: {$u.user_register_date}<br/>
					{if $u.last_order}
					Последний заказ: {$u.last_order.date}
					{/if}
				</p>
				{if $u.user_id == $USER->id}
					<p style="margin-top:10px;"><b>Вы вошли под этим аккаунтом</b></p>
				{/if}
				<div>
					<h4>Код отправлен на {$phone}</h4>
					<form class="comebackForm" id="comebackForm{$u.user_id}" action="/{$module}/phonemerge/confirm/">
						<label for="comeback-pass">Введите код</label>
						<input type="text" class="comeback-pass" name="code" >
						<input type="hidden" name="key" value="{$key}" />
						<input type="hidden" name="next" value="{$next}" />
						<input type="hidden" name="user_id" class="comeback-user" value="{$u.user_id}" />
						<input type="submit" class="submitbtn" name="submit" value="Объединить" /> <!--onclick="return check_confirm($(this).parent());"-->
						<span class="error_sml">Неверный пароль</span>
					</form>
				</div>
			</div>
		</div>
		{/foreach}
	</div>

	{literal}
	<style>
		.password-forms {border:1px solid #fff;padding:0;}
		.password-forms:hover {border:1px solid #00A851;}
		.selectedAccount {border: 4px solid #545454!important; height:auto!important;}
	</style>

	<script>
	function show_password_form(id, obj) 
	{
		$('.error_sml').hide();
		
		$('.accountsList > li').removeClass('selected');
		$(obj).addClass('selected');
		$('.password-forms').hide();
		$('#password-' + id).show();
		
		if (!$(obj).hasClass('sended')) 
		{
			// отправка письма с паролем и с ссылкой для автоматического перехода
			$.post('/{/literal}{$module}{literal}/phonemerge/?key={/literal}{$key}{literal}', {'select': 1, 'id' : id}, function (r) {
				$(obj).addClass('sended');
			});
		}
	}
		
	var forms = {
		
		currentForm: '',

		init: function () {
			this.initSelectAcc();
			this.initSubmitForm();		
		},
		// Когда пользователь выбирает один из аккаунтов - мы открываем форму 
		// и отправляем на прикрепленный имейл пароль 
		initSelectAcc : function () {
			$(".password-forms").click(function () {
				$('.error_sml').hide();
				$('.password-forms').removeClass('selectedAccount');
				$(this).addClass('selectedAccount');
				
				forms.currentForm = this;
				
				if (!$(forms.currentForm).hasClass('sended')) {
					var id = $(forms.currentForm).find('.comebackForm').children("input.comeback-user").val();
					$.post('/{/literal}{$module}{literal}/phonemerge/?key={/literal}{$key}{literal}', {'select': 1, 'id' : id}, function (r) {
						$(forms.currentForm).addClass('sended');
					});
				}
			
			});
		
		},
		
		initSubmitForm : function () {
			$("form.comebackForm").submit(function(){		// перед сабмитом формы объединения аккаунтов, сначаса делаем проверку через ajax-правильно ли юзер
															// ввел пароль к даному логину, а потом сабмитим, что-бы объединить аккаунты.
				//console.log("submit FORM "+$(this).attr("id"));
				if ($(this).hasClass("canSubmit")) {				
					//console.log("submit true");
					return true;
					exit();
				} else {
					forms.cur_form = $(this);
					var pass = $(forms.cur_form).children('.comeback-pass').val();
					var user = $(forms.cur_form).children('.comeback-user').val();
					if (pass.length > 0) {
						$.get('/{/literal}{$module}{literal}/phonemerge/check_confirm/', {'password' : pass, 'user_id' : user}, function (r) {
							if (r == 'true') {
								$('.error_sml').hide();
								$(forms.cur_form).addClass("canSubmit").unbind("submit");							
								//console.log($(forms.cur_form).attr("id"));
								//$(forms.cur_form).submit();
								$("form#"+$(forms.cur_form).attr("id")+" input[type=submit]").click();							
								return true;
							} else {
								$('.error_sml').show();
							}
						});
					} else {
						$('.error_sml').show();					
					}
					console.log("submit false");
					return false;
				}
			});
		}
	};	

	$(document).ready(function(){
		forms.init();
		
		$(".comebackBox ul li").hover(
			function(){$(this).addClass("hover")},
			function(){$(this).removeClass("hover")}
		);
	});
	</script>
	{/literal}

{elseif $action == "facebook"}


<!-- Стили для форм регистрации, восстановления пароля и других стилей -->
<div class="fbConnect">
	<form id="connectFbAccount" class="bordR3" method="post">  
		<h4>У меня уже есть аккаунт на Maryjane.ru</h4>
		<div class="error">
		{if $error1} 
			{$error1.text}
		{/if}
		</div>
		
		<input type="hidden" name="fb_user_id" class="fbuid" id="fb_user_id" value="" />
		<div class="input">
			<label for="mj_login">Имя пользователя</label>
			<input type="text" name="mj_login" id="mj_login" value="" />
		</div>
		<div class="input">
			<label for="password">Пароль <br/> <a href="/login/">Забыли пароль?</a></label>
			<input type="password" name="password" id="password" value="" />
		</div>		
		<div class="submit">
			<input id="submitbtn" type="submit" name="bind" value="Связать мои аккаунты" />
		</div>
	</form>

	<form id="newFromFacebook" class="bordR3" method="post">
		<h4>Я регистрируюсь впервые</h4>
		<div class="error">
		{if $error2} 
			{$error2.text}
		{/if}
		</div>
		<input type="hidden" name="fb_user_id" class="fbuid" id="fb_user_id" value="" />
		<div class="input">
			<label for="fb_user_login">Имя пользователя</label>
			<input type="text" name="fb_user_login" id="fb_user_login" value="" readonly="readonly" />
		</div>
		<div class="input">
			<label for="fb_user_email">Email</label>
			<input type="text" name="fb_user_email" id="fb_user_email" value=""  readonly="readonly" />
		</div>
		<label for="news" class="newsSubscribe"><input type="checkbox" name="news" id="news" checked="checked" />Подпишитесь на новости</label>
		<!-- Скрытые поля  -->
		<input type="hidden" name="fb_user_fname" id="fb_user_fname" value="" />
		<input type="hidden" name="fb_user_lname" id="fb_user_lname" value="" />
		<input type="hidden" name="fb_user_gender" id="fb_user_gender" value="" />
		
		<div class="submit">
			<input id="submitbtn" type="submit" name="new" value="Новый пользователь" />
		</div>	
	</form>
</div>

{literal}
<!-- Джава скрипт для страницы Конекта с фейсбуком -->
<div id="fb-root"></div><!-- Обязательный тег для работы фейсбука -->
<script src="http://connect.facebook.net/en_US/all.js"></script>
<script type="text/javascript">

var fb_login = {
	name: 'fb_login',
	btn_fb_enter : '#fb_enter',
	fb_connect_link: '/ajax/facebook/connect/',
	fb_permission: 'user_about_me,email',
	fb_user_id : 'input.fbuid',
	fb_reg_url : '/{/literal}{$module}{literal}/facebook/',
	checklogin_link: '/ajax/checkavailloginfb/',
	fb_check_connection : '/{/literal}{$module}{literal}/facebook/check',
	
	init: function () {
		$("#fb_user_login").val('');
		 FB.init({
			 appId  : '192523004126352',
			 status : true, // check login status
			 cookie : true, // enable cookies to allow the server to access the session
			 xfbml  : true  // parse XFBML
		   });		 
		
		this.checkFBUserData();
	},

	checkFBUserData : function () {		
		FB.getLoginStatus(function(response) {
			if (response.session) {
			// logged in and connected user, someone you know
				fb_login.fillFBInformation();
			} else {
				// no user session available, someone you dont know
				FB.login(function(response){
					if (response.status) {
						if (response.authResponse.userID) {	// Если пользователь залогинен в фейсбук и дает разрешения на доступ к своей информации на фейсбуке
							fb_login.fillFBInformation();
						} else {
							alert('Пользователь не дал прав на доступ к своим данным');
						}
					} else {
						document.location.href = "/";
					}
				},{scope: 'user_about_me,email'});
			}
		});
	},
	
	// Заполняем данные пользователя из Фейсбука
	fillFBInformation:function () {
		FB.api('/me', function(response) {
			if (!response || response.error) {
				//alert(response.username);
				alert('Ошибка! Попробуйте еще раз пожалуйста.\n'+ response.error.message);
			} else {
				$("#fb_user_email").val(response.email);
				$("#fb_user_fname").val(response.first_name);
				$("#fb_user_lname").val(response.last_name);
				$("#fb_user_gender").val(response.gender);

				$(fb_login.fb_user_id).val(response.id);
				// Если у пользователя не задан логин, формируем его из мыла и отправляем на сервер для проверки
				if (response.username == undefined) {					
					new_login = response.email.substr(0,response.email.lastIndexOf('@'));
					//new_login = /([a-zA-Z\d]*)/.exec(new_login);
					new_login = new_login.replace(/[\.\-]+/,'');					
					$.get(fb_login.checklogin_link, {login:new_login}, function (valid_login){
						$("#fb_user_login").val(valid_login);						
					});
					
				} else {
					$("#fb_user_login").val(response.username);
				}
					
			}		  
		});
	}
};

$(document).ready(function(){
	fb_login.init();
});

// Получаем данные пользователя с фейсбука и заполняем поля на формах
</script>
{/literal}

{/if}