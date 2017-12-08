<header id="gkHeader">
    <div>
        <div class="gkPage" id="gkHeaderNav">                    	

            <a href="/" id="gkLogo">
                <img src="/public/images/logo/logo_curry-moon.png" alt="Curry Moon" />
                <span class="gkLogoSlogan">СТИЛЬНЫЕ АКСЕССУАРЫ И УКРАШЕНИЯ</span>
            </a>

            <div id="gkMobileMenu" class="gkPage"> 
                <i id="mobile-menu-toggler" class="fa fa-bars"></i>
                <select id="mobileMenu" onChange="window.location.href=this.value;" class="chzn-done">
                    <option selected="selected"  value="/">Главная</option>
                    <option  value="/ru/shop">Каталог</option>
                    <option  value="/ru/shop/scarves">&mdash; Шарфы</option>
                    <option  value="/ru/shop/jewellery">&mdash; Украшения</option>
                    <option  value="/ru/shop/jewellery/silver">&mdash;&mdash; Серебро</option>
                    <option  value="/ru/shop/jewellery/bijouterie">&mdash;&mdash; Бижутерия</option>
                    <option  value="/ru/shop/bags">&mdash; Сумки</option>
                    <option  value="/ru/shop/tippets">&mdash; Палантины</option>
                    <option  value="/ru/shop/clothing">&mdash; Одежда</option>
                    <option  value="/ru/aktcii">Акции</option>
                    <option  value="/ru/blog">Блог</option>
                    <option  value="/ru/lookbook">LookBook</option>
                    <option  value="/ru/payment">Оплата</option>
                    <option  value="/ru/delivery">Доставка</option>
                    <option  value="/ru/contact-us">Контакты</option>			       
                </select>
            </div>

            <div id="gkTopNav">
                <div id="gkUserArea" class=" uk-hidden-small">
                    Здравствуйте, 
                    {if $USER->authorized}
                        {$USER->user_name}, вы можете <a href="/ru/users/logout">выйти</a>
                    {else}
                        вы можете <a href="/ru/users/login" id="gkLogin">войти</a> или создать <a href="/ru/users/registration">аккаунт</a>
                    {/if}
                </div>

                <div id="gkTopMenu" class="uk-hidden-small">
                    <div class="TopPhone uk-hidden-small"><i class="fa fa-phone" aria-hidden="true"></i> <a href="tel:+79164162063">+7 (916) 416-20-63</a><br/><i class="fa fa-whatsapp" aria-hidden="true"></i> <a href="tel:+79859740837">+7 (985) 974-08-37</a></div>

                    <ul class="menu">
                        <li class="item-1064"><a href="/ru/cart" class="gk-cart uk-width-1-1"><img src="/public/images/icons/cart16.png" alt="Корзина" /><span class="image-title">Корзина</span></a></li>
                    </ul>
                </div>

                <div id="gkTopMenu" class="phone uk-visible-small">

                    <div class="uk-margin-small-top">&nbsp;</div>

                    <ul class="menu">
                        <li class="item-1064"><a href="/ru/cart" class="gk-cart uk-width-1-1"><img src="/public/images/icons/cart16.png" alt="Корзина" /><span class="image-title">Корзина</span></a></li>
                    </ul>

                </div>

                <div class="uk-panel uk-visible-small uk-margin-large-top uk-margin-large-left"><i class="fa fa-phone" aria-hidden="true"></i> <a href="tel:+79164162063">+7 (916) 416-20-63</a><br/><i class="fa fa-whatsapp" aria-hidden="true"></i> <a href="tel:+79859740837">+7 (985) 974-08-37</a></div>
            </div>

            <div id="gkMainMenu" class="gkPage gkMenuClassic">
                <nav id="gkExtraMenu" class="gkMenu">
                    <ul class="gkmenu level0">
                        <li class="first active">
                           <a href="/"  class=" first active" id="menu640" title=" Home Menu Item" >Главная</a>
                        </li>
                        <li class="haschild">
                           <a href="/ru/shop" class="haschild" id="menu938">Каталог</a>
                           <div class="childcontent">
                               <div class="childcontent-inner">
                                   <div class="gkcol gkcol1  first">
                                       <ul class="gkmenu level1">
                                           <li class="first">
                                               <a href="/ru/shop/scarves"  class=" first" id="menu1037"  >Шарфы</a>
                                           </li>
                                           <li class="haschild">
                                               <a href="/ru/shop/jewellery"  class=" haschild" id="menu1038"  >Украшения</a>
                                               <div class="childcontent">
                                                   <div class="childcontent-inner">
                                                       <div class="gkcol gkcol1  first">
                                                          <ul class="gkmenu level2">
                                                               <li  class="first"><a href="/ru/shop/jewellery/silver"  class=" first" id="menu1039"  >Серебро</a></li>
                                                               <li  class="last"><a href="/ru/shop/jewellery/bijouterie"  class=" last" id="menu1040"  >Бижутерия</a></li>
                                                           </ul>
                                                       </div>
                                                   </div>
                                               </div>
                                            </li>
                                            <li ><a href="/ru/shop/bags"  id="menu1041"  >Сумки</a></li>
                                            <li ><a href="/ru/shop/tippets"  id="menu1042"  >Палантины</a></li>
                                            <li  class="last"><a href="/ru/shop/clothing"  class=" last" id="menu1043"  >Одежда</a></li>
                                        </ul>
                                   </div>
                               </div>
                           </div>
                        </li>
                        <li><a href="/ru/aktcii"  id="menu1126">Акции</a></li>
                        <li><a href="/ru/blog"  id="menu880">Блог</a></li>
                        <li><a href="/ru/lookbook"  id="menu1045"  >LookBook</a></li>
                        <li><a href="/ru/payment"  id="menu1032"  >Оплата</a></li>
                        <li><a href="/ru/delivery"  id="menu1033"  >Доставка</a></li>
                        <li class="last"><a href="/ru/contact-us"  class=" last" id="menu1046">Контакты</a></li>
                    </ul>
                </nav>                 
            </div>
        </div>
    </div>
</header>