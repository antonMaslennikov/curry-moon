
<section id="gkMainbody" style="font-size: 100%;">
    <article itemscope="" itemtype="http://schema.org/Article" id="k2Container" class="itemView">  
        <meta itemprop="interactionCount" content="UserPageVisits:306">     
        
        <header>
            <h1 itemprop="name">{$post->title}</h1>
        </header>
        
        {if $post->image}
        <div class="itemImageBlock" itemscope="" itemtype="http://schema.org/ImageObject">
            <meta content="{$post->title}" itemprop="description">
            <img itemprop="contentUrl" src="{$post->image|pictureId2path}" alt={$post->title}" style="width:1280px; height:auto;">
        </div>
        {/if}
        
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
                {*
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
                *}
            </div>

            {* БЛОК С ШАРОЙ В СОЦ СЕТИ *}
            <script type="text/javascript">(function(w,doc) {
            if (!w.__utlWdgt ) {
                w.__utlWdgt = true;
                var d = doc, s = d.createElement('script'), g = 'getElementsByTagName';
                s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
                s.src = ('https:' == w.location.protocol ? 'https' : 'http')  + '://w.uptolike.com/widgets/v1/uptolike.js';
                var h=d[g]('body')[0];
                h.appendChild(s);
            }})(window,document);
            </script>
            
            <div data-mobile-view="true" data-share-size="30" data-like-text-enable="false" data-background-alpha="0.0" data-pid="1379675" data-mode="share" data-background-color="#ffffff" data-hover-effect="scale" data-share-shape="rectangle" data-share-counter-size="12" data-icon-color="#ffffff" data-mobile-sn-ids="fb.vk.tw.ok.gp." data-text-color="#000000" data-buttons-color="#FFFFFF" data-counter-background-color="#ffffff" data-share-counter-type="common" data-orientation="horizontal" data-following-enable="false" data-sn-ids="fb.vk.tw.ok.gp.ps." data-preview-mobile="false" data-selection-enable="false" data-exclude-show-more="true" data-share-style="1" data-counter-background-alpha="1.0" data-top-button="false" class="uptolike-buttons" ></div>

        </div>
    </article>

</section>