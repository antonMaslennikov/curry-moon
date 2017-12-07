<aside id="gkSidebar" class="gkOnlyOne">
<div>
    <div class="box menu light"><h3 class="header">Каталог</h3>
        <div class="content">
            <ul class="menu">

                {foreach from=$categorys item="c"}
                <li>
                    <div>
                        <a href="/ru/shop{$c.link}">{$c.title}</a>
                    </div>
                </li>
                {/foreach}
                
                {*
                <li class="active">
                    <div>
                        <a href="/ru/shop/jewellery">Украшения</a>	
                    </div>
                    <ul class="menu">
                        <li>
                            <div><a href="/ru/shop/jewellery/silver">Серебро</a></div>
                        </li>
                        <li>
                            <div><a href="/ru/shop/jewellery/bijouterie">Бижутерия</a></div>
                        </li>
                    </ul>
                </li>
                *}
            </ul>
        </div>
    </div>
</div>
</aside>