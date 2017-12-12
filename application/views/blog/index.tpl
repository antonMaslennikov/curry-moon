<section id="gkMainbody" style="font-size: 100%;">

    <div id="k2Container" class="itemListView">
        <div class="itemListCategoriesBlock">
        </div>
        <div class="itemList">
            <div id="itemListLeading">
               
                {foreach from=$posts item="p"}
                
                <div class="itemContainer itemContainerLast" style="width:99.9%;">

                    <article class="itemView groupLeading">
                        <div class="itemBlock">
                        <header>
                        <h2>
                            <a href="/ru/blog/{$p.slug}">{$p.title}</a>
                        </h2>
                        </header>

                        <div class="itemImageBlock"> 
                            <a class="itemImage" href="/ru/blog/{$p.slug}" title="{$p.title}"> 
                                <img src="{$p.picture_path}" alt="{$p.title}" style="width:1280px; height:auto;">
                            </a> 
                        </div>

                        <div class="itemBody"> 
                            <div class="richsnippetsvote" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
                                <meta itemprop="ratingValue" content="5.0">
                                <meta itemprop="bestRating" content="5">
                                <meta itemprop="ratingCount" content="2"> 
                            </div>
                           
                            <div class="itemIntroText">  </div>

                            <a class="button" href="/ru/blog/{$p.slug}"> Подробнее ... </a>
                        </div>


                        </div>
                    </article>
                </div>
                <div class="clr"></div>

                {/foreach}
           
            </div>
        </div>
    </div>

</section>


<style>
.richsnippetsvote{
display:none;
}
</style>