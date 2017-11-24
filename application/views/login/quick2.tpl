<div class="q-login-form-v2">
	<div class="small_title"></div>
	<a href="#!/close-form" class="close-btn" onclick="qLogin.hideForm();tb_remove();return false;" ><img src="/images/icons/delete.png" alt="close"/></a>

	<div class="form-wrap">
		<form method="post" action="/login/" id="quickLoginForm" style="margin-left:0">
			<div class="input">
				<label for="qlogin">{$L.QUICK_ENTRY_login}, {if $PAGE->lang == 'ru'}{$L.QUICK_ENTRY_phone_or} {/if}E-mail<br> <a href="/registration/">{$L.QUICK_ENTRY_sign_up}</a></label>			
				<input MAXLENGTH="55" type="text" name="login" id="reglogin" tabindex="1" placeholder="{if $USER->client->ismobiledevice == '1' && $USER->client->istablet == 0}{$L.QUICK_ENTRY_login}, {if $PAGE->lang == 'ru'}{$L.QUICK_ENTRY_phone_or} {/if}E-mail{/if}" _placeholder="{if $USER->client->ismobiledevice == '1' && $USER->client->istablet == 0}{$L.QUICK_ENTRY_login}, {if $PAGE->lang == 'ru'}{$L.QUICK_ENTRY_phone_or} {/if}E-mail{/if}" class="inputbox" value="" style="width:162px"/>
				<span class="box-input-result" style="cursor: pointer;right:-2px;top:3px;"></span><br/>
				<span class="error_sml" style="padding:2px 0 1px 1px;position:absolute;left:149px;top:32px"></span>
			</div>
			<div class="input">
				<label for="qpassword">{$L.QUICK_ENTRY_password} <br> <a href="/registration/recover/">{$L.QUICK_ENTRY_forgot_password}?</a></label>			
				<input type="password" name="password" placeholder="{if $USER->client->ismobiledevice == '1' && $USER->client->istablet == 0}{$L.QUICK_ENTRY_password}{/if}" _placeholder="{if $USER->client->ismobiledevice == '1' && $USER->client->istablet == 0}{$L.QUICK_ENTRY_password}{/if}" id="regpassowrd" class="inputbox" tabindex="2" value="" style="width:162px;"/>
				<span class="box-input-result" style="cursor:pointer;right:-2px;top:3px"></span><br/>
				<span class="error_sml" style="padding:2px 0 1px 1px;position:absolute;left: 149px;top:32px"></span>
			</div>
			
			<input type="hidden" name="HTTP_REFERER" value="{$HTTP_REFERER}" />
			<input type="hidden" name="ACTION" value="{$action}" />
			
			<label class="remember" for="remember" style="margin-left:105px;float:left;"><input type="checkbox" checked="checked" id="remember" name="remember"> {$L.QUICK_ENTRY_save_me}</label>
			<input type="submit" name="submit" value="{$L.QUICK_ENTRY_enter}" class="submit" tabindex="3" style=""/>
			
		</form>		
		
		<form method="post" action="/registration/quick/" id="quickReg">
			<h4>{$L.QUICK_ENTRY}</h4>
			<span class="hint">{$L.QUICK_ENTRY_dlya_tekh_kto_vpervye}</span>
			<div class="input">
				<label for="email" style="display:none">{if $USER->country == 'RU'} {$L.QUICK_ENTRY_phone}{else}e-mail{/if}</label>
				<input type="text" name="email" id="email" country="{$USER->country}" placeholder="{$L.QUICK_ENTRY_enter2} {if $USER->country == 'RU'}{$L.QUICK_ENTRY_phone}{else}e-mail{/if}" _placeholder="{$L.QUICK_ENTRY_enter2} {if $USER->country == 'RU'}{$L.QUICK_ENTRY_phone}{else}e-mail{/if}" style="font-weight:normal" class="inputbox" value="" tabindex="4" />
				<span class="box-input-result" style="cursor:pointer"></span><br/>
				<span class="error_sml" style="padding:2px 0 1px 1px;position:absolute;left:0;top:46px"></span>
			</div>
			<input type="hidden" name="next" value="{$HTTP_REFERER}" />
			<input type="submit" name="submit" value="{$L.QUICK_ENTRY_enter}" class="submit" tabindex="5"/>
			<label class="remember" for="subscribe" style="display:none"><input type="checkbox" checked="checked" id="subscribe" name="subscribe" tabindex="6"><span class="label">Новости</span></label>
		</form>

		<div class="private_policy">Осуществляя вход в "Личный кабинет" сайта, вы даёте <a href="/private_policy/" target="_blank">согласие на обработку персональных данных</a>.</div>

		<div class="fbLoginBlock clearfix">
			<h4>{$L.QUICK_ENTRY_voyti_na_sayt}</h4>
			<a href="#facebook_enter" id="fb_enter_dva" onclick="return qLogin.loginWithFB();" tabindex="7">{$L.QUICK_ENTRY_voyti_cherez_fb}</a>
			<a href="#" id="vk_enter_dva" onclick="" class="button">{$L.QUICK_ENTRY_voyti_cherez_vk}</a>			
		</div>
		<div class="vkLoginBlock" style="display:none;">
			<h4>Войти на сайт используя аккаунт Вконтакaте</h4>			
			<span class="hint">{$L.QUICK_ENTRY_ispolzuyte_svoy_akkaunt_sos_seti} maryjane.ru</span>
		</div>
		<script type="text/javascript">
			
			$('#quickLoginForm').submit(function() {
				
				if (!valid.check($('#quickLoginForm input[name="login"]')) || !valid.check($('#quickLoginForm input[name="password"]'))) 
					return false;
				
				trackUser('Регистрация/Авторизации','Авторизиция','отправление данные для авторизации');
				return true;
			});
			
			$('#quickReg').submit(function() {
				if (!valid.check($('#quickReg input[name="email"]'))){ 
					return false; 
				}
				
				if (isValidEmail($.trim($('#quickReg #email').val()))) { 				
					//только для мыла
					$('<span>').addClass('triggerVKregistration').appendTo('body').trigger( "click" );//ремаркетинг VK создаем и активируем событие
					trackUser('Регистрация/Авторизации','registration - отправлено на подтверждение', 'Быстрый вход(e-mail)'+(document.location.search==='?next=/senddrawing.design/' || document.location.search==='?next=/senddrawing.sticker1color/' || document.location.search==='?next=/senddrawing.pattern/' || document.location.search==='?next=/senddrawing.pro/' ?' перекинуло с senddrawing':''));//так как данные и на регистрацию идут
				}
				
				trackUser('Регистрация/Авторизации','Авторизиция', 'Быстрый вход(e-mail)');
				
				return true;
			});
			
			$('.q-login-form-v2 .close-btn').click(function() { 
				trackUser('Регистрация/Авторизации','Авторизиция', 'закрытие окна крестиком');
			});
			
			$('#fb_enter_dva').attr('onclick','').click(function() { 
				trackUser('Регистрация/Авторизации','Авторизиция', 'Вход через Facebook');
				window.open('http://www.maryjane.ru/login/fb/',(IE?'':'Вход через Facebook'),'location,width=800,height=400');
				//return false;
			});
			
			$('#vk_enter_dva').click(function(){ 
				trackUser('Регистрация/Авторизации','Авторизиция', 'Вход через Вконтакте');
				window.open('http://api.vkontakte.ru/oauth/authorize?client_id='+VK_APP_ID+'&scope=&redirect_uri=http://www.maryjane.ru/login/vk/&response_type=code&display=popup',(IE?'':'Вход через Вконтакте'),'location,width=400,height=300');
				//return false;
			});
		
		valid = {
			inputs: 'input[name=login],#quickReg input[name=email]', //, input[name=phone]

			errorClass: 'error',
			validClass: 'complite',				
			error: '',

			validPass: function(t, afterExec){
				this.error = '';
				//пусто
				if ($.trim(t.val()).length == 0) { 					
					this.error = 'Это обязательное поле'; 
					trackUser('Регистрация/Авторизации','Ошибки', 'Авторизиция - пароль -' +this.error);
					return false;		
				}
					
				return true;
			},			
			validLogin: function(t, afterExec){
				var self = this;				
				this.error = '';
								
				//пусто
				if ($.trim(t.val()).length == 0) { 					
					this.error = 'Это обязательное поле';
					trackUser('Регистрация/Авторизации','Ошибки', 'Авторизиция - логин -' +this.error);
					return false; }
				
				if (/[а-я]+/gi.test($.trim(t.val()))) {
					this.error = 'Только английские, пожалуйста';
					trackUser('Регистрация/Авторизации','Ошибки', 'Авторизиция - логин -' +this.error);
					return false;
				}								
				return true;
			},	
			
			validMailPhone: function(t, afterExec){
				var self = this;				
				this.error = '';
			
				//пусто
				if ($.trim(t.val()).length == 0) { 
					this.error = 'Это обязательное поле'; 
					trackUser('Регистрация/Авторизации','Ошибки', 'Авторизиция - мыло -' +this.error);return false; }
				
				if (/[а-я]+/gi.test($.trim(t.val()))) {
					this.error = 'Только английские, пожалуйста';
					trackUser('Регистрация/Авторизации','Ошибки', 'Авторизиция - мыло -' +this.error);
					return false;
				}				
				
				if (t.attr('country') == 'RU') { 
					//phone - россия
					var isPhone = /^[0-9\+]+$/gi.test(t.val());
					if (isNaN(t.val()*1)) isPhone = false;
					if (t.val()*1<99999)  isPhone = false;
					if (!isPhone) { 
						this.error = 'Не похоже на телефон'; 
						trackUser('Регистрация/Авторизации','Ошибки', 'Авторизиция - ' +this.error);
						return false;
					}
				}else{	
					//email
					var isMail = isValidEmail($.trim(t.val()));	
					if (!isMail) { 
						this.error = 'Не похоже на email'; 
						trackUser('Регистрация/Авторизации','Ошибки', 'Авторизиция - ' +this.error);
						return false;
					}
				}				
				return true;
			},	
			valid: function(t, afterExec){
				switch(t.attr('name')) {
					case 'email': return this.validMailPhone(t, afterExec);
					case 'login': return this.validLogin(t, afterExec);
					case 'password': return this.validPass(t, afterExec);
				}
			},
			check: function(el, t, afterExec){
				var self = this;
				var err = !this.valid(el, afterExec);		
				if (!t)
					this.paint(el,err,t);
				return !err;
			},
			paint: function(el,err,t) {				
				$(el).parent().find('.box-input-result:first').css('cursor','default').click(function(){}).removeClass(this.errorClass).removeClass(this.validClass).addClass(err?this.errorClass:this.validClass).show();
					$(el).parent().find('.error_sml:first').html(err?this.error:'');
					if (!this.error || this.error == '') { $(el).parent().find('.box-input-result:first').hide(); return; }
					if (err) 
						$(el).parent().find('.box-input-result:first').css('cursor','pointer').click(function(){
							$(this).css('cursor','default').click(function(){}).removeClass(self.errorClass);
							$(this).parent().find('.error_sml:first').html('');
							var inp = $(this).parent().find('input:first,select:first,textarea:first');
							var pl = inp.attr('_placeholder');
							inp.attr('placeholder', pl).val('').focus();
						});	
			},			
			init: function(afterExec){
				var self = this;
				$(this.inputs).change(function(){
					self.check($(this), false, afterExec);
				});/*.keyup(function(){
					self.check($(this), false, afterExec);
				});*/
				return this;
			}
		}.init();
		
		$(document).ready(function() {
			$('input[name=login]').focus(function(){
				 $('input[name=login]').keyup(function(event){
					if(event.keyCode==13){
						$('input[name=password]').focus();
					}
				})
			});
		});

		</script>
	</div>
</div>