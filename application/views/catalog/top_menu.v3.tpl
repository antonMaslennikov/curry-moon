<div class="b-header topbar-remove" style="z-index:1;">
	<div class="p-head" style="height:30px;">
		<div class="sub-menu">
			<!--a rel="nofollow" href="/catalog/sex,male/" class="sub_male {if !$category && $filters.sex == 'male'}active{/if}" h="shop" >Мужские</a-->
			<!--a rel="nofollow" href="/catalog/sex,female/" class="sub_female {if !$category && $filters.sex == 'female'}active{/if}" h="shop">Женские</a-->
			<a rel="nofollow" href="/catalog/category,futbolki/" class="sub_futbolki {if $filters.category == 'futbolki' && $filters.sex == 'male'}active{/if}" h="shop" style="min-width:153px;">Мужские&nbsp;футболки</a>
            <a rel="nofollow" href="/catalog/category,futbolki/female/" class="sub_futbolki {if $filters.category == 'futbolki' && $filters.sex == 'female'}active{/if}" h="shop" style="min-width:153px;">Женские&nbsp;футболки</a>
			
			<a href="/catalog/category,tolstovki/new/" class="sub_tolstovki {if $category == 'tolstovki'}active{/if}" h="shop" style="min-width:95px;">{$L.HEADER_menu_tolstovki}</a>
			{*			
			<a href="/catalog/category,sumki/" class="sub_sumki {if $category == 'sumki'}active{/if}" h="shop" style="min-width:76px;">Сумки</a>
			*}
            <a rel="nofollow" href="/catalog/category,mayki-alkogolichki/" class="sub_tshirt {if $category == 'mayki-alkogolichki'}active{/if}" h="shop" style="min-width:68px;">Майки</a>
			<!--a rel="nofollow" href="/catalog/last/sex,female;category,sumki/" class="sub_sumki {if $category == 'sumki'}active{/if}" h="shop">Сумки</a-->
            <a style="width:145px;" rel="nofollow" href="/catalog/auto/new/" class="sub_auto {if $task == 'auto' || $category == 'touchpads'}active{/if}" h="shop">Наклейки&nbsp;на&nbsp;авто</a>
			<a href="/catalog/cases/" class="sub_phones {if $category == 'phones' || $category == 'cases'}active{/if}" h="shop" style="min-width:101px;">Чехлы iPhone</a>
			
			<a rel="nofollow" href="/customize/" class="sub_constructor {if $module == 'customize'}active{/if}" h="shop" style="width: 90px;">Конструктор</a>
			<a rel="nofollow" href="/voting/competition/main/" class="sub_voting {if $module == 'voting'}active{/if}" h="community" style="width:120px;display:none">Голосование</a>
			<a rel="nofollow" href="/senddrawing.design/" class="sub_subddrawing {if $module == 'senddrawing.design'}active{/if}" h="community" style="display:none;{if $module == 'senddrawing.design'} font-weight:bold;{/if}">Прислать работу</a>
			<a rel="nofollow" href="/blog/" class="sub_blog {if $module == 'blog'}active{/if}" h="community" style="display:none">Блог</a>
			<a rel="nofollow" href="/gallery/" class="sub_gallery {if $module == 'gallery'}active{/if}" h="community" style="display:none">Галерея</a>

			{include file="top_menu_search.tpl"}
			
			<div style="clear:both;"></div>
		</div>
		<script type="text/javascript">
				{if $module == 'blog' || $module == 'voting' || $module == 'gallery' || $module == 'senddrawing.design'}
					$('.sub-menu a[h=shop]').hide();
					$('.sub-menu a[h=community]').show();
				{/if}
				
			/*$(document).ready(function(){
				$('.top-menu .top-menu-shop, .top-menu .top-menu-community').click(function(){
					$('.top-menu .top-menu-shop, .top-menu .top-menu-community').removeClass('active');
					$(this).addClass('active');
					var d = /top-menu-(\w+)/.exec(this.className);
					$('.sub-menu a').hide();
					$('.sub-menu a[h='+d[1]+']').show();
					return false;
				});
			});*/
		</script>
	</div>
	
	{* 3ий уровень меню - "хлебные крошки" *}
	{if $bc}
	<div class="b-title-url-path">
		<div class="url-path">
			<a href="/" rel="nofollow">Главная</a>&#8594;
			{foreach from=$bc item="i" name="bc_foreach"}
			
				{if !$smarty.foreach.bc_foreach.last}
					<a href="{$i.link}" rel="nofollow">{$i.caption}</a>
				{else}
					<h1>{$i.caption}</h1>
				{/if}
				
				{if !$smarty.foreach.bc_foreach.last}&rarr;{/if}
				
			{/foreach}
		</div>
	</div>
	
	{/if}
</div>