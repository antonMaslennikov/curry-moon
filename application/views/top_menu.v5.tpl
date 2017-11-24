<div class="b-header topbar-remove top_menu_main" style="z-index:98">	
	<div class="p-head">
		
		{if $MobilePageVersion}<div id="wallWrapmain"></div>{/if}
		
		<div class="sub-menu-k">			
			
			{if $module == 'foto-na-futbolku'}
				<div id="fakeLiSubMenu">Конструктор - цифровая печать на футболках</div>
			{/if}
			
			<ul><!--noindex-->
				{if !$MobilePageVersion && ($module == 'blog' || $module == 'voting' || $module == 'gallery' || $module == 'people' || $module == 'senddrawing.design' || $module == 'senddrawing.pattern' || $module == 'senddrawing.pro' || $module == 'senddrawing.sticker1color' || $module == 'senddrawing.couple' || ($module == 'tag' && $type == 'posts'))}
					
					<li h="community" class="l1 {if (($module == 'catalog' || $module == 'catalog.dev') && $user && !$good) || $module == 'people'}active{/if}">
						<a {if $PAGE->module != "people"}{/if} href="{if $PAGE->url != "/people/designers/"}/people/designers/{else}#{/if}" title="{$L.HEADER_menu_people}">{$L.HEADER_menu_people}</a>
					</li>
					<li h="community" class="l1 {if $module == 'voting'}active{/if}">
						<a {if $PAGE->module != "voting"}{/if} href="{if $PAGE->url != "/voting/competition/main/"}/voting/competition/main/{else}#{/if}" title="{$L.HEADER_menu_voting}">{$L.HEADER_menu_voting}</a>
					</li>
					<li h="community" class="l1 {if $module == 'senddrawing.design'}active{/if}">
						<a {if $PAGE->module != "senddrawing.design"}{/if} href="{if $PAGE->url != "/senddrawing.design/"}/senddrawing.design/{else}#{/if}"  style="{if $module == 'senddrawing.design'} font-weight:bold;{/if}" title="{$L.HEADER_menu_senddrawing}">{$L.HEADER_menu_senddrawing}</a>
					</li>
					<li h="community" class="l1 {if $module == 'blog' || ($module == 'tag' && $type == 'posts')}active{/if}">
						<a {if $PAGE->module != "blog"}{/if} href="{if $PAGE->url != "/blog/"}/blog/{else}#{/if}" title="{$L.HEADER_menu_blog}">{$L.HEADER_menu_blog}</a>
					</li>
					<li h="community"  class="l1 {if $module == 'gallery'}active{/if}">
						<a {if $PAGE->module != "gallery"}{/if} href="{if $PAGE->url != "/gallery/"}/gallery/{else}#{/if}" title="{$L.HEADER_menu_gallery}">{$L.HEADER_menu_gallery}</a>	
					</li>
									
				{else}
			
					{if $MobilePageVersion}
						<li class="l1 {if $module == 'customize' || $module == 'foto-na-futbolku'}active{/if}">
							<a href="{if $PAGE->url != "/customize/"}/customize/{else}#{/if}" title="{$L.HEADER_menu_customize}">{$L.HEADER_menu_customize}</a>
						</li>							
						<li class="l1 {if $module == 'voting'}active{/if} arr">
							<a href="/voting/last/" title="{$L.HEADER_menu_voting}">{$L.HEADER_menu_voting}</a>
							<ul>
								<li><a href="/voting/last/" title="">Все работы</a></li>
								{*<li class="{if $comp.competition_slug == 'kasperskiy2016'}active{/if} twoLines"><a href="/voting/competition/kasperskiy2016/"  title="Конкурс Касперского" >Конкурс  «Новые креативные идеи для Лаборатории Касперского»</a></li>*}
							</ul>
						</li>
					{/if}
					
					{if !$MobilePageVersion && ($module == 'customize' || $module == 'stickermize' || $module == 'customize.v2' || $module == 'stickermize.v2')}
					
						{include file="customize/customize.top_menu.tpl"}
					
					{else}
									
						<li h="shop" class="l1 {if $PAGE->url == '/catalog/male/' || (isset($filters.category) && ($filters.category == 'futbolki' || $filters.category == 'longsleeve_tshirt'|| $filters.category == 'patterns') && $filters.sex == 'male') || ($PAGE->reqUrl.2 == 'full-printed-t-shirt-male' && isset($Style) && $Style->style_sex == 'male')}active{/if} arr">
							<a {if $PAGE->url != '/catalog/male/'&&($filters.sex == 'male' || (isset($Style) && $Style->style_sex == 'male'))}{else}{/if} href="{if $PAGE->url != "/catalog/male/"}/catalog/male/{else}#{/if}"  title="{$L.HEADER_menu_men}">{$L.HEADER_menu_men}</a>
							<ul>						
								<li {if isset($filters.category) && $filters.category == 'futbolki' && $filters.sex == 'male'}class="active"{/if}><a href="{if $PAGE->url != "/catalog/futbolki/male/"}/catalog/futbolki/male/{else}#{/if}" title="{$L.HEADER_menu_tshirt}" >{$L.HEADER_menu_tshirt} хлопок</a></li>							
								<li {if ($PAGE->reqUrl.2 == 'full-printed-t-shirt-male' && (isset($Style) && $Style->style_sex == 'male')) || ($filters.category == 'patterns' && $filters.sex == 'male')}class="active"{/if}><a href="{if $PAGE->url != "/catalog/patterns/full-printed-t-shirt-male/"}/catalog/patterns/full-printed-t-shirt-male/{else}#{/if}"  title="{$L.HEADER_menu_tshirt_full_print}" >{$L.HEADER_menu_tshirt_full_print}</a></li>							
								{*<li {if $filters.category == 'longsleeve_tshirt' && $filters.sex == 'male'}class="active"{/if}><a href="{if $PAGE->url != "/catalog/longsleeve_tshirt/"}/catalog/longsleeve_tshirt/{else}#{/if}"  title="{$L.HEADER_menu_tshirt_long}" >{$L.HEADER_menu_tshirt_long}</a></li>*}													
								<li {if $filters.category == 'sweatshirts' && $filters.sex == 'male'}class="active"{/if}><a href="{if $PAGE->url != "/catalog/sweatshirts/"}/catalog/sweatshirts/{else}#{/if}" title="{$L.HEADER_menu_svitshota}">{$L.HEADER_menu_svitshota} хлопок</a></li>
								<li {if ($PAGE->reqUrl.2 == 'full-printed-sweatshirt-male' && (isset($Style) && $Style->style_sex == 'male')) || ($filters.category == 'patterns-sweatshirts' && $filters.sex == 'male')}class="active"{/if}><a href="{if $PAGE->url != "/catalog/patterns-sweatshirts/full-printed-sweatshirt-male/"}/catalog/patterns-sweatshirts/full-printed-sweatshirt-male/{else}#{/if}" title="{$L.HEADER_menu_svitshota_full_print}" >{$L.HEADER_menu_svitshota_full_print}</a></li>
								<li {if $filters.category == 'tolstovki' && $filters.sex == 'male'  && $filters.color!='127'}class="active"{/if}><a href="{if $PAGE->url != "/catalog/tolstovki/"}/catalog/tolstovki/{else}#{/if}" title="{$L.HEADER_menu_tolstovki}" >{$L.HEADER_menu_tolstovki} хлопок</a></li>
								{*<li {if $filters.category == 'patterns-bag'}class="active"{/if}><a href="{if $PAGE->url != "/catalog/patterns-bag/sumka-s-polnoj-zapechatkoj/"}/catalog/patterns-bag/sumka-s-polnoj-zapechatkoj/{else}#{/if}"  title="Сумки" >Сумки</a></li>*}
								<li {if ($PAGE->reqUrl.2 == 'bomber' && (isset($Style) && $Style->style_sex == 'male'))}class="active"{/if}><a href="{if $PAGE->url != "/catalog/bomber/full-printed-bomber-male/"}/catalog/bomber/full-printed-bomber-male/{else}#{/if}"  title="Мужские бомберы">Бомберы</a><span class="new-p"><img src="/images/3/fresh.gif" width="22" height="13" alt="{$L.HEADER_menu_new}" title="{$L.HEADER_menu_new}"></span></li>	
							</ul>
						</li>
						
						<li h="shop" class="l1 {if $PAGE->url == '/catalog/female/' || (($filters.category == 'futbolki' || $filters.category == 'mayki-alkogolichki' || $filters.category == 'mayki' || $filters.category == 'longsleeve_tshirt' || $filters.category == 'platya')&& $filters.sex == 'female') || (($PAGE->reqUrl.2 == 'full-printed-t-shirt-female' || $PAGE->reqUrl.2 == 'full-printed-t-shirt-female-short') && (isset($Style) && $Style->style_sex == 'female'))}active{/if} arr">
							<a {if $PAGE->url != '/catalog/female/' && ($filters.sex == 'female' || (isset($Style) && $Style->style_sex == 'female'))}{else}{/if} href="{if $PAGE->url != "/catalog/female/"}/catalog/female/{else}#{/if}"  title="{$L.HEADER_menu_women}">{$L.HEADER_menu_women}</a>
							<ul>
								<li {if $filters.category == 'futbolki' && $filters.sex == 'female'}class="active"{/if}><a href="{if $PAGE->url != "/catalog/futbolki/female/"}/catalog/futbolki/female/{else}#{/if}"  title="{$L.HEADER_menu_tshirt}" >{$L.HEADER_menu_tshirt} хлопок</a></li>
								<li {if ($PAGE->reqUrl.2 == 'full-printed-t-shirt-female-short' && (isset($Style) && $Style->style_sex == 'female')) || ($filters.category == 'patterns' && $filters.sex == 'female')}class="active"{/if}><a href="{if $PAGE->url != "/catalog/patterns/full-printed-t-shirt-female-short/"}/catalog/patterns/full-printed-t-shirt-female-short/{else}#{/if}"  title="{$L.HEADER_menu_tshirt_full_print}" >{$L.HEADER_menu_tshirt_full_print}</a></li>
								<li {if $filters.category == 'mayki-alkogolichki' && $filters.sex == 'female'}class="active"{/if}><a href="{if $PAGE->url != "/catalog/mayki-alkogolichki/female/"}/catalog/mayki-alkogolichki/female/{else}#{/if}" title="{$L.HEADER_menu_tank}" >{$L.HEADER_menu_tank} хлопок</a></li>
								<li {if $filters.category == 'longsleeve_tshirt' && $filters.sex == 'female'}class="active"{/if}><a href="{if $PAGE->url != "/catalog/longsleeve_tshirt/female/"}/catalog/longsleeve_tshirt/female/{else}#{/if}"  title="{$L.HEADER_menu_tshirt_long}" >{$L.HEADER_menu_tshirt_long}</a></li>				
								<li {if $filters.category == 'sweatshirts' && $filters.sex == 'female'}class="active"{/if}><a href="{if $PAGE->url != "/catalog/sweatshirts/female/"}/catalog/sweatshirts/female/{else}#{/if}" title="{$L.HEADER_menu_svitshota}" >{$L.HEADER_menu_svitshota} хлопок</a></li>							
								<li {if ($PAGE->reqUrl.2 == 'full-printed-sweatshirt-female' && (isset($Style) && $Style->style_sex == 'female')) || ($filters.category == 'patterns-sweatshirts' && $filters.sex == 'female')}class="active"{/if}><a href="{if $PAGE->url != "/catalog/patterns-sweatshirts/full-printed-sweatshirt-female/"}/catalog/patterns-sweatshirts/full-printed-sweatshirt-female/{else}#{/if}" title="{$L.HEADER_menu_svitshota_full_print}" >{$L.HEADER_menu_svitshota_full_print}</a></li>	
								<li {if $filters.category == 'tolstovki' && $filters.sex == 'female' && $filters.color!='127'}class="active"{/if}><a href="{if $PAGE->url != "/catalog/tolstovki/female/"}/catalog/tolstovki/female/{else}#{/if}" title="{$L.HEADER_menu_tolstovki}" >{$L.HEADER_menu_tolstovki} хлопок</a></li>
								<li {if ($PAGE->reqUrl.2 == 'full-printed-tolstovka-pocket-female' && (isset($Style) && $Style->style_sex == 'female'))}class="active"{/if}><a href="{if $PAGE->url != "/catalog/patterns-tolstovki/full-printed-tolstovka-pocket-female/"}/catalog/patterns-tolstovki/full-printed-tolstovka-pocket-female/{else}#{/if}"  title="{$L.HEADER_menu_tolstovki_fullprinted}">{$L.HEADER_menu_tolstovki_fullprinted}</a><span class="new-p"><img src="/images/3/fresh.gif" width="22" height="13" alt="{$L.HEADER_menu_new}" title="{$L.HEADER_menu_new}"></span></li>
								<li {if ($PAGE->reqUrl.2 == 'full-printed-tolstovka-zip-female' && (isset($Style) && $Style->style_sex == 'female'))}class="active"{/if}><a href="{if $PAGE->url != "/catalog/patterns-tolstovki/full-printed-tolstovka-zip-female/"}/catalog/patterns-tolstovki/full-printed-tolstovka-zip-female/{else}#{/if}"  title="{$L.HEADER_menu_tolstovki_fullprinted_zip}">{$L.HEADER_menu_tolstovki_fullprinted_zip}</a><span class="new-p"><img src="/images/3/fresh.gif" width="22" height="13" alt="{$L.HEADER_menu_new}" title="{$L.HEADER_menu_new}"></span></li>
								<li {if ($PAGE->reqUrl.2 == 'body' && (isset($Style) && $Style->style_sex == 'female'))}class="active"{/if}><a href="{if $PAGE->url != "/catalog/body/full-printed-body-female-sleeveless/"}/catalog/body/full-printed-body-female-sleeveless/{else}#{/if}"  title="Женские боди">Боди</a><span class="new-p"><img src="/images/3/fresh.gif" width="22" height="13" alt="{$L.HEADER_menu_new}" title="{$L.HEADER_menu_new}"></span></li>
							</ul>
						</li>				

						<li h="shop" class="l1 {if $filters.category == 'futbolki'&& $filters.sex == 'kids'}active{/if} arr">
							<a href="{if $PAGE->url != "/catalog/kids/"}/catalog/kids/{else}#{/if}" {if isset($Style) && $Style->style_sex != "kids"}{/if} title="{$L.HEADER_menu_kids}">{$L.HEADER_menu_kids}</a>
							<ul>
								<li {if $filters.category == 'futbolki' && $filters.sex == 'kids'}class="active"{/if}><a href="{if $PAGE->url != "/catalog/futbolki/kids/"}/catalog/futbolki/kids/{else}#{/if}"  title="{$L.HEADER_menu_tshirt}" >{$L.HEADER_menu_tshirt} хлопок</a></li>
								<li {if $filters.category == 'sweatshirts' && $filters.sex == 'kids'}class="active"{/if}><a href="{if $PAGE->url != "/catalog/sweatshirts/kids/"}/catalog/sweatshirts/kids/{else}#{/if}" title="{$L.HEADER_menu_svitshota}" >{$L.HEADER_menu_svitshota} хлопок</a> {*<span class="new-p"><img src="/images/3/sale.gif" width="22" height="14" alt="" title=""></span>*}</li>
								<li {if ($PAGE->reqUrl.2 == 'full-printed-futbolki-kids' && (isset($Style) && $Style->style_sex == 'kids')) || ($filters.category == 'patterns' && $filters.sex == 'kids')}class="active"{/if}><a href="{if $PAGE->url != "/catalog/patterns/full-printed-t-shirt-kids/"}/catalog/patterns/full-printed-t-shirt-kids/{else}#{/if}" title="Футболка детская 3D" >{$L.HEADER_menu_tshirt_full_print}</a><span class="new-p"><img src="/images/3/fresh.gif" width="22" height="13" alt="{$L.HEADER_menu_new}" title="{$L.HEADER_menu_new}"></span></li>
								<li {if ($PAGE->reqUrl.2 == 'full-printed-sweatshirt-kids' && (isset($Style) && $Style->style_sex == 'kids')) || ($filters.category == 'patterns-sweatshirts' && $filters.sex == 'kids')}class="active"{/if}><a href="{if $PAGE->url != "/catalog/patterns-sweatshirts/full-printed-sweatshirt-kids/"}/catalog/patterns-sweatshirts/full-printed-sweatshirt-kids/{else}#{/if}" title="Свитшот детский 3D" >{$L.HEADER_menu_svitshota_full_print}</a><span class="new-p"><img src="/images/3/fresh.gif" width="22" height="13" alt="{$L.HEADER_menu_new}" title="{$L.HEADER_menu_new}"></span></li>							
							</ul>
						</li>
						
						{if !$MobilePageVersion}
						<li h="shop" class="l1 {if $filters.category == 'tolstovki'}active{/if} arr">
							<a href="{if $PAGE->url != "/catalog/tolstovki/"}/catalog/tolstovki/{else}#{/if}" {if isset($Style) && $Style->category != "tolstovki"}{/if} title="{$L.HEADER_menu_tolstovki}">{$L.HEADER_menu_tolstovki}</a>
							<ul>
								<li {if $filters.category == 'tolstovki' && $filters.sex == 'male'  && $filters.color!='127'}class="active"{/if}><a href="{if $PAGE->url != "/catalog/tolstovki/"}/catalog/tolstovki/{else}#{/if}" title="{$L.HEADER_menu_men}" >{$L.HEADER_menu_men} хлопок</a></li>
								<li {if $filters.category == 'tolstovki' && $filters.sex == 'female' && $filters.color!='127'}class="active"{/if}><a href="{if $PAGE->url != "/catalog/tolstovki/female/"}/catalog/tolstovki/female/{else}#{/if}" title="{$L.HEADER_menu_women}" >{$L.HEADER_menu_women} хлопок</a></li>
								<li {if ($PAGE->reqUrl.2 == 'full-printed-tolstovka-pocket-female' && (isset($Style) && $Style->style_sex == 'female'))}class="active"{/if}><a href="{if $PAGE->url != "/catalog/patterns-tolstovki/full-printed-tolstovka-pocket-female/"}/catalog/patterns-tolstovki/full-printed-tolstovka-pocket-female/{else}#{/if}"  title="{$L.HEADER_menu_tolstovki_fullprinted2}">{$L.HEADER_menu_tolstovki_fullprinted2}</a><span class="new-p"><img src="/images/3/fresh.gif" width="22" height="13" alt="{$L.HEADER_menu_new}" title="{$L.HEADER_menu_new}"></span></li>
								<li {if ($PAGE->reqUrl.2 == 'full-printed-tolstovka-zip-female' && (isset($Style) && $Style->style_sex == 'female'))}class="active"{/if}><a href="{if $PAGE->url != "/catalog/patterns-tolstovki/full-printed-tolstovka-zip-female/"}/catalog/patterns-tolstovki/full-printed-tolstovka-zip-female/{else}#{/if}"  title="{$L.HEADER_menu_tolstovki_fullprinted_zip2}">{$L.HEADER_menu_tolstovki_fullprinted_zip2}</a><span class="new-p"><img src="/images/3/fresh.gif" width="22" height="13" alt="{$L.HEADER_menu_new}" title="{$L.HEADER_menu_new}"></span></li>
							</ul>
						</li>
						
						<li h="shop" class="l1 {if $filters.category == 'sweatshirts' || $filters.category == 'patterns-sweatshirts' || $PAGE->reqUrl.2 == 'full-printed-sweatshirt-female' ||$PAGE->reqUrl.2 == 'full-printed-sweatshirt-male'}active{/if} arr">
							<a href="{if $PAGE->url != "/catalog/sweatshirts/"}/catalog/sweatshirts/{else}#{/if}" {if isset($Style) && $Style->category != "sweatshirts" && $Style->category != "patterns-sweatshirts"}{/if} title="{$L.HEADER_menu_svitshota}">{$L.HEADER_menu_svitshota}</a>	
							<ul>
								<li {if $filters.category == 'sweatshirts' && $filters.sex == 'male'}class="active"{/if}><a href="{if $PAGE->url != "/catalog/sweatshirts/"}/catalog/sweatshirts/{else}#{/if}" title="{$L.HEADER_menu_men}">{$L.HEADER_menu_men} хлопок</a></li>
								<li {if ($PAGE->reqUrl.2 == 'full-printed-sweatshirt-male' && (isset($Style) && $Style->style_sex == 'male'))|| ($filters.category == 'patterns-sweatshirts' && $filters.sex == 'male')}class="active"{/if}><a href="{if $PAGE->url != "/catalog/patterns-sweatshirts/full-printed-sweatshirt-male/"}/catalog/patterns-sweatshirts/full-printed-sweatshirt-male/{else}#{/if}" title="{$L.HEADER_menu_men_full_print} {$L.HEADER_menu_full_print}" >{$L.HEADER_menu_men_full_print} {$L.HEADER_menu_full_print}</a></li>							
								<li {if $filters.category == 'sweatshirts' && $filters.sex == 'female'}class="active"{/if}><a href="{if $PAGE->url != "/catalog/sweatshirts/female/"}/catalog/sweatshirts/female/{else}#{/if}" title="{$L.HEADER_menu_women}" >{$L.HEADER_menu_women} хлопок</a></li>
								<li {if ($PAGE->reqUrl.2 == 'full-printed-sweatshirt-male' && (isset($Style) && $Style->style_sex == 'female')) || ($filters.category == 'patterns-sweatshirts' && $filters.sex == 'female')}class="active"{/if}><a href="{if $PAGE->url != "/catalog/patterns-sweatshirts/full-printed-sweatshirt-female/"}/catalog/patterns-sweatshirts/full-printed-sweatshirt-female/{else}#{/if}" title="{$L.HEADER_menu_women_full_print} {$L.HEADER_menu_full_print}" >{$L.HEADER_menu_women_full_print} {$L.HEADER_menu_full_print}</a></li>
								{*<li {if $filters.category == 'sweatshirts' && $filters.sex == 'kids'}class="active"{/if}><a href="{if $PAGE->url != "/catalog/sweatshirts/kids/"}/catalog/sweatshirts/kids/{else}#{/if}" title="{$L.HEADER_menu_svitshota}" >Детские</a></li>*}
								<li {if ($PAGE->reqUrl.2 == 'full-printed-sweatshirt-kids' && (isset($Style) && $Style->style_sex == 'kids')) || ($filters.category == 'patterns-sweatshirts' && $filters.sex == 'kids')}class="active"{/if}><a href="{if $PAGE->url != "/catalog/patterns-sweatshirts/full-printed-sweatshirt-kids/"}/catalog/patterns-sweatshirts/full-printed-sweatshirt-kids/{else}#{/if}" title="Свитшот детский 3D" >Детские 3D</a><span class="new-p"><img src="/images/3/fresh.gif" width="22" height="13" alt="{$L.HEADER_menu_new}" title="{$L.HEADER_menu_new}"></span></li>
							</ul>
						</li>
						{/if}
						
						{*--=чехлы=--*}
						<li h="shop" class="l1 cases {if $category == 'cases' || $style_slug == 'case-ipad-mini' || $style_slug == 'iphone-4-bumper' || $style_slug == 'iphone-5-bumper' || $style_slug == 'case-ipad-3' || $style->style_slug == 'iphone-4-bumper' || $style->style_slug == 'iphone-5-bumper' || $style->style_slug == 'case-ipad-3' || $style->style_slug == 'case-iphone-7' || $style->style_slug == 'case-iphone-8' || $style->style_slug == 'case-iphone-6' ||$style->style_slug == 'case-iphone-6-plus'||$style->style_slug == 'case-iphone-5' || $style->style_slug == 'case-iphone-4'||$style->style_slug == 'case-iphone-4-glossy'||$style->style_slug == 'case-galaxy-s5'}active{/if} arr">
							<a href="{if $PAGE->url != "/catalog/cases/"}/catalog/cases/{else}#{/if}" {if $Style->category != 'cases' && $Style->category != 'bumper'}{/if} title="{$L.HEADER_menu_cases}"><b>{$L.HEADER_menu_cases}</b></a>
							
							<ul>	
								{*<li class="{if $style_slug=='case-iphone-8-glossy' || $style->style_slug == 'case-iphone-8'}active{/if}"><a href="{if $PAGE->url != "/catalog/cases/case-iphone-8/"}/catalog/cases/case-iphone-8/{else}#{/if}">iPhone 8</a></li>*}
								<li class="{if $style_slug=='case-iphone-7-glossy' || $style->style_slug == 'case-iphone-7'}active{/if}"><a href="{if $PAGE->url != "/catalog/cases/case-iphone-7/"}/catalog/cases/case-iphone-7/{else}#{/if}" title="{$L.HEADER_menu_cases_Iphone7}" >iPhone 7</a></li>
								<li class="{if $style_slug=='case-iphone-6-glossy' || $style->style_slug == 'case-iphone-6'}active{/if}"><a href="{if $PAGE->url != "/catalog/cases/case-iphone-6-glossy/"}/catalog/cases/case-iphone-6-glossy/{else}#{/if}" title="{$L.HEADER_menu_cases_Iphone6}" >iPhone 6</a></li>	
								<li class="{if $style_slug=='case-iphone-4-glossy'|| $style->style_slug == 'case-iphone-4'|| $style->style_slug == 'case-iphone-4-glossy'}active{/if}"><a href="{if $PAGE->url != "/catalog/cases/case-iphone-4-glossy/"}/catalog/cases/case-iphone-4-glossy/{else}#{/if}" title="{$L.HEADER_menu_cases_Iphone4}" >iPhone 4,4s</a></li>
								<li class="{if $style_slug=='case-iphone-5'|| $style->style_slug == 'case-iphone-5'}active{/if}"><a href="{if $PAGE->url != "/catalog/cases/case-iphone-5/"}/catalog/cases/case-iphone-5/{else}#{/if}" title="{$L.HEADER_menu_cases_Iphone5}" >iPhone 5,5s</a></li>
							</ul>					
						</li>
						
						{*--=наклейки=--*}
						<li h="shop" class="l1 stickers {if ($filters.category=='touchpads' || $filters.category=='laptops' || $filters.category=='phones' || $filters.category=='ipodmp3' || $category=='ipodmp3' || $category=='touchpads' || $category=='laptops' || $category=='phones' || $filters.category == 'auto' || $filters.category == 'moto'  || $module == 'stickerset' || ($good && $default.category=='laptops')) && $style->style_slug != 'iphone-4-bumper' && $style->style_slug != 'iphone-5-bumper' && $style->style_slug != 'iphone-6-bumper' && $style->style_slug != 'case-ipad-mini' && $style->style_slug != 'case-ipad-3' && $style_slug != 'iphone-4-bumper' && $style_slug != 'iphone-5-bumper' && $style_slug != 'iphone-6-bumper' && $style_slug != 'case-ipad-mini' && $style_slug != 'case-ipad-3' && $style->style_slug != 'case-iphone-6' && $style->style_slug != 'case-iphone-6-plus'&& $style->style_slug != 'case-iphone-5'&&  $style->style_slug != 'case-iphone-4'&& $style->style_slug != 'case-iphone-4-glossy'&& $style->style_slug != 'case-galaxy-s5'|| $PAGE->url == '/catalog/stickers/'}active{/if} arr">
							<a href="{if $PAGE->url != "/catalog/stickers/"}/catalog/stickers/{else}#{/if}" title="{$L.HEADER_menu_stickers}" {if $PAGE->module != 'stickerset' && $Style->category != 'auto' && $Style->category != 'phones' && $Style->category != 'laptops' && $Style->category != 'touchpads' && $Style->category != 'ipodmp3'&& $Style->category != 'boards'}{/if}>{$L.HEADER_menu_stickers}</a>
							<ul>
								<li class="{if ($category=='phones'  || $filters.category=='phones')&& $style_slug != 'iphone-5-bumper' && $style_slug != 'iphone-4-bumper'}active{/if}"><a href="{if $PAGE->url != "/catalog/phones/"}/catalog/phones/{else}#{/if}" _onclick="return false;" title="{$L.HEADER_menu_st_phones}">{$L.HEADER_menu_phone}</a></li>	
								<li class="{if ($category=='touchpads' || $filters.category=='touchpads') && $style_slug != 'case-ipad-mini'}active{/if}"><a href="{if $PAGE->url != "/catalog/touchpads/"}/catalog/touchpads/{else}#{/if}" _onclick="return false;" title="{$L.HEADER_menu_st_touchpads}" >{$L.HEADER_menu_touchpad}</a></li>	
								<li class="{if $category=='laptops' || $filters.category=='laptops'}active{/if}"><a href="{if $PAGE->url != "/catalog/laptops/"}/catalog/laptops/{else}#{/if}" _onclick="return false;" title="{$L.HEADER_menu_st_laptops}">{$L.HEADER_menu_laptop}</a></li>			
								<li class="{if isset($filters.category) && $filters.category == 'auto'}active{/if}"><a href="{if $PAGE->url != "/catalog/auto/preview/"}/catalog/auto/preview/{else}#{/if}" title="{$L.HEADER_menu_auto}" style="font-weight:bold;">{$L.HEADER_menu_auto_short}</a></li>
								<li class="{if $module == 'stickerset'}active{/if}"><a href="{if $PAGE->url != "/stickerset/"}/stickerset/{else}#{/if}" title="{$L.HEADER_menu_stickerset}" >{$L.HEADER_menu_stickerset}</a></li>	
							</ul>					
						</li>

						{*--=для дома=--*}
						<li h="shop" class="l1 {if isset($filters.category) && ($filters.category == 'poster' || $filters.category == 'cup' || $filters.category == 'bag')}active{/if} arr {if $MobilePageVersion}forHome{/if}">
							<a href="{if $PAGE->url != "/catalog/home/"}/catalog/home/{else}#{/if}" title="Для дома" {if $Style->category != "cup" && $Style->category != "poster"}{/if}>Для дома</a>
							<ul>
								<li {if isset($filters.category) && $filters.category == 'cup'}class="active"{/if}><a href="{if $PAGE->url != "/catalog/cup/"}/catalog/cup/{else}#{/if}"  title="Кружки" >Кружки</a></li>
								<li {if isset($filters.category) && $filters.category == 'textile'}class="active"{/if}><a href="{if $PAGE->url != "/catalog/textile/"}/catalog/textile/{else}#{/if}"  title="Ткань" >Ткань</a></li>
								{if $USER->meta->mjteam}
								<li {if isset($filters.category) && $filters.category == 'bag'}class="active"{/if} style="border-bottom:1px dashed orange"><a href="{if $PAGE->url != "/catalog/bag/"}/catalog/bag/{else}#{/if}"  title="Рюкзаки" >Рюкзаки</a></li>
								{/if}
								<li {if isset($filters.category) && $filters.category == 'pillows'}class="active"{/if}><a href="{if $PAGE->url != "/catalog/pillows/"}/catalog/pillows/{else}#{/if}"  title="Подушки" >Подушки</a></li>
								<li {if $filters.category == 'patterns-bag'}class="active"{/if}><a href="{if $PAGE->url != "/catalog/patterns-bag/sumka-s-polnoj-zapechatkoj/"}/catalog/patterns-bag/sumka-s-polnoj-zapechatkoj/{else}#{/if}"  title="Сумки" >Сумки</a></li>
								<li {if isset($style_slug) && ($style_slug == 'poster-frame-vertical-white-50-x-70' || $style_slug == 'poster-frame-vertical-white-30-x-40' || $style_slug == 'poster-frame-vertical-white-40-x-50' || $style_slug == 'poster-frame-vertical-white-50-x-70' || $style_slug == 'poster-frame-horizontal-white-30-x-20' || $style_slug == 'poster-frame-horizontal-white-40-x-30' || $style_slug == 'poster-frame-horizontal-white-50-x-40' || $style_slug == 'poster-frame-horizontal-white-70-x-50' || $style_slug == 'poster-frame-square-white-50-x-50')}class="active"{/if}><a href="{if $PAGE->url != "/catalog/poster/poster-frame-horizontal-white-30-x-20/"}/catalog/poster/poster-frame-horizontal-white-30-x-20/{else}#{/if}"  title="Постеры" >Постеры</a></li>
							</ul>	
						</li>
						
						{if !$MobilePageVersion}
						<li h="shop" class="l1 {if $module == 'customize' || $module == 'foto-na-futbolku'}active{/if} customize">
							<a {if $PAGE->module != "customize" && $PAGE->module != "stickermize"}{/if} href="{if $PAGE->url != "/customize/"}/customize/{else}#{/if}" title="{$L.HEADER_menu_customize}">{$L.HEADER_menu_customize}</a>
						</li>
						{/if}
					{/if}
				{/if}	
				<!--/noindex-->
			</ul>
			
			{if $MobilePageVersion}
			<ul class="second_menu">
				{if $USER->authorized}
				{*<li><a href="/profile/{$USER->id}/" >{$USER->avatar}{$USER->user_login}</a></li>*}
				<li><a href="/logout/"  title="{$L.HEADER_singout}">{$L.HEADER_singout}</a></li>
				{else}
				<li><a href="#" _href="/registration/" onclick="document.location = $(this).attr('_href');return false;"  title="{$L.HEADER_register}">{$L.HEADER_register}</a></li>
				<li><a href="#" _href="/login/" onclick="document.location = $(this).attr('_href');return false;"  title="{$L.HEADER_singin}">{$L.HEADER_singin}</a></li>
				{/if}			
				
				<li><a href="/about/"  title="{$L.FOOTER_contacts}">{$L.FOOTER_contacts}</a></li>
				<li><a href="/faq/group/10/view/96/"  title="Возврат">Возврат</a></li>
				<li><a href="/faq/#delivery"  title="{$L.FOOTER_menu_delivery}">{$L.FOOTER_menu_delivery}</a></li>
				<li><a href="/faq/"  title="{$L.FOOTER_menu_help}">{$L.FOOTER_menu_help} ?</a></li>
				
				<li><a class="showFeedback" href="#" title="Остался вопрос?"  onclick="">Напишите нам</a></li>
				
				<li class="last"><a href=""  class="SwitchToStandardVersion">Стандартная версия сайта</a></li>
			</ul>
			{/if}
			
		</div>
		 
		{if $module != 'customize' && $module != 'stickermize'&& $module != 'design'  && $module != 'foto-na-futbolku'}
			{if $module == 'catalog' && $good && !$MobilePageVersion}
				{*во внутряке уберем*}
			{else}
				{include file="top_menu_search.tpl"}
			{/if}	
		{/if}
		
	</div>
	{if !$MobilePageVersion}
		{include file="top_menu.url-path.tpl"}
	{/if}
</div>