<script type="text/javascript" src="/js/registration.js"></script>
<link rel="stylesheet" href="/css/registration_page.css" type="text/css" media="screen"/>

{literal}
<style type="text/css">
.registration042011 .leftSide label {font-size: 14px;padding-top: 11px;}
.registration042011 .rightSide label {font-size: 14px;padding-top: 11px;width:70px;}
.registration042011 .submit input#submitbtn {font-size: 14px;}
.registration042011 .submit input#submitbtn:disabled, .registration042011 .submit input#submitbtn:disabled:hover { background-color: gray !important; border:0px; cursor:default !important; }
</style>
{/literal}





{if $USER->client->ismobiledevice == '1'}
	<a style="margin:0 0 0 10px;" href="/" rel="nofollow">
		<img width="121" height="21" title="{$L.HEADER_title}" src="/images/logo_mj_small.gif"/>
	</a>
{/if}



{if $action == ""}
	{if !$authorized}
		<p class="registration">{$L.REGISTRATION_you_registr}? <a href="/login/" rel="nofollow">{$L.REGISTRATION_this_enter}</a>
		 <a class="registration-cha-lang" href="/language/{if $Page.lang == 'ru'}en{else}ru{/if}/" rel="nofollow" title="{if $Page.lang == 'ru'}Switch to English{else}Перейти на Русский{/if}">{if $Page.lang == 'ru'}Switch to English{else}Перейти на Русский{/if}</a>
		</p>
	{/if}
			
