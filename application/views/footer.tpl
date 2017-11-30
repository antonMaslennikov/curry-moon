<section id="gkBottom3">
    <div class="gkCols6 gkNoMargin gkPage">

         <div class="box  gkmod-3"><h3 class="header">Мы в соцсетях</h3>
            <div class="content">
                <div class="custom "  >

                    <div class="bottom-soc-icons">
                        <a href="https://www.facebook.com/CurryMoonShop" target="_blank"><img src="/public/images/social/facebook.png" alt="facebook" class="soc-icon-bw" /></a>&nbsp;&nbsp;<a href="https://instagram.com/currymoon/" target="_blank"><img src="/public/images/social/instagram.png" alt="instagram" class="soc-icon-bw" /></a>&nbsp;&nbsp;<a href="https://ru.pinterest.com/currymoon/" target="_blank"><img src="/public/images/social/pinterest.png" alt="pinterest" class="soc-icon-bw" /></a>&nbsp;&nbsp;<a href="https://vk.com/currymoon" target="_blank"><img src="/public/images/social/vk.png" alt="vk" class="soc-icon-bw" /></a>
                    </div>

                    <h3 class="header">Способы оплаты</h3>

                    <div class="payicon"><img src="/public/images/payment/visa.png" alt="visa" /><img src="/public/images/payment/mastercard.png" alt="Mastercard" /><img src="/public/images/payment/maestro.png" alt="Maestro" /></div>

                    <div style="clear: all; line-height: 2em;">&nbsp;</div>

                    <div class="payicon"><img src="/public/images/payment/alfabank-white.png" alt="Альфа-Банк" /><img src="/public/images/payment/sberbank.png" alt="Сбербанк" /><img src="/public/images/payment/yandexmoney.png" alt="Яндекс.Деньги" /></div>	
                </div>
            </div>
        </div>

        <div class="box  gkmod-3">
            <div class="content">
                <ul class="menu">
                    <li class="item-1077"><a href="/ru/about" >О нас</a></li>
                    <li class="item-1125"><a href="/ru/discount" >Скидки и бонусы</a></li>
                    <li class="item-1079"><a href="/ru/payment" >Оплата</a></li>
                    <li class="item-1080"><a href="/ru/delivery" >Доставка</a></li>
                    <li class="item-1166"><a href="/ru/sotrudnichestvo" >Сотрудничество</a></li>
                    <li class="item-1070"><a href="/ru/terms-and-conditions" >Условия обслуживания</a></li>
                    <li class="item-1081"><a href="/ru/contact-us" >Контакты</a></li>
                </ul>
            </div>
        </div>

        <div class="box  gkmod-3">
            <div class="content">
               <div class="acymailing_module" id="acymailing_module_formAcymailing84821">
                    <div class="acymailing_fulldiv" id="acymailing_fulldiv_formAcymailing84821"  >
                        <form id="formAcymailing84821" action="/ru/users/subscribe" method="post" name="formAcymailing84821"  >
                            <div class="acymailing_module_form" >
                                <div class="acymailing_introtext">Будьте в курсе новинок нашего магазина. Узнавайте первыми о промо-акциях и распродажах.</div>			
                                <table class="acymailing_form">
                                <tr>
                                    <td class="acyfield_email acy_requiredField">
                                        <input id="user_email_formAcymailing84821" class="inputbox" type="text" name="user[email]" style="width:100%" placeholder="Адрес эл. почты" title="Адрес эл. почты"/>
                                    </td> 

                                    <td  class="acysubbuttons">
                                        <input class="button subbutton btn btn-primary" type="submit" value="Подписаться" name="Submit" />
                                    </td>
                                </tr>
                                </table>

                                <div class="acymailing_finaltext">Мы гарантируем отсутствие спама и конфиденциальность ваших данных.  </div>			
                                <input type="hidden" name="csrf_token" value="{$csrf_token}" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<footer id="gkFooter">
    <div class="gkPage">
        <div id="gkFooterNav">
            <div class="custom "  >

            {if $appMode == 'production'}
                <!-- Yandex.Metrika counter -->
                <script type="text/javascript">
                    (function (d, w, c) {
                    (w[c] = w[c] || []).push(function() {
                    try {
                    w.yaCounter31148776 = new Ya.Metrika({
                    id:31148776,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true,
                    ut:"noindex"
                    });
                    } catch(e) { }
                    });

                    var n = d.getElementsByTagName("script")[0],
                    s = d.createElement("script"),
                    f = function () { n.parentNode.insertBefore(s, n); };
                    s.type = "text/javascript";
                    s.async = true;
                    s.src = "https://mc.yandex.ru/metrika/watch.js";

                    if (w.opera == "[object Opera]") {
                    d.addEventListener("DOMContentLoaded", f, false);
                    } else { f(); }
                    })(document, window, "yandex_metrika_callbacks");
                </script>
                <noscript><div><img src="https://mc.yandex.ru/watch/31148776?ut=noindex" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
                <!-- /Yandex.Metrika counter -->	
            {/if}

            </div>
        </div>

        <p id="gkCopyrights">©{$datetime.year} <a href="http://curry-moon.com">Curry Moon</a>
        <small>Все права защищены</small>
        </p>			
    </div>
</footer> 