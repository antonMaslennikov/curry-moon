<section id="gkMainbody" style="font-size: 100%;">

    {if $PAGE->h1}
    <section id="k2Container" class="genericView">
    {else}
    <div id="k2Container" class="itemListView">
    {/if}
       
        {if $PAGE->h1}
        <header>
			<h1>{$PAGE->h1}</h1>
		</header>
        {/if}
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
                            <a class="itemImage" href="/ru/{if $p.category == 1}aktcii{elseif $p.category == 2}lookbook{else}blog{/if}/{$p.slug}" title="{$p.title}"> 
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

                            <a class="button" href="/ru/{if $p.category == 1}aktcii{elseif $p.category == 2}lookbook{else}blog{/if}/{$p.slug}"> Подробнее ... </a>
                        </div>


                        </div>
                    </article>
                </div>
                <div class="clr"></div>

                {/foreach}
           
            </div>
        </div>
    {if $PAGE->h1}
    </section>
    {else}
    </div>
    {/if}
    
    {include file="pagination.tpl"}

</section>


<style>
.richsnippetsvote{
display:none;
}
</style>