{if !$filters.category && ($TAG|| $SEARCH  || $module=='catalog' || $module == 'search')}
	<option value="">{$L.LIST_menu_choose_carrier}</option>
{/if}

{if $filters.sex == 'female'}
	{******************Женские************************}
	<option {if $filters.category == 'futbolki' && $filters.sex == 'female'}selected="selected"{/if} value="/{$module}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}/{$user.user_login}{/if}/futbolki/female/new/">{$L.SIDEBAR_menu_t_shirts2} {$L.SIDEBAR_menu_female_sex}</option>
	{*<option {if $filters.category == 'longsleeve_tshirt' && $filters.sex == 'female'}selected="selected"{/if} value="/{$module}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}/{$user.user_login}{/if}/longsleeve_tshirt/female/new/">{$L.SIDEBAR_menu_tshirt_long}</option>*}		
	<option {if $filters.category == 'mayki-alkogolichki' && $filters.sex == 'female'}selected="selected"{/if} value="/{$module}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}/{$user.user_login}{/if}/mayki-alkogolichki/female/new/">{$L.SIDEBAR_menu_tank}</option>
	<option {if $filters.category == 'tolstovki' && $filters.sex == 'female' && $filters.color!='127'}selected="selected"{/if} value="/{$module}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}/{$user.user_login}{/if}/tolstovki/female/new/">{$L.SIDEBAR_menu_hoodies2} {$L.SIDEBAR_menu_female_sex}</option>

	{*<option {if $filters.category == 'tolstovki' && $filters.sex == 'female' && $filters.color=='127'}selected="selected"{/if} value="/{$module}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}/{$user.user_login}{/if}/tolstovki;color,127/female/new/">{$L.HEADER_menu_tolstovki_zipper} {$L.SIDEBAR_menu_female_sex}</option>*}
	
	<option {if $filters.category == 'sweatshirts' && $filters.sex == 'female'}selected="selected"{/if} value="/{$module}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}/{$user.user_login}{/if}/sweatshirts/female/new/">{$L.SIDEBAR_menu_svitshota} {$L.SIDEBAR_menu_female_sex}</option>
	<option {if $filters.category == 'body' && $filters.sex == 'female'}selected="selected"{/if} value="/{$module}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}/{$user.user_login}{/if}/body/{if $Style->category == 'body'}{$Style->style_slug}{else}full-printed-body-female-long-sleeves{/if}/new/">Боди</option>
	
	{*<option {if $filters.category == 'platya' && $filters.sex == 'female'}selected="selected"{/if} value="/{$module}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}/{$user.user_login}{/if}/platya/female/new/">{$L.HEADER_menu_platie}</option>*}
{/if}

{if $filters.sex == 'male'}
	{************мужские**********}
	<option {if $filters.category == 'futbolki' && $filters.sex == 'male'}selected="selected"{/if} value="/{$module}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}/{$user.user_login}{/if}/futbolki/new/">{$L.SIDEBAR_menu_t_shirts1} {$L.SIDEBAR_menu_men_sex}</option>
	<option {if $filters.category == 'longsleeve_tshirt' && $filters.sex == 'male'}selected="selected"{/if} value="/{$module}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}/{$user.user_login}{/if}/longsleeve_tshirt/male/new/">{$L.SIDEBAR_menu_tshirt_long}</option>
	<option {if $filters.category == 'tolstovki' && $filters.sex == 'male' && $filters.color!='127'}selected="selected"{/if} value="/{$module}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}/{$user.user_login}{/if}/tolstovki/new/">{$L.SIDEBAR_menu_hoodies1} {$L.SIDEBAR_menu_men_sex}</option>
	
	{if $filters.category == 'tolstovki' && $filters.sex == 'male' && $filters.color=='127'}
	<option {if $filters.category == 'tolstovki' && $filters.sex == 'male' && $filters.color=='127'}selected="selected"{/if} value="/{$module}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}/{$user.user_login}{/if}/tolstovki;color,127/new/">{$L.HEADER_menu_tolstovki_zipper}</option>
	{/if}
	
	<option {if $filters.category == 'sweatshirts' && $filters.sex == 'male'}selected="selected"{/if} value="/{$module}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}/{$user.user_login}{/if}/sweatshirts/new/">{$L.SIDEBAR_menu_svitshota} {$L.SIDEBAR_menu_men_sex}</option>
{/if}

