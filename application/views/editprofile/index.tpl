{if $registration_complite}  
<div class="apruv-registr" trackUser="registration_complite">
	Ваша регистрация, успешно подтверждена!
</div>
{/if} 

<div class="tabz clearfix">
	<a href="{if $USER->user_is_fake == 'true'}#{else}/profile/{/if}" class="active">Профиль</a>
</div>

<div class="blok-info" style="">
	<div class="person_info">
		
		{if $CHANGE_EMAIL_SUCCESS}
		<div class="center pa10 border mb10" style="color:green">
		<p>Вы успешно изменили свой email на {$CHANGE_EMAIL_SUCCESS.email}. <a href="/{$module}/">Закрыть</a></p>
		</div>
		{/if}
		
		{if $changeLoginSuccess}
		<div class="center pa10 border">
		<p>Спасибо. Вы полностью активировали свой аккаунт и теперь вам доступны все возможности maryjane.ru.</p>
		<p><a href="/{$module}/">Перейти к редактирования профиля</a></p>
		</div>
		{/if}
		
		{if $changeLoginError}
		<div class="center pa10 border">
			<p style="color:red">Произошла ошибка при изменении вашего имени.<br /> {$changeLoginError.text} <br />Если ошибку не удалось исправить - пишите на <a href=mailto:info@maryjane.ru>team@maryjane.ru</a></p>
			<p><a href="/{$module}/">Вернуться к редактирования профиля</a></p>
		</div>
		{/if}
		
		<h2 style="font-size:16px;margin: 0 0 25px;">Редактировать персональные данные:</h2>
		<form method="post" action="/{$module}/" id="editprofileForm">
			{if $USER->meta->goodApproved > 0}
			<div class="input">
				<label for="regemail">Название витрины</label>
				<input type="text" name="personal_title" class="" value="{if $USER->meta->personal_title}{$USER->meta->personal_title}{else}Магазин футболок {$USER->user_login}{/if}">
				<span class="box-input-result complite" style="cursor: default;"></span>
			</div>
			{/if}
			<div class="input">
				<label for="regemail">E-mail</label>
				<input type="text" name="email" {if !$USER->user_email}class="empty" style="border-color:#B00000"{/if} value="{$USER->user_email}">
				<span class="box-input-result complite" style="cursor: default;"></span>
				<span class="error_sml">{if $emailMessage}{$emailMessage}{/if}</span>
			</div>
			<div class="input">
				<label for="reglogin">Логин</label>
				<input type="text" name="login" class="" maxlength="25" value="{$USER->user_login}" {if !$CHANGELOGIN}disabled="disabled"{/if}>
				<span class="box-input-result complite" style="cursor: default;"></span>
				<span class="error_sml"></span>
			</div>
			<div class="input">
				<label for="reglogin">Имя</label>
				<input type="text" name="name" class="" maxlength="100" value="{$USER->user_name}">
				<span class="box-input-result" style="cursor: default;"></span>
				<span class="error_sml"></span>
			</div>
			<div class="input" style="margin-bottom: 40px;">
				<label for="reglogin">Показывать имя</label>
				<input type="checkbox" name="showRealName" class="" maxlength="25" style="width:15px;padding:0;margin:0" {if $USER->user_show_name == 'true'}checked=""{/if} />
				<span class="box-input-result" style="cursor: default;"></span>
				<span class="error_sml"></span>
			</div>
			
			<div style="clear:both"></div>
			<div class="select" style="">
				<label for="sex" style="float:left">Пол</label>			
				<input type="hidden" name="sex" value="{$USER->user_sex}" />
				<div id="input_man_woman" class="b-radio-manwomen radio-input selected-woman" style="width: 142px; visibility: visible;margin:0;position:relative">
					<a rel="nofollow" href="#" id="male" class="type-select {if $USER->user_sex == 'male'}active{/if}" style="display:block"></a>
					<a rel="nofollow" href="#" id="female" class="type-select {if $USER->user_sex == 'female'}active{/if}" style="display:block"></a>
				</div>
				<div style="margin: 10px 0 0 50px;float: left;">
					<input type="radio" name="whoami" value="designer" {if $whoami == 'designer'}checked=""{/if} style="width:25px;vertical-align:middle" /> Я дизайнер
					<input type="radio" name="whoami" value="customer" {if $whoami == 'customer'}checked=""{/if} style="width:25px;vertical-align:middle" /> Я покупатель
				</div>				
			</div>
			<div style="clear: both;"></div>
			<div class="select date" style="margin:40px 0 40px;">
				<label for="day" style="display: block;float: left;">День<br/>рождения</label>
				<select name="birthdayDay" id="day" style="" {if $USER->user_birthday != '' && $USER->user_birthday != '0000-00-00'}disabled="disabled"{/if}>
					<option value=""></option>
					{foreach from=$bdays item="d"}
					<option value="{$d}" {if $USER->birthday.2 == $d}selected="selected"{/if}>{$d}</option>
					{/foreach}                  
				</select>
				<select name="birthdayMonth" id="month" style="" {if $USER->user_birthday != '' && $USER->user_birthday != '0000-00-00'}disabled="disabled"{/if}>
					<option value=""></option>
					<option value="1" {if $USER->birthday.1 == 1}selected="selected"{/if}>января</option>
					<option value="2" {if $USER->birthday.1 == 2}selected="selected"{/if}>февраля</option>
					<option value="3" {if $USER->birthday.1 == 3}selected="selected"{/if}>марта</option>
					<option value="4" {if $USER->birthday.1 == 4}selected="selected"{/if}>апреля</option>
					<option value="5" {if $USER->birthday.1 == 5}selected="selected"{/if}>мая</option>
					<option value="6" {if $USER->birthday.1 == 6}selected="selected"{/if}>июня</option>
					<option value="7" {if $USER->birthday.1 == 7}selected="selected"{/if}>июля</option>
					<option value="8" {if $USER->birthday.1 == 8}selected="selected"{/if}>августа</option>
					<option value="9" {if $USER->birthday.1 == 9}selected="selected"{/if}>сентября</option>
					<option value="10" {if $USER->birthday.1 == 10}selected="selected"{/if}>октября</option>
					<option value="11" {if $USER->birthday.1 == 11}selected="selected"{/if}>ноября</option>
					<option value="12" {if $USER->birthday.1 == 12}selected="selected"{/if}>декабря</option>
				</select>
				<select name="birthdayYear" id="year" {if $USER->user_birthday != '' && $USER->user_birthday != '0000-00-00'}disabled="disabled"{/if}>
					<option value=""></option>
					{foreach from=$byears item="y"}
					<option value="{$y}" {if $USER->birthday.0 == $y}selected="selected"{/if}>{$y}</option>
					{/foreach}
				</select>
			</div>
			<div class="input" style="margin-bottom: 40px;">
				<label for="reglogin">Немного о себе</label>
				<textarea class="textarea" name="aboutMe">{$aboutMe}</textarea>
			</div>
			
			<div class="input">
				<label for="reglogin">Сайт</label>
				<input type="text" name="url" class="" value="{$USER->user_url}">
				<span class="box-input-result" style="cursor: default;"></span>
				<span class="error_sml"></span>
			</div>
			<div class="input">
				<label for="reglogin">Город</label>
				<input type="text" name="city" id="city_input" class="" maxlength="25" value="{$USER->user_city}">
				<span class="box-input-result" style="cursor: default;"></span>
				<span class="error_sml"></span>
			</div>
			<div class="input">
				<label for="reglogin">Подписка<br/>на рассылки</label>
				<div style="display: block;width: 400px;margin-top: 14px;float: left;">
					<input style="vertical-align:middle;width: auto;" type="checkbox" name="subscriptions" {if $USER->user_email == ''}disabled=""{/if} {if $USER->user_subscription_status == 'active'}checked=""{/if}>
					{if $USER->user_subscription_status == 'canceled'}
						<span class="" style="padding-left: 5px;font-size: 12px;color:red"><em>Вы отписаны от всех рассылок</em></span>
					{else}
						<span class="" style="padding-left: 5px;font-size: 12px;">Хочу узнавать о новинках</span>
					{/if}
				</div>
			</div>
			<div class="input">
				<label for="reglogin">Получать<br/>уведомления</label>
				<div style="display: block;width: 200px;margin-top: 14px;float: left;">
					<input style="vertical-align:middle;width: auto;" type="checkbox" name="notifications" {if $USER->user_email == ''}disabled=""{/if} {if $watches|count > 0}checked=""{/if}>
					<span class="" style="padding-left: 5px;font-size: 12px;">Оповещать о сообщениях</span>
				</div>
			</div>
			
			<div class="input">
				<a name="saleReport"></a>
					
					{if $PAGE->reqUrl.2 == 'saved'}
					<div style="text-align:center;background: #cfcfcf;color:#fff;padding:10px;margin-bottom:10px">Выбранный Вами интервал уведомления сохранён</div>
					{/if}
					
				<label>Отчёт по<br/>продажам</label>
				<div class="clearfix" style="display: block;width:485px;margin-top:2px;float:left">
					<label class="left" style="margin-right:5px;width:auto"><input style="vertical-align:middle;width: auto;margin-top:2px;" type="radio" name="saleReport" value="everyday" {if $USER->meta->saleReport || $USER->meta->saleReport == "everyday"}checked=""{/if}> <span class="" style="padding-left: 5px;font-size: 12px;">каждый день</span></label>

					<label class="left" style="margin-right:5px;width:auto"><input style="vertical-align:middle;width: auto;margin-top:2px;" type="radio" name="saleReport" value="everyweek" {if $USER->meta->saleReport == "everyweek"}checked=""{/if}> <span class="" style="padding-left: 5px;font-size: 12px;">каждую неделю</span></label>

					<label class="left" style="margin-right:5px;width:auto"><input style="vertical-align:middle;width: auto;margin-top:2px;" type="radio" name="saleReport" value="everymonth" {if $USER->meta->saleReport == "everymonth"}checked=""{/if}> <span class="" style="padding-left: 5px;font-size: 12px;">каждый месяц</span></label>

					<label class="left" style="width:auto"><input style="vertical-align:middle;width: auto;margin-top:2px;" type="radio" name="saleReport" value="no" {if $USER->meta->saleReport == "no"}checked=""{/if}> <span class="" style="padding-left: 5px;font-size: 12px;">не получать</span></label>
				</div>
			</div>
			
			<div style="clear: both;"></div>
			<div class="submit">
				<input id="" type="submit" name="save" {*disabled="disabled"*} value="Сохранить">
			</div>	
		</form>
	</div>
	<div class="right" style="width:310px">
		
		{if ($USER->user_email != '' && $USER->user_activation != 'done') || $USER->user_email == ''}
		<div class="red_msg">
			{if $CHANGE_PHONE}
				<i class="tel"></i>
			{else}
				<i class="mail"></i>
			{/if}
			<div class="" style="color:#ffffff;font-size:12px">
				{if $CHANGE_PHONE}
					Подтвердите свой телефон
				{else}
					{if $USER->user_email}
						Вы пока не подтвердили свой<br/>почтовый адрес.<br/><br/>Подтвержение дает возможности в полном<br/>объеме пользоваться всеми<br/>функциями сайта.
					{else}
						Вы ещё не указали свой почтовый адрес.<br/><br/>Укажите его, чтобы стать членом клуба<br/>Maryjane.ru
					{/if}				
				{/if}
			</div>	
			{if $CHANGE_PHONE}
			{else}		
				{if $USER->user_email != ''}
				<form action="/registration/resend/" method="get" id="resendForm">
					<input type="hidden" name="resendemail" id="resendregemail" value="{$USER->user_email}" />
					<a class="bot" id="resendFormButton" href="#" rel="nofollow" title="Выслать письмо с активацией">Вышлите мне письмо</a>
				</form>
				{else}
					<a class="bot" id="firstSendMessage" href="#" rel="nofollow" title="Выслать письмо с активацией">Вышлите мне письмо</a>
				{/if}				
			{/if}
			<br/>
		</div>
		
		<h5 class="error" id="resendEmailError"></h5>
		<h5 class="green" id="resendEmailSuccess"></h5>
		{/if}
		
			<div style="width:100px;padding:10px;margin-left:95px">
			{literal}
			<script>
				var FileAPI = {
					  debug: false
					, pingUrl: false 
					
					// @required
					, staticPath: '/js/file-upload/' // @default: "./"

					// @optional
					, flashUrl: '/js/file-upload/FileAPI.flash.swf' // @default: FileAPI.staticPath + "FileAPI.flash.swf"
					, flashImageUrl: '/js/file-upload/FileAPI.flash.image.swf' // @default: FileAPI.staticPath + "FileAPI.flash.image.swf"
					//,support: { html5: false }
				};
			</script>
			<script src="/js/file-upload/FileAPI.min.js"></script>
			<script src="/js/file-upload/FileAPI.id3.js"></script>
			<script src="/js/file-upload/FileAPI.exif.js"></script>
			<script src="/js/file-upload/FileAPI.framework.js"></script>
			<script>
				$(document).ready(function(){
					
					$('#addavatar').FileAPI({
						url: $('#addavatar').parents('form:first').attr('action'),
						data: {name: $('#addavatar').attr('name')},
						fileExt: '.png,.jpg,.jpeg,.gif',
						select: function(file){
							$('#addavatar').hide();
							$('#addavatarprogress').show();
							$('img.avatar').attr('src','/images/empty.gif'+'?p='+(Math.random()*10000).toString()).addClass('loading');
						},
						complete: function(file, response){
							$('#addavatarprogress').hide();
							$('#addavatar').show();
							
							var e = eval('('+response+')');
							if (e.error) { alert(e.error); }
							else if (e.ok) {
								$('img.avatar').attr('src', e.ok+'?p='+(Math.random()*10000).toString()).removeClass('loading');
								$('#addavatar').parent().hide();
								$('a#remavatar').show();
							}
						}
					});
					
					$('a#remavatar').click(function(){
						$.get($(this).attr('href'),function(response){ 
							var e = eval('('+response+')');
							if (e.error) { alert(e.error); }
							else if (e.status == 'ok') {
								$('img.avatar').attr('src', '/images/designers/nophoto100.gif?p='+(Math.random()*10000).toString());
								$('#addavatar').parent().show();
								$('a#remavatar').hide();
							}
						});
						return false;
					});
				});
			</script>
			{/literal}		
			<!--noindex-->				
			{include file="profile/addavatar.tpl"}

			{$avatar}
			<br />
			<form action="/editprofile/adduseravatar/" method="post" enctype="multipart/form-data">
				<a href='#' class='green dashed' style="cursor:pointer;display: {if $USER->user_picture < 1}block{else}none{/if};width: 100px;"><input id="addavatar" name="avatar" type="file" />Добавить аватар</a>
				<a href='/editprofile/deleteuseravatar/' id="remavatar" style="text-align: center;display: {if $USER->user_picture < 1}none{else}block{/if}">Удалить</a>
			</form>
		<!--/noindex-->
		</div>
		
		
		{if $PAGE->lang == 'ru'}
		{* привязка телефона работает только для россии *}
		<div class="edit_pass">
			<form method="post" id="changePhone" action="/{$module}/changephone/">
				<div class="tit">{if $CHANGE_PHONE}Подтвердите телефон{else}Телефон{/if}</div>
				
				{if $phoneMessage}
					<span style="color:#00a851">{$phoneMessage}</span>
				{/if}
				
				{if $phoneError}
					<span style="color:red">{$phoneError}</span>
				{/if}
				
				{if !$CHANGE_PHONE}
				<div class="input">
					<div class="tit" style="margin: 0;">{$CHANGE_PHONE.phone}</div>
					<input type="text" name="phone" id="regphone" class="" value="{$USER->user_phone}">
					<span class="box-input-result error" style="cursor: pointer;bottom: 2px;top: auto !important;{if $phoneChangeSuccess}display: inline;background-position: 0 -24px;{/if}"></span>
					<span class="error_sml"></span>
				</div>
				<div class="submit">
					<input id="" type="submit" name="" value="{if $USER->user_phone != ''}Изменить{else}Добавить{/if}">
				</div>
				{/if}	
			</form>
			
			{if $CHANGE_PHONE}
			<form method="post" id="checkPhoneCode" action="/{$module}/changephone/">	
				<div style="font-size: 18px">{$CHANGE_PHONE.phone}&nbsp;&nbsp;<a href="/{$module}/changephone/reedit/" style="font-size: 12px;text-decoration: none;border-bottom: 1px dashed #6B7172;color: #6B7172;">изменить</a></div>
				<div class="input" style="margin-bottom:8px">
					<input type="text" size="4" name="code" style="margin-right: 30px;width:60px" placeholder="Код" autocomplete="off"/>
					<a href="/{$module}/changephone/resend/" id="resendccode" style="padding: 13px 0 0 0px;display: block;float: left;text-decoration: none;border-bottom: 1px dashed #6B7172;color: #6B7172;">отправить повторно</a>
					<span class="box-input-result error" style="cursor: pointer;left:74px;bottom: 2px;top: auto !important;"></span><br/>
					<span class="error_sml" style="padding: 2px 0 1px 1px;position: absolute;left: 0px;top: 37px;"></span>
				</div>
				<div class="input" style="margin-bottom:8px;display:none">
					<input type="password" name="password" style="width:120px" placeholder="Новый пароль" />
					<span class="box-input-result error" style="cursor: pointer;left:74px;bottom:2px;top: auto !important"></span><br/>
					<span class="error_sml" style="padding: 2px 0 1px 1px;position: absolute;left:0px;top:37px"></span>
				</div>
				<div class="submit">
					<input type="submit" name="confirm" value="Подтвердить" style="width:90px" />
				</div>
			</form>
			{/if}		
		</div>
		{/if}
		
		<div class="edit_pass">
			<div class="tit">Людям интересны Вы и Ваши проекты, добавьте свои социальные контакты</div>
			
			<p>
				<img border="0" style="vertical-align: middle;margin:0;margin-right: 8px;" src="/images/social/facebook.gif" title="" alt="F" width="16" height="16"/>
				facebook
				{if $USER->meta->user_facebook}
					<a href="/{$module}/socialUnlink/facebook/" onclick="return confirm('Вы уверены?')">отвязать</a>
				{else}
					<a href="#" id="topLineFBLogin" style="margin-left:0;margin-right:20px;vertical-align: middle;">привязать</a>
				{/if}
			</p>
			
			<p>
				<img border="0" style="vertical-align: middle;margin:0;margin-right: 8px;" alt="Vk" title="" src="/images/social/vkontakte.png" width="16" height="16"/>
				вконтакте 
				{if $USER->meta->user_vk}
					<a href="/{$module}/socialUnlink/vk/" onclick="return confirm('Вы уверены?')">отвязать</a>
				{else}
					<a href="#" id="topLineVKLogin" style="margin-left:0;margin-right:20px;vertical-align: middle;">привязать</a>
				{/if}
			</p>
			
			<p>
				<img border="0" style="vertical-align: middle;margin:0;margin-right: 8px;" src="/images/social/google-plus.gif" title="" alt="g+"width="16" height="16"/>
				Google+ 
				{if $USER->meta->user_gplus}
					<a href="/{$module}/socialUnlink/gplus/" onclick="return confirm('Вы уверены?')">отвязать</a>
				{else}
					<a href="#" id="topLineGplusLogin" style="margin-left:0;margin-right:20px;vertical-align: middle;">привязать</a>
				{/if}
			</p>
			
			<p>
				<img border="0" style="vertical-align: middle;margin:0;margin-right: 8px;" src="/images/social/Instagram.jpg" title="Instagram" alt="Instagram"width="16" height="16"/>
				instagram 
				{if $USER->meta->user_instagram}
					<a href="/{$module}/socialUnlink/instagram/" onclick="return confirm('Вы уверены?')">отвязать</a>
				{else}
					<a href="#" id="topLineInstagramLogin" style="margin-left:0;margin-right:20px;vertical-align: middle;">привязать</a>
				{/if}
			</p>
		</div>
		
		
		<div class="edit_pass">
			<a rel="nofollow" class='recover' href="/registration/recover/" title="Забыли пароль?">Забыли пароль?</a>
			<form method="post" id="changePassword" action="/{$module}/changePassword/#pass">
				<div class="tit">Изменить пароль</div>
				<div class="input">
					<label for="reglogin">Текущий пароль</label>
					<input type="password" name="pass" class="" maxlength="25" value="">
					<span class="box-input-result complite" style="cursor: default;"></span>
					<span class="error_sml"></span>
				</div>
				<div class="input">
					<label for="regemail">Новый пароль</label>
					<input type="password" name="password1" class="" id="password1" value="">
					<span class="box-input-result error" style="cursor: pointer;"></span>
					<span class="error_sml"></span>
				</div>
				<div class="input">
					<label for="regemail">Повторите новый пароль</label>
					<input type="password" name="password2" class="" id="password2" value="">
					<span class="box-input-result" style="cursor: pointer;"></span>
				</div>
				<div style="clear: both;"></div>				
				<div class="submit">
					<input id="" type="submit" name="" value="Изменить" disabled="disabled" />
				</div>	
				
				<div class="pa10" id="changePasswordError" style="display:{if $changePasswordError}block{else}none{/if}">
					<p style="color:red">{$changePasswordError.text}</p>
				</div>
				
				<div class="pa10" id="changePasswordSuccess" style="display:{if $changePasswordSuccess}block{else}none{/if}">
					<p style="color:green">Вы успешно изменили свой пароль.</p>
				</div>
			</form>
		</div>
	</div>
