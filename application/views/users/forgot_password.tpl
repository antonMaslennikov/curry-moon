<section id="gkMainbody" style="font-size: 100%;">
    <section class="reset{if 111}-confirm{/if}">
       
        {if 111}
           
            <form id="user-registration" action="/ru/users/forgot-password/request" method="post" class="form-validate">
                <p>Пожалуйста, введите адрес электронной почты, указанный в параметрах вашей учётной записи. На него будет отправлен специальный проверочный код. После его получения вы сможете ввести новый пароль для вашей учётной записи.</p>
                <fieldset>
                    <dl>
                        <dt><label id="jform_email-lbl" for="jform_email" class="hasPopover required" title="" data-content="Введите, пожалуйста, адрес электронной почты, указанный в параметрах вашей учётной записи.<br />На этот адрес будет отправлен специальный проверочный код. После его получения вы сможете ввести новый пароль для вашей учётной записи." data-original-title="Адрес электронной почты">
                        Адрес электронной почты<span class="star">&nbsp;*</span></label>
                        </dt>
                        <dd><input type="text" name="jform[email]" id="jform_email" value="" class="validate-username required" size="30" required="required" aria-required="true"></dd>
                        <dt><label id="jform_captcha-lbl" for="jform_captcha" class="hasPopover required" title="" data-content="Введите текст, который вы видите на картинке." data-original-title="CAPTCHA">CAPTCHA<span class="star">&nbsp;*</span></label>
                        </dt>
                        <dd><div id="jform_captcha" class="g-recaptcha  required" data-sitekey="6LdUhxwTAAAAAIH2NNLdSM2SQv079eAidSABPt6k" data-theme="light" data-size="normal"><div style="width: 304px; height: 78px;"><div><iframe src="https://www.google.com/recaptcha/api2/anchor?k=6LdUhxwTAAAAAIH2NNLdSM2SQv079eAidSABPt6k&amp;co=aHR0cHM6Ly93d3cuY3VycnktbW9vbi5jb206NDQz&amp;hl=ru&amp;v=r20171129143447&amp;theme=light&amp;size=normal&amp;cb=9njyvpkikug1" width="304" height="78" role="presentation" frameborder="0" scrolling="no" sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-top-navigation allow-modals allow-popups-to-escape-sandbox"></iframe></div>
                        <textarea id="g-recaptcha-response" name="g-recaptcha-response" class="g-recaptcha-response" style="width: 250px; height: 40px; border: 1px solid #c1c1c1; margin: 10px 25px; padding: 0px; resize: none;  display: none; "></textarea></div></div></dd>
                        </dl>
                </fieldset>

        {else}
           
            <form action="/ru/users/forgot-password/confirm/submit" method="post" class="form-validate">
                <p>На ваш адрес электронной почты было отправлено письмо, содержащее проверочный код. Введите его, пожалуйста, в поле ниже. Это подтвердит, что именно вы являетесь владельцем данной учётной записи.</p>
                <fieldset>
                <dl>
                    <dt><label id="jform_token-lbl" for="jform_token" class="hasPopover required" title="" data-content="Введите проверочный код, который получили по электронной почте." data-original-title="Код подтверждения">
                    Код подтверждения:<span class="star">&nbsp;*</span></label>
                    </dt>
                    <dd><input type="text" name="jform[token]" id="jform_token" value="" class="required" size="32" required="required" aria-required="true">
                    </dd>
                </dl>
                </fieldset>

        {/if}
        
            {if $error}
            <div class="new-error-block">Ошибка: {$error}</div>
            {/if}
           
            <div>
                <button type="submit" class="validate">Отправить</button>
                <input type="hidden" name="csrf_token" value="{$csrf_token}" />		
            </div>
        </form>
    </section>
</section>