{if $filters.sex == 'kids'}
	<option value="/catalog/futbolki/kids/" {if $filters.category == 'futbolki' && $filters.sex == 'kids'}selected="selected"{/if}>{$L.SIDEBAR_menu_t_shirts1}</option>
	{*<option value="/catalog/sweatshirts/kids/" {if $filters.category == 'sweatshirts' && $filters.sex == 'kids'}selected="selected"{/if}>{$L.SIDEBAR_menu_svitshota} </option>	*}
{/if}


{if $filters.sex || $Style->category =='patterns' || $Style->category =='patterns-sweatshirts'}
	{if $filters.sex != 'female'}
		<option  {if $PAGE->reqUrl.2 && $Style->style_sex == 'male' && $Style->category == 'patterns'}selected="selected"{/if} value="/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}/{$user.user_login}{/if}/patterns/full-printed-t-shirt-male/popular/{if $SEARCH}?q={$SEARCH}{/if}">{$L.HEADER_menu_men_full_print} {$L.HEADER_menu_tshirt_sl} 3D</option>
		<option {if $PAGE->reqUrl.2 && $Style->style_sex == 'male' && $Style->category == 'patterns-sweatshirts'}selected="selected"{/if} value="/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}/{$user.user_login}{/if}/patterns-sweatshirts/full-printed-sweatshirt-male/popular/{if $SEARCH}?q={$SEARCH}{/if}">{$L.HEADER_menu_men_full_print} {$L.HEADER_menu_svitshota_sl} 3D</option>
	{/if}
	
	{if $filters.sex != 'male'}
		<option {if $PAGE->reqUrl.2 && $Style->style_sex == 'female' && $Style->category == 'patterns'}selected="selected"{/if} value="/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}/{$user.user_login}{/if}/patterns/full-printed-t-shirt-female-short/popular/{if $SEARCH}?q={$SEARCH}{/if}">{$L.HEADER_menu_women_full_print} {$L.HEADER_menu_tshirt_sl} 3D</option>
		<option {if $PAGE->reqUrl.2 && $Style->style_sex == 'female' && $Style->category == 'patterns-sweatshirts'}selected="selected"{/if} value="/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}/{$user.user_login}{/if}/patterns-sweatshirts/full-printed-sweatshirt-female/popular/{if $SEARCH}?q={$SEARCH}{/if}">{$L.HEADER_menu_women_full_print}  {$L.HEADER_menu_svitshota_sl} 3D</option>			
	{/if}
{/if}

{if $category == 'cases' || $style_slug == 'case-ipad-mini' || $style_slug == 'case-ipad-3' || $style_slug == 'iphone-5-bumper' || $style_slug == 'iphone-4-bumper'|| $style_slug == 'iphone-6-bumper'}
	<option value="/catalog/cases/new/" {if $style_slug == 'case-iphone-4'}selected="selected"{/if}>{$L.SIDEBAR_menu_cases_Iphone4} {$L.LIST_cases_mat}</option>
	<option value="/catalog/cases/case-iphone-4-glossy/new/" {if $style_slug == 'case-iphone-4-glossy'}selected="selected"{/if}>{$L.SIDEBAR_menu_cases_Iphone4} {$L.LIST_cases_glossy}</option>
	<option value="/catalog/cases/case-iphone-5/new/" {if $style_slug == 'case-iphone-5'}selected="selected"{/if}>{$L.SIDEBAR_menu_cases_Iphone5} {$L.LIST_cases_mat}</option>
	<option value="/catalog/cases/case-iphone-5-glossy/new/" {if $style_slug == 'case-iphone-5-glossy'}selected="selected"{/if}>{$L.SIDEBAR_menu_cases_Iphone5} {$L.LIST_cases_glossy}</option>
	<option value="/catalog/cases/case-iphone-6/new/" {if $style_slug == 'case-iphone-6'}selected="selected"{/if}>{$L.SIDEBAR_menu_cases_Iphone6} {$L.LIST_cases_mat}</option>	
	<option value="/catalog/cases/case-iphone-6-glossy/new/" {if $style_slug == 'case-iphone-6-glossy'}selected="selected"{/if}>{$L.SIDEBAR_menu_cases_Iphone6} {$L.LIST_cases_glossy}</option>
	<option value="/catalog/cases/case-iphone-6-plus/new/" {if $style_slug == 'case-iphone-6-plus'}selected="selected"{/if}>{$L.SIDEBAR_menu_cases_Iphone6} plus {$L.LIST_cases_mat}</option>
	<option value="/catalog/cases/case-iphone-6-plus-glossy/new/" {if $style_slug == 'case-iphone-6-plus-glossy'}selected="selected"{/if}>{$L.SIDEBAR_menu_cases_Iphone6} plus {$L.LIST_cases_glossy}</option>	
	<option value="/catalog/cases/case-galaxy-s5/new/" {if $style_slug == 'case-galaxy-s5'}selected="selected"{/if}>Чехол  Samsung Galaxy S5 матовый</option>
	
	{*<option value="/catalog/touchpads/case-ipad-mini/new/" {if $category == "touchpads" && $category_style == 482}selected="selected"{/if}>{$L.SIDEBAR_menu_cases_Ipad_mini}</option>*}
	{*<option value="/catalog/touchpads/case-ipad-3/new/" {if $style_slug == 'case-ipad-3'}selected="selected"{/if}>{$L.SIDEBAR_menu_cases_Ipad3}</option>*}
	{*<option value="/catalog/phones/iphone-4-bumper/" {if $style_slug == 'iphone-4-bumper'}selected="selected"{/if}>{$L.SIDEBAR_menu_bumper_Iphone4}</option>*}
	<option value="/catalog/phones/iphone-5-bumper/" {if $style_slug == 'iphone-5-bumper'}selected="selected"{/if}>{$L.SIDEBAR_menu_bumper_Iphone5}</option>
	{*<option value="/catalog/phones/iphone-6-bumper/" {if $style_slug == 'iphone-6-bumper'}selected="selected"{/if}>{$L.HEADER_menu_bumper_Iphone6}</option>*}	
	