</div>



{literal}
<script type="text/javascript">

//класс для валидации
valid = {
	inputs: '#editprofileForm input[name=email],#editprofileForm input[name=login],#editprofileForm input[name="name"],#editprofileForm input[name=url]',

	errorClass: 'error',
	validClass: 'complite',
		
	error: '',
	
	validFIO: function(t){ 
		this.error = 'Это обязательное поле'; 		
		if ($.trim(t.val()).length == 0) return false;
		//if (/^[a-z0-9]+$/gi.test($('#basket_fio').val())) {
		//	this.error = 'Только русские, пожалуйста';
		//	return false;
		//} 
		return true;
	},
	//validMail: function(t){ this.error = 'Это не похоже на email'; return isValidEmail(t.val()); },
	validLogin: function(t){ 
		this.error = 'Это обязательное поле'; 
		if ($(t)[0].disabled) return true;
		if ($.trim(t.val()).length == 0) return false;
		if ($.trim(t.val()).length < 2) { this.error = 'Минимум 2 символа';  return false; }
		if ($.trim(t.val()).length > 15) { this.error = 'Длина логина не должна превышать 15 символов';  return false; }
		if (!/^[a-z0-9\-\_]+$/gi.test(t.val())) {
			this.error = 'Только английские, пожалуйста';
			return false;
		}
		var self = this;
		$.get('/ajax/?action=checkavaillogin&login=' + t.val(), function(d) {
			if (d == 'ok')
				self.paint(t,false);
			else {
				self.error = d;
				self.paint(t,true);
			}
		});
		return true;
	},
	validMail: function(t){ 
		this.error = 'Это не похоже на email'; 
		if (!isValidEmail(t.val())) return false;
		var self = this;
		$.get('/ajax/?action=checkemail', { 'email' : t.val() }, function(d) {
			if (d == 'ok')
				self.paint(t,false);
			else {
				self.error = d;
				self.paint(t,true);
			}
		});
		return true;
	},	
	validUrl: function(t){ 
		this.error = 'Не правильно введен адрес ссылки';
		if ($.trim(t.val()) == '' || $.trim(t.val()) == 'http://') return true;
		var u= /[-\w\.]{3,}\.[A-Za-z]{2,3}/;
		return u.test(t.val());	
	},
	validPassword: function(t,v){
		this.error = 'Это обязательное поле'; 		
		if ($.trim(t.val()).length == 0) return false;
		if (v == 2)
			if ($('input[name=password1]').val() != $('input[name=password2]').val() && $('input[name=password2]').val().trim().length>0) { this.error = 'Введеные пароли не совпадают'; return false; }
			
		return true;
	},
	validPhone: function(t){ 
		this.error = 'Это обязательное поле'; 
		if ($.trim(t.val()).length == 0) return false;
		if (!/^[0-9\+]+$/gi.test(t.val())) {
			this.error = 'Только цифры, пожалуйста';
			return false;
		}
		if (isNaN(t.val()*1)) return false;
		if (t.val()*1<99999) return false;
		return true;
	},
	validPhoneCode: function(t){ 
		this.error = 'Это обязательное поле'; 
		if ($.trim(t.val()).length == 0) return false;
		if (!/^[0-9\+]+$/gi.test(t.val())) {
			this.error = 'Только цифры, пожалуйста';
			return false;
		}
		if (isNaN(t.val()*1)) return false;
		//if (t.val()*1<99999) return false;
		return true;
	},
	valid: function(t){
		switch(t.attr('name')) {
			case 'name': return this.validFIO(t);
			case 'email': return this.validMail(t);
			case 'login': return this.validLogin(t);			
			case 'phone': return this.validPhone(t);
			case 'code': return this.validPhoneCode(t);
			case 'url': return this.validUrl(t);
			case 'pass': return this.validPassword(t,1);
			case 'password1': return this.validPassword(t,2);
		}
	},
	
	checkAll: function(onlyvalid){
		var b = true;
		var self = this;
		$(this.inputs).each(function(){ 
			if ($(this).css('display') != 'none' && $(this).parent().css('display') != 'none')
				if (!self.check($(this), true)) b = false;				
		});
		if (!onlyvalid) {
			var el = $('.box-input-result.'+this.errorClass).parent().find('input:first, textarea:first, select:first');
			if (el.length>0) $(el[0]).focus().select();
		}
		return b;		
	},
	
	check: function(el, t){
		var self = this;
		var err = !this.valid(el);			
		//if (!t)
			this.paint(el,err,t);
		return !err;
	},
	
	paint: function(el,err,t) {
		$(el).parent().find('.box-input-result:first').css('cursor','default').click(function(){}).removeClass(this.errorClass).removeClass(this.validClass).addClass(err?this.errorClass:this.validClass).show();
			$(el).parent().find('.error_sml:first').html(err?this.error:'');
			if (err) 
				$(el).parent().find('.box-input-result:first').css('cursor','pointer').click(function(){
					$(this).css('cursor','default').click(function(){}).removeClass(self.errorClass);
					$(this).parent().find('.error_sml:first').html('');
					var inp = $(this).parent().find('input:first,select:first,textarea:first');
					var pl = inp.attr('_placeholder');
					inp.attr('placeholder', pl).val('').focus();
				});	
	},
	
	init: function(){
		var self = this;
		$(this.inputs).change(function(){
			self.check($(this));
		}).keyup(function(){
			self.check($(this));
		});
		return this;
	}
}.init();


