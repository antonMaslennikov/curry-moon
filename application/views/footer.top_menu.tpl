{if $PAGE->module == 'catalog' || $PAGE->module == 'tag' || $TAG || $SEARCH || $PAGE->module == 'search'}
	<script>
	$('<option value="">цвет</option>').insertAfter($('.pageTitle .NLtags select option:first, .fix-menu .NLtags select option:first'));
	if($('.pageTitle .NLtags select').length > 0)
		$('.pageTitle .NLtags select').selectbox();
	
	$(document).ready(function() { 	

		if($('.NLtags').length > 0){
			$("body").click(function(){
				$(".b-filter_tsh, .b-filter_view").removeClass('filterА');
			});
			
			var i=0;		
			$('.NLtags select option').each(function(){
				$('.NLtags ul li:eq('+i+')').prepend('<img width="125" height="125" title="" alt="" src="'+($(this).attr('img')?$(this).attr('img'):'')+'">');
				i++;
			});
			
			$(".NLtags div.select").click(function(){
				if($('.fix-menu .NLtags .dropdown').is(':hidden') && $('.pageTitle .NLtags .dropdown').is(':hidden')){
					$(".b-filter_tsh, .b-filter_view").removeClass('filterА');
				}else{
					$(".b-filter_tsh, .b-filter_view").addClass('filterА');
				}
			});

			/*дополнительные два ли в коллекцию*/
			addTagsRound('{if $filters.category}{if $filters.category!="phones" && $filters.category!="cases" && $filters.category!="touchpads" && $filters.category!="auto" && $filters.category!="cup" && $filters.category!="poster" && $filters.category!="bag" && $task !="patterns" && $task !="patterns-sweatshirts"}category,{/if}{$filters.category}{if $filters.category=="cases" || $filters.category=="touchpads" || ( $style_slug == "iphone-6-bumper" || $style_slug == "iphone-5-bumper") || $filters.category=="poster"}/{$style_slug}{/if}{if $task =="patterns" || $task =="patterns-sweatshirts"}/{$Style->style_slug}{/if}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{/if}{if $filters.sex == "female"}female/{/if}{if $filters.category!="cup" && $filters.category!="bag" && $filters.category!="laptops"}new/{/if}');
		}
		
	});	
	</script>	
{/if}
	
{if $PAGE->reqUrl.0 != ''}
	<div class="footer-plashka">
		{if $PAGE->module == 'voting'}	
		<img src="/images/banners/senddrawing_icon_best2.gif" alt="" title="" width="980" height="58" border="0"/>
		{else}
		<img src="/images/banners/icon_best2.gif" alt="Вы рисуете мы продаем. Лучшие авторы. Конкурс 15000 руб. Нам 12 лет Магазин №1. Доставка 24 часа. Высшее качество. Доставка 365 дней" title="" width="980" height="74" border="0">
		<a style="position:absolute;top:4px;left:5px;width:140px;height:60px" href="/voting/competition/main/"  title="Дизайн конкурсы - каждые две недели 15000 рублей за лучшую работу"></a>
		<a style="position:absolute;top:4px;left:155px;width:125px;height:60px" href="/people/designers/"  title="Художники и Дизайнеры"></a>
		<a style="position:absolute;top:6px;left:293px;width:125px;height:60px" href="/voting/"  title="Дизайн конкурс — каждые две недели 15000 рублей за лучшую работу"></a>
		
		<a style="position:absolute;top:7px;right:138px;width:125px;height:60px" href="/faq/#delivery"  title="Доставка 24 часа"></a>
		<a style="position:absolute;top:7px;right:3px;width:125px;height:60px" href="/faq/group/10/"  title="Возврат 365 дней."></a>
		{/if}
	</div>
{/if}