{elseif !$filters.sex && $Style->category !='patterns' && $Style->category !='patterns-sweatshirts'}

	<option {if $filters.category == 'futbolki' && $filters.sex == 'male'}selected="selected"{/if} value="/{$module}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}/{$user.user_login}{/if}/futbolki/new/">{$L.SIDEBAR_menu_t_shirts1}</option>
	
	{if $Style->category !='patterns' && $Style->category !='patterns-sweatshirts'}
		<option  value="/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}/{$user.user_login}{/if}/patterns/full-printed-t-shirt-male/{if $SEARCH}?q={$SEARCH}{/if}">{$L.HEADER_menu_clothing_full_print}</option>
	{/if}
	
	{*<option {if $filters.category == 'longsleeve_tshirt' && $filters.sex == 'female'}selected="selected"{/if} value="/{$module}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}/{$user.user_login}{/if}/longsleeve_tshirt/female/new/">{$L.SIDEBAR_menu_tshirt_long}</option>*}
	{*<option {if $filters.category == 'tolstovki' && $filters.sex == 'male' && $filters.color!='127'}selected="selected"{/if} value="/{$module}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}/{$user.user_login}{/if}/tolstovki/new/">{$L.SIDEBAR_menu_hoodies1}</option>*}
	{*<option {if $filters.category == 'tolstovki' && $filters.sex == 'female' && $filters.color=='127'}selected="selected"{/if} value="/{$module}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}/{$user.user_login}{/if}/tolstovki;color,127/female/new/">{$L.HEADER_menu_tolstovki_zipper}</option>*}
	<option {if $filters.category == 'sweatshirts' && $filters.sex == 'male'}selected="selected"{/if} value="/{$module}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}/{$user.user_login}{/if}/sweatshirts/new/">{$L.SIDEBAR_menu_svitshota} </option>
	<option {if $filters.category == 'mayki-alkogolichki' && $filters.sex == 'female'}selected="selected"{/if} value="/{$module}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}/{$user.user_login}{/if}/mayki-alkogolichki/female/new/">{$L.SIDEBAR_menu_tank}</option>
	

	<option {if $filters.category == 'cup'}selected="selected"{/if} value="/catalog{if $user}/{$user.user_login}{/if}/cup/">Кружки</option>	
	{*<option value="/catalog{if $user}/{$user.user_login}{/if}/sumki/new/" {if $filters.category == 'sumki'}selected="selected"{/if}>{$L.SIDEBAR_menu_bags}</option>*}				
	<option value="/catalog{if $user}/{$user.user_login}{/if}/laptops/new/" {if $category == "laptops"}selected="selected"{/if}>{$L.SIDEBAR_menu_st_laptop}</option>
	<option value="/catalog{if $user}/{$user.user_login}{/if}/touchpads/new/" {if $category == "touchpads"}selected="selected"{/if}>{$L.SIDEBAR_menu_st_touchpads}</option>			
	<option value="/catalog{if $user}/{$user.user_login}{/if}/phones/new/" {if $category == "phones"}selected="selected"{/if}>{$L.SIDEBAR_menu_st_pohes}</option>
	<option value="/catalog{if $user}/{$user.user_login}{/if}/ipodmp3/new/" {if $category == "ipodmp3"}selected="selected"{/if}>{$L.FOOTER_menu_st_players}</option>				

	{*<option value="/catalog{if $user}/{$user.user_login}{/if}/phones/iphone-4-resin/new/" {if $style_slug == 'iphone-4-resin'}selected="selected"{/if}>{$L.HEADER_menu_st_iphone4_pitch}</option>
	<option value="/catalog{if $user}/{$user.user_login}{/if}/phones/iphone-5-resin/new/" {if $style_slug == 'iphone-5-resin'}selected="selected"{/if}>{$L.HEADER_menu_st_iphone5_pitch}</option>
	<option value="/catalog{if $user}/{$user.user_login}{/if}/phones/iphone-6-resin/new/" {if $style_slug == 'iphone-6-resin'}selected="selected"{/if}>{$L.HEADER_menu_st_iphone6_pitch}</option>*}
	<option value="/catalog{if $user}/{$user.user_login}{/if}/phones/iphone-6/new/" {if $style_slug=="iphone-6"}selected="selected"{/if}>{$L.HEADER_menu_st_iphone6}</option>	

	<option value="/catalog{if $user}/{$user.user_login}{/if}/phones/iphone-4/new/" {if $style_slug=="iphone-4"}selected="selected"{/if}>{$L.SIDEBAR_menu_st_iphone4}</option>
	<option value="/catalog{if $user}/{$user.user_login}{/if}/phones/new/" {if $style_slug=="iphone-5"}selected="selected"{/if}>{$L.SIDEBAR_menu_st_iphone5}</option>
	<option value="/catalog{if $user}/{$user.user_login}{/if}/auto/new/" {if $task == 'auto' || $task == '1color'}selected="selected"{/if}>{$L.SIDEBAR_menu_st_auto}</option>
	
	{if $category == 'moto'}<option value="/catalog{if $user}/{$user.user_login}{/if}/moto/" {if $category == 'moto'}selected="selected"{/if}>{$L.SIDEBAR_menu_st_moto}</option>{/if}
	
	<option {if $category == 'poster'}selected="selected"{/if} value="/catalog{if $user}/{$user.user_login}{/if}/poster/poster-canvas-vertical-20-x-30/">{$L.HEADER_menu_posters}</option>
	<option value="/catalog{if $user}/{$user.user_login}{/if}/stickers/" {if $task == 'stickers'}selected="selected"{/if}>{$L.SIDEBAR_menu_st_vinyl}</option>	
	
	<option value="/catalog{if $user}/{$user.user_login}{/if}/boards/skateboard/" {if $style_slug == 'skateboard'}selected="selected"{/if}>Наклейки на доски</option>			
	{if $filters.category=='boards'}	
		<option value="/catalog{if $user}/{$user.user_login}{/if}/boards/longboard/" {if $style_slug == 'longboard'}selected="selected"{/if}>Наклейки на лонгборд</option>
		<option value="/catalog{if $user}/{$user.user_login}{/if}/boards/snowboard/" {if $style_slug == 'snowboard'}selected="selected"{/if}>Наклейки на сноуборд</option>		
		<option value="/catalog{if $user}/{$user.user_login}{/if}/boards/ski/" {if $style_slug == 'ski'}selected="selected"{/if}>Наклейки на лыжи</option>
		<option value="/catalog{if $user}/{$user.user_login}{/if}/boards/kite" {if $style_slug == 'kite'}selected="selected"{/if}>Наклейки на кайтсерфинг/вэйк</option>
		<option value="/catalog{if $user}/{$user.user_login}{/if}/boards/serf/" {if $style_slug == 'serf'}selected="selected"{/if}>Наклейки на серф</option>	
	{/if}
{/if}