function showChangeEmailForm(obj) {
    $(obj).hide();
    $('#changeEmailForm').show();
    return false;
}

function showChangePhoneForm(obj) {
    $(obj).hide();
    $('#changePhoneForm').show();
    $('#changePhoneForm1').show();
    $('#changePhoneForm2').hide();
    return false;
}

/*function validPhone(){
	var inputId = '#phone';
	$("#phone_res").removeClass('complite error'); // Обнуляем предыдущие валидации
	valid_result = ($(inputId).val() != '' && $(inputId).val() != $(inputId).prev('label').text() && $(inputId).val()*1>99999);
	if (!valid_result && $(inputId).val()*1<99999 && $(inputId).val()*1>1) { 
		$('#error_phone').hide();
	} else if (!valid_result) {
		$('#error_phone').show();
	}
	$("#phone_res").addClass(valid_result?'complite':'error');
	return valid_result;
}
$('#phone').change(validPhone).keyup(validPhone);
*/

$('input[type="submit"][name=save]').parents('form').submit(function(){
	if (!valid.checkAll()) return false;	
	
	if ($('.person_info input[name=email]').hasClass('empty')){
		$('<span>').addClass('triggerVKregistration').appendTo('body').trigger( "click" );//ремаркетинг VK создаем и активируем событие
		/*если мыла изначально небыло*/
		trackUser('Регистрация/Авторизации', 'registration - отправлено на подтверждение',module);	
	}
});

