<section id="gkMainbody" style="font-size: 100%;">
    <div id="vmMainPageOPC">
        <div class="opc_unlogged_wrapper" id="opc_unlogged_wrapper">
            <div id="top_basket_wrapper">
                <div id="opc_basket">
                    <div id="basket_container" style="float: left;">
                        <div class="col-module_content">
                            <table class="table table-striped table-hover" style="width: 100%">
                            <thead>
                                <tr>
                                <th style="width: 50%; text-align: left;" colspan="2">Название</th>
                                <th style="width: 15%; text-align: left;">Артикул</th>
                                <th style="width: 15%; text-align: left;">Кол-во / Обновить</th>
                                <th style="width: 10%; text-align: right;">Цена</th>
                                <th style="width: 10%; text-align: right;">Итого</th>
                                </tr>
                            </thead>
                            <tbody>
                            {foreach from=$basket->basketGoods item="p" key="k"}
                            <tr>
                                <td class="cart-prod-img">
                                    <div style="height: 60px; width: 60px; ">
                                    <div style="float: left; width: 7px; height: 100%;"></div>
                                    <div style="float: left; width: 53px; height: 0px;"></div>
                                        <div style="float: left; width: 53px; height: 60px;">
                                            <img src="{$p.picture_path}" width="46px" height="60px">
                                        </div>
                                    </div>
                                </td>
                                <td class="cart-prod-name">
                                    <a href="/ru/shop/openproduct/{$p.good_id}" class="opc_product_name  _product">{$p.product_name}</a>
                                    <div class="vm-customfield-cart"></div>
                                </td>
                                <td class="cart-prod-sku">{$p.product_sku}</td>
                                <td class="cart-prod-quantity">
                                    <div class="quantity">
                                        <form action="/cart/updatecart" method="post" style="display: inline; margin-left: 4px;">
                                            <div class="input-append">
                                                <input class="span2" id="appendedInputButtons" type="text" title="Обновить количество в корзине" size="3" name="quantity" value="{$p.quantity}">
                                                <input type="hidden" name="product_id" value="{$p.good_id}">
                                                <a class="button-del" title="Удалить товар из корзины" href="/cart/delete?product_id={$p.good_id}"><i class="fa fa-trash-o"></i></a>
                                                <button class="button-upd" type="submit" name="update" title="Обновить количество в корзине" value=" "><i class="fa fa-refresh"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                                <td class="cart-prod-price">
                                    <div class="opc_price_general opc_PricesalesPrice vm-display vm-price-value"><span class="opc_price_general opc_PricesalesPrice">{$p.price} руб</span></div>
                                </td>
                                <td class="cart-prod-subtotal">
                                    <div class="opc_PricesalesPrice vm-display vm-price-value"><span class="opc_PricesalesPrice">{$p.tprice} руб</span></div>
                                </td>
                            </tr>
                            {/foreach}
                            <tr>
                                <td colspan="4" style="text-align: right">
                                Промежуточный итог:
                                </td>
                                <td colspan="2">
                                <div class="opc_Priceproduct_subtotal_opc vm-display vm-price-value"><span class="opc_vm-price-desc"></span><span class="opc_Priceproduct_subtotal_opc">{$basket->basketSum} руб</span></div>						
                                </td>
                            </tr>
                            {if $basket->user_basket_payment_partical > 0}
                            <tr>
                                <td colspan="4" style="text-align: right">
                                Оплачено купоном:
                                </td>
                                <td colspan="2">
                                <div class="opc_Priceproduct_subtotal_opc vm-display vm-price-value"><span class="opc_vm-price-desc"></span><span class="opc_Priceproduct_subtotal_opc">{$basket->user_basket_payment_partical} руб</span></div>						
                                </td>
                            </tr>
                            {/if}
                            <tr>
                                <td colspan="4" style="text-align: right">
                                Стоимость обработки и доставки:
                                </td>
                                <td colspan="2">
                                <div id="tt_shipping_rate_basket" style="text-align: right">0 <span class="currency_format">руб</span></div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" style="text-align: right">
                                Всего:
                                </td>
                                <td colspan="2">
                                <div id="tt_total_basket" style="text-align: right">{$basket->basketSum} <span class="currency_format">руб</span></div>
                                </td>
                            </tr>
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- end id opc_basket -->			
                
                {if $basket->logs.activateCertificate || $basket->logs.activateDiscontCard}
                <div class="coupon_activated_section" style="padding-bottom: 70px;">
                У вас активирован купон на скидку! <a href="#" onclick="jQuery('.coupon_section').toggle(); jQuery(this).parent().hide();return false;">Активировать другой?</a>
                </div>
                {/if}
                
                <div class="coupon_section" {if $basket->logs.activateCertificate || $basket->logs.activateDiscontCard}style="display:none"{/if}>
                   
                    <form action="/cart/setcoupon" method="post" id="userForm">
                        <button type="submit" class="btn"><span>Активировать</span></button>
                        <input type="text" placeholder="Промокод" name="coupon_code" autocomplete="off" id="coupon_code" class="inputbox span3" required="required">
                        <input type="hidden" name="csrf_token" value="{$csrf_token}" />
                    </form>	
                    <div style="margin-bottom: 15px;"><a href="/ru/discount" target="_blank">Как получить купон на скидку?</a></div>
                </div>
                
                <div id="checkout-advertise-box">
                    <div class="checkout-advertise">
                        <div class="payments-signin-button"></div>				
                    </div>
                    <div class="checkout-advertise">
                    </div>
                </div>
            </div>


            <div class="dob0">
                <!-- main onepage div, set to hidden and will reveal after javascript test -->

                <!-- start of checkout form -->
                <form action="" method="post" id="adminForm" name="adminForm" class="form-valid2ate" novalidate="novalidate" autocomplete="on">

                    <div style="display: none;"><input type="submit" onclick="return Onepage.formSubmit(event, this);" name="hidden_submit" value="hidden_submit"></div>

                    <div class="dob1" id="dob1" style="min-height: 520px;">
                       
                        <div class="op_inner">


                        <!-- login box -->

                        {if !$USER->authorized}
                        <div id="tab_selector">
                            <fieldset>
                                <input name="regtypesel" type="radio" id="op_login_btn" onclick="javascript: jQuery('#logintab').show();" style="border: none;" class="styled"><label for="op_login_btn" class="radio" id="op_round_and_separator">Постоянный покупатель</label>
                                <br style="clear: both;">
                                <input class="styled" name="regtypesel" type="radio" checked="checked" id="op_register_btn" onclick="javascript: jQuery('#logintab').hide();" style="border: none;"><label for="op_register_btn" class="radio">Новый покупатель</label>
                            </fieldset>
                        </div>
                        
                        <div>
                            <div>
                                <div id="logintab" style="display: none;">

                                    <div class="formField">
                                        <input type="text" id="username_login" placeholder="Имя пользователя" name="username_login" value="" class="inputbox" size="20" onfocus="inputclear(this)" autocomplete="off">				
                                        <input type="hidden" id="saved_username_login_field" name="savedtitle" value="Имя пользователя">			
                                    </div>

                                    <div class="formField">
                                        <input type="password" id="passwd_login" name="passwd" placeholder="Пароль" value="" class="inputbox" size="20" onkeypress="return Onepage.submitenter(this,event)" onfocus="inputclear(this)" autocomplete="off">				
                                        <input type="hidden" id="saved_password_field" name="savedtitle" value="Пароль">			
                                    </div>
                                    
                                    <input type="hidden" name="remember" value="yes">
                                    
                                    <div style="width: 100%;">
                                        <span style="float: left;">
                                        (<a title="Забыли свой пароль?" href="/ru/users/forgot-password">Забыли свой пароль?</a>)
                                        </span>
                                        <div style="clear: both;">
                                        </div>	
                                        <input type="button" name="LoginSubmit" class="btn btn-small" value="Вход" onclick="javascript: return Onepage.op_login();">

                                        <input type="hidden" name="csrf_token" value="{$csrf_token}" />
                                        <br style="clear: both;">
                                    </div>

                                </div>
                            </div>
                        </div>
                        {/if}
                        
                        <!-- user registration and fields -->

                        <h4>1. Данные покупателя </h4>

                        {if $USER->authorized}
                        <!-- Customer Information -->   
                        <div id="opc_st_245">
                            <div style="width: 100%" class="BTaddress">
                                <div style="clear: both;">
                                   <div class="address_field_name" style="">Эл.почта </div>
                                   <div class="address_field_value" style="">smash@maryjane.ru</div>
                                </div>
                                <div style="clear: both;">
                                   <div class="address_field_name" style="">Фамилия </div>
                                   <div class="address_field_value" style="">s</div>
                                </div>
                                <div style="clear: both;">
                                   <div class="address_field_name" style="">Имя </div>
                                   <div class="address_field_value" style="">s</div>
                                </div>
                                <div style="clear: both;">
                                   <div class="address_field_name" style="">Адрес </div>
                                   <div class="address_field_value" style="">a</div>
                                </div>
                                <div style="clear: both;">
                                   <div class="address_field_name" style="">Почтовый индекс </div>
                                   <div class="address_field_value" style="">a</div>
                                </div>
                                <div style="clear: both;">
                                   <div class="address_field_name" style="">Город </div>
                                   <div class="address_field_value" style="">1</div>
                                </div>
                                <div style="clear: both;">
                                   <div class="address_field_name" style="">Страна </div>
                                   <div class="address_field_value" style="">Российская федерация</div>
                                </div>

                                <div style="clear: both;">
                                    <a href="#" onclick="jQuery(this).parent().parent().toggle(); jQuery('#customer-form').toggle(); return false;">(Добавить/Изменить адрес плательщика)</a>
                                </div>
                            </div>
                        </div>
                        <!-- customer information ends -->
                        {/if}
                        
                        <div id="customer-form" style="width:100%;{if $USER->authorized}display:none{/if}"> 
                            <div class="formField email" id="email_input" title="Эл.почта *">
                               <div test="test">
                                   <input onfocus="inputclear(this)" placeholder="Эл.почта *" autocomplete="off" type="email" id="email_field" name="email" size="30" value="" class="required email " disabledmaxlength="100"> 
                               </div>
                            </div>

                            <div class="formField text" id="last_name_input" title="Фамилия *">
                                <div>
                                    <input onfocus="inputclear(this)" placeholder="Фамилия *" type="text" id="last_name_field" name="last_name" size="30" value="" class="required" disabledmaxlength="32" autocomplete="off"> 
                                </div>
                            </div>

                            <div class="formField text" id="first_name_input" title="Имя *">
                                <div>
                                    <input onfocus="inputclear(this)" placeholder="Имя *" type="text" id="first_name_field" name="first_name" size="30" value="" class="required" disabledmaxlength="32" autocomplete="off"> 
                                </div>
                            </div>

                            <div class="formField text" id="middle_name_input" title="Отчество">
                                <span id="middle_name_div" style="" class="formLabel "></span>
                                <div>
                                    <input onfocus="inputclear(this)" placeholder="Отчество" type="text" id="middle_name_field" name="middle_name" size="30" value="" disabledmaxlength="32" autocomplete="off" class=""> 
                                </div>
                            </div>

                            <div class="formField text" id="address_1_input" title="Адрес *">
                                <div>
                                    <input onfocus="inputclear(this)" placeholder="Адрес *" type="text" onblur="javascript:Onepage.op_runSS(this);" id="address_1_field" name="address_1" size="30" value="" class="required" disabledmaxlength="64" autocomplete="off"> 
                                </div>
                            </div>

                            <div class="formField text" id="zip_input" title="Почтовый индекс *">
                                <div>
                                    <input onfocus="inputclear(this)" placeholder="Почтовый индекс *" type="text" onblur="javascript:Onepage.op_runSS(this);" id="zip_field" name="zip" size="30" value="" class="required" disabledmaxlength="32" autocomplete="off"> 
                                    <input type="hidden" id="saved_zip_field" name="savedtitle" value="Почтовый индекс *">
                                </div>
                            </div>

                            <div class="formField text" id="city_input" title="Город *">
                                <div>
                                    <input onfocus="inputclear(this)" placeholder="Город *" type="text" id="city_field" name="city" size="30" value="" class="required" disabledmaxlength="32" autocomplete="off"> 
                                    <input type="hidden" id="saved_city_field" name="savedtitle" value="Город *">
                                </div>
                            </div>

                            <div class="formField select" id="virtuemart_country_id_input" title="Страна *">
                                <label class="label_selects" style="clear: both; " for="virtuemart_country_id_field">Страна *</label>
                                <div>
                                    <select autocomplete="off" name="virtuemart_country_id" class=" required">
                                        <option value="">-- Выберите --</option>
                                        <option value="15">Азербайджан</option>
                                        <option value="11">Армения</option>
                                        <option value="20">Беларусь</option>
                                        <option value="109">Казахстан</option>
                                        <option value="140">Молдавская республика</option>
                                        <option value="176" selected="selected">Российская федерация</option>
                                        <option value="207">Таджикистан</option>
                                        <option value="216">Туркменистан</option>
                                        <option value="226">Узбекистан</option>
                                        <option value="220">Украина</option>
                                    </select>
                                </div>
                            </div>

                            <div class="formField text" id="phone_1_input" title="Телефон">
                                <span id="phone_1_div" style="" class="formLabel "></span>
                                <div>
                                    <input onfocus="inputclear(this)" placeholder="Телефон" type="text" id="phone_1_field" name="phone_1" size="30" value="" disabledmaxlength="32" autocomplete="off" class=""> 
                                </div>
                            </div>
                        </div>
                        

                        <div style="display:none;" id="inform_html">&nbsp;</div>

                        <br style="clear: both;">
                        <!-- end user registration and fields --> 
                        <!-- shipping address info -->

                        </div>
                    </div>

                    <div class="dob2" id="dob2" style="min-height: 520px;">
                       
                        <div class="op_inner">

                        <!-- end shipping address info -->

                            <div>
                                <h4>2. Способ доставки</h4>	

                                <!-- shipping methodd -->
                                <div id="ajaxshipping" style="min-height: 30px;"><div class="opc_ship_wrap opc_ship_wrap_2 selected" id="opc_ship_wrap_2"><input type="radio" data-dynamic-update="1" autocomplete="off" name="virtuemart_shipmentmethod_id" onclick="javascript:Onepage.changeTextOnePage3(op_textinclship, op_currency, op_ordertotal);" id="shipment_id_2" checked="checked" value="2">
                                <label for="shipment_id_2"><span class="vmshipment"><span class="vmCartShipmentLogo"><img align="middle" src="https://www.curry-moon.com//images/virtuemart/shipment/major.png" alt="major"></span>  <span class="vmshipment_name">Major Express</span><span class="vmshipment_description">(Оплата доставки при получении. <a href="http://www.major-express.ru/calculator.aspx" target="_blank">Калькулятор стоимости.</a>)</span></span></label></div><!-- opc_ship_wrap end --><br><input type="hidden" name="7997f186caeb9f36c4c0c5020f6e6c0f" value="1"></div>
                                <br>
                            <!-- end shipping methodd -->
                            </div>

                            <div id="payment_top_wrapper" style="display: block;">

                                <h4 class="payment_header">3. Способ оплаты </h4>

                                <div id="payment_html"><div class="payment_inner_html" rel="force_show_payments"><div style="clear: both;"><div id="opc_payment_wrap_3" class="opc_payment_wrap opc_payment_wrap_3 selected"><input type="radio" autocomplete="off" data-dynamic-update="1" name="virtuemart_paymentmethod_id" onclick="javascript: Onepage.runPay('','',op_textinclship, op_currency, 0)" id="payment_id_3" value="3" checked="checked">
                                <label for="payment_id_3"><span class="vmpayment"><img align="middle" src="https://www.curry-moon.com//plugins/vmpayment/yandexapi/visa-mc-yandex.png" alt="visa-mc-yandex">  <span class="vmpayment_name">Яндекс.Деньги</span></span></label>
                                </div></div><div style="clear: both;"><div id="opc_payment_wrap_5" class="opc_payment_wrap opc_payment_wrap_5"><input type="radio" autocomplete="off" data-dynamic-update="1" name="virtuemart_paymentmethod_id" onclick="javascript: Onepage.runPay('','',op_textinclship, op_currency, 0)" id="payment_id_5" value="5">
                                <label for="payment_id_5"><span class="vmpayment"><span class="vmCartPaymentLogo"><img align="middle" src="https://www.curry-moon.com//images/stories/virtuemart/payment/alfabank-white.png" alt="alfabank-white"></span>  <span class="vmpayment_name">Карта Альфа-Банка</span><span class="vmpayment_description vmpayment_description_5">Номер карты для оплаты отправляется на email</span></span></label>
                                </div></div><div style="clear: both;"><div id="opc_payment_wrap_6" class="opc_payment_wrap opc_payment_wrap_6"><input type="radio" autocomplete="off" data-dynamic-update="1" name="virtuemart_paymentmethod_id" onclick="javascript: Onepage.runPay('','',op_textinclship, op_currency, 0)" id="payment_id_6" value="6">
                                <label for="payment_id_6"><span class="vmpayment"><span class="vmCartPaymentLogo"><img align="middle" src="https://www.curry-moon.com//images/stories/virtuemart/payment/sberbank.png" alt="sberbank"></span>  <span class="vmpayment_name">Карта Сбербанка</span><span class="vmpayment_description vmpayment_description_6">Номер карты для оплаты отправляется на email</span></span></label>
                                </div></div><div style="clear: both;"><div id="opc_payment_wrap_8" class="opc_payment_wrap opc_payment_wrap_8"><input type="radio" autocomplete="off" data-dynamic-update="1" name="virtuemart_paymentmethod_id" onclick="javascript: Onepage.runPay('','',op_textinclship, op_currency, 0)" id="payment_id_8" value="8">
                                <label for="payment_id_8"><span class="vmpayment"><span class="vmCartPaymentLogo"><img align="middle" src="https://www.curry-moon.com//images/stories/virtuemart/payment/cash_rub.png" alt="cash_rub"></span>  <span class="vmpayment_name">Наличные (при самовывозе)</span></span></label>
                                </div></div><input type="hidden" name="opc_payment_method_id" id="opc_payment_method_id" value="payment_id_3"><div style="display: none;"><input type="radio" value="0" name="virtuemart_paymentmethod_id" id="virtuemart_paymentmethod_id_0"></div></div><input type="hidden" name="7997f186caeb9f36c4c0c5020f6e6c0f" value="1"></div><div id="payment_extra_outside_basket"></div><input type="hidden" name="7997f186caeb9f36c4c0c5020f6e6c0f" value="1"><br style="clear: both;">

                                <br style="clear: both;">
                                <!-- end shipping methodd -->
                            </div>	  

                        </div>
                        <!-- end payment method -->
                    </div>

                    <div class="dob3" id="dob3" style="min-height: 520px;">

                        <div class="op_inner">

                            <!-- customer note box -->
                            <!-- end of customer note -->

                            <h4>4. Подтвердить заказ</h4>
                            
                            <div>
                                <div id="totalam">
                                <div id="tt_order_subtotal_div" style="display: block;">
                                    <span id="tt_order_subtotal_txt" class="bottom_totals_txt">Промежуточный итог</span>
                                    <span id="tt_order_subtotal" class="bottom_totals">{$basket->basketSum} <span class="currency_format">руб</span></span>
                                <br class="op_clear">
                                </div>
                                <div id="tt_order_payment_discount_before_div">
                                    <span id="tt_order_payment_discount_before_txt" class="bottom_totals_txt"></span>
                                    <span class="bottom_totals" id="tt_order_payment_discount_before"></span><br class="op_clear">
                                </div>
                                <div id="tt_order_discount_before_div" style="display: none;">
                                    <span id="tt_order_discount_before_txt" class="bottom_totals_txt"></span>
                                    <span id="tt_order_discount_before" class="bottom_totals"></span><br class="op_clear">
                                </div>
                                <div id="tt_shipping_rate_div" style="display: block;"><span id="tt_shipping_rate_txt" class="bottom_totals_txt">Цена доставки</span><span id="tt_shipping_rate" class="bottom_totals">0 <span class="currency_format">руб</span></span><br class="op_clear"></div>
                                <div id="tt_shipping_tax_div" style="display: none;"><span id="tt_shipping_tax_txt" class="bottom_totals_txt"></span><span id="tt_shipping_tax" class="bottom_totals"></span><br class="op_clear"></div>
                                <div id="tt_tax_total_0_div" style="display: none;"><span id="tt_tax_total_0_txt" class="bottom_totals_txt"></span><span id="tt_tax_total_0" class="bottom_totals"></span><br class="op_clear"></div>
                                <div id="tt_tax_total_1_div" style="display: none;"><span id="tt_tax_total_1_txt" class="bottom_totals_txt"></span><span id="tt_tax_total_1" class="bottom_totals"></span><br class="op_clear"></div>
                                <div id="tt_tax_total_2_div" style="display: none;"><span id="tt_tax_total_2_txt" class="bottom_totals_txt"></span><span id="tt_tax_total_2" class="bottom_totals"></span><br class="op_clear"></div>
                                <div id="tt_tax_total_3_div" style="display: none;"><span id="tt_tax_total_3_txt" class="bottom_totals_txt"></span><span id="tt_tax_total_3" class="bottom_totals"></span><br class="op_clear"></div>
                                <div id="tt_tax_total_4_div" style="display: none;"><span id="tt_tax_total_4_txt" class="bottom_totals_txt"></span><span id="tt_tax_total_4" class="bottom_totals"></span><br class="op_clear"></div>
                                <div id="tt_order_payment_discount_after_div" style="display: none;"><span id="tt_order_payment_discount_after_txt" class="bottom_totals_txt"></span><span id="tt_order_payment_discount_after" class="bottom_totals"></span><br class="op_clear"></div>
                                <div id="tt_order_discount_after_div" style="display: none;"><span id="tt_order_discount_after_txt" class="bottom_totals_txt">Купон на скидку<span id="tt_order_discount_after_txt_code"></span></span><span id="tt_order_discount_after" class="bottom_totals"></span><br class="op_clear"></div>
                                <div id="tt_genericwrapper_bottom" class="dynamic_lines_bottom" style="display: none;"><span class="bottom_totals_txt dynamic_col1_bottom"></span><span class="bottom_totals dynamic_col2_bottom"></span><br class="op_clear"></div>
                                <div id="tt_total_div"><span id="tt_total_txt" class="bottom_totals_txt">Итого</span><span id="tt_total" class="bottom_totals">286671540 <span class="currency_format">руб</span></span><br class="op_clear"></div>
                                </div>
                                <div class="op_hr">&nbsp;</div>
                            </div>

                            <div style="width: 100%; float: left;">
                                <span id="customer_note_input" class="">
                                    <label for="customer_note_field">Комментарии к заказу:</label>
                                    <textarea rows="3" cols="30" name="customer_comment" id="customer_note_field"></textarea>
                                </span>
                                <br style="clear: both;">
                            </div>
                            
                            <div id="rbsubmit" style="width: 100%; float: right;">
                                <!-- show total amount at the bottom of checkout and payment information, don't change ids as javascript will not find them and OPC will not function -->
                                <div id="onepage_info_above_button">
                                <div id="onepage_total_inc_sh">
                                </div>

                                <!-- content of next div will be changed by javascript, please don't change it's id -->

                                <!-- end of total amount and payment info -->
                                <!-- submit button -->


                                <!-- show TOS and checkbox before button -->
                                <div id="agreed_div" class="formLabel fullwidth" style="text-align: left;">
                                    <div class="left_checkbox">
                                        <input value="1" type="checkbox" id="agreed_field" name="tosAccepted" checked="checked" class="terms-of-service" required="required" autocomplete="off">
                                    </div>
                                    <div class="right_label">
                                        <label for="agreed_field">Я согласен с Условиями обслуживания<a target="_blank" rel="{ldelim}handler: 'iframe', size: {ldelim}x: 500, y: 400{rdelim}{rdelim}" class="opcmodal" href="https://www.curry-moon.com/index.php?nosef=1&amp;format=html&amp;option=com_virtuemart&amp;view=vendor&amp;layout=tos&amp;virtuemart_vendor_id=1&amp;tmpl=component&amp;Itemid=1064&amp;lang=ru" onclick="javascript: return Onepage.op_openlink(this); "><br>
                                        (Условия обслуживания)
                                        </a></label>
                                    </div>		
                                </div>
                                <div class="formField" id="agreed_input">
                                </div>

                                <!-- end show TOS and checkbox before button -->

                                <br style="clear: both;">
                                </div>
                                <!-- end of submit button -->
                            </div>

                            <div style="clear: both;"></div>

                        </div>

                        <div class="bottom_button">
                            <div id="payment_info"></div>
                            <button class="btn btn-success btn-block btn-large" type="submit" onclick="javascript:return Onepage.validateFormOnePage(event, this, true);" autocomplete="off"><span>Подтвердить заказ</span></button>
                        </div>

                    </div>
                    <!-- end of tricks -->

                </form>
                <!-- end of checkout form -->
                <!-- end of main onepage div, set to hidden and will reveal after javascript test -->

            </div>

            <div id="tracking_div"></div>

            <br style="end_br">

            <br style="clear: both; float: none;">
            <br style="clear: both; float: left;">
        </div>
    </div>
    
    <script> if (typeof sessMin == 'undefined') var sessMin = 15; </script>
    
    <form action="#" name="hidden_form">

        <div style="display: none;">
            <input type="text" name="fool" value="1" required="required" class="required hasTip" title="">
            <select class="vm-chzn-select " name="hidden">
            <option value="1">test</option>
            </select>
        </div>

        <input type="hidden" name="opc_min_pov" id="opc_min_pov" value="">

        <div style="display: none;">
            {literal}
            <a href="#" rel="{handler: 'iframe', size: {x: 500, y: 400}}" class="opcmodal">a</a>
            <a href="#" rel="{handler: 'iframe', size: {x: 500, y: 400}}" class="pfdmodal">a</a>
            {/literal}
        </div>

        <div style="display: none;"><select id="no_states" name="no_states"><option value="">-- Выберите --</option></select></div>

        <script type="text/javascript">
            var selected_bt_state = '';
            var selected_st_state = '';
        </script>
        
    </form>
    
    {literal}
    <script type="text/javascript">
        /* <![CDATA[ */   
        if (typeof Onepage != 'undefined')
        {
            Onepage.ga('Checkout Impression', 'Checkout General'); 
        }
        /* ]]> */
    </script>

    <script id="box_js" type="text/javascript">//<![CDATA[ 
        jQuery(document).ready(function($) {
            $('div#full-tos').hide();
            var con = $('div#full-tos').html();
            $('a#terms-of-service').click(function(event) {
            event.preventDefault();
            $.facebox( { div: '#full-tos' }, 'my-groovy-style');
            });
        }); //]]>
    </script>

</section>
{/literal}