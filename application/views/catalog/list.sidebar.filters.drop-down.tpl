<div class="doble_menu clearfix{if $filters.category=='poster' || $filters.category=='bag'} fixLock{/if}"{*if $filters.sex == 'kids' || $filters.category=='bag'|| $filters.category=='cup'}style="padding-top:0"{/if*}>
	{if $filters.category == 'enduro' || $filters.category == 'jetski' || $filters.category == 'atv'  || $filters.category == 'snowmobile'|| $filters.category == 'helmet' || $filters.category == 'helm'}
		<a href="/catalog/enduro/ready/" title="{$L.SIDEBAR_menu_gallery_of_works}"  class="{if $PAGE->reqUrl.2 == 'ready'}activ{/if} link-ready-gallery">{$L.SIDEBAR_menu_gallery_of_works}</a>
		<a href="/catalog/enduro/gallery/" title="Фотогалерея"  class="{if $filters.category == 'enduro' && $PAGE->reqUrl.2 == 'gallery'}activ{/if} link-ready-gallery">Фотогалерея</a>
	{/if}
	
	{if $category != 'laptops'}
		<ul class="clearfix">
			{if $filters.category == 'enduro' || $filters.category == 'jetski' || $filters.category == 'atv' || $filters.category == 'snowmobile'|| $filters.category == 'helmet' || $filters.category == 'helm'}
			<li class="l1 arr {if $filters.category == 'enduro' && $PAGE->reqUrl.2 != 'ready' && $PAGE->reqUrl.2 != 'gallery' && $PAGE->reqUrl.2 != 'zakaz-nakleek' && $PAGE->reqUrl.2 != 'kak-oformit-zakaz' && $PAGE->reqUrl.2 != 'kleim-nakleyki-na-mototsikl'}active{/if}"><a  href="/catalog/enduro/"  title="{$L.SIDEBAR_menu_motorcycles}">{$L.SIDEBAR_menu_motorcycles}</a>
				<ul>								
					<li><a href="/catalog/enduro/bmw/" title="BMW"   {if $manufacturer == 'bmw' && $filters.category == 'enduro'}class="active"{/if}>BMW</a></li>
					<li><a href="/catalog/enduro/gas_gas/" title="Gas Gas"   {if $manufacturer == 'gas_gas' && $filters.category == 'enduro'}class="active"{/if}>Gas Gas</a></li>	
					<li><a href="/catalog/enduro/honda/" title="Нonda"  {if $manufacturer == 'honda' && $filters.category == 'enduro'}class="active"{/if}>Нonda</a></li>
					<li><a href="/catalog/enduro/husaberg/" title="husaberg"   {if $manufacturer == 'husaberg' && $filters.category == 'enduro'}class="active"{/if}>husaberg</a></li>	
					<li><a href="/catalog/enduro/husqvarna/" title="husqvarna"   {if $manufacturer == 'husqvarna' && $filters.category == 'enduro'}class="active"{/if}>husqvarna</a></li>	
					<li><a href="/catalog/enduro/kawasaki/" title="kawasaki"  {if $manufacturer == 'kawasaki' && $filters.category == 'enduro'}class="active"{/if}>kawasaki</a></li>	
					<li><a href="/catalog/enduro/ktm/" title="ktm"  {if $manufacturer == 'ktm' && $filters.category == 'enduro'}class="active"{/if}>KTM</a></li>	
					
					<li><a href="/catalog/enduro/sherco/" title="sherco"   {if $manufacturer == 'sherco' && $filters.category == 'enduro'}class="active"{/if}>sherco</a></li>	
					<li><a href="/catalog/enduro/suzuki/" title="suzuki"   {if $manufacturer == 'suzuki' && $filters.category == 'enduro'}class="active"{/if}>suzuki</a></li>
					<li><a href="/catalog/enduro/tm/" title="tm"   {if $manufacturer == 'tm' && $filters.category == 'enduro'}class="active"{/if}>tm</a></li>	
					<li><a href="/catalog/enduro/yamaha/" title="yamaha"   {if $manufacturer == 'yamaha' && $filters.category == 'enduro'}class="active"{/if}>yamaha</a></li>	
				</ul>
			</li>
			<li class="l1 arr {if $filters.category == 'jetski'}active goUp{/if}"><a  href="/catalog/jetski/"  title="{$L.SIDEBAR_menu_jetski}">{$L.SIDEBAR_menu_jetski}</a>
				<ul>					
					<li><a href="/catalog/jetski/hsr-benelli/" title="hsr-benelli"   {if $manufacturer == 'hsr-benelli' && $filters.category == 'jetski'}class="active"{/if}>hsr-benelli</a></li>	
					<li><a href="/catalog/jetski/hydrospace/" title="hydrospace"   {if $manufacturer == 'hydrospace' && $filters.category == 'jetski'}class="active"{/if}>hydrospace</a></li>	
					<li><a href="/catalog/jetski/kawasaki/" title="kawasaki"  {if $manufacturer == 'kawasaki' && $filters.category == 'jetski'}class="active"{/if}>kawasaki</a></li>
					<li><a href="/catalog/jetski/sea-doo/" title="sea-doo"   {if $manufacturer == 'sea-doo' && $filters.category == 'jetski'}class="active"{/if}>sea-doo</a></li>	
					<li><a href="/catalog/jetski/yamaha/" title="yamaha"   {if $manufacturer == 'yamaha' && $filters.category == 'jetski'}class="active"{/if}>yamaha</a></li>
				</ul>
			</li>	
			<li class="l1 arr {if $filters.category == 'atv'}active goUp{/if}"><a  href="/catalog/atv/"  title="{$L.SIDEBAR_menu_atv}">{$L.SIDEBAR_menu_atv}</a>
				<ul>
					<li><a href="/catalog/atv/apex/" title="apex"   {if $manufacturer == 'apex' && $filters.category == 'atv'}class="active"{/if}>apex</a></li>	
					<li><a href="/catalog/atv/artic/" title="artic"   {if $manufacturer == 'artic' && $filters.category == 'atv'}class="active"{/if}>artic</a></li>
					<li><a href="/catalog/atv/arctic_cat/" title="arctic cat"   {if $manufacturer == 'arctic_cat' && $filters.category == 'atv'}class="active"{/if}>arctic cat</a></li>
					<li><a href="/catalog/atv/bombardier/" title="bombardier"   {if $manufacturer == 'bombardier' && $filters.category == 'atv'}class="active"{/if}>bombardier</a></li>
					<li><a href="/catalog/atv/cannondale/" title="cannondale"   {if $manufacturer == 'cannondale' && $filters.category == 'atv'}class="active"{/if}>cannondale</a></li>
					<li><a href="/catalog/atv/can-am/" title="Can-Am"   {if $manufacturer == 'can-am' && $filters.category == 'atv'}class="active"{/if}>Can-Am</a></li>	
					<li><a href="/catalog/atv/cobra/" title="cobra"   {if $manufacturer == 'cobra' && $filters.category == 'atv'}class="active"{/if}>cobra</a></li>
					<li><a href="/catalog/atv/drr/" title="drr"   {if $manufacturer == 'drr' && $filters.category == 'atv'}class="active"{/if}>drr</a></li>
					<li><a href="/catalog/atv/honda/" title="honda"   {if $manufacturer == 'honda' && $filters.category == 'atv'}class="active"{/if}>honda</a></li>
					<li><a href="/catalog/atv/kawasaki/" title="kawasaki"   {if $manufacturer == 'kawasaki' && $filters.category == 'atv'}class="active"{/if}>kawasaki</a></li>	
					<li><a href="/catalog/atv/ktm/" title="ktm"   {if $manufacturer == 'ktm' && $filters.category == 'atv'}class="active"{/if}>ktm</a></li>
					<li><a href="/catalog/atv/lem/" title="lem"   {if $manufacturer == 'lem' && $filters.category == 'atv'}class="active"{/if}>lem</a></li>	
					<li><a href="/catalog/atv/pitster/" title="pitster"   {if $manufacturer == 'pitster' && $filters.category == 'atv'}class="active"{/if}>pitster</a></li>
					<li><a href="/catalog/atv/polaris/" title="polaris"  {if $manufacturer == 'polaris' && $filters.category == 'atv'}class="active"{/if}>polaris</a></li>
					<li><a href="/catalog/atv/suzuki/" title="suzuki"   {if $manufacturer == 'suzuki' && $filters.category == 'atv'}class="active"{/if}>suzuki</a></li>	
					<li><a href="/catalog/atv/w-tec/" title="w-tec"   {if $manufacturer == 'w-tec' && $filters.category == 'atv'}class="active"{/if}>w-tec</a></li>			
					<li><a href="/catalog/atv/yamaha/" title="yamaha"   {if $manufacturer == 'yamaha' && $filters.category == 'atv'}class="active"{/if}>yamaha</a></li>	
				</ul>
			</li>	
			<li class="l1 arr {if $filters.category == 'snowmobile'}active goUp{/if}"><a  href="/catalog/snowmobile/" title="{$L.SIDEBAR_menu_snowmobile}">{$L.SIDEBAR_menu_snowmobile}</a>
				<ul>
					<li><a href="/catalog/snowmobile/arctic_cat/" title="arctic cat"   {if $manufacturer == 'arctic_cat' && $filters.category == 'snowmobile'}class="active"{/if}>arctic cat</a></li>
					<li><a href="/catalog/snowmobile/lynx/" title="lynx"   {if $manufacturer == 'lynx' && $filters.category == 'snowmobile'}class="active"{/if}>lynx</a></li>				
					<li><a href="/catalog/snowmobile/polaris/" title="polaris"  {if $manufacturer == 'polaris' && $filters.category == 'snowmobile'}class="active"{/if}>polaris</a></li>				
					<li><a href="/catalog/snowmobile/skidoo/" title="skidoo"   {if $manufacturer == 'skidoo' && $filters.category == 'snowmobile'}class="active"{/if}>skidoo</a></li>
					<li><a href="/catalog/snowmobile/timbersled/" title="timbersled"   {if $manufacturer == 'timbersled' && $filters.category == 'snowmobile'}class="active"{/if}>timbersled</a></li>				
					<li><a href="/catalog/snowmobile/yamaha/" title="yamaha"   {if $manufacturer == 'yamaha' && $filters.category == 'snowmobile'}class="active"{/if}>yamaha</a></li>
					<li><a href="/catalog/snowmobile/brp/" title="brp"   {if $manufacturer == 'brp' && $filters.category == 'snowmobile'}class="active"{/if}>BRP</a></li>
				</ul>
			</li>
			
			<li class="l1 {if $PAGE->reqUrl.2 == 'kleim-nakleyki-na-mototsikl'}active{/if}">
				<a class="textTwoLines"  href="/catalog/enduro/kleim-nakleyki-na-mototsikl/" title="Клеим наклейки на мотоцикл">Клеим наклейки на мотоцикл</a>	
			</li>
			<li class="l1 {if $PAGE->reqUrl.2 == 'zakaz-nakleek'}active{/if}">
				<a class="textTwoLines"  href="/catalog/enduro/zakaz-nakleek/" title="Купить наклейки на мотоцикл">Купить наклейки на мотоцикл</a>	
			</li>		
			<li class="l1 {if $PAGE->reqUrl.2 == 'kak-oformit-zakaz'}active{/if}">
				<a  href="/catalog/enduro/kak-oformit-zakaz/" title="Как оформить заказ">Как оформить заказ</a>	
			</li>
			
		{else}
		
			{* мужское *}
			<li class="l1 arr {if ($filters.sex == 'male' && $filters.category !='cup') || $Style->style_sex == 'male'}active{/if}">
				<a  href="/{if $module == 'tag' && !$TAG}catalog{else}{if $SEARCH}search{else}{$module}{/if}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}/futbolki/{if $SEARCH}?q={$SEARCH}{/if}"  title="{$L.HEADER_menu_men}">{$L.HEADER_menu_men}</a>
				<ul>						
					<li {if $filters.category == 'futbolki' && $filters.sex == 'male'}class="active"{/if}><a href="{if $filters.category == 'futbolki' && $filters.sex == 'male'}#{else}/{if $module == 'tag' && !$TAG}catalog{else}{if $SEARCH}search{else}{$module}{/if}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}{if $TAG && $module != 'tag'}/tag/{$TAG.slug}{/if}/futbolki/{if $SEARCH}?q={$SEARCH}{/if}{/if}"  title="{$L.HEADER_menu_tshirt}" >{$L.HEADER_menu_tshirt}</a></li>
					<li {if $PAGE->reqUrl.2 && $Style->style_sex == 'male' && $Style->style_slug == 'full-printed-t-shirt-male'}class="active"{/if}><a href="{if $PAGE->reqUrl.2 && $Style->style_sex == 'male' && $Style->style_slug == 'full-printed-t-shirt-male'}#{else}{if $PAGE->reqUrl.2 && $Style->style_sex == 'male' && $Style->style_slug == 'full-printed-t-shirt-male'}#{else}/{if $module == 'tag' && !$TAG}catalog{else}{if $SEARCH}search{else}{$module}{/if}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}{if $TAG && $module != 'tag'}/tag/{$TAG.slug}{/if}/patterns/full-printed-t-shirt-male/{if $SEARCH}?q={$SEARCH}{/if}{/if}{/if}"  title="{$L.HEADER_menu_tshirt_full_print}" >{$L.HEADER_menu_tshirt_full_print}</a></li>
					<li {if $filters.category == 'sweatshirts' && $filters.sex == 'male'}class="active"{/if}><a href="{if $filters.category == 'sweatshirts' && $filters.sex == 'male'}#{else}/{if $module == 'tag' && !$TAG}catalog{else}{if $SEARCH}search{else}{$module}{/if}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}{if $TAG && $module != 'tag'}/tag/{$TAG.slug}{/if}/sweatshirts/{if $SEARCH}?q={$SEARCH}{/if}{/if}"  title="{$L.HEADER_menu_svitshota}">{$L.HEADER_menu_svitshota}</a></li>
					<li {if $PAGE->reqUrl.2 && $Style->style_sex == 'male' && $Style->style_slug == 'full-printed-sweatshirt-male'}class="active"{/if}><a href="{if $PAGE->reqUrl.2 && $Style->style_sex == 'male' && $Style->style_slug == 'full-printed-sweatshirt-male'}#{else}/{if $module == 'tag' && !$TAG}catalog{else}{if $SEARCH}search{else}{$module}{/if}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}{if $TAG && $module != 'tag'}/tag/{$TAG.slug}{/if}/patterns-sweatshirts/full-printed-sweatshirt-male/{if $SEARCH}?q={$SEARCH}{/if}{/if}" title="{$L.HEADER_menu_svitshota_full_print}" >{$L.HEADER_menu_svitshota_full_print}</a></li>	
					<li {if $filters.category == 'tolstovki' && $filters.sex == 'male' && $filters.color!='127'}class="active"{/if}><a href="{if $filters.category == 'tolstovki' && $filters.sex == 'male' && $filters.color!='127'}#{else}/{if $module == 'tag' && !$TAG}catalog{else}{if $SEARCH}search{else}{$module}{/if}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}{if $TAG && $module != 'tag'}/tag/{$TAG.slug}{/if}/tolstovki/{if $SEARCH}?q={$SEARCH}{/if}{/if}" title="{$L.HEADER_menu_tolstovki}" >{$L.HEADER_menu_tolstovki}</a></li>
					<li {if $filters.category == 'pillows'}class="active"{/if}><a href="{if $filters.category == 'pillows'}#{else}/{if $module == 'tag' && !$TAG}catalog{else}{if $SEARCH}search{else}{$module}{/if}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}{if $TAG && $module != 'tag'}/tag/{$TAG.slug}{/if}/pillows/new/{if $SEARCH}?q={$SEARCH}{/if}{/if}" title="{$L.HEADER_menu_tolstovki}" >Подушки</a></li>				
				</ul>
			</li>
			
			{* женское *}
			<li class="l1 arr {if $filters.sex == 'female' || $Style->style_sex == 'female'}active goUp{/if}">
				<a  href="/{if $module == 'tag' && !$TAG}catalog{else}{if $SEARCH}search{else}{$module}{/if}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}/futbolki/female/{if $SEARCH}?q={$SEARCH}{/if}"  title="{$L.HEADER_menu_women}">{$L.HEADER_menu_women}</a>
				<ul>
					<li {if $filters.category == 'futbolki' && $filters.sex == 'female'}class="active"{/if}><a href="{if $filters.category == 'futbolki' && $filters.sex == 'female'}#{else}/{if $module == 'tag' && !$TAG}catalog{else}{if $SEARCH}search{else}{$module}{/if}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}{if $TAG && $module != 'tag'}/tag/{$TAG.slug}{/if}/futbolki/female/{if $SEARCH}?q={$SEARCH}{/if}{/if}"  title="{$L.HEADER_menu_tshirt}" >{$L.HEADER_menu_tshirt}</a></li>
					<li {if $PAGE->reqUrl.2 && $Style->style_sex == 'female' && $Style->style_slug == 'full-printed-t-shirt-female-short'}class="active"{/if}><a href="{if $PAGE->reqUrl.2 && $Style->style_sex == 'female' && $Style->style_slug == 'full-printed-t-shirt-female-short'}#{else}/{if $module == 'tag' && !$TAG}catalog{else}{if $SEARCH}search{else}{$module}{/if}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}{if $TAG && $module != 'tag'}/tag/{$TAG.slug}{/if}/patterns/full-printed-t-shirt-female-short/{if $SEARCH}?q={$SEARCH}{/if}{/if}"  title="{$L.HEADER_menu_tshirt_full_print}" >{$L.HEADER_menu_tshirt_full_print}</a></li>
					<li {if $filters.category == 'tolstovki' && $filters.sex == 'female' && $filters.color!='127'}class="active"{/if}><a href="{if $filters.category == 'tolstovki' && $filters.sex == 'female' && $filters.color!='127'}#{else}/{if $module == 'tag' && !$TAG}catalog{else}{if $SEARCH}search{else}{$module}{/if}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}{if $TAG && $module != 'tag'}/tag/{$TAG.slug}{/if}/tolstovki/female/{if $SEARCH}?q={$SEARCH}{/if}{/if}" title="{$L.HEADER_menu_tolstovki}" >{$L.HEADER_menu_tolstovki}</a></li>		
					<li {if $filters.category == 'sweatshirts' && $filters.sex == 'female'}class="active"{/if}><a href="{if $filters.category == 'sweatshirts' && $filters.sex == 'female'}#{else}/{if $module == 'tag' && !$TAG}catalog{else}{if $SEARCH}search{else}{$module}{/if}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}{if $TAG && $module != 'tag'}/tag/{$TAG.slug}{/if}/sweatshirts/female/{if $SEARCH}?q={$SEARCH}{/if}{/if}" title="{$L.HEADER_menu_svitshota}" >{$L.HEADER_menu_svitshota}</a></li>
					<li {if $PAGE->reqUrl.2 && $Style->style_sex == 'female' && $Style->style_slug == 'full-printed-sweatshirt-female'}class="active"{/if}><a href="{if $PAGE->reqUrl.2 && $Style->style_sex == 'female' && $Style->style_slug == 'full-printed-sweatshirt-female'}#{else}/{if $module == 'tag' && !$TAG}catalog{else}{if $SEARCH}search{else}{$module}{/if}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}{if $TAG && $module != 'tag'}/tag/{$TAG.slug}{/if}/patterns-sweatshirts/full-printed-sweatshirt-female/{if $SEARCH}?q={$SEARCH}{/if}{/if}" title="{$L.HEADER_menu_svitshota_full_print}" >{$L.HEADER_menu_svitshota_full_print}</a></li>	
					<li {if $filters.category == 'mayki-alkogolichki' && $filters.sex == 'female'}class="active"{/if}><a href="{if $filters.category == 'mayki-alkogolichki' && $filters.sex == 'female'}#{else}/{if $module == 'tag' && !$TAG}catalog{else}{if $SEARCH}search{else}{$module}{/if}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}{if $TAG && $module != 'tag'}/tag/{$TAG.slug}{/if}/mayki-alkogolichki/female/new/{if $SEARCH}?q={$SEARCH}{/if}{/if}" title="{$L.HEADER_menu_tank}" >{$L.HEADER_menu_tank}</a></li>
					<li {if $filters.category == 'pillows'}class="active"{/if}><a href="{if $filters.category == 'pillows'}#{else}/{if $module == 'tag' && !$TAG}catalog{else}{if $SEARCH}search{else}{$module}{/if}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}{if $TAG && $module != 'tag'}/tag/{$TAG.slug}{/if}/pillows/new/{if $SEARCH}?q={$SEARCH}{/if}{/if}" title="{$L.HEADER_menu_tolstovki}" >Подушки</a></li>
				</ul>
			</li>
	
			{* детское *}		
			<li class="l1 arr {if $filters.sex == 'kids'}active goUp{/if}">
				<a href="/{if $module == 'tag' && !$TAG}catalog{else}{if $SEARCH}search{else}{$module}{/if}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}/futbolki/kids/{if $SEARCH}?q={$SEARCH}{/if}"  title="{$L.HEADER_menu_kids}">{$L.HEADER_menu_kids}</a>
				<ul>
					<li {if $filters.category == 'futbolki' && $filters.sex == 'kids'}class="active"{/if}><a href="{if $filters.category == 'futbolki' && $filters.sex == 'kids'}#{else}/{if $module == 'tag' && !$TAG}catalog{else}{if $SEARCH}search{else}{$module}{/if}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}/futbolki/kids/{if $SEARCH}?q={$SEARCH}{/if}{/if}"  title="{$L.HEADER_menu_tshirt}" >{$L.HEADER_menu_tshirt}</a></li>
					<li {if $filters.category == 'sweatshirts' && $filters.sex == 'kids'}class="active"{/if}><a href="{if $filters.category == 'sweatshirts' && $filters.sex == 'kids'}#{else}/{if $module == 'tag' && !$TAG}catalog{else}{if $SEARCH}search{else}{$module}{/if}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}/sweatshirts/kids/{if $SEARCH}?q={$SEARCH}{/if}{/if}"  title="{$L.HEADER_menu_svitshota}" >{$L.HEADER_menu_svitshota}</a></li>
				</ul>
			</li>
	
			{* чехлы и наклейки *}
			<li class="l1 {if $PAGE->url != '/catalog/stickers/'}arr {if $category == 'cases' || $style_slug == 'case-ipad-mini' ||$style_slug == 'case-ipad-3' || $style_slug == 'iphone-5-bumper' || $style_slug == 'iphone-4-bumper' || $style_slug == 'iphone-6-bumper'}active goUp{/if}{/if}">
				<a href="/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}/cases/case-iphone-5-glossy/new/{if $SEARCH}?q={$SEARCH}{/if}"  title="{$L.HEADER_menu_cases}"><b>{$L.HEADER_menu_cases}</b></a>		
				{if $PAGE->url != "/catalog/stickers/"}
				<ul>
					<li {if $style_slug=='case-iphone-4'}class="active"{/if}><a href="{if $style_slug=='case-iphone-4'}#{else}/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}/cases/case-iphone-4/new/{if $SEARCH}?q={$SEARCH}{/if}{/if}"  title="{$L.SIDEBAR_menu_cases_Iphone4} матовый">iPhone 4 (мат.)</a></li>
					<li {if $style_slug=='case-iphone-4-glossy'}class="active"{/if}><a href="{if $style_slug=='case-iphone-4-glossy'}#{else}/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}/cases/case-iphone-4-glossy/new/{if $SEARCH}?q={$SEARCH}{/if}{/if}"  title="{$L.SIDEBAR_menu_cases_Iphone4} глянцевый">iPhone 4 (глянц.)</a></li>		
					<li {if $style_slug=='case-iphone-5'}class="active"{/if}><a href="{if $style_slug=='case-iphone-5'}#{else}/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}/cases/case-iphone-5/new/{if $SEARCH}?q={$SEARCH}{/if}{/if}"   title="{$L.SIDEBAR_menu_cases_Iphone5} матовый">iPhone 5 (мат.)</a></li>
					<li {if $style_slug=='case-iphone-5-glossy'}class="active"{/if}><a href="{if $style_slug=='case-iphone-5-glossy'}#{else}/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}/cases/case-iphone-5-glossy/new/{if $SEARCH}?q={$SEARCH}{/if}{/if}"   title="{$L.SIDEBAR_menu_cases_Iphone5} глянцевый">iPhone 5 (глянц.)</a></li>	
					<li {if $style_slug=='case-iphone-6'}class="active"{/if}><a href="{if $style_slug=='case-iphone-6'}#{else}/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}/cases/case-iphone-6/{if $SEARCH}?q={$SEARCH}{/if}{/if}"   title="{$L.SIDEBAR_menu_cases_Iphone6} матовый">iPhone 6 (мат.)</a></li>	
					<li {if $style_slug=='case-iphone-6-glossy'}class="active"{/if}><a href="{if $style_slug=='case-iphone-6-glossy'}#{else}/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}/cases/case-iphone-6-glossy/{if $SEARCH}?q={$SEARCH}{/if}{/if}"   title="{$L.SIDEBAR_menu_cases_Iphone6} глянцевый">iPhone 6 (глянц.)</a></li>					
					{*
					<li {if $style_slug=='case-iphone-6-plus'}class="active"{/if}><a href="{if $style_slug=='case-iphone-6-plus'}#{else}/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}/cases/case-iphone-6-plus/{if $SEARCH}?q={$SEARCH}{/if}{/if}"   title="{$L.SIDEBAR_menu_cases_Iphone6} plus матовый">iPhone 6  plus (мат.)</a></li>
					<li {if $style_slug=='case-iphone-6-plus-glossy'}class="active"{/if}><a href="{if $style_slug=='case-iphone-6-plus-glossy'}#{else}/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}/cases/case-iphone-6-plus-glossy/{if $SEARCH}?q={$SEARCH}{/if}{/if}"   title="{$L.SIDEBAR_menu_cases_Iphone6} plus глянцевый">iPhone 6  plus (глянц.)</a></li>
					<li {if $style_slug=='case-galaxy-s5'}class="active"{/if}><a href="{if $style_slug=='case-galaxy-s5'}#{else}/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}/cases/case-galaxy-s5/{if $SEARCH}?q={$SEARCH}{/if}{/if}" title="Чехол  Samsung Galaxy S5 матовый" > Samsung Galaxy S5</a></li>
					*}
					<li {if $style_slug=='case-iphone-7'}class="active"{/if}><a href="{if $style_slug=='case-iphone-7'}#{else}/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}/cases/case-iphone-7/{if $SEARCH}?q={$SEARCH}{/if}{/if}"   title="{$L.SIDEBAR_menu_cases_Iphone6} plus матовый">iPhone 7 (мат.)</a></li>
					{*<li {if $style_slug=='case-iphone-8'}class="active"{/if}><a href="{if $style_slug=='case-iphone-8'}#{else}/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}/cases/case-iphone-8/{if $SEARCH}?q={$SEARCH}{/if}{/if}"   title="{$L.SIDEBAR_menu_cases_Iphone6} plus матовый">iPhone 8 (мат.)</a></li>*}
				</ul>
				{/if}
			</li>
		
			{*
			{if $PAGE->url != "/catalog/stickers/"}
				<li class="l1 arr {if $category == 'boards' || $category== 'moto'}active goUp{/if}">
					<a href="/catalog{if $ACTIONS}/actions{/if}/auto/new/preview/" title="{$L.HEADER_menu_stickers}" >{$L.HEADER_menu_stickers}</a>	
					<ul>
						{if $category != 'phones'}<li><a href="/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}/phones/new/{if $SEARCH}?q={$SEARCH}{/if}" title="{$L.SIDEBAR_menu_st_pohes}" >{$L.HEADER_menu_phone}</a></li>{/if}
						{if $category != 'touchpads'}<li><a href="/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}/touchpads/new/{if $SEARCH}?q={$SEARCH}{/if}" title="{$L.SIDEBAR_menu_st_touchpads}" >{$L.HEADER_menu_touchpad}</a></li>{/if}			
						{if $category != 'laptops'}<li><a href="/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}/laptops/new/{if $SEARCH}?q={$SEARCH}{/if}" title="{$L.SIDEBAR_menu_st_laptop}" >{$L.HEADER_menu_laptop}</a></li>	{/if}				
						{if $category != 'ipodmp3'}<li><a href="/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}/ipodmp3/new/{if $SEARCH}?q={$SEARCH}{/if}" title="{$L.FOOTER_menu_st_players}" >{$L.HEADER_menu_ipodmp3}</a></li>{/if}
						<li><a href="/catalog{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}/auto/new/preview/"  title="{$L.SIDEBAR_menu_st_auto}">{$L.SIDEBAR_menu_st_auto}</a></li>	
						<li {if $category=='boards' && $style_slug == 'snowboard'}class="active"{/if}><a href="/catalog{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}/boards/snowboard/"  title="Наклейки на доски">Наклейки на доски</a></li>	
						<li><a href="/stickerset/" title="{$L.SIDEBAR_menu_st_stickerset}" >{$L.SIDEBAR_menu_st_stickerset}</a></li>
					</ul>
				</li>
				
				<li class="l1 arr {if $filters.category =='cup' || $category == 'cup' || $category== 'bag'}active goUp{/if}">
					<a href="/catalog{if $ACTIONS}/actions{/if}/poster/poster-frame-vertical-black-30-x-40/{if $SEARCH}?q={$SEARCH}{/if}" title="Для дома" >Для дома</a>	
					<ul>				
						<li {if $filters.category == 'cup'}class="active"{/if}>
							<a href="{if $filters.category == 'cup'}#{else}/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}/cup/{/if}{if $SEARCH}?q={$SEARCH}{/if}" title="Кружки" >Кружки</a>
							<span class="new-p"><img src="/images/3/fresh.gif" width="22" height="13" alt="{$L.HEADER_menu_new}" title="{$L.HEADER_menu_new}"></span>
						</li>
						
						<li {if $filters.category == 'textile'}class="active"{/if}>
							<a href="{if $filters.category == 'textile'}#{else}/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}/textile/{/if}{if $SEARCH}?q={$SEARCH}{/if}"  title="Ткань" >Ткань</a>
							<span class="new-p"><img src="/images/3/fresh.gif" width="22" height="13" alt="{$L.HEADER_menu_new}" title="{$L.HEADER_menu_new}"></span>
						</li>
						
						{if $USER->meta->mjteam}
							<li {if $filters.category == 'bag'}class="active"{/if}>
								<a href="{if $filters.category == 'bag'}#{else}/catalog{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}/bag/{/if}{if $SEARCH}?q={$SEARCH}{/if}"  title="Сумки"  style="border-bottom:1px dashed orange">Рюкзаки</a>
								<span class="new-p"><img src="/images/3/fresh.gif" width="22" height="13" alt="{$L.HEADER_menu_new}" title="{$L.HEADER_menu_new}"></span>
							</li>
						{/if}
						
						{if $category != 'poster'}
							<li><a href="/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}/poster/poster-frame-vertical-white-20-x-30/{if $SEARCH}?q={$SEARCH}{/if}"  title="Постер в белой раме" >Постер в белой раме</a></li>
							<li><a href="/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}/poster/poster-frame-vertical-black-30-x-40/{if $SEARCH}?q={$SEARCH}{/if}" title="Постер в чёрной раме" >Постер в чёрной раме</a></li>					
							<li><a href="/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}{if $partner}/selected{/if}/{$user.user_login}{else if $ACTIONS}/actions{/if}/poster/poster-canvas-vertical-20-x-30/{if $SEARCH}?q={$SEARCH}{/if}" title="Холст" >Холст</a></li>
						{/if}
					</ul>
				</li>
			{/if}
			*}
			
			{if $MobilePageVersion || ($PAGE->module == 'catalog.v3' && $user && !$good && !$filters.category)}
				{*исключаем с новой витрины*}
				{if $MobilePageVersion}<br clear="all" />{/if}
			{else}
				<li class="l1"><a href="/customize/" >{$L.SIDEBAR_menu_constructor}</a></li>
				<li class="l1 arr"><a  href="#"  title="{$L.SIDEBAR_menu_service}">{$L.SIDEBAR_menu_service}</a>
					<ul>
						<li><a href="/customize/filter/futbolki/"  title="{$L.SIDEBAR_menu_print_t_shirt}">{$L.SIDEBAR_menu_print_t_shirt}</a></li>					
						<li><a href="/customize/filter/stickers/"  title="Печать наклеек">Печать наклеек</a></li>
						<li><a href="/dealer/"  title="{$L.SIDEBAR_menu_posiv_t_shirt}">{$L.SIDEBAR_menu_posiv_t_shirt}</a></li>
						<li><a href="/dealer/izgotovlenie-tolstovok.php"  title="{$L.SIDEBAR_menu_posiv_hoodies}">{$L.SIDEBAR_menu_posiv_hoodies}</a></li>
						<li><a href="/dealer/izgotovlenie-polo.php" >Поло с вышивкой</a></li>
						<li><a href="/dealer/sublimation_printing.php" >Полная запечатка</a></li>
						<li><a href="/dealer/cases.php">Чехлы</a></li>
						<li><a href="/dealer/pillows.php">Подушки</a></li>
						<li><a href="/dealer/bags.php">Сумки</a></li>
						<li><a href="/embroidery.php">Вышивка</a></li>
						<li><a href="/dealer/cup.php"  title="Печать на кружках оптом">Печать на кружках оптом</a></li>
						<li><a href="/design/create/futbolki/"  title="Разработка дизайна">Разработка дизайна</a>
							<span class="new-p"><img src="/images/3/fresh.gif" width="22" height="13" alt="{$L.HEADER_menu_new}" title="{$L.HEADER_menu_new}"></span>
						</li>
					</ul>
				</li>	
			{/if}
			
			{/if}		
		</ul>
	{/if}
	
	{if $TAGS}
		<ul class="differentTag clearfix" style="padding-top:20px">
			{foreach from=$TAGS item="tag"}
			<li><a href="/tag/{$tag.slug}/{if $Style}{$Style->category}/{$Style->style_slug}/{/if}">{$tag.name}</a></li>
			{/foreach}	
			<li><a href="/tag/">Все теги</a></li>	
		</ul>
	{/if}
	
</div>			

{literal}
<style>
	.sidebar_filter ul.filter-list.v2 li .f-item.line2px{border-bottom:none!important;}
	.sidebar_filter ul.filter-list.v2 li:not(.h) {margin:0!important;line-height:28px!important;}
	.sidebar_filter ul.filter-list.v2 .dropdown li{margin:0 0 7px 22px!important;line-height:18px!important;color:#231F20;}
</style>

<script>
	$(".doble_menu li.l1:eq(0)").before($('.doble_menu li.goUp'));
</script>
{/literal}