$('#resendccode').click(function(){
	var l = this; 
	$.get($(this).attr('href'), function() {
		$(l).text('Отправлено');
	});
	return false;
});

$('#changePhone').submit(function(){
	if ($('#regphone').length>0) {
		if (!valid.check($('#regphone'))) return false;	
	} else if ($('input[name="code"]').length>0) {
		if (!valid.check($('input[name="code"]'))) return false;		
	}
});

$('#changePassword').submit(function(){
	if (!valid.check($('input[name=pass]')) ||
		!valid.check($('input[name=password1]')))
	return false;
	
	$('#changePassword').find('input[type=submit]').attr('disabled', 'disabled');
	
	$('#changePasswordSuccess, #changePasswordError').hide();
	
	$.post($(this).attr('action'), {'pass' : $('input[name=pass]').val(), 'password1' : $('input[name=password1]').val(), 'password2' : $('input[name=password2]').val()}, function(r) {
		
		r = eval('('+r+')');

		if (r.error) {
			$('#changePasswordError').children('p').text(r.error).show();
			$('#changePasswordError').show();
		}
		
		if (r.success) {
			$('#changePasswordSuccess').show();
		}
		
		$('#changePassword').find('input[type=submit]').removeAttr('disabled');
	});
	
	return false;
});


$('#checkPhoneCode').submit(function(){
	
	var form   = this; 
	var cinput = $(this).find('input[name=code]');
	var code   = $(cinput).val();
	
	if (code.length > 0){
		$.get('/editprofile/changephone/checkcode/', {'code': code}, function(r){
			
			if (r == 'true'){
				var pass = $(form).find('input[type=password]').val();
				
				if (pass.length > 0){
					$(form).unbind('submit').submit();
				}else{
					$(cinput).parent().find('.box-input-result:first').removeClass('error').addClass('complite').show();
					$(cinput).parent().next().show();
					$(form).find('input[type=submit]').val('Сохранить');
				}
			} else {
				$(cinput).parent().find('.box-input-result:first').removeClass('complite').addClass('error').show()
				$(cinput).parent().next().hide();
			}
			
		});
	}
	
	return false;
});


