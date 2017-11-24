{if $USER->meta->mjteam && $USER->country!='RU'}{*yandex переводчик - ТОЛЬКО ДЛЯ НАС -  для остальных в тегменеджере, в head нельзя - нужно  после тега*}<script src="https://translate.yandex.net/website-widget/v1/widget.js?widgetId=ytWidget&pageLang=ru&widgetTheme=light&trnslKey=trnsl.1.1.20150713T073056Z.c4c1f11c5756ec50.e4f968cd9b96c79e3bb9bc6fad61f62f373e61fa&autoMode=false" type="text/javascript"></script>{/if}


{if !$USER->meta->mjteam && $USER->id != 190169 && $USER->id != 96976 && $USER->id != 10092 && !$smarty.cookies.eatmj19022013}

	{if $GA_T_I}	
	
	<script type="text/javascript">
		
		{*
		/*
		ga('require', 'ecommerce');
		
		ga('ecommerce:addTransaction',{
			'id': '{$order.user_basket_id}',    	// order ID - required
			'affiliation':'maryjane.ru',       	// affiliation or store name
			'revenue':'{$totalPrice}',     // total - required
			'shipping':'{$deliveryCost}',   // shipping
			'tax':'0'           		// tax
			{if $PAGE->lang == 'en'},'currency': 'USD'{/if} // local currency code.				
			//'{$basketCity}',     // city
			//'{$basketKray}',     // state or province
			//'{$basketCountry}'   // country
		});
	
		{foreach from=$GA_T_I item="i"}  
		
		ga('ecommerce:addItem', {
			'id': '{$order.user_basket_id}',		// order ID - required			    
			'name': '{$i.style_name} {if $i.color_id > 0}({$i.color_name}){/if} {$i.good_name}, {$i.size_name}',		// product name 
			'sku':'{$i.user_basket_good_id}',		// SKU/code
			'category':'{$i.style_name}',		// category or variation
			'price':'{$i.tprice / $i.quantity}',		// unit price - required
			'quantity': '{$i.quantity}'		// quantity - required
		});
		{/foreach}
		
		ga('ecommerce:send'); //submits transaction to the Analytics servers
		*/
		*}
		
		{*
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){ (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');	
		ga('create', 'UA-2491544-1', 'auto');
		ga('require', 'displayfeatures');
		*}
		
		ga('create', 'UA-2491544-1');
		
		ga('require', 'ec');
		
		{foreach from=$GA_T_I item="i"}  
		ga('ec:addProduct', {               // Provide product details in an productFieldObject.
		  'id': '{$i.good_id}_{$i.good_stock_id}',                   // Product ID (string).
		  'name': '{$i.style_name} {$i.good_name}, {$i.size_name}', // Product name (string).
		  'category': '{$i.category}/{$i.style_name}',            // Product category (string).
		  'brand': '{$i.user_login}',                // Product brand (string).
		  'variant': '{if $i.color_id > 0}({$i.color_name}){/if}',               // Product variant (string).
		  'price': '{$i.tprice / $i.quantity}',                 // Product price (currency).
		  'quantity': {$i.quantity}                     // Product quantity (number).
		});
		{/foreach}
		
		ga('ec:setAction', 'purchase', {          // Transaction details are provided in an actionFieldObject.
		  'id': '{$order.user_basket_id}',                         // (Required) Transaction id (string).
		  'affiliation': 'maryjane.ru', // Affiliation (string).
		  'revenue': '{$totalPrice}',                     // Revenue (currency).
		  'tax': '0',                          // Tax (currency).
		  'shipping': '{$deliveryCost}',                     // Shipping (currency).
		});
		
		ga('send', 'pageview');
		
		/**
		 * ecommerce от Yandex 
		 */
		window.dataLayer = window.dataLayer || [];
		window.dataLayer.push({
		    "ecommerce": {
		        "purchase": {
		            "actionField": {
		                "id" : "{$order.user_basket_id}",
		                "goal_id" : "6361848", 
		            },
		            "products": [
		            	{foreach from=$GA_T_I item="i" name="ga_t_i"}  
		                {
		                    "id": "{$i.user_basket_good_id}",
		                    "name": '{$i.style_name} {if $i.color_id > 0}({$i.color_name}){/if} {$i.good_name}, {$i.size_name}',
		                    "price": {$i.tprice / $i.quantity},
		                    "brand": "maryjane",
		                    "category": "{$i.style_name}",
		                    "variant": "{if $i.color_id > 0}({$i.color_name}){/if}",
		                    'quantity':{$i.quantity},
		                }
		                {if !$smarty.foreach.ga_t_i.last},{/if}
		                {/foreach}
		            ]
		        }
			}
		});
		
	</script>
	
	{/if}
	
{/if}



<!-- параметры google remarketing -->
<script type="text/javascript">
{if not isset($PAGE->reqUrl.0) || $PAGE->reqUrl.0 == ''}
	{literal}
		var google_tag_params = {
			ecomm_pagetype: 'home'
		};
	{/literal}
{else if ($module == 'catalog' || $module == 'catalog.dev' || $module == 'catalog.v2' || $module == 'catalog.v3') && $good}
	{literal}
	var google_tag_params = {
		ecomm_prodid: {/literal}{if $default_stockid}'{$good.good_id}_{$default_stockid}'{else}{$good.good_id}{/if}{literal},
		ecomm_pagetype: 'product',
		ecomm_totalvalue: {/literal}{if $default_price}{if $filters.category == "auto"}150{else if $filters.category == "moto"}750{else if $filters.category == "poster"}490{else if $filters.category == "ipodmp3"}400{else if $filters.category == "laptops"}800{else if $filters.category == "touchpads"}790{else if $style_slug == 'iphone-5-bumper'}750{else}{$default_price}{/if}{else}790{/if}{literal}			
	};
	{/literal}
{else if (($module == 'catalog' || $module == 'catalog.dev' || $module == 'catalog.v2' || $module == 'catalog.v3') && !$good) || $module == 'tag' || $module == '8march' || $module == '14february' || $module == '23february' || $module == 'prikol' || $module == '9may'}
	{literal}
	var google_tag_params = {
		ecomm_pagetype: 'category'
	};
	{/literal}
{else if $module == 'search'}
	{literal}
	var google_tag_params = {
		 ecomm_pagetype: 'searchresults'
	};
	{/literal}
{else if $module == 'order.v3' && ($PAGE->reqUrl.1 == 'confirm' || $PAGE->reqUrl.1 == 'confirm-customize')}
	var google_tag_params = {	
		ecomm_totalvalue: {$basket_sum},			
		ecomm_pagetype: 'purchase'
	};		
{else if $module == 'order.v3' || $module == 'basket'}
	var google_tag_params = {
		{if $basket->basketGoods|count > 1 || $basket->basketGifts|count > 1}
			{if $basket->basketGoods|count > 1 || $basket->basketGifts|count > 1 || ($basket->basketGifts|count==1 && $basket->basketGoods|count == 1)}			
			ecomm_prodid: [{foreach from=$gifts item="g" key="k" name="foo"}{$g.gift_id}{if !$smarty.foreach.foo.last},{/if}{/foreach}{if $basket->basketGifts|count>0 && $basket->basketGoods|count >0},{/if}{foreach from=$goods item="g" key="k" name="foo"}{$g.good_id}{if !$smarty.foreach.foo.last},{/if}{/foreach}],
			{else}
			ecomm_prodid: {foreach from=$gifts item="g"}{$g.gift_id}{/foreach}{foreach from=$goods item="g" key="k" name="foo"}{$g.good_id}{/foreach},
			{/if}
		{/if}
		ecomm_totalvalue: {$basket_sum},			
		ecomm_pagetype: 'cart'
	};
{else}	
	var google_tag_params = {
		ecomm_pagetype: 'other'
	};
{/if}	
</script>

<!-- скрипт google remarketing -->
{literal}
	<script type="text/javascript">
		/* <![CDATA[ */
		var google_conversion_id = 965648494;
		var google_custom_params = window.google_tag_params;
		var google_remarketing_only = true;
		/* ]]> */
	</script>
	<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
	</script>
	<noscript>
		<div style="display:inline;">
		<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/965648494/?value=0&guid=ON&script=0" />
		</div>
	</noscript>
{/literal}
