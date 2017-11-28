<?php
/* Smarty version 3.1.31, created on 2017-11-27 18:51:06
  from "C:\OpenServer\domains\shop.loc\application\views\footer.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5a1c346ac1ac64_86215299',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '36cdcdf55be4478162b44111c1f667539cdbb52c' => 
    array (
      0 => 'C:\\OpenServer\\domains\\shop.loc\\application\\views\\footer.tpl',
      1 => 1511797865,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5a1c346ac1ac64_86215299 (Smarty_Internal_Template $_smarty_tpl) {
?>
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
                        <form id="formAcymailing84821" action="https://curry-moon.com/ru" onsubmit="return submitacymailingform('optin','formAcymailing84821')" method="post" name="formAcymailing84821"  >
                            <div class="acymailing_module_form" >
                                <div class="acymailing_introtext">Будьте в курсе новинок нашего магазина. Узнавайте первыми о промо-акциях и распродажах.</div>			
                                <table class="acymailing_form">
                                <tr>
                                    <td class="acyfield_email acy_requiredField">
                                        <input id="user_email_formAcymailing84821"  onfocus="if(this.value == 'Адрес эл. почты') this.value = '';" onblur="if(this.value=='') this.value='Адрес эл. почты';" class="inputbox" type="text" name="user[email]" style="width:100%" value="Адрес эл. почты" title="Адрес эл. почты"/>
                                    </td> 

                                    <td  class="acysubbuttons">
                                        
                                        <input class="button subbutton btn btn-primary" type="submit" value="Подписаться" name="Submit" onclick="try{ return submitacymailingform('optin','formAcymailing84821'); }catch(err){alert('The form could not be submitted '+err);return false;}"/>
                                        
                                    </td>
                                </tr>
                                </table>

                                <div class="acymailing_finaltext">Мы гарантируем отсутствие спама и конфиденциальность ваших данных.  </div>			<input type="hidden" name="ajax" value="0" />
                                <input type="hidden" name="acy_source" value="module_718" />
                                <input type="hidden" name="ctrl" value="sub"/>
                                <input type="hidden" name="task" value="notask"/>
                                <input type="hidden" name="redirect" value="https%3A%2F%2Fcurry-moon.com%2Fru"/>
                                <input type="hidden" name="redirectunsub" value="https%3A%2F%2Fcurry-moon.com%2Fru"/>
                                <input type="hidden" name="option" value="com_acymailing"/>
                                <input type="hidden" name="hiddenlists" value="1"/>
                                <input type="hidden" name="acyformname" value="formAcymailing84821" />
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

            <?php if ($_smarty_tpl->tpl_vars['appMode']->value == 'production') {?>
                <!-- Yandex.Metrika counter -->
                <?php echo '<script'; ?>
 type="text/javascript">
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
                <?php echo '</script'; ?>
>
                <noscript><div><img src="https://mc.yandex.ru/watch/31148776?ut=noindex" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
                <!-- /Yandex.Metrika counter -->	
            <?php }?>

            </div>
        </div>

        <p id="gkCopyrights">©2015 <a href="http://curry-moon.com">Curry Moon</a>
        <small>Все права защищены</small>
        </p>			
    </div>
</footer> <?php }
}
