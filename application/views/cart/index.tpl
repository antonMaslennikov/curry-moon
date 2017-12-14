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
                            {foreachelse}
                            <tr>
                                <td colspan="20" style="text-align: center"><br />Ваша корзина пуста<br /><br /></td>
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
                
                {if $basket->basketGoods|count > 0}
                
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
                
                {/if}
                
            </div>

            {if $basket->basketGoods|count > 0}
            
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
                                        <input type="text" id="username_login" placeholder="Имя пользователя" name="username_login" value="" class="inputbox" size="20" autocomplete="off">				
                                        <input type="hidden" id="saved_username_login_field" name="savedtitle" value="Имя пользователя">			
                                    </div>

                                    <div class="formField">
                                        <input type="password" id="passwd_login" name="passwd" placeholder="Пароль" value="" class="inputbox" size="20" onkeypress="return Onepage.submitenter(this,event)" autocomplete="off">				
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
                                   <div class="address_field_value" style="">{$USER->user_email}</div>
                                </div>
                                <div style="clear: both;">
                                   <div class="address_field_name" style="">Фамилия / Имя</div>
                                   <div class="address_field_value" style="">{$USER->user_name}</div>
                                </div>
                                <div style="clear: both;">
                                   <div class="address_field_name" style="">Телефон</div>
                                   <div class="address_field_value" style="">{$USER->user_phone}</div>
                                </div>
                                <div style="clear: both;">
                                   <div class="address_field_name" style="">Адрес </div>
                                   <div class="address_field_value" style="">{$USER->user_address}</div>
                                </div>
                                <div style="clear: both;">
                                   <div class="address_field_name" style="">Почтовый индекс </div>
                                   <div class="address_field_value" style="">{$USER->user_zip}</div>
                                </div>
                                <div style="clear: both;">
                                   <div class="address_field_name" style="">Город </div>
                                   <div class="address_field_value" style="">{$USER->user_city_name}</div>
                                </div>
                                <div style="clear: both;">
                                   <div class="address_field_name" style="">Страна </div>
                                   <div class="address_field_value" style="">{$USER->user_country_name}</div>
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
                                   <input placeholder="Эл.почта *" autocomplete="off" type="email" id="email_field" name="user[user_email]" size="30" class="required email " disabledmaxlength="100" value="{$USER->user_email}{$smarty.post.user.user_email}"> 
                               </div>
                            </div>

                            <div class="formField text" id="last_name_input" title="ФИО *">
                                <div>
                                    <input placeholder="Фамилия *" type="text" id="last_name_field" name="user[user_name]" size="30" required class="required" disabledmaxlength="32" autocomplete="off" value="{$USER->user_name}{$smarty.post.user.user_name}"> 
                                </div>
                            </div>

                            <div class="formField text" id="address_1_input" title="Адрес *">
                                <div>
                                    <input placeholder="Адрес *" type="text" id="address_1_field" name="user[user_address]" size="30" required class="required" disabledmaxlength="64" autocomplete="off" value="{$USER->user_address}{$smarty.post.user.user_address}"> 
                                </div>
                            </div>

                            <div class="formField text" id="zip_input" title="Почтовый индекс *">
                                <div>
                                    <input placeholder="Почтовый индекс *" type="text" id="zip_field" name="user[user_zip]" size="30" value="{$USER->user_zip}{$smarty.post.user.user_zip}" class="required" disabledmaxlength="32" autocomplete="off"> 
                                </div>
                            </div>

                            <div class="formField text" id="city_input" title="Город *">
                                <div>
                                    <input placeholder="Город *" type="text" id="city_field" name="user[city_name]" size="30" required class="required" disabledmaxlength="32" autocomplete="off" value="{$USER->user_city_name}{$smarty.post.user.city_name}"> 
                                </div>
                            </div>

                            <div class="formField select" id="virtuemart_country_id_input" title="Страна *">
                                <label class="label_selects" style="clear: both; " for="country_id_field">Страна *</label>
                                <div>
                                    <select autocomplete="off" name="user[user_country_id]" class=" required" required>
                                        <option value="">-- Выберите --</option>
                                        <option value="675" {if $USER->user_country_id == 675 || $smarty.post.user.user_country_id == 675}selected="selected"{/if}>Азербайджан</option>
                                        <option value="685" {if $USER->user_country_id == 685 || $smarty.post.user.user_country_id == 685}selected="selected"{/if}>Армения</option>
                                        <option value="940" {if $USER->user_country_id == 940 || $smarty.post.user.user_country_id == 940}selected="selected"{/if}>Беларусь</option>
                                        <option value="759" {if $USER->user_country_id == 759 || $smarty.post.user.user_country_id == 759}selected="selected"{/if}>Казахстан</option>
                                        <option value="807" {if $USER->user_country_id == 807 || $smarty.post.user.user_country_id == 807}selected="selected"{/if}>Молдавская республика</option>
                                        <option value="838" {if $USER->user_country_id == 838 || $smarty.post.user.user_country_id == 838}selected="selected"{/if}>Российская федерация</option>
                                        <option value="865" {if $USER->user_country_id == 865 || $smarty.post.user.user_country_id == 865}selected="selected"{/if}>Таджикистан</option>
                                        <option value="876" {if $USER->user_country_id == 876 || $smarty.post.user.user_country_id == 876}selected="selected"{/if}>Туркменистан</option>
                                        <option value="879" {if $USER->user_country_id == 879 || $smarty.post.user.user_country_id == 879}selected="selected"{/if}>Узбекистан</option>
                                        <option value="880" {if $USER->user_country_id == 880 || $smarty.post.user.user_country_id == 880}selected="selected"{/if}>Украина</option>
                                    </select>
                                </div>
                            </div>

                            <div class="formField text" id="phone_1_input" title="Телефон">
                                <div>
                                    <input placeholder="Телефон" type="text" id="phone_1_field" name="user[user_phone]" size="30" disabledmaxlength="32" autocomplete="off" class="" value="{$USER->user_phone}{$smarty.post.user.user_phone}"> 
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

                                <h4>2. Способ доставки</h4>	

                                <!-- shipping methodd -->
                                {include file="cart/shipping.tpl"}
                                <br>
                                <!-- end shipping methodd -->
                            

                            <div id="payment_top_wrapper" style="display: block;">

                                <h4 class="payment_header">3. Способ оплаты </h4>

                                {include file="cart/payment.tpl"}

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
                                    <div id="tt_shipping_rate_div" style="display: block;">
                                        <span id="tt_shipping_rate_txt" class="bottom_totals_txt">Цена доставки</span>
                                        <span id="tt_shipping_rate" class="bottom_totals">0 <span class="currency_format">руб</span></span>
                                        <br class="op_clear">
                                    </div>
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
                                        <label for="agreed_field">Я согласен с Условиями обслуживания<a target="_blank" rel="{ldelim}handler: 'iframe', size: {ldelim}x: 500, y: 400{rdelim}{rdelim}" class="opcmodal" id="terms-of-service" href="/ru/cart/terms-of-service"><br>
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
                            <button class="btn btn-success btn-block btn-large" type="submit" autocomplete="off"><span>Подтвердить заказ</span></button>
                        </div>

                    </div>
                    <!-- end of tricks -->

                </form>
                <!-- end of checkout form -->
                <!-- end of main onepage div, set to hidden and will reveal after javascript test -->

            </div>

            {/if}

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
    <script id="box_js" type="text/javascript">//<![CDATA[ 
        jQuery(document).ready(function($) {
            jQuery('div#full-tos').hide();
            var con = jQuery('div#full-tos').html();
            
            jQuery('a#terms-of-service').click(function(event) {
                jQuery.facebox({
                    ajax: $(this).attr('href'),
                    rev: 'iframe|300|300'
                });
                return false;
            });
            
            jQuery('select[name=country_id]').change(function(){
               
                if (jQuery(this).val() == 838) {
                    jQuery('#opc_ship_wrap_6').show();
                } else {
                    jQuery('#opc_ship_wrap_6, #opc_payment_wrap_8').hide();
                    
                    if (jQuery('#shipment_id_6').attr('checked'))
                        jQuery('input[name=shipmentmethod_id]:visible').eq(0).attr('checked', 'checked');
                    
                    if (jQuery('#payment_id_8').attr('checked'))
                        jQuery('input[name=paymentmethod_id]:visible').eq(0).attr('checked', 'checked');
                }
                
            });
            
            jQuery('input[name=shipmentmethod_id]').change(function() {
                
                if (jQuery(this).val() == 'user') {
                    jQuery('#opc_payment_wrap_8').show();
                } else {
                    jQuery('#opc_payment_wrap_8').hide();
                    if (jQuery('#payment_id_8').attr('checked'))
                        jQuery('input[name=paymentmethod_id]:visible').eq(0).attr('checked', 'checked');
                }
                
            });
        }); //]]>
    </script>
    {/literal}
    
</section>