var f = function(){
	if (valid.check($('input[name=pass]')) &&
		valid.check($('input[name=password1]')))
		$('#changePassword input[type=submit]').removeAttr('disabled');
	else
		$('#changePassword input[type=submit]').attr('disabled', 'disabled');
};
$('#changePassword input').bind('change', f).bind('keyup', f);

$('#changePhoneForm form').submit(function () {

    var f = $(this);
    var u = $(f).attr('action');
    var m = $(f).attr('method');
    var hash = {'ajax':true};

    $(f).find('input, select, textarea').each(function(i) {
        hash['' + $(this).attr('name') + ''] = $(this).val();
    });

    $.ajax({
        url    : u,
        type   : m,
        data   : hash,
        success: function(r)
        {
            $(f).hide();
            $('#showChangePhoneForm').text('изменить').show();

            if ($(f).attr('id') == 'changePhoneForm1')
            {
                $(f).next('form').show();
            }

            if (r == 'ok' && $(f).attr('id') == 'changePhoneForm2')
            {
                $('#userPhone').hide();
                $('#changePhoneSuccess').show();
            }
        }
    });

    return false;
});

function showChangeLoginForm(obj) {
    $(obj).hide();
    $('#changeLoginForm').show();
    return false;
}

function showHideCityInput(obj) {

    $('.cityInputs').hide();

    if ($(obj).val() == 838) {
        $('#citySelect').show();
    }
    else
    {
        $('#cityInput').show();
    }
}

