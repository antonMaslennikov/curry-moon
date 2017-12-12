
<section id="gkMainbody" style="font-size: 100%;">
    <article itemscope="" itemtype="http://schema.org/Article" id="k2Container" class="itemView">  
        <meta itemprop="interactionCount" content="UserPageVisits:306">     
        
        <header>
            <h1 itemprop="name">{$post->title}</h1>
        </header>
        
        <div class="itemImageBlock" itemscope="" itemtype="http://schema.org/ImageObject">
            <meta content="{$post->title}" itemprop="description">
            <img itemprop="contentUrl" src="{$post->image|pictureId2path}" alt={$post->title}" style="width:1280px; height:auto;">
        </div>

        <div itemprop="articleBody" class="itemBody"> 
            <div class="richsnippetsvote" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
                <meta itemprop="ratingValue" content="5.0">
                <meta itemprop="bestRating" content="5">
                <meta itemprop="ratingCount" content="2"> 
            </div>

            <style>
            .richsnippetsvote{
                display:none;
            }
            </style>                                                             

            <div class="itemIntroText">  </div>
            <div class="itemFullText"> 
                {$post->content}
            </div>
            <div class="itemLinks">
                <div class="itemTagsBlock"> 
                    <span>Теги</span>
                    <ul class="itemTags">
                        {foreach from=$post->tags item="t"}
                        <li itemprop="keywords"> <a href="/ru/tegi/{$t.slug}">{$t.name}</a> </li>
                        {/foreach}
                    </ul>
                </div>
                <div class="itemRatingBlock"> <span>Оцените материал</span>
                    <div class="itemRatingForm">
                        <ul class="itemRatingList">
                            <li class="itemCurrentRating" id="itemCurrentRating374" style="width:100%;"></li>
                            <li> <a href="#" rel="374" title="1 звезда из 5" class="one-star">1</a> </li>
                            <li> <a href="#" rel="374" title="2 звезды из 5" class="two-stars">2</a> </li>
                            <li> <a href="#" rel="374" title="3 звезды из 5" class="three-stars">3</a> </li>
                            <li> <a href="#" rel="374" title="4 звезды из 5" class="four-stars">4</a> </li>
                            <li> <a href="#" rel="374" title="5 звезд из 5" class="five-stars">5</a> </li>
                        </ul>
                        <div id="itemRatingLog374" class="itemRatingLog"> (2 голосов) </div>
                    </div>
                </div>
            </div>

            {* БЛОК С ШАРОЙ В СОЦ СЕТИ *}
            

        </div>
    </article>

</section>