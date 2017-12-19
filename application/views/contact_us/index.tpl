<section id="gkMainbody" style="font-size: 100%;">

    <div class="contact" itemscope="" itemtype="http://schema.org/Person">

    <h3>Контакты</h3>		

    <div class="contact-contactinfo">
        <div>
            <span class="jicons-text" itemprop="email"> E-mail:  </span> 
            <span class="contact-emailto"> 
               <span id="cloak7abf8e00dab6fce8796c14b1e5d6f813"><a href="mailto:info@curry-moon.com">{$VARS.contactEmail}</a></span>
               <script type="text/javascript">
                document.getElementById('cloak7abf8e00dab6fce8796c14b1e5d6f813').innerHTML = '';
                var prefix = '&#109;a' + 'i&#108;' + '&#116;o';
                var path = 'hr' + 'ef' + '=';
                var addy7abf8e00dab6fce8796c14b1e5d6f813 = '&#105;nf&#111;' + '&#64;';
                addy7abf8e00dab6fce8796c14b1e5d6f813 = addy7abf8e00dab6fce8796c14b1e5d6f813 + 'c&#117;rry-m&#111;&#111;n' + '&#46;' + 'c&#111;m';
                var addy_text7abf8e00dab6fce8796c14b1e5d6f813 = '&#105;nf&#111;' + '&#64;' + 'c&#117;rry-m&#111;&#111;n' + '&#46;' + 'c&#111;m';document.getElementById('cloak7abf8e00dab6fce8796c14b1e5d6f813').innerHTML += '<a ' + path + '\'' + prefix + ':' + addy7abf8e00dab6fce8796c14b1e5d6f813 + '\'>'+addy_text7abf8e00dab6fce8796c14b1e5d6f813+'<\/a>';
               </script> 
            </span>
        </div>
        <div>
            <span class="jicons-text" itemprop="telephone"> Телефон:  </span> 
            <span class="contact-telephone">{$VARS.contactPhone1}, {$VARS.contactPhone2}</span>
        </div>
    </div>


    <div class="contact-misc">
        <p>Уважаемые покупатели, по всем интересующим вопросам Вы можете связаться с нами по электронной почте,&nbsp;позвонить по указанным телефонам, написать сообщение с помощью сервиса Whats App или отправить сообщение через форму обратной связи ниже. Мы обязательно ответим в ближайшее время.</p>
        <p>Наш адрес: {$VARS.contactAddress}</p>
        <h4>Мы в социальных сетях</h4>
        <p>&nbsp;<a href="{$VARS.soc_fb}" target="_blank"><img src="/public/images/social/facebook.png" alt="facebook"></a> &nbsp; <a href="{$VARS.soc_insta}" target="_blank"><img src="/public/images/social/instagram.png" alt="instagram"></a> &nbsp; <a href="{$VARS.soc_pint}" target="_blank"><img src="/public/images/social/pinterest.png" alt="pinterest"></a>&nbsp;&nbsp; <a href="{$VARS.soc_vk}" target="_blank"><img src="/public/images/social/vk.png" alt="vk"></a></p>
    </div>


    <h3>Форма обратной связи</h3>				
    
        <div class="contact-form">
            <form id="contact-form" action="/ru/contact-us" method="post" class="form-validate clearfix" style="margin-bottom: 40px;">
                <fieldset>
                    <legend>Отправить сообщение. Все поля, отмеченные звёздочкой, являются обязательными.</legend>
                                        
                    <dl>
                        <dt>
                            <label id="jform_contact_name-lbl" for="jform_contact_name" class="hasPopover required" title="" data-content="Ваше имя" data-original-title="Имя">
                        Имя<span class="star">&nbsp;*</span></label>
                        </dt>
                        <dd>
                            <input type="text" name="jform[feedback_name]" id="jform_contact_name" value="" class="required" size="30" required="required" aria-required="true">
                        </dd>
                        <dt>
                            <label id="jform_contact_email-lbl" for="jform_contact_email" class="hasPopover required" title="" data-content="Адрес электронной почты контакта" data-original-title="E-mail">
                        E-mail<span class="star">&nbsp;*</span></label>
                        </dt>
                        <dd>
                            <input type="email" name="jform[feedback_email]" class="validate-email required" id="jform_contact_email" value="" size="30" autocomplete="email" required="required" aria-required="true"></dd>
                        <dt>
                            <label id="jform_contact_emailmsg-lbl" for="jform_contact_emailmsg" class="hasPopover required" title="" data-content="Тема сообщения" data-original-title="Тема">
                            Тема<span class="star">&nbsp;*</span></label>
                        </dt>
                        <dd>
                            <input type="text" name="jform[feedback_topic]" id="jform_contact_emailmsg" value="" class="required" size="60" required="required" aria-required="true">
                        </dd>
                        <dt class="inline">
                            <label id="jform_contact_email_copy-lbl" for="jform_contact_email_copy" class="hasPopover" title="" data-content="Отправляет копию данного сообщения на указанный вами адрес." data-original-title="Отправить копию этого сообщения на ваш адрес">
                            Отправить копию этого сообщения на ваш адрес</label>
                        </dt>
                        <dd class="inline">
                            <input type="checkbox" name="jform[contact_email_copy]" id="jform_contact_email_copy" value="1">
                        </dd>
                        <dt> 
                            <label id="jform_captcha-lbl" for="jform_captcha" class="hasPopover required" title="" data-content="Введите текст, который вы видите на картинке." data-original-title="CAPTCHA">
                        CAPTCHA<span class="star">&nbsp;*</span></label>
                        </dt>
                        <dd>
                            <div class="g-recaptcha" data-sitekey="6LdVqj0UAAAAAH8r0RnFeNQVxQni7qMniZI_LU6v"></div>
                        </dd>

                    </dl>
                    <dl>
                        <dt>
                            <label id="jform_contact_message-lbl" for="jform_contact_message" class="hasPopover required" title="" data-content="Введите текст вашего сообщения" data-original-title="Сообщение">
                        Сообщение<span class="star">&nbsp;*</span></label>
                        </dt>
                        <dd>
                            <textarea name="jform[feedback_text]" id="jform_contact_message" cols="50" rows="10" class="required" required="required" aria-required="true"></textarea>
                        </dd>
                        <dd>
                            <button class="button validate" type="submit">Отправить сообщение</button>
                            
                            <input type="hidden" name="csrf_token" value="{$csrf_token}" />
                        </dd>
                    </dl>
                    <br clear="all">
                </fieldset>
            </form>
        </div>

    </div>
</section>


<script src='https://www.google.com/recaptcha/api.js'></script>