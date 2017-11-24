<div class="b-catalog_v2 moveSidebarToLeft patterns patternsMain">

	<div class="pageTitle table">
		
		<h1>{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}</h1>

		{*<img title="" alt="" class="bann" src="/images/patterns/small-banner-paterns.gif" width="427" height="42"/>*}
		
		<div class="b-filter_tsh {$PAGE->lang}">
			<a data-sort="new" href="/catalog/patterns{if $task =='patterns-sweatshirts'}-sweatshirts{/if}/{$Style->style_slug}/" title="{$L.GOOD_new}" rel="nofollow" class="new-filter {if !$orderBy || $orderBy == 'new'}active{/if}">{*Новое*}</a>
			<a data-sort="popular" href="/catalog/patterns{if $task =='patterns-sweatshirts'}-sweatshirts{/if}/{$Style->style_slug}/popular/" rel="nofollow" class="pop-filter {if $orderBy == 'popular'}active{/if}" title="{$L.GOOD_popular}">{*Популярное*}</a>
			<a data-sort="grade" href="/catalog/patterns{if $task =='patterns-sweatshirts'}-sweatshirts{/if}/{$Style->style_slug}/grade/" rel="nofollow" class="score-filter {if $orderBy == 'grade'}active{/if}" title="{$L.GOOD_rating}"></a>
		</div>
		
		<div style="clear:both"></div>
	</div>

	{if !$MobilePageVersion}
		<!-- Фильтр в левом сайдбаре -->
		{include file="catalog/list.sidebar.tpl"}
	{/if}

	<div class="catalog_goods_list">
		{if !$MobilePageVersion}
		<div class="kaketo">		
			{*<div class="tl">Как мы это делаем</div>*}
			<div class="left">
				<img title="Печать и пошив 3 рабочих дня. Доставка на 4-й день" alt="Печать и пошив 3 рабочих дня. Доставка на 4-й день" class="" src="/images/patterns/kaketo2.gif" width="376" height="117">
			</div>
			<div class="right">	
				<div class="video">	
					{if $USER->client->ismobiledevice == '1'}
						<iframe src="https://player.vimeo.com/video/113540583" width="295" height="166" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
					{else}
					 <object width="295" height="166">
						<param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id=113540583">
						<param name="wmode" value="window">
						<param name="allowFullScreen" value="true">
						<embed src="http://vimeo.com/moogaloop.swf?clip_id=113540583" type="application/x-shockwave-flash" wmode="window" allowfullscreen="true" width="295" height="166">
					  </object>	
					 {/if}
				</div>
			</div>
			<div style="clear:both"></div>
			{*<div class="text">
				<span>Cостав ткани: футболка 30% хлопка 70 % полиэстер, свитшот 100 % полиэстер футер с начёсом</span><br/>
				<span>Метод печати: 10-цветная сублимационная печать на ткани</span>
			</div>*}
		</div>	
		{/if}
		<div class="list_wrap">		
			<ul>
				{foreach from=$pics item="g"}
				
				<li class="m13 {if $g.hidden && $g.user_id != $USER->id}hidden{/if}">
					<a rel="nofollow" class="item" title="{$good.good_name}" href="/catalog/{$g.user_login}/{$g.good_id}/{$g.style_slug}/">
						<span class="list-img-wrap">				
							<img title="" alt="{$g.good_name}" src="{$g.picture_path}" width="382" height="391" class="pattern-front">
							<img title="" alt="{$g.good_name}" src="{if $g.back}{$g.back}{else}/ajax/generatePreview/?good_id={$g.good_id}&style_id={$g.style_id}&width=382&side=back{/if}" width="382" height="391" class="pattern-back" style="display: none">
						</span>
						{*<span class="pAx" title="70% полиэстер"></span>
						<span class="pAx xlopok" title="30% хлопок"></span>*}
					</a>
					<div class="item">						
						<a rel="nofollow" class="rotate" href="#"></a>
						{*<a rel="nofollow" class="zoom thickbox" href="/basket/zoom/{$g.good_id}/{$g.good_stock_id}/front/?height=530&width=510"></a>*}
						{if $USER->authorized && ($USER->id == $g.user_id || $USER->id == 27278 || $USER->id == 6199 || $USER->id == 52426 || $USER->id == 105091)}
							<a class="btn_editing" href="/senddrawing.pattern/{$g.good_id}/"></a>
						{/if}
						
						<!--noindex-->
						<span class="price">{$g.price}&nbsp;руб.</span>
						<!--/noindex-->	
						
						<!--noindex-->	
						<a rel="nofollow" href="/catalog/{$g.cat_slug}/{$g.style_slug}/" class="title-cat" title="{$g.style_name}">{$g.style_name}</a>
						<!--/noindex-->	
						
						{*
						<a href="/catalog/{$g.user_login}/{$g.good_id}/category,{$g.cat_slug};sex,{$g.style_sex};color,{$g.style_color};size,{$g.size_id}/" class="title" title="{$g.good_name}">{$g.good_name}</a>
						<!--noindex-->
						<a href="/catalog/{$g.user_login}/" rel="nofollow" class="mini-avatar" title="{$g.user_login}">{$g.user_avatar}</a>
						<span class="author">							
							<a rel="nofollow" title="{$g.user_login}" href="/profile/{$g.user_id}/">{$g.user_login}</a>{$g.user_designer_level}
						</span>
						*}
						{*
						{if $g.city_name}					
							<div class="autor-city">{$g.city_name}</div>
						{/if}
						*}
						<!--/noindex-->						
						<div style="clear:both"></div>
					</div>
					{if $USER->id == 27278 || $USER->id == 6199 || $USER->id == 105091}
						<div class="r-m12-menu">
							<a href="#" title="" rel="nofollow" class="edittags" _id="{$g.good_id}">теги</a>
							<span>|</span>
							<a href="#" title="" class="hideDesign" _id="{$g.good_id}" _style="{$Style->id}" rel="nofollow">{if $g.hidden}показать{else}скрыть{/if}</a>
							<span>|</span>
							<a href="#" title="" class="disableDesign" _id="{$g.good_id}"_style="{$Style->id}" _category="{$filters.category}" rel="nofollow">{if $g.hidden}вкл.{else}выкл.{/if}</a>
							{if $g.good_status == 'archived'}
							<span>|</span>
							<a href="#" title="" class="promote2" _id="{$g.good_id}" _to="pretendent" rel="nofollow">победитель</a>
							{/if}
							{if $g.good_status == 'pretendent'}
							<span>|</span>
							<a href="#" title="" class="promote2" _id="{$g.good_id}" _to="archived" rel="nofollow">архив</a>
							{/if}
							<span>|</span>
							<a href="/ajax/setsex/" title="" class="setsex {if $g.good_sex == 'male' || $g.good_sex_alt == 'male'}sex-active{/if}" data-sex="male" _id="{$g.good_id}" rel="nofollow">муж.</a>
							<span>|</span>
							<a href="/ajax/setsex/" title="" class="setsex {if $g.good_sex == 'female' || $g.good_sex_alt == 'female'}sex-active{/if}" data-sex="female" _id="{$g.good_id}" rel="nofollow">жен.</a>
							<span>|</span>
							<a href="/ajax/setsex/" title="" class="setsex {if $g.good_sex == 'kids' || $g.good_sex_alt == 'kids'}sex-active{/if}" data-sex="kids" _id="{$g.good_id}" rel="nofollow">дет.</a>
						</div>
					{/if}
				</li>
			
				{/foreach}
					
			</ul>			
				
			<div class="archived-catalog">
				<a href="/catalog/patterns/full-printed-t-shirt-male/" rel="nofollow">Каталог мужских</a>
				&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="/catalog/patterns/full-printed-t-shirt-female/" rel="nofollow">Каталог женских</a>	
			</div>
			
		</div>		
	</div>
	
	{if !$user_id}
		<!-- Кнопка ВВерх -->
		<div id="button_toTop"><div>{$L.SHOPWINDOW_top}</div></div>
	{/if}
</div>


{if !$user_id}
	<br clear="all" />
	{include file="catalog/list.seotexts.tpl"}
{/if}
