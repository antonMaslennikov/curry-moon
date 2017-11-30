<div id="vmMainPageOPC">
    <div class="opc_unlogged_wrapper" id="opc_unlogged_wrapper">
        <div id="onepage_main_div">
            <form action="/ru/users/registration" method="post" id="adminForm" name="adminForm" class="form-ivalidate" autocomplete="on">
            
                <div id="register_box" style="width: 100%; clear: both;">
                    <div id="register_head" class="bandBoxStyle">Регистрация</div>
                </div>

                <div id="billTo_box" style="width: 100%; clear: both;">
                    
                    <div id="billTo_head" class="bandBoxStyle">Данные покупателя</div>
                    
                    <div id="billTo_container">
                        <div style="width:100%;"> 
                           <div class="formField email" id="email_input" title="Эл.почта *">
                                <div test="test"><input placeholder="Эл.почта *" required="required" autocomplete="off" type="email" id="email_field" name="email" size="30" value="{$smarty.post.email}" class="required email " disabledmaxlength="100"></div>
                            </div>

                           <div class="formField text" id="last_name_input" title="Фамилия *">
                               <div><input  placeholder="Фамилия *" required="required" type="text" id="last_name_field" name="last_name" size="30" value="{$smarty.post.last_name}" class="required" disabledmaxlength="32" autocomplete="off"></div>
                           </div>

                           <div class="formField text" id="first_name_input" title="Имя *">
                               <div><input  placeholder="Имя *" required="required" type="text" id="first_name_field" name="first_name" size="30" value="{$smarty.post.first_name}" class="required" disabledmaxlength="32" autocomplete="off"></div>
                           </div>

                           <div class="formField text" id="middle_name_input" title="Отчество">
                               <span id="middle_name_div" style="" class="formLabel "></span><div><input  placeholder="Отчество" type="text" id="middle_name_field" name="middle_name" size="30" value="{$smarty.post.middle_name}" disabledmaxlength="32" autocomplete="off" class=""></div>
                           </div>

                           <div class="formField text" id="address_1_input" title="Адрес *">
                               <div><input  placeholder="Адрес *" required="required" type="text" id="address_1_field" name="address_1" size="30" value="{$smarty.post.address_1}" class="required" disabledmaxlength="64" autocomplete="off"></div>
                           </div>

                           <div class="formField text" id="zip_input" title="Почтовый индекс *">
                               <div><input  placeholder="Почтовый индекс *" required="required" type="text" id="zip_field" name="zip" size="30" value="{$smarty.post.zip}" class="required" disabledmaxlength="32" autocomplete="off"></div>
                           </div>

                           <div class="formField text" id="city_input" title="Город *">
                               <div><input  placeholder="Город *" required="required" type="text" id="city_field" name="city" size="30" value="{$smarty.post.city}" class="required" disabledmaxlength="32" autocomplete="off"></div>
                           </div>

                           <div class="formField select" id="country_id_input" title="Страна *">
                               <span id="country_id_div" style="" class="formLabel ">Missing</span><label class="label_selects" style="clear: both; " for="country_id_field">Страна *</label>
                               <div>
                                   <select autocomplete="off" name="country_id" class=" required" required="required">
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
                                <div><input  placeholder="Телефон" type="text" id="phone_1_field" name="phone_1" size="30" value="{$smarty.post.phone_1}" disabledmaxlength="32" autocomplete="off"></div>
                            </div>
                        </div>
                     </div>
                 </div>
                 
                 {if $error}
                 <div class="registration-error">Ошибка: {$error}</div>
                 {/if}
                 
                 <div class="confirmbtn_button-wrapper">
                     <input id="confirmbtn_button" name="submit" type="submit" class="submitbtn bandBoxRedStyle" autocomplete="off">
                 </div>
 
                <input type="hidden" name="csrf_token" value="{$csrf_token}" />
            </form>
        </div>
    </div>
</div>


{literal}
<style>
    .registration-error {
        margin:20px 0;
        padding:20px;
        background-color: antiquewhite;
        color: red;
    }
    
    .confirmbtn_button-wrapper {
        margin-top:20px;
    }
</style>
{/literal}