{if $USER->client->ismobiledevice == '1' && $USER->client->istablet == 0}
	<a style="margin:0 0 0 10px;" href="/" rel="nofollow">
		<img width="121" height="21" title="{$L.HEADER_title}" src="/images/logo_mj_small.gif"/>
	</a>
{/if}

{if !$USER->authorized}
	<p class="registration">{$L.REGISTRATION_you_registr}? <a href="#" _href="/login/" onclick="document.location = $(this).attr('_href');return false;" rel="nofollow">{$L.REGISTRATION_this_enter}</a>
	 <a class="registration-cha-lang" href="#" _href="/language/{if $PAGE->lang == 'ru'}en{else}ru{/if}/" onclick="document.location = $(this).attr('_href');return false;" rel="nofollow" title="{if $PAGE->lang == 'ru'}Switch to English{else}Перейти на Русский{/if}">{if $PAGE->lang == 'ru'}Switch to English{else}Перейти на Русский{/if}</a>
	</p>
{/if}
		
<div class="registration042011 v2">
	
	{if $error}
	<div class="serverErr">
		{$error.text}
	</div>
	{/if}
	
	<form method="post">
		<div class="clearfix">
			<div class="leftSide">
				<div class="input">
					<label for="reglogin">{$L.REGISTRATION_name}</label>
					<input type="text" name="login" id="reglogin" class="required_input" maxlength="25" value="{$POST.login}" {if $USER->client->ismobiledevice == '1' && $USER->client->istablet == 0}placeholder="{$L.REGISTRATION_name}"{else}placeholder="{$L.REGISTRATION_only_latin}" _placeholder="{$L.REGISTRATION_only_latin}"{/if} />
					<span class="box-input-result"></span>
					<span class="error_sml"><!-- Это обязательное поле --></span>
				</div>
				<div class="input">
					<label for="regemail">{$L.REGISTRATION_email}</label>
					<input type="text" name="email" id="regemail" class="required_input" value="{$POST.email}"  {if $USER->client->ismobiledevice == '1' && $USER->client->istablet == 0}placeholder="E-Mail"{/if}/>
					<span class="box-input-result"></span>
					<span class="error_sml font-size-11"><!-- Неправильный адрес электронной почты --></span>
				</div>
				{*if $USER->country == 'RU'}
				<div class="input">
					<label for="regphone">{$L.REGISTRATION_phone}</label>
					<input type="text" name="phone" id="regphone" class="" value="{$POST.phone}"  {if $USER->client->ismobiledevice == '1' && $USER->client->istablet == 0}placeholder="{$L.REGISTRATION_phone}"{/if} />
					<span class="box-input-result"></span>
					<span class="error_sml"><!-- Неправильный телефон --></span>
				</div>
				{/if*}
				<div class="input">
					<label for="regpassword1">{$L.REGISTRATION_pass}</label>
					<input name="password1" type="password" id="regpassword1" class="required_input"  {if $USER->client->ismobiledevice == '1' && $USER->client->istablet == 0}placeholder="{$L.REGISTRATION_pass}"{else}placeholder="{$L.REGISTRATION_min_5}" _placeholder="{$L.REGISTRATION_min_5}"{/if} />
					<span class="box-input-result"></span>
					<span class="error_sml"><!-- Пароли не совпадают --></span>
				</div>
				<div class="input">
					<label for="regpassword2">{$L.REGISTRATION_pass2}</label>
					<input name="password2" type="password" id="regpassword2" class="required_input"  {if $USER->client->ismobiledevice == '1'  && $USER->client->istablet == 0}placeholder="{$L.REGISTRATION_pass2}"{/if} />
					<span class="box-input-result"></span>
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
					<input type="text" name="fio" id="fio" maxlength="50" value="{$POST.fio}" {if $USER->client->ismobiledevice == '1' && $USER->client->istablet == 0}placeholder="{$L.REGISTRATION_fio}"{/if}/>
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
						<option value=""></option>
						{foreach from=$byears item="y"}
						<option value="{$y}">{$y}</option>
						{/foreach}
					</select>
				</div>
				<div class="input">
					<label for="city">{$L.REGISTRATION_sity}</label>
					<input type="text" name="city" id="city" maxlength="80" value="{$POST.city}" {if $USER->client->ismobiledevice == '1'  && $USER->client->istablet == 0}placeholder="{$L.REGISTRATION_sity}"{/if}/>
				</div>
			</div>
		</div>
		
		
		<div id="acceptPersonaData" class="clearfix">
			<input type="checkbox" name="personal_data" id="personal_data-checkbox" />
			<div>
				<label for="personal_data-checkbox">Я согласен с обработкой моих данных</label>*<br />
				<small>В соответствии с федеральным законом Российской Федерации от 27 июля 2006г.<br />№152-ФЗ "О персональных данных"</small>
			</div>
		</div>
		
		<div class="subscribeNews"  style="display:none">{$L.REGISTRATION_news_always}. 
			<label><input type="checkbox" checked="checked" id="news" name="newsLetter">&nbsp;{$L.REGISTRATION_subsc_news}.</label>
		</div>
		<div class="subscribeNews" style="margin:5px 0px;">&nbsp;</div>
		<div class="submit">
			<input id="submitbtn" type="submit" name="submit" class="disabled" value="{$L.REGISTRATION_bott_registr}" />
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
			window.open('//www.maryjane.ru/login/fb/',(IE?'':'Вход через Facebook'),'location,width=800,height=400');
		});
		
		$('a#vk_enter.gotoVKConnect').click(function(){
			window.open('//www.maryjane.ru/login/vk/',(IE?'':'Вход через Вконтакте'),'location,width=800,height=400');
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

<style type="text/css">  
  .disabled {  background-color: #ccc !important;  border-color: #ccc !important;  }
</style>

{/literal}