function checkAvailLogin()
{
    var login = $('#change_login_input').val();

    if (login.length != 0)
	{
	    if (login.length < 5)
	    {
	        $('#loginError').html('Длина имени не может быть меньше 5 символов').show();
	        return false;
	    }
	
	    $("#loginCheck").show();
	
	        $.post('/{/literal}{$module}{literal}/changeLogin/', {'login' : login}, function(r) {
		    if (r == "ok") {
		        $("#changemail").empty().text('Ваш логин был успешно изменён. Поздравляем!')
		    } else {
		        $('#loginError').html(r).show();
		    }
		});
	}

    return false;
}

$(document).ready(function(){

	//отправка мыло если до этого его небыло
	$('#firstSendMessage').click(function(){
		$('.person_info input[name=save]').trigger( "click" );
	});
	
	$('#input_man_woman a').click(function(){
		$('#input_man_woman a').removeClass('active');
		$(this).addClass('active');
		$('input[type=hidden][name=sex]').val($(this).attr('id'));
		return false;
	});
	
    $('#city_input').click(function () {
    	if($(this).val() == '{/literal}{$USER->user_city}{literal}') $(this).val('');
	});
	
    $('#city_input').blur(function () {
        if($(this).val().length == 0) $(this).val('{/literal}{$USER->user_city}{literal}');
	});

    $("#city_input").
        autocomplete('/ajax/?action=city_autocomplit').
        result(function(event, item) {
    });
});
</script>
{/literal}