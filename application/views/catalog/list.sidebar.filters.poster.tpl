{if $MobilePageVersion}
	<span name="type" class="FAKEselectbox posters_menu" style="margin:0 0 5px 10px;z-index:6;">
		<div class="select">
			<div class="text"></div>
			<b class="trigger"><i class="arrow"></i></b>
		</div>
		<div class="dropdown">
			<ul>
			{foreach from=$size_filters key="color_key" item="color"}				
				{if $color_key == "wood"}
					<li type="{$color_key}" class="aInside{if $size_filters_active == 'wood'} selected{/if}"><a href="/{$PAGE->module}/{if $TAG.slug}{$TAG.slug}/{/if}poster/poster-frame-vertical-wood-20-x-30/{if $SEARCH}?q={$SEARCH}{/if}" title="" rel="nofollow">Постер в дер. раме</a></li>
				{elseif $color_key == "white"}	
					<li type="{$color_key}" class="aInside{if $size_filters_active == 'white'} selected{/if}"><a href="/{$PAGE->module}/{if $TAG.slug}{$TAG.slug}/{/if}poster/poster-frame-vertical-white-18-x-24/{if $SEARCH}?q={$SEARCH}{/if}" title="" rel="nofollow">Постер в белой раме</a></li>
				{elseif $color_key == "black"}
					<li type="{$color_key}" class="aInside{if $size_filters_active == 'black'} selected{/if}"><a href="/{$PAGE->module}/{if $TAG.slug}{$TAG.slug}/{/if}poster/poster-frame-vertical-black-18-x-24/{if $SEARCH}?q={$SEARCH}{/if}" title="" rel="nofollow">Постер в черной раме</a></li>				
				{elseif $color_key == "paper"}
					<li type="{$color_key}" class="aInside{if $size_filters_active == 'paper'} selected{/if}"><a href="/{$PAGE->module}/{if $TAG.slug}{$TAG.slug}/{/if}poster/poster-vertical-60-x-90/{if $SEARCH}?q={$SEARCH}{/if}" title="" rel="nofollow">Постер в тубусе</a></li>	
				{elseif $color_key == "holst"}
					<li type="{$color_key}" class="aInside{if $size_filters_active == 'holst'} selected{/if}"><a href="/{$PAGE->module}/{if $TAG.slug}{$TAG.slug}/{/if}poster/poster-canvas-square-20-x-20/{if $SEARCH}?q={$SEARCH}{/if}" title="" rel="nofollow">Холст</a></li>	
				{elseif $color_key == "black-heavy-frame"}
					<li type="{$color_key}" class="aInside{if $size_filters_active == 'black-heavy-frame'} selected{/if}"><a href="/{$PAGE->module}/{if $TAG.slug}{$TAG.slug}/{/if}poster/poster-heavy-frame-vertical-black-20-x-30/{if $SEARCH}?q={$SEARCH}{/if}" title="Постер в толстой черной раме" rel="nofollow">Постер в толстой черной раме</a></li>		
				{elseif $color_key == "white-heavy-frame"}
					<li type="{$color_key}" class="aInside{if $size_filters_active == 'white-heavy-frame'} selected{/if}"><a href="/{$PAGE->module}/{if $TAG.slug}{$TAG.slug}/{/if}poster/poster-heavy-frame-vertical-white-20-x-30/{if $SEARCH}?q={$SEARCH}{/if}" title="Постер в толстой белой раме" rel="nofollow">Постер в толстой белой раме</a></li>
				{/if}
			{/foreach}
			</ul>
		</div>
	</span>	
	<span name="size" class="FAKEselectbox posters_menu" style="margin:0 0 0px 10px">
		<div class="select" style="">
			<div class="text"></div>
			<b class="trigger"><i class="arrow"></i></b>
		</div>
		<div class="dropdown">
			<ul>
				{foreach from=$size_filters key="color_key" item="color"}
					{if $size_filters_active == $color_key}
						{foreach from=$color item="orientation" key="orientation_key"}
							{if $orientation|count > 0}	
								{foreach from=$orientation key="size_key" item="size"}
									<li class="aInside {$color_key} {if $style_slug == $size.style_slug}selected{/if}" style="display:{if $size_filters_active == $color_key}block{else}none{/if}"><a href="/{$PAGE->module}/{if $TAG.slug}{$TAG.slug}/{/if}poster/{$size.style_slug}/{if $SEARCH}?q={$SEARCH}{/if}" title="" rel="nofollow">{*if $orientation_key == "vertical"}
											Вертикальный
										{elseif $orientation_key == "horizontal"}
											Горизонтальный
										{elseif $orientation_key == "square"}
											Квадратные
										{/if*}							
										{$size.size_name}</a></li>
								{/foreach}								
							{/if}
						{/foreach}
					{/if}
				{/foreach}
			</ul>
		</div>
	</span>	
	{*literal}<script>
	$('.MPV .FAKEselectbox[name="type"] ul li').click(function(){
		var type=$(this).attr('type');
		$('.MPV .FAKEselectbox[name="size"] .text').text('');
		$('.MPV .FAKEselectbox[name="size"] ul li').hide();
		$('.MPV .FAKEselectbox[name="size"] ul li.'+type).show();
	});
	</script>{/literal*}	
	