{if $PAGE->module == 'catalog' && $user && !$good && !$filters.category}
	{*исключаем с новой витрины*}
	<style>.bottom_menu_level_2 { border-top:1px solid #ccc;margin-top:10px } </style>
{else}
	{if $PAGE->module != 'voting' && $PAGE->module != 'messages'}
		<div class="bottom_menu" >
			<div class="bl" style="margin-left:10px;">
				<ul>
					<li class="h">{$L.FOOTER_menu_wear_men}</li>				
					<li>{if $filters.category == 'futbolki' && $filters.sex == 'male'}{$L.FOOTER_menu_t_shirts1}{else}<a  title="{$L.FOOTER_menu_t_shirts1}" href="/catalog/category,futbolki/new/">{$L.FOOTER_menu_t_shirts1}</a>{/if}</li>	
					<li>{if $PAGE->reqUrl.2 == 'full-printed-t-shirt-male' && $Style->style_sex == 'male'}{$L.HEADER_menu_tshirt_full_print}{else}<a  title="{$L.HEADER_menu_tshirt_full_print}" href="/catalog/patterns/full-printed-t-shirt-male/popular/">{$L.HEADER_menu_tshirt_full_print}</a>{/if}</li>
					<li>{if $filters.category == 'longsleeve_tshirt' && $filters.sex == 'male'}{$L.FOOTER_menu_tshirt_long}{else}<a href="/catalog/category,longsleeve_tshirt/male/new/" title="{$L.FOOTER_menu_tshirt_long}" >{$L.FOOTER_menu_tshirt_long}</a>{/if}</li>					
					<li>{if $filters.category == 'tolstovki' && $filters.sex == 'male'  && $filters.color!='127'}{$L.FOOTER_menu_hoodies1}{else}<a  title="{$L.FOOTER_menu_hoodies1}" href="/catalog/category,tolstovki/new/">{$L.FOOTER_menu_hoodies1}</a>{/if}</li>				
					{*
					<li>{if $filters.category == 'tolstovki' && $filters.sex == 'male' && $filters.color=='127'}{$L.HEADER_menu_tolstovki_zipper}{else}<a href="/catalog/category,tolstovki;color,127/male/new/" title="{$L.HEADER_menu_tolstovki_zipper}" >{$L.HEADER_menu_tolstovki_zipper}</a>{/if}</li>	*}			
					<li>{if $filters.category == 'sweatshirts' && $filters.sex == 'male'}{$L.FOOTER_menu_svitshota}{else}<a  title="{$L.FOOTER_menu_svitshota}" href="/catalog/category,sweatshirts/new/">{$L.FOOTER_menu_svitshota}</a>{/if}</li>
					<li>{if $PAGE->reqUrl.2 == 'full-printed-sweatshirt-male' && $Style->style_sex == 'male'}{$L.HEADER_menu_svitshota_full_print}{else}<a  title="{$L.HEADER_menu_svitshota_full_print}" href="/catalog/patterns-sweatshirts/full-printed-sweatshirt-male/popular/">{$L.HEADER_menu_svitshota_full_print}</a>{/if}</li>
					{*<li class="h2" style="margin: 10px 0;"><a href="/dealer/" title="{$L.FOOTER_menu_t_shirts_wholesale}" >{$L.FOOTER_menu_t_shirts_wholesale}</a></li>
					<li class="h2" style="margin: 10px 0;"><a  title="{$L.FOOTER_menu_hoodies_wholesale}" href="/dealer/tolstovki-optom.v3.php">{$L.FOOTER_menu_hoodies_wholesale}</a></li>*}				
				</ul>
			</div>
			<div class="bl">
				<ul>
					<li class="h">{$L.FOOTER_menu_wear_women}</li>
					<li>{if $filters.category == 'futbolki' && $filters.sex == 'female'}{$L.FOOTER_menu_t_shirts2}{else}<a href="/catalog/category,futbolki/female/new/" title="{$L.FOOTER_menu_t_shirts2}" >{$L.FOOTER_menu_t_shirts2}</a>{/if}</li>
					{*<li>{if $PAGE->reqUrl.2 == 'full-printed-t-shirt-female' && $Style->style_sex == 'female'}{$L.HEADER_menu_tank_full_print}{else}<a  title="{$L.HEADER_menu_tank_full_print}" href="/catalog/patterns/full-printed-t-shirt-female-short/popular/">{$L.HEADER_menu_tank_full_print}</a>{/if}</li>*}
					
					<li>{if $PAGE->reqUrl.2 == 'full-printed-t-shirt-female-short' && $Style->style_sex == 'female'}{$L.HEADER_menu_tshirt_full_print}{else}<a  title="{$L.HEADER_menu_tshirt_full_print}" href="/catalog/patterns/full-printed-t-shirt-female-short/popular/">{$L.HEADER_menu_tshirt_full_print}</a>{/if}</li>
					
					<li>{if $filters.category == 'longsleeve_tshirt' && $filters.sex == 'female'}{$L.FOOTER_menu_tshirt_long}{else}<a href="/catalog/category,longsleeve_tshirt/female/new/" title="{$L.FOOTER_menu_tshirt_long}" >{$L.FOOTER_menu_tshirt_long}</a>{/if}</li>
					{*<li>{if $filters.category == 'mayki-alkogolichki' && $filters.sex == 'female'}{$L.FOOTER_menu_tank}{else}<a href="/catalog/category,mayki-alkogolichki/female/new/"  title="{$L.FOOTER_menu_tank}">{$L.FOOTER_menu_tank}</a>{/if}</li>*}
					{* <li>{if $filters.category == 'mayki' && $filters.sex == 'female'}{$L.FOOTER_menu_tees}{else}<a href="/catalog/category,mayki/new/"  title="{$L.FOOTER_menu_tees}">{$L.FOOTER_menu_tees}</a>{/if}</li> *}
					<li>{if $filters.category == 'tolstovki' && $filters.sex == 'female' && $filters.color!='127'}{$L.FOOTER_menu_hoodies2}{else}<a href="/catalog/category,tolstovki/female/new/" title="{$L.FOOTER_menu_hoodies2}" >{$L.FOOTER_menu_hoodies2}</a>{/if}</li>		
					{*<li>{if $filters.category == 'tolstovki' && $filters.sex == 'female' && $filters.color=='127'}{$L.HEADER_menu_tolstovki_zipper}{else}<a href="/catalog/category,tolstovki;color,127/female/new/" title="{$L.HEADER_menu_tolstovki_zipper}" >{$L.HEADER_menu_tolstovki_zipper}</a>{/if}</li>	*}			
					<li>{if $filters.category == 'sweatshirts' && $filters.sex == 'female'}{$L.FOOTER_menu_svitshota}{else}<a  title="{$L.FOOTER_menu_svitshota}" href="/catalog/category,sweatshirts/female/new/">{$L.FOOTER_menu_svitshota}</a>{/if}</li>
					
					<li>{if $PAGE->reqUrl.2 == 'full-printed-sweatshirt-female' && $Style->style_sex == 'female'}{$L.HEADER_menu_svitshota_full_print}{else}<a  title="{$L.HEADER_menu_svitshota_full_print}" href="/catalog/patterns-sweatshirts/full-printed-sweatshirt-female/popular/">{$L.HEADER_menu_svitshota_full_print}</a>{/if}</li>
					
					{*<li>{if $filters.category == 'platya' && $filters.sex == 'female'}{$L.FOOTER_menu_dress}{else}<a href="/catalog/category,platya/female/new/" title="{$L.FOOTER_menu_dress}" >{$L.FOOTER_menu_dress}</a>{/if}</li>*}
					
					{*<li><a  title="{$L.FOOTER_menu_kids}" href="/catalog/kids/">{$L.FOOTER_menu_kids}</a></li>*}
				</ul>
			</div>
			
			<div class="bl">
				<ul>
					<li class="h">{$L.FOOTER_menu_cases}</li>
					<li>{if $style_slug=="case-iphone-4"}iPhone 4, 4s{else}<a href="/catalog/cases/case-iphone-4/new/" title="{$L.FOOTER_menu_cases_Iphone4}" >iPhone 4, 4s</a>{/if}</li>
					<li>{if $style_slug=="case-iphone-5"}iPhone 5, 5s{else}<a href="/catalog/cases/case-iphone-5/new/" title="{$L.FOOTER_menu_cases_Iphone5}" >iPhone 5, 5s</a>{/if}</li>				
					<li>{if $style_slug=="case-iphone-6"}iPhone 6{else}<a href="/catalog/cases/case-iphone-6/new/" title="{$L.FOOTER_menu_cases_Iphone6}" >iPhone 6</a>{/if}</li>		
					<li>{if $style_slug=="case-iphone-6-plus"}iPhone 6 plus{else}<a href="/catalog/cases/case-iphone-6-plus/new/" title="{$L.FOOTER_menu_cases_Iphone6} plus" >iPhone 6 plus</a>{/if}</li>
					<li>{if $style_slug=="case-iphone-7"}iPhone 7{else}<a href="/catalog/cases/case-iphone-7/new/" title="{$L.FOOTER_menu_cases_Iphone7}" >iPhone 7</a>{/if}</li>
					{*<li>{if $style_slug=="case-iphone-8"}iPhone 8{else}<a href="/catalog/cases/case-iphone-8/new/" title="" >iPhone 8</a>{/if}</li>*}
					
					{*<li>{if $style_slug=="case-galaxy-s5"}Samsung Galaxy S5{else}<a href="/catalog/cases/case-galaxy-s5/new/" title="Чехол  Samsung Galaxy S5" > Samsung Galaxy S5</a>{/if}</li>*}

					{*<li>{if $style_slug=="case-ipad-4-retina"}{$L.FOOTER_menu_cases_Ipad4}{else}<a href="/catalog/touchpads/case-ipad-4-retina/new/" title="{$L.FOOTER_menu_cases_Ipad4}" >{$L.FOOTER_menu_cases_Ipad4}</a>{/if}</li>*}		
					{*<li>{if $style_slug=="case-ipad-mini"}iPad-mini{else}<a href="/catalog/touchpads/case-ipad-mini/new/" title="{$L.FOOTER_menu_cases_Ipad_mini}" >iPad-mini</a>{/if}</li>*}
					{*<li>{if $style_slug=="case-ipad-3"}{$L.FOOTER_menu_cases_Ipad3}{else}<a href="/catalog/touchpads/case-ipad-3/new/" title="{$L.FOOTER_menu_cases_Ipad3}" >{$L.FOOTER_menu_cases_Ipad3}</a>{/if}</li>*}
				</ul>
			</div>
			
			<div class="bl">
				<ul>
					<li class="h">Для дома</li>
					<li>{if $filters.category == 'patterns-bag'}{$L.FOOTER_menu_bags}{else}<a href="/catalog/patterns-bag/sumka-s-polnoj-zapechatkoj/" title="{$L.FOOTER_menu_bags}" >{$L.FOOTER_menu_bags}</a>{/if}</li>
					<li>{if $filters.category == 'textile'}{$L.FOOTER_menu_bags}{else}<a href="/catalog/textile/new/" title="Ткань" >Ткань</a>{/if}</li>
					
					<li>{if $filters.category == 'poster'}{$L.FOOTER_menu_poster_canvas}{else}<a href="/catalog/poster/poster-frame-vertical-white-20-x-30/" title="{$L.FOOTER_menu_poster_canvas}" >Постеры</a>{/if}</li>					
					<li>{if $filters.category == 'cup'}Кружки{else}<a href="/catalog/cup/" title="Кружки" >Кружки</a>{/if}</li>
				</ul>
			</div>
			
			<div class="bl">
				<ul>
					<li class="h">{$L.FOOTER_menu_constructor}</li>
					<li>{if $PAGE->url == "/stickermize/cases/"}{$L.FOOTER_menu_t_shirts1}{else}<a href="/customize/filter/futbolki/" title="{$L.FOOTER_menu_bags}" >{$L.FOOTER_menu_t_shirts1}</a>{/if}</li>
					<li>{if $PAGE->url == "/stickermize/cases/"}{$L.FOOTER_menu_svitshota}{else}<a href="/customize/filter/sweatshirts/" title="Ткань" >{$L.FOOTER_menu_svitshota}</a>{/if}</li>
					<li>{if $PAGE->url == "/stickermize/cases/"}{$L.FOOTER_menu_hoodies1}{else}<a href="/customize/filter/tolstovki/" title="{$L.FOOTER_menu_hoodies1}" >{$L.FOOTER_menu_hoodies1}</a>{/if}</li>					
					<li>{if $PAGE->url == "/stickermize/cases/"}{$L.FOOTER_menu_cases}{else}<a href="/stickermize/cases/" title="{$L.FOOTER_menu_cases}" >{$L.FOOTER_menu_cases}</a>{/if}</li>
					<li>{if $PAGE->url == "/customize/filter/pillows/"}Подушки{else}<a href="/customize/filter/pillows/" title="Подушки" >Подушки</a>{/if}</li>
					<li><a href="/stickermize/style,744/" title="{$L.FOOTER_menu_bags}" >{$L.FOOTER_menu_bags}</a></li>
				</ul>
			</div>
			
			<div class="bl">
				<ul>
					<li class="h">Оптом</li>
					<li><a href="/dealer/priceforprint.php" title="Печать">Печать</a>, <a href="/dealer/embroidery.php" title="вышивка">вышивка</a></li>
					<li><a href="/dealer/" title="Футболки">Футболки</a></li>
					<li><a href="/dealer/izgotovlenie-polo.php" title="Рубашки-поло">Рубашки-поло</a></li>					
					<li><a href="/dealer/izgotovlenie-switshotov.php" title="Свитшоты">Свитшоты</a></li>
					<li><a href="/dealer/izgotovlenie-tolstovok.php" title="Толстовки">Толстовки</a></li>
					<li><a href="/dealer/cases.php" title="Чехлы">Чехлы</a></li>
					<li><a href="/dealer/pillows.php" title="Подушки">Подушки</a></li>
					<li><a href="/dealer/bags.php" title="Сумки">Сумки</a></li>
				</ul>
			</div>
			
			
			<div style="clear:both"></div>
		</div>
		
		{if $Page.lang=='ru'}
			<div class="bottom_menu_dealer">
				Мы шьем и печатаем на заказ:&nbsp;&nbsp;&nbsp;<a href="/dealer/" title="футболки оптом" >футболки оптом</a>,&nbsp;&nbsp;&nbsp;<a href="/dealer/izgotovlenie-tolstovok.php" title="толстовки оптом" >толстовки оптом</a>
			</div>
		{/if}	
	{/if}
	
{/if}

<div class="bottom_menu_level_2 {$Page.lang}" {if $PAGE->module == 'voting' || $PAGE->module == 'messages'}style="border-top:1px solid #ccc;margin-top:10px"{/if}>
	<ul>
		<li>{if $PAGE->module == 'people'}{$L.FOOTER_menu_authors}{else}<a href="/people/designers/" title="{$L.FOOTER_menu_authors}" >{$L.FOOTER_menu_authors}</a>{/if}</li>
		<li>{if $PAGE->module == 'voting'}{$L.FOOTER_menu_voting}{else}<a href="/voting/competition/main/" title="{$L.FOOTER_menu_voting}" >{$L.FOOTER_menu_voting}</a>{/if}</li>
		<li>{if $PAGE->module == 'senddrawing.design'}{$L.FOOTER_menu_send_work}{else}<a href="/senddrawing.design/" title="{$L.FOOTER_menu_send_work}" >{$L.FOOTER_menu_send_work}</a>{/if}</li>
		<li>{if $PAGE->module == 'blog'}{$L.FOOTER_menu_blog}{else}<a href="/blog/"  title="{$L.FOOTER_menu_blog}">{$L.FOOTER_menu_blog}</a>{/if}</li>
		<li>{if $PAGE->module == 'gallery'}{$L.FOOTER_menu_gallery}{else}<a href="/gallery/"  title="{$L.FOOTER_menu_gallery}">{$L.FOOTER_menu_gallery}</a>{/if}</li>
		<li {if $PAGE->module == 'voting'}style="margin-right:0;"{/if}>{if $PAGE->module == 'faq'}{$L.FOOTER_menu_help}{else}<a href="#" _href="/faq/"  title="{$L.FOOTER_menu_help}">{$L.FOOTER_menu_help}</a>{/if}</li>
		{if $PAGE->module != 'voting'}
			<li><a href="/faq/#order"  title="{$L.FOOTER_menu_order}">{$L.FOOTER_menu_order}</a></li>
			<li><a href="/faq/#delivery"  title="{$L.FOOTER_menu_delivery}">{$L.FOOTER_menu_delivery}</a></li>
			<li style="margin-right:0;">{if $PAGE->module == 'contacts'}{$L.FOOTER_contacts}{else}<a href="/about/"  title="{$L.FOOTER_contacts}">{$L.FOOTER_contacts}</a>{/if}</li>	
		{/if}
		<div style="clear: both"></div>
	</ul>		
</div>