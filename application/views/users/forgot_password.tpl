<section id="gkMainbody" style="font-size: 100%;">
    <section class="reset{if $confirm}-confirm{/if}">
       
        {if $request}
           
            <form id="user-registration" action="/ru/users/forgot-password/request" method="post" class="form-validate">
                <p>Пожалуйста, введите адрес электронной почты, указанный в параметрах вашей учётной записи. На него будет отправлен специальный проверочный код. После его получения вы сможете ввести новый пароль для вашей учётной записи.</p>
                <fieldset>
                    <dl>
                        <dt>
                            <label id="jform_email-lbl" for="jform_email" class="hasPopover required" title="" data-content="Введите, пожалуйста, адрес электронной почты, указанный в параметрах вашей учётной записи.<br />На этот адрес будет отправлен специальный проверочный код. После его получения вы сможете ввести новый пароль для вашей учётной записи." data-original-title="Адрес электронной почты">
                            Адрес электронной почты<span class="star">&nbsp;*</span></label>
                        </dt>
                        <dd>
                            <input type="text" value="{$smarty.post.jform.email}" name="jform[email]" id="jform_email" value="" class="validate-username required" size="30" required="required" aria-required="true">
                        </dd>
                        <dt>
                            <label id="jform_captcha-lbl" for="jform_captcha" class="hasPopover required" title="" data-content="Введите текст, который вы видите на картинке." data-original-title="CAPTCHA">CAPTCHA<span class="star">&nbsp;*</span></label>
                        </dt>
                        <dd>
                            <div class="g-recaptcha" data-sitekey="6LdVqj0UAAAAAH8r0RnFeNQVxQni7qMniZI_LU6v"></div>
                            <script src='https://www.google.com/recaptcha/api.js'></script>
                        </dd>
                    </dl>
                </fieldset>

        {elseif $confirm}
           
            <form action="/ru/users/forgot-password/confirm/{$U->id}" method="post" class="form-validate">
                <p>На ваш адрес электронной почты было отправлено письмо, содержащее проверочный код. Введите его, пожалуйста, в поле ниже. Это подтвердит, что именно вы являетесь владельцем данной учётной записи.</p>
                <fieldset>
                <dl>
                    <dt><label id="jform_token-lbl" for="jform_token" class="hasPopover required" title="" data-content="Введите проверочный код, который получили по электронной почте." data-original-title="Код подтверждения">
                    Код подтверждения:<span class="star">&nbsp;*</span></label>
                    </dt>
                    <dd>
                        <input type="text" name="jform[token]" id="jform_token" value="" class="required" size="32" required="required" aria-required="true">
                    </dd>
                </dl>
                </fieldset>
                
        {elseif $success}
            
            <form action="#" method="post" class="form-validate">
            
                <p>Ваш новый пароль был отправлена вам на почту</p>
            
        {/if}
        
        {if !$success}
            {if $error}
            <div class="new-error-block">Ошибка: {$error}</div>
            {/if}
           
            <div>
                <button type="submit" class="validate">Отправить</button>
                <input type="hidden" name="csrf_token" value="{$csrf_token}" />		
            </div>
        {/if}
        </form>
    </section>
</section>