{else}

	{literal}
	<style type="text/css">
	ul.poster-cat-size li{margin-bottom: 5px;}
	.productFilter .sizes .variants {margin-bottom: 3px;margin-top: -2px;}
	</style>
	{/literal}

	<div class="poster_params_menu clearfix">
		{foreach from=$size_filters key="color_key" item="color"}
			{*if $color_key == "paper"}
			<div class="item paper {if $size_filters_active == 'paper'}active{/if}">
				<div class="icon">
					<a href="#" title="Постер" rel="nofollow"><span></span></a>
				</div>
				<div class="title">Постер</div>
			</div>
			{elseif $color_key == "wood"}
			<div class="item wood {if $size_filters_active =='wood'}active{/if}">
				<div class="icon">
					<a href="#" title="Постер в дер. раме" rel="nofollow"><span></span></a>
				</div>
				<div class="title">Постер в<br>дер.<br>раме</div>
			</div>
			{elseif $color_key == "white"}	
			<div class="item white {if $size_filters_active =='white'}active{/if}">
				<div class="icon">
					<a href="#" title="Постер в белой раме" rel="nofollow"><span></span></a>
				</div>
				<div class="title">Постер в<br>белой<br>раме</div>
			</div>
			{elseif $color_key == "black"}
			<div class="item blask {if $size_filters_active =='white'}active{/if}">
				<div class="icon">
					<a href="#" title="Постер в черной раме" rel="nofollow"><span></span></a>
				</div>
				<div class="title">Постер в<br>черной<br>раме</div>
			</div>
			{elseif $color_key == "holst"}
			<div class="item xolst {if $size_filters_active=='holst'}active{/if}">
				<div class="icon">
					<a href="#" title="Холст" rel="nofollow"><span></span></a>
				</div>
				<div class="title">Холст</div>
			</div>
			{/if*}
			
			<div style="clear:left"></div>	
			{if $size_filters_active == $color_key}
				<div class="wrap-item-size {$color_key} clearfix" style="border-top:0px;display:{if $size_filters_active == $color_key}block{else}none{/if}">
					{foreach from=$color item="orientation" key="orientation_key"}
					
						{if $orientation|count == 0}
							<div class="item">&nbsp;</div>		
						{else}
							<div class="item clearfix">
							
								<span style="font-size:10px">
									{if $orientation_key == "vertical"}
										Вертикальный
									{elseif $orientation_key == "horizontal"}
										Горизонтальный
									{elseif $orientation_key == "square"}
										Квадратные
									{/if}
								</span>
								
								<ul class="size-ul clearfix">
									{foreach from=$orientation key="size_key" item="size"}
									<li {if $style_slug == $size.style_slug}class="on"{/if}><a href="/{$PAGE->module}/{if $TAG.slug}{$TAG.slug}/{/if}poster/{$size.style_slug}/{if $SEARCH}?q={$SEARCH}{/if}" title="" rel="nofollow">{$size.size_name}</a></li>
									{/foreach}
								</ul>
							</div>
						{/if}
					{/foreach}
				</div>
			{/if}
		{/foreach}
	</div>
	
	<h4 class="posterH4" style="border:0 !important;">{$L.SIDEBAR_poster_select_format}: {if $style_slug}<a href="{if $TAG.slug != $module}/{$module}{/if}{if $TAG.slug}/{$TAG.slug}{/if}/{$filters.category}{if $orderBy == 'new'}/{$orderBy}{/if}/{if $SEARCH}?q={$SEARCH}{/if}" title="Отменить фильтр" style="margin-left:10px"><img src="/images/icons/delete.gif" class="ico" /></a>{/if}</h4>

	<div class="poster_params_menu clearfix">
		
		{foreach from=$size_filters key="color_key" item="color"}				
			{if $color_key == "wood"}
			<div type="{$color_key}" class="item wood {if $size_filters_active =='wood'}active{/if}">
				<div class="icon">
					<a href="/{$PAGE->module}/{if $TAG.slug}{$TAG.slug}/{/if}poster/poster-frame-vertical-wood-20-x-30/{if $SEARCH}?q={$SEARCH}{/if}" title="Постер в дер. раме" rel="nofollow"><span></span></a>
				</div>
				<div class="title">Постер в<br>дер.<br>раме</div>
			</div>
			{elseif $color_key == "white"}	
			<div type="{$color_key}" class="item white {if $size_filters_active =='white'}active{/if}">
				<div class="icon">
					<a href="/{$PAGE->module}/{if $TAG.slug}{$TAG.slug}/{/if}poster/poster-frame-vertical-white-30-x-40/{if $SEARCH}?q={$SEARCH}{/if}" title="Постер в белой раме" rel="nofollow"><span></span></a>
				</div>
				<div class="title">Постер в<br>белой<br>раме</div>
			</div>
			{elseif $color_key == "black"}
			<div type="{$color_key}" class="item blask {if $size_filters_active =='black'}active{/if}">
				<div class="icon">
					<a href="/{$PAGE->module}/{if $TAG.slug}{$TAG.slug}/{/if}poster/poster-frame-vertical-black-50-x-70/{if $SEARCH}?q={$SEARCH}{/if}" title="Постер в черной раме" rel="nofollow"><span></span></a>
				</div>
				<div class="title">Постер в<br>черной<br>раме</div>
			</div>
			{elseif $color_key == "paper"}
			<div type="{$color_key}" class="item paper {if $size_filters_active == 'paper'}active{/if}">
				<div class="icon">
					<a href="/{$PAGE->module}/{if $TAG.slug}{$TAG.slug}/{/if}poster/poster-vertical-50-x-70/{if $SEARCH}?q={$SEARCH}{/if}" title="Постер в тубусе" rel="nofollow"><span></span></a>
				</div>
				<div class="title">Постер в<br>тубусе</div>
			</div>				
			{elseif $color_key == "holst"}
			<div type="{$color_key}" class="item xolst {if $size_filters_active=='holst'}active{/if}">
				<div class="icon">
					<a href="/{$PAGE->module}/{if $TAG.slug}{$TAG.slug}/{/if}poster/poster-canvas-vertical-50-x-70/{if $SEARCH}?q={$SEARCH}{/if}" title="Холст" rel="nofollow"><span></span></a>
				</div>
				<div class="title">Холст</div>
			</div>
			{elseif $color_key == "black-heavy-frame"}
			<div type="{$color_key}" class="item blask-heavy-frame  {if $size_filters_active=='black-heavy-frame'}active{/if}">
				<div class="icon">
					<a href="/{$PAGE->module}/{if $TAG.slug}{$TAG.slug}/{/if}poster/poster-heavy-frame-vertical-black-20-x-30/{if $SEARCH}?q={$SEARCH}{/if}" title="Постер в толстой черной раме" rel="nofollow"><span></span></a>
				</div>
				<div class="title">Постер в<br>толстой<br>черной раме</div>
			</div>
			{elseif $color_key == "white-heavy-frame"}
			<div type="{$color_key}" class="item white-heavy-frame  {if $size_filters_active=='white-heavy-frame'}active{/if}">
				<div class="icon">
					<a href="/{$PAGE->module}/{if $TAG.slug}{$TAG.slug}/{/if}poster/poster-heavy-frame-vertical-white-20-x-30/{if $SEARCH}?q={$SEARCH}{/if}" title="Постер в толстой белой раме" rel="nofollow"><span></span></a>
				</div>
				<div class="title">Постер в<br>толстой<br>белой раме</div>
			</div>				
			{/if}
		{/foreach}
		
		<br/>
		{literal}<!--script>
		$(".sidebar_filter .poster_params_menu .icon a").click(function(){
			if (!$(this).hasClass('active')) {
				$(this).closest('.poster_params_menu').find('.item[type]').removeClass('active');
				$(this).closest('.item').addClass('active');
				var type=$(this).closest('.item').attr('type');
				$(this).closest('.poster_params_menu').find('.wrap-item-size').hide();
				$(this).closest('.poster_params_menu').find('.wrap-item-size.'+type).show();

			}				
			return false;
		});
		</script-->
		<style>.sidebar_filter .poster_params_menu .wrap-item-size .item{width:100%;float:none;margin-bottom:6px;}
		.sidebar_filter .poster_params_menu .item .size-ul li{width:auto;margin:0 4px 0 0;}		
		</style>{/literal}
		
	</div>	
	<div class="clear"></div>
{/if}