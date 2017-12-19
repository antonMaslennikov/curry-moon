<section id="gkMainbody" style="font-size: 100%;">

    <div id="k2Container" class="itemListView">
        <div class="itemListCategoriesBlock"></div>
        
        <div class="itemList">
            <div id="itemListLeading">
               
                {foreach from=$posts item="p"}
               
                    <div class="itemContainer itemContainerLast" style="width:99.9%;">

                        <article class="itemView groupLeading">  		
                            <div class="itemBlock">	
                                <header>
                                    <h2>{$p.title}</h2>
                                </header>

                                <div class="itemBody nodate">  												
                                    <div class="itemIntroText"> 
                                        {$p.content}
                                    </div>
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