<div class="registration042011 v2">
	
	{if $error}
	<div class="serverErr">
		{$error.text}
	</div>
	{/if}
	
	<form method="post">
		<div class="leftSide">
			<div class="input">
				<label for="reglogin">{$L.REGISTRATION_name}</label>
				<input type="text" name="login" id="reglogin" class="required_input" maxlength="25" value="{$POST.login}" {if $USER->client->ismobiledevice == '1'}placeholder="{$L.REGISTRATION_name}"{/if} />
				<span class="box-input-result"></span>
				<span class="error_sml"><!-- Это обязательное поле --></span>
			</div>
			<div class="input">
				<label for="regemail">{$L.REGISTRATION_email}</label>
				<input type="text" name="email" id="regemail" class="required_input" value="{$POST.email}"  {if $USER->client->ismobiledevice == '1'}placeholder="E-Mail"{/if}/>
				<span class="box-input-result"></span>
				<span class="error_sml"><!-- Неправильный адрес электронной почты --></span>
			</div>
			{if $location.country == 'RU'}
			<div class="input">
				<label for="regphone">{$L.REGISTRATION_phone}</label>
				<input type="text" name="phone" id="regphone" class="" value="{$POST.phone}"  {if $USER->client->ismobiledevice == '1'}placeholder="{$L.REGISTRATION_phone}"{/if} />
				<span class="box-input-result"></span>
				<span class="error_sml"><!-- Неправильный телефон --></span>
			</div>
			{/if}
			<div class="input">
				<label for="regpassword1">{$L.REGISTRATION_pass}</label>
				<input name="password1" type="password" id="regpassword1" class="required_input"  {if $USER->client->ismobiledevice == '1'}placeholder="{$L.REGISTRATION_pass}"{/if} />
				<span class="box-input-result"></span>
				<span class="error_sml"><!-- Пароли не совпадают --></span>
			</div>
			<div class="input">
				<label for="regpassword2">{$L.REGISTRATION_pass2}</label>
				<input name="password2" type="password" id="regpassword2" class="required_input"  {if $USER->client->ismobiledevice == '1'}placeholder="{$L.REGISTRATION_pass2}"{/if} />
				<span class="box-input-result"></span>
        <span class="error_sml"><!-- Пароли не совпадают --></span>
        </div>
		</div>
		
		<div class="rightSide">
			{*<div class="input">
				<label for="f_name">Имя</label>
				<input type="text" name="f_name" id="f_name" maxlength="25" value="{$POST.f_name}" />
			</div>
			<div class="input">
				<label for="l_name">Фамилия</label>
				<input type="text" name="l_name" id="l_name" maxlength="50" value="{$POST.l_name}" />
			</div>*}
			<div class="input">
				<label for="l_name">{$L.REGISTRATION_fio}</label>
				<input type="text" name="fio" id="fio" maxlength="50" value="{$POST.fio}" {if $USER->client->ismobiledevice == '1'}placeholder="{$L.REGISTRATION_fio}"{/if}/>
			</div>
			

			<div class="select">
				<label for="sex" style="padding-top:10px;">{$L.REGISTRATION_sex}</label>
				
				<div id="input_man_woman" class="b-radio-manwomen radio-input selected-woman" style="width: auto; visibility: visible;margin:0;position:relative">
					<input name="sex" id="sex" type="hidden" value="{$POST.sex}" />
					<a rel="nofollow" href="#" id="male" class="type-select active" style="display: block; "></a>
					<a rel="nofollow" href="#" id="female" class="type-select" style="display: block; "></a>
				</div>
				
				<!--<select name="sex" id="sex">
					<option value="empty" {if $POST.sex != "male" && $POST.sex != "female"} selected="selected" {/if}></option>
					<option value="male" {if $POST.sex == "male"} selected="selected" {/if}>Мужской</option>
					<option value="female" {if $POST.sex == "female"} selected="selected" {/if}>Женский</option>
				</select>-->
			</div>
			<div class="select date">
				<label for="day" style="padding-top:0px;">{$L.REGISTRATION_birthday}</label>
				<select name="day" id="day">
                    <option value=""></option><option value=1>1</option><option value=2>2</option><option value=3>3</option><option value=4>4</option><option value=5>5</option><option value=6>6</option><option value=7>7</option><option value=8>8</option><option value=9>9</option><option value=10>10</option><option value=11>11</option><option value=12>12</option><option value=13>13</option><option value=14>14</option><option value=15>15</option><option value=16>16</option><option value=17>17</option><option value=18>18</option><option value=19>19</option><option value=20>20</option><option value=21>21</option><option value=22>22</option><option value=23>23</option><option value=24>24</option><option value=25>25</option><option value=26>26</option><option value=27>27</option><option value=28>28</option><option value=29>29</option><option value=30>30</option><option value=31>31</option>                  
				</select>
				<select name="month" id="month">
					<option value=""></option><option value=1>{$L.REGISTRATION_month1}</option><option value=2>{$L.REGISTRATION_month2}</option><option value=3>{$L.REGISTRATION_month3}</option><option value=4>{$L.REGISTRATION_month4}</option><option value=5>{$L.REGISTRATION_month5}</option><option value=6>{$L.REGISTRATION_month6}</option><option value=7>{$L.REGISTRATION_month7}</option><option value=8>{$L.REGISTRATION_month8}</option><option value=9>{$L.REGISTRATION_month9}</option><option value=10>{$L.REGISTRATION_month10}</option><option value=11>{$L.REGISTRATION_month11}</option><option value=12>{$L.REGISTRATION_month12}</option>
				</select>
				<select name="year" id="year">
					<option value=""></option><option value=1950>1950</option><option value=1951>1951</option><option value=1952>1952</option><option value=1953>1953</option><option value=1954>1954</option><option value=1955>1955</option><option value=1956>1956</option><option value=1957>1957</option><option value=1958>1958</option><option value=1959>1959</option><option value=1960>1960</option><option value=1961>1961</option><option value=1962>1962</option><option value=1963>1963</option><option value=1964>1964</option><option value=1965>1965</option><option value=1966>1966</option><option value=1967>1967</option><option value=1968>1968</option><option value=1969>1969</option><option value=1970>1970</option><option value=1971>1971</option><option value=1972>1972</option><option value=1973>1973</option><option value=1974>1974</option><option value=1975>1975</option><option value=1976>1976</option><option value=1977>1977</option><option value=1978>1978</option><option value=1979>1979</option><option value=1980>1980</option><option value=1981>1981</option><option value=1982>1982</option><option value=1983>1983</option><option value=1984>1984</option><option value=1985>1985</option><option value=1986>1986</option><option value=1987>1987</option><option value=1988>1988</option><option value=1989>1989</option><option value=1990>1990</option><option value=1991>1991</option><option value=1992>1992</option><option value=1993>1993</option><option value=1994>1994</option><option value=1995>1995</option><option value=1996>1996</option><option value=1997>1997</option><option value=1998>1998</option><option value=1999>1999</option><option value=2000>2000</option>
				</select>
			</div>
			<div class="input">
				<label for="city">{$L.REGISTRATION_sity}</label>
				<input type="text" name="city" id="city" maxlength="80" value="{$POST.city}" {if $USER->client->ismobiledevice == '1'}placeholder="{$L.REGISTRATION_sity}"{/if}/>
			</div>
		</div>
		
		<div class="subscribeNews"  style="display:none">{$L.REGISTRATION_news_always}. 
			<label><input type="checkbox" checked="checked" id="news" name="newsLetter">&nbsp;{$L.REGISTRATION_subsc_news}.</label>
		</div>
		<div class="subscribeNews" style="margin:5px 0px;">&nbsp;</div>
		<div class="submit">
			<input id="submitbtn" type="submit" name="submit" disabled="disabled" value="{$L.REGISTRATION_bott_registr}" />
		</div>	
	</form>
	
	<p class="sometext">{$L.REGISTRATION_yuo_registr_soc}</p>
	<div class="fbconn">
		<a id="fb_enter" class="gotoFBConnect" href="#">{$L.REGISTRATION_soc_reg_in} Facebook</a>
		<a id="vk_enter" class="gotoVKConnect" href="#">{$L.REGISTRATION_soc_reg_in} {$L.REGISTRATION_soc_vk}</a>
	</div>
	
