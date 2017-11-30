<div id="vmMainPageOPC">
    <div class="opc_unlogged_wrapper" id="opc_unlogged_wrapper">
        <div id="onepage_main_div">
            <form action="/index.php?option=com_onepage&amp;view=opc&amp;controller=opc&amp;task=opcregister&amp;nosef=1&amp;lang=ru" method="post" id="adminForm" name="adminForm" class="form-ivalidate" autocomplete="on">
            
                <div style="display: none;"><input type="submit" onclick="return Onepage.formSubmit(event, this);" name="hidden_submit" value="hidden_submit"></div>

                    <div id="register_box" style="width: 100%; clear: both;">
                        <div id="register_head" class="bandBoxStyle">Регистрация</div>
                    </div>

                    <div id="billTo_box" style="width: 100%; clear: both;">
                        <div id="billTo_head" class="bandBoxStyle">Данные покупателя</div>
                        <div id="billTo_container">
                            <div style="width:100%;"> 
                               <div class="formField email" id="email_input" title="Эл.почта *">
                                    <span id="email_div" style="" class="formLabel ">Missing</span>
                                    <div test="test"><input onfocus="inputclear(this)" placeholder="Эл.почта *" autocomplete="off" type="email" id="email_field" name="email" size="30" value="" class="required email " disabledmaxlength="100"> <input type="hidden" id="saved_email_field" name="savedtitle" value="Эл.почта *"></div>
                                </div>

                               <div class="formField text" id="last_name_input" title="Фамилия *">
                                   <span id="last_name_div" style="" class="formLabel ">Missing</span>
                                   <div><input onfocus="inputclear(this)" placeholder="Фамилия *" type="text" id="last_name_field" name="last_name" size="30" value="" class="required" disabledmaxlength="32" autocomplete="off"> <input type="hidden" id="saved_last_name_field" name="savedtitle" value="Фамилия *"></div></div>

                               <div class="formField text" id="first_name_input" title="Имя *">
                                   <span id="first_name_div" style="" class="formLabel ">Missing</span>
                                   <div><input onfocus="inputclear(this)" placeholder="Имя *" type="text" id="first_name_field" name="first_name" size="30" value="" class="required" disabledmaxlength="32" autocomplete="off"> <input type="hidden" id="saved_first_name_field" name="savedtitle" value="Имя *"></div></div>

                               <div class="formField text" id="middle_name_input" title="Отчество">
                                   <span id="middle_name_div" style="" class="formLabel "></span><div><input onfocus="inputclear(this)" placeholder="Отчество" type="text" id="middle_name_field" name="middle_name" size="30" value="" disabledmaxlength="32" autocomplete="off" class=""> <input type="hidden" id="saved_middle_name_field" name="savedtitle" value="Отчество"></div></div>

                               <div class="formField text" id="address_1_input" title="Адрес *">
                                   <span id="address_1_div" style="" class="formLabel ">Missing</span>
                                   <div><input onfocus="inputclear(this)" placeholder="Адрес *" type="text" onblur="javascript:Onepage.op_runSS(this);" id="address_1_field" name="address_1" size="30" value="" class="required" disabledmaxlength="64" autocomplete="off"> <input type="hidden" id="saved_address_1_field" name="savedtitle" value="Адрес *"></div></div>

                               <div class="formField text" id="zip_input" title="Почтовый индекс *">
                                   <span id="zip_div" style="" class="formLabel ">Missing</span>
                                   <div><input onfocus="inputclear(this)" placeholder="Почтовый индекс *" type="text" onblur="javascript:Onepage.op_runSS(this);" id="zip_field" name="zip" size="30" value="" class="required" disabledmaxlength="32" autocomplete="off"> <input type="hidden" id="saved_zip_field" name="savedtitle" value="Почтовый индекс *"></div></div>

                               <div class="formField text" id="city_input" title="Город *">
                                   <span id="city_div" style="" class="formLabel ">Missing</span>
                                   <div><input onfocus="inputclear(this)" placeholder="Город *" type="text" id="city_field" name="city" size="30" value="" class="required" disabledmaxlength="32" autocomplete="off"> <input type="hidden" id="saved_city_field" name="savedtitle" value="Город *"></div></div>

                               <div class="formField select" id="virtuemart_country_id_input" title="Страна *">
                                   <span id="virtuemart_country_id_div" style="" class="formLabel ">Missing</span><label class="label_selects" style="clear: both; " for="virtuemart_country_id_field">Страна *</label>
                                   <div>
                                       <select onchange="javascript: Onepage.op_validateCountryOp2('false', 'false', this);" id="virtuemart_country_id" autocomplete="off" name="virtuemart_country_id" class=" required">
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
                                     <span id="phone_1_div" style="" class="formLabel "></span><div><input onfocus="inputclear(this)" placeholder="Телефон" type="text" id="phone_1_field" name="phone_1" size="30" value="" disabledmaxlength="32" autocomplete="off" class=""> <input type="hidden" id="saved_phone_1_field" name="savedtitle" value="Телефон"></div>
                            </div>
                        </div>
                          
                        <div style="display:none;"></div>
                                      
                     </div>
                 </div>
                 
                 <div style="float: left; clear: both;">
                    <input id="confirmbtn_button" type="submit" class="submitbtn bandBoxRedStyle" autocomplete="off">
                 </div>
 
                <input type="hidden" name="csrf_token" value="{$csrf_token}" />
                <input type="hidden" name="opcregistration" value="1">
            </form>
        </div>
    </div>
</div>