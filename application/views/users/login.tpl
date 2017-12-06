<section id="gkMainbody" style="font-size: 100%;">
<section class="login">

<form action="/ru/users/login" method="post" id="com-login-form">
    <fieldset>
        <div class="login-fields">
            <label id="username-lbl" for="username" class="required">
            Логин<span class="star">&nbsp;*</span></label>
            <input type="text" name="login" id="username" value="" class="validate-username required" size="25" required="" aria-required="true" autofocus="">
        </div>
        <div class="login-fields">
            <label id="password-lbl" for="password" class="required">
            Пароль<span class="star">&nbsp;*</span></label>
            <input type="password" name="password" id="password" value="" class="validate-password required" size="25" maxlength="99" required="" aria-required="true">				
        </div>
        <div class="login-fields">
            <label>Запомнить меня</label>
            <input id="remember" type="checkbox" name="remember" class="inputbox" value="yes">
        </div>
        <button type="submit" class="button">Войти</button>

        <input type="hidden" name="return" value="">

    </fieldset>
    
    <input type="hidden" name="csrf_token" value="{$csrf_token}" />
</form>

<ul>
    <li>
        <a href="/ru/users/forgot-password">Забыли пароль?</a>
    </li>
    <li>
        <a href="/ru/users/registration">Ещё нет учётной записи?</a>
    </li>
</ul>
</section>
</section>