</div>
<div id="fb-root"></div>
{literal}
<script>
$(document).ready(function(){
	
	IE = /(msie) ([\w.]+)/.test(navigator.userAgent.toLowerCase());
	
	//fb_login.init();
	$('a#fb_enter.gotoFBConnect').click(function(){
		window.open('http://www.maryjane.ru/login/fb/',(IE?'':'Вход через Facebook'),'location,width=800,height=400');
	});
	
	$('a#vk_enter.gotoVKConnect').click(function(){
		window.open('http://api.vkontakte.ru/oauth/authorize?client_id='+VK_APP_ID+'&scope=&redirect_uri=http://www.maryjane.ru/login/vk/&response_type=code&display=popup',(IE?'':'Вход через Вконтакте'),'location,width=400,height=300');
	}); 
	
	$('#input_man_woman a').click(function(){
		$('#input_man_woman a.active').removeClass('active');
		$(this).addClass('active');
		$('#sex').val(this.id);
		return false;
	});
	
	reg_valid.init();
});
</script>
{/literal}
<link rel="stylesheet" href="/css/jquery.autocomplete.css" type="text/css" />
<script type="text/javascript" src="/js/jquery.autocomplete.pack.js"></script>

{elseif $action == "needActivation"}

<div class="activationBox">
	<h3>Активация аккаунта</h3>
	
	{*
	<p>
		На указаный почтовый адрес <span class="uemail">{$EMAIL}</span> выслано письмо с активацией. <br/>
		Перейдите по ссылке, указанной в письме, что-бы закончить регистрацию.
	</p>
	
	<form id="email_for_activation">		
		<label for="uemail">Не получили код активации?</label>
		<input type="text" id="uemail" name="uemail" value="{$USER.user_email}" /><input type="submit" id="activSubmit" disabled="disabled" value="Отправить повторно" />
		<span class="error_sml">Отправка активации не чаще чем 1 раз в 5 минут</span>
	</form>
	*}
	
	{if $byEmail} {* активация по мылу *}

        <p>
            На указаный почтовый адрес <span class="uemail">{$USER.user_email}</span> выслано письмо с активацией. <br/>
            Перейдите по ссылке, указанной в письме, что-бы закончить регистрацию.
        </p>

        <form id="email_for_activation" action="/{$PAGE->module}/resend/" method="get" style="margin: 40px 0 0 200px;">
            <label for="uemail">Не получили код активации?</label>
            <input type="hidden" id="uemail" name="email" value="{$USER.user_email}" /> <input style="margin: 0 0 0 35px;" type="submit" id="activSubmit" value="Отправить повторно" />
            <span class="error_sml">Отправка активации не чаще чем 1 раз в 5 минут</span>
        </form>
        
        {if $message}
        <p class="error">{$message}</p>
        {/if}

    {elseif $byPhone} {* активация по телефону *}

        <p>
            На указаный телефонный номер <span class="uemail"><b>{$USER.user_phone}</b></span> выслано смс с кодом активации. <br/>
            Введите его в поле ниже
        </p>

        {if $error == 1}
            <div class="error" style="clear:both;margin-top:160px">Вы ввели не правильный код активации</div>
        {/if}

        <form id="email_for_activation" action="/{$module}/activate/?userid={$USER.user_id}" method="post">
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
        <p style="display:none;">
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
				<td><input type="text" name="keystring" id="key" size="2"  maxlength="4" style="height:38px;width:98px;font-size:38px;font-family:Times New Roman;"></td>
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
		$.post('/{/literal}{$module}{literal}/merge/?key={/literal}{$key}{literal}', {'select': 1, 'id' : id}, function (r) {
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
				$.post('/{/literal}{$module}{literal}/merge/?key={/literal}{$key}{literal}', {'select': 1, 'id' : id}, function (r) {
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


<div class="comebackBox comebackBoxOne">
	
	<h3 style="margin-right: 20px;">С возвращением! <br />
	Однажды вы уже регистрировались на этот e-mail <strong>{$u.email}</strong>
	</h3>

	<div class="comebackAcc">
		<div class="mainAcc" style="margin-left: 30px;">
			<div class="uPic">{$u.user_avatar}</div>
			<i>{$u.user_login}</i>
			<p class="top">{if $u.user_phone}тел.: {$u.user_phone} <br/>{/if}Заказов: {$u.orders_count} / Бонусов: {$u.user_bonus} руб. / Работ: {$u.goods_count}</p>
			<p class="detail">Зарегистрирован: {$u.user_register_date}<br/>
				{if $u.last_order}
				Последний заказ: {$u.last_order.date}
				{/if}
			</p>
		</div>

		<p class="ag" style="width: 550px;margin-left: -63px;margin-bottom: 20px;text-align: center;"><a href="#" onclick="return resendPassword('{$u.user_id}', this);">Отправить инструкцию</a> по восстановлению пароля на {$email}</p>
		
		<form id="comebackFx3" class="comebackForm" action="/{$module}/comeback/confirm/" style="margin-left: 37px;width:378px">
			
			<label for="comeback-pass" style="padding-top: 6px;">Введите пароль</label>
			<input type="password" class="comeback-pass" name="password" >
			<input type="hidden" name="next" value="{$next}" />
			<input type="hidden" name="user_id" class="comeback-user" value="{$u.user_id}" />
			<input type="hidden" name="social" value="{$social}" />
			<input type="hidden" name="social_user_id" value="{$social_user_id}" />
			<input type="submit" style="margin-top: 2px;" class="submitbtn" name="submit" value="Вход" />
			<span class="error_sml">Неверный пароль</span>
		</form>
		
	</div>
	
</div>

{literal}
<style>
.comebackBoxOne h3 {
	font-weight: normal;
	text-align: center;
}
.comebackBoxOne .mainAcc {width:380px;}
.comebackBoxOne .mainAcc p {width: 270px;}

.comebackAcc {
	position: relative;
	width: 420px;
	margin: 15px auto;
}
.comebackAcc .userInfo {
	border-bottom: 1px solid #afafaf;
}
.comebackAcc p.ag {float:left; text-transform: normal; width:100%; padding:0 0 10px; text-align: left; margin: 0;}
.comebackAcc p.ag a {color: #00A851;}
</style>

<script>
	function resendPassword(id, o)
	{
		$(o).hide();
		
		$.post('/{/literal}{$module}{literal}/comeback/resend/', {'user_id' : id}, function(r) {
			$(o).show();
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


<div class="comebackBox comebackBoxOne" style="padding: 65px 72px 80px 70px;">
	
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
			
			<form id="comebackFx3" class="comebackForm" method="post" action="/{$module}/phonecomeback/confirm/" style="margin-left: 40px;">
				<label style="margin-top: 4px;" for="comeback-pass">Введите код</label>
				<input type="text" class="comeback-pass" name="code" >
				<input type="hidden" name="next" value="{$next}" />
				<input type="hidden" name="user_id" class="comeback-user" value="{$u.user_id}" />
				<input style="margin-top: 2px;" type="submit" class="submitbtn" name="submit" value="Вход" />
				<span class="error_sml">Неверный код</span>
				<input type="password" name="password" style="width:100px;margin-left:111px;margin-top:8px;display:none" placeholder="Новый пароль" />
			</form>
		
	</div>
	
</div>

{literal}
<style>
.comebackBoxOne h3 {
	font-weight: normal;
	text-align: center;
}
.comebackBoxOne .mainAcc {width:380px;}
.comebackBoxOne .mainAcc p {width: 270px;}

.comebackAcc {
	position: relative;
	width: 420px;
	margin: 15px auto;
}
.comebackAcc .userInfo {
	border-bottom: 1px solid #afafaf;
}
.comebackAcc p.ag {float:left; text-transform: normal; width:100%; padding:0 0 10px; text-align: left; margin: 0;}
.comebackAcc p.ag a {color: #00A851;}
</style>

<script>

	function resendPassword(id, o)
	{
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
								$(forms.cur_form).find('input[type=password]').show();
								$(forms.cur_form).find('input[type=submit]').val('Сохранить');
							}
							
							$('.error_sml').hide();
							
							//$(forms.cur_form).addClass("canSubmit");
							//$(forms.cur_form).addClass("canSubmit").unbind("submit");							
							//$("form#"+$(forms.cur_form).attr("id")+" input[type=submit]").click();							
							return true;
						} else {
							$('.error_sml').show();
							$(forms.cur_form).find('input[type=password]').hide();
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