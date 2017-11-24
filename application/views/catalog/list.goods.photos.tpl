{literal}
<script type="text/javascript">

    $(document).ready(function(){

        //инициализируем лайканье для работ
        $('.vote, .vote-small').each(function(){
            if (!this.vote) this.vote = new vote({ div: this, id: parseInt($(this).attr('_id')), style_id: {/literal}{if $style_id}{$style_id}{else}''{/if}{literal} });
        });
        
		if(!ismobiledevice){
			$('.m12 .zoom').unbind('click').bind('click', function(){
				trackUser('Zoom_catalog_list','вызов окна Зумa','');
				tb_show('', $(this).attr('href')+'?KeepThis=true&height=520&width=910');
				return false;
			});
		}
		
        //инициализируем "Показать ещё"
        initShowMore();

    });
</script>
{/literal}

{if $rest <= 0 || !$rest}
	{literal}
	<script type="text/javascript">
		var itWasTheLastLoading =true;//это была последняя подгрузка
		$(document).ready(function(){$(window).unbind('scroll',pScroll);  });
	</script>
	{/literal}
{/if}

{foreach from=$goods item="g" key="k"}

    {if ($g.good_status == "new" ||  $g.good_visible == "modify") && $g.good_status != "deny"}

    <li class="m12" page="{$page}">
        <a class="item" title="{$g.good_name} - футболки на заказ " href="{if $g.link}{$g.link}{else}/catalog/{$g.user_login}/{$g.good_id}/{/if}">
            <span class="list-img-wrap" ><img title="{$g.good_name} - футболки на заказ" alt="{$g.good_name} - футболки на заказ" style="background-color: #{$g.bg};" src="{$g.picture_path}" width="" height=""/></span>
        </a>
		{if $USER->authorized && ($USER->id == $user_id || $USER->id == 27278 || $USER->id == 6199 || $USER->id == 52426 || $USER->id == 105091)}
			<a class="btn_editing" href="/senddrawing.design/{$g.good_id}/"></a>
			{if $USER->id == 27278 || $USER->id == 6199 || $USER->id == 105091}
			<a class="btn_editing krest" href="#" onclick="tb_show('', '/senddrawing.design/{$g.good_id}/customize/{$category}/?width=930&height=620{if $filters.sex}&sex={$filters.sex}{/if}');return false;"></a>
			{/if}
		{/if}
        <div class="infobar">
            <div class="vote_count vote-small vote_count_id_{$g.good_id} {if $g.liked} select{/if} {if $g.place > 0}red_vote{/if}" _id="{$g.good_id}" _style_id="{$style_id}">
				<span>0</span>
			</div>
            <div class="preview_count">0</div>
        </div>
        <div class="item">
            <span class="title">{$g.good_name}</span>
            <span class="author" style="color:#F00; font-style:italic; font-size:11px; font-weight:bold">{if $g.good_visible == 'modify'}После правок работа на XC{else}работа на худсовете{/if}</span>
        </div>
    </li>

    {else}
	
    <li class="m12 {if ($USER->id == 27278 || $USER->id == 6199 || $USER->id == 105091) && $g.hidden}hidden{/if}" {if $USER->id == 27278 || $USER->id == 6199 || $USER->id == 105091}sexLeft="{$g.good_sex}"{/if} {if $g.good_status != 'archived'}page="{$page}"{/if}>
    	
        <a class="item" title="{$g.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="{if $g.link}{$g.link}{else}/catalog/{$g.user_login}/{$g.good_id}/{if $filters.sex}sex,{$filters.sex};{/if}category,{$category}/{/if}">
            <span class="list-img-wrap">				
				<img title="{$g.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" alt="{$g.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if} " style="background-color: #{$g.bg};" src="{$g.picture_path}"/>
			</span>
        </a>	
        
        {if !$MobilePageVersion}
		<a rel="nofollow" href="{if $g.link}{$g.link}zoom/{else}/catalog/{$g.user_login}/{$g.good_id}/zoom/{if $filters.sex}sex,{$filters.sex};{/if}category,{$category}/{/if}" class="zoom"></a>
        {/if}
        
        <div class="vote{if $g.liked} select{/if} {if $g.place > 0} heart_red {/if}" _id="{$g.good_id}"><span>{if $g.good_status != "voting"}{$g.likes}{else}-{/if}</span></div>
	    
	    {if $USER->authorized && ($USER->id == $user_id || $USER->id == 27278 || $USER->id == 6199 || $USER->id == 52426 || $USER->id == 86455  || $USER->id == 105091)}
			<a class="btn_editing" href="/senddrawing.design/{$g.good_id}/" rel="nofollow"></a>
			{if $USER->id == 27278 || $USER->id == 6199 || $USER->id == 86455  || $USER->id == 105091}
			<a class="btn_editing krest" href="#" onclick="tb_show('', '/senddrawing.design/{$g.good_id}/customize/{$category}/{$style_id}/{if $filters.color}{$filters.color}/{/if}?width=930&height=620{if $filters.sex}&sex={$filters.sex}{/if}');return false;"></a>
			{/if}
		{/if}

			
		<div class="infobar">
            {if $g.good_status != "voting"}
                <div class="vote_count vote-small vote_count_id_{$g.good_id} {if $g.liked} select{/if} {if $g.place > 0}red_vote{/if}" _id="{$g.good_id}" _style_id="{$style_id}">
                    <span>{$g.likes}</span>
                </div>
            {/if}
            <div class="preview_count">{$g.visits_catalog}</div>            
        </div>	

		<div class="item">
            <a href="{if $g.link}{$g.link}{else}/catalog/{$g.user_login}/{$g.good_id}/{/if}" class="title">{if $Style_category_title}{$Style_category_title}{/if} {$g.good_name}</a>
            {*if $g.good_discount > 0*}
            {if !$user && !$MobilePageVersion}
                <!--noindex--><span class="author">автор&nbsp;&mdash;&nbsp;<a title="Дизайнер {$g.user_login}" href="/catalog/{$g.user_login}/">{$g.user_login}</a> {$g.user_designer_level}</span><!--/noindex-->
            {/if}

            {if $g.disabled && ($USER->id == $user_id || $USER->id == 27278 || $USER->id == 6199)}
                <span class="author" style="color:#F00; font-style:italic; font-size:11px; font-weight:bold">Отсутствует исходник</span>
            {/if}

            {if $g.no_src && ($USER->id == $user_id || $USER->id == 27278 || $USER->id == 6199)}
                <span class="author" style="color:#F00; font-style:italic; font-size:11px; font-weight:bold">Отсутствует исходник</span>
            {/if}
			
			{*<!--noindex-->{if $g.city_name && !$user}<div class="autor-city">{$g.city_name}</div>{/if}<!--/noindex-->*}
			<br/>

			{if $stock.good_stock_quantity > 0}
			<div class="price-wrap">
				<span>цена:</span>
				<span class="price {if $g.price_old > $g.price}gold{/if}">{if $g.price}{if $g.price_old > $g.price}<strike>{$g.price_old}</strike>{/if} {$g.price}&nbsp;{$L.CURRENT_CURRENCY}{else}<strike>1090</strike> 790&nbsp;руб.{/if}</span><!--/noindex-->
				<div class="one_click_order_block">
					<a href="#" data-good_id="{$g.good_id}" _href="/order.v3/" rel="nofollow">Купить быстро</a>
				</div>
			</div>
			{/if}
			
        </div>
		
		{if $USER->id == 27278 || $USER->id == 6199}
			<div class="r-m12-menu">
				<a href="#" title="" rel="nofollow" class="edittags" _id="{$g.good_id}">теги</a>
				<span>|</span>
				<a href="#" title="" class="hideDesign" _id="{$g.good_id}" _style="{$style_id}" rel="nofollow">{if $g.hidden}показать{else}скрыть{/if}</a>
				<span>|</span>
				<a href="#" title="" class="disableDesign" _id="{$g.good_id}" _style="{$style_id}" _category="{$filters.category}" rel="nofollow">{if $g.src_enabled == 0}вкл.{else}выкл.{/if}</a>
				{if $g.good_status == 'archived'}
				<span>|</span>
				<a href="#" title="" class="promote2" _id="{$g.good_id}" _to="pretendent" rel="nofollow">победитель</a>
				{/if}
				{if $g.good_status == 'pretendent'}
				<span>|</span>
				<a href="#" title="" class="promote2" _id="{$g.good_id}" _to="archived" rel="nofollow">архив</a>
				{/if}
				
				<span>|</span>
				<a href="/ajax/setsex/" title="" class="setsex {if $g.good_sex == 'male' || $g.good_sex_alt == 'male'}sex-active{/if}" data-sex="male" _id="{$g.good_id}" rel="nofollow">м.</a>
				<span>|</span>
				<a href="/ajax/setsex/" title="" class="setsex {if $g.good_sex == 'female' || $g.good_sex_alt == 'female'}sex-active{/if}" data-sex="female" _id="{$g.good_id}" rel="nofollow">ж.</a>
				<span>|</span>
				<a href="/ajax/setsex/" title="" class="setsex {if $g.good_sex == 'kids' || $g.good_sex_alt == 'kids'}sex-active{/if}" data-sex="kids" _id="{$g.good_id}" rel="nofollow">д.</a>
				
				<span>|</span>
				<a href="/ajax/exportgood/{$g.good_id}/?style={$style_id}" download>скачать</a>
				
			</div>
		{/if}
    </li>
    {/if}
{/foreach}

{if $rest > 0}
	<div class="div-show-more" style="position:relative;float:left;width:100%" parentlist="true">
		<a class="show-more-link withFont" id="show-more-link" href="{$link}/page/{$page + 1}/{if $SEARCH}?q={$SEARCH}{/if}" rel="nofollow"><font>Ещё {$rest} из {$total}</font></a><div class="allpager" style="display:none;margin-left:20px" title="Включить автолистание"></div>
	</div>
{/if}

{if !$user && ($rest <= 0 || !$rest) && $rest_archived > 0 && ($TAG || $SEARCH)}
	<a href="{$link}{if $filters.good_status}/page/{$page + 1}{/if}/{if !$filters.good_status}archived/{/if}{if $SEARCH}?q={$SEARCH}{/if}" class="archived-catalog pos_inherit" rel="nofollow">Показать работы из архива</a>
{/if}