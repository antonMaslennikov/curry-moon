	design = {$jstyles};
	good_id = {$good.good_id};
	good_comments = '{$good.comments}';
var	default_sex = '{$default.sex}';
	default_category = '{$default.category}';
	default_color = '{$default.color}';

{*
var	default_sex = '{$default.sex}',	
	default_category = '{$default.category}',
	default_color = '{$default.color}',	
	
	default_size = '{$default.size}',	
	default_good_name = "{$good.good_name_escaped}",
	default_new_id = '{$style->id}',
	default_new_slug = '{$style->style_slug}',
	default_new_category = '{$style->category}';
*}
	
	stickers_set = {
		production_time_printing : parseFloat({$production_time_printing}),
		production_time_cutting : parseFloat({$production_time_cutting}),
		production_cost : parseFloat({$production_cost}),
		ink_cost : parseFloat({$ink_cost}),
		price_margins : {$price_margins}
	};