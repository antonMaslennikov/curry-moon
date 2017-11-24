<link rel="stylesheet" href="/css/vk_steps.css" type="text/css" media="all"/>
{literal}
<script>
function isValidEmail (email, strict) {
	if ( !strict ) email = email.replace(/^\s+|\s+$/g, '');
	return (/^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@([a-z0-9][a-z0-9\-]*[a-z0-9]\.)+[a-z]{2,4}$/i).test(email);
}

valid = {
	inputs: 'input[name="name"], input[name="email"], input[name="phone"]',

	errorClass: 'error',
	validClass: 'complite',
		
	error: '',
	
	validFIO: function(t){ 
		this.error = 'Это обязательное поле'; 		
		if ($.trim(t.val()).length == 0) return false;
		//if (/^[a-z0-9]+$/gi.test(t.val())) {
		//	this.error = 'Только русские, пожалуйста';
		//	return false;
		//} 
		return true;
	},
	validLogin: function(t){ 
		this.error = 'Это обязательное поле'; 
		if ($.trim(t.val()).length == 0) return false;
		if ($.trim(t.val()).length < 4) { this.error = 'Минимум 4 символа';  return false; }
		if ($.trim(t.val()).length > 15) { this.error = 'Длина логина не должна превышать 15 символов';  return false; }
		if (!/^[a-z0-9]+$/gi.test(t.val())) {
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
	//validMail: function(t){ this.error = 'Это не похоже на email'; return isValidEmail(t.val()); },
	validMail: function(t){ 
		this.error = 'Это не похоже на email'; 
		if (!isValidEmail($.trim(t.val()))) return false;
		var self = this;
		$.get('/ajax/?action=checkemail', { 'email' : $.trim(t.val()) }, function(d) {
			if (d == 'ok')
				self.paint(t,false);
			else {
				self.error = d;
				self.paint(t,true);
			}
		});
		return true;
	},
	validPhone: function(t){ 
		this.error = '';//'Это обязательное поле'; 
		if ($.trim(t.val()).length == 0) return false;
		if (!/^[0-9\+]+$/gi.test(t.val())) {
			this.error = 'Только цифры, пожалуйста';
			return false;
		}
		if (isNaN(t.val()*1)) { this.error = 'Только цифры, пожалуйста';  return false; }
		if (t.val()*1<99999) { this.error = 'Слишком короткий номер'; return false; }
		return true;
	},
	
	valid: function(t){
		switch(t.attr('name')) {
			case 'name': return this.validFIO(t);
			//case 'login': return this.validLogin(t);			
			case 'email': return this.validMail(t);
			case 'phone': return this.validPhone(t);
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
	
	paint: function(el,err,t) {
		$(el).parent().find('.box-input-result').css('cursor','default').click(function(){}).removeClass(this.errorClass).removeClass(this.validClass).addClass(err?this.errorClass:this.validClass);
		$(el).parent().find('.error_sml').html(err?this.error:'');
		if (err) 
			$(el).parent().find('.box-input-result').css('cursor','pointer').click(function(){
				$(this).css('cursor','default').click(function(){}).removeClass(self.errorClass);
				$(this).parent().find('.error_sml').html('');
				var inp = $(this).parent().find('input');
				var pl = inp.attr('_placeholder');
				inp.attr('placeholder', pl).val('').focus();
			});	
	},
	
	check: function(el, t){
		var self = this;
		var err = !this.valid(el);
		//if (!t)
			this.paint(el,err,t);
		return !err;
	},
	
	init: function(){
		var self = this;
		$(this.inputs).change(function(){
			self.check($(this));
		}).keyup(function(){
			self.check($(this));
		});
	}
}
	
	$(document).ready(function(){
		
		//инициализируем валидацию
		valid.init();
		
		//событие по выбору пола
		$('#input_man_woman a').click(function(){
			$('#input_man_woman a').removeClass('active');
			$(this).addClass('active');
			$(this).parent().find('input[name="sex"]').val($(this).attr('id'));
		});
		
		//событие на валидацию пола
		$('.vk_step1 form').submit(function(){
			var b = valid.checkAll();
			return b;
		});
	});
</script>
{/literal}
<div class="vk_step1">
	<form method="post" action="/editprofile/">
		<div class="ovas">Немного о Вас</div>
		
		<div class="input">
			<label for="reglogin">Имя</label>
			<input type="text" name="name" class="" maxlength="25" value="{$USER->user_name}">
			<span class="box-input-result" style="cursor: default;"></span>
			<span class="error_sml"></span>
		</div>
		<div class="input">
			<label for="regemail">Почта</label>
			<input type="text" name="email" class="required_input" value="">
			<span class="box-input-result" style="cursor: pointer;"></span>
			<span class="error_sml"></span>
		</div>
		{if $USER->country == 'RU'}
		{* привязка телефона работает только для россии *}
		<div class="input">
			<label for="regemail">Телефон</label>
			<input type="text" name="phone" class="" value="">
			<span class="box-input-result" style="cursor: pointer;"></span>
			<span class="error_sml"></span>
		</div>
		{/if}
		<div style="clear: both;"></div>
		<div class="select" style="">
			<label for="sex" style="float: left;width: 90px;">Пол</label>			
			<input type="hidden" name="sex" value="{$USER->user_sex}" />
			<div id="input_man_woman" class="b-radio-manwomen radio-input selected-woman" style="width: 142px; visibility: visible;margin:0;position:relative">
				<input type="hidden" name="sex" value="female" />
				<a rel="nofollow" href="#" id="male" class="type-select {if $USER->user_sex == 'male'}active{/if}" style="display: block; "></a>
				<a rel="nofollow" href="#" id="female" class="type-select {if $USER->user_sex == 'female'}active{/if}" style="display: block; "></a>
			</div>
		</div>
		<div style="clear: both;"></div>
		<div class="select date" style="margin-top: 30px;">
			<label for="day" style="display: block;float: left; width: 90px;">День<br/>рождения</label>
			<select name="birthdayDay" id="day" style="" {if $USER->user_is_fake == 'false'}disabled="disabled"{/if}>
				<option value=""></option>
				{foreach from=$bdays item="d"}
				<option value="{$d}" {if $birthday.2 == $d}selected="selected"{/if}>{$d}</option>
				{/foreach}                  
			</select>
			<select name="birthdayMonth" id="month" style="" {if $USER->user_is_fake == 'false'}disabled="disabled"{/if}>
				<option value=""></option>
				<option value="1" {if $birthday.1 == 1}selected="selected"{/if}>января</option>
				<option value="2" {if $birthday.1 == 2}selected="selected"{/if}>февраля</option>
				<option value="3" {if $birthday.1 == 3}selected="selected"{/if}>марта</option>
				<option value="4" {if $birthday.1 == 4}selected="selected"{/if}>апреля</option>
				<option value="5" {if $birthday.1 == 5}selected="selected"{/if}>мая</option>
				<option value="6" {if $birthday.1 == 6}selected="selected"{/if}>июня</option>
				<option value="7" {if $birthday.1 == 7}selected="selected"{/if}>июля</option>
				<option value="8" {if $birthday.1 == 8}selected="selected"{/if}>августа</option>
				<option value="9" {if $birthday.1 == 9}selected="selected"{/if}>сентября</option>
				<option value="10" {if $birthday.1 == 10}selected="selected"{/if}>октября</option>
				<option value="11" {if $birthday.1 == 11}selected="selected"{/if}>ноября</option>
				<option value="12" {if $birthday.1 == 12}selected="selected"{/if}>декабря</option>
			</select>
			<select name="birthdayYear" id="year" {if $USER->user_is_fake == 'false'}disabled="disabled"{/if}>
				<option value=""></option>
				{foreach from=$byears item="y"}
				<option value="{$y}" {if $birthday.0 == $y}selected="selected"{/if}>{$y}</option>
				{/foreach}
			</select>
		</div>
		<div class="submit">
			<input id="submitbtn" type="submit" name="social_submit" {* disabled="disabled" *} value="Сохранить">
		</div>
		
	</form>
</div>