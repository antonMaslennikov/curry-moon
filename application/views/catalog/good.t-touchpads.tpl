<div class="good-tpl-touchpads">
<div class="ul-labels">
</div>
{if 1==2&&($USER->user_id == 27278 || $USER->user_id == 6199 || $USER->user_id == 63250 || $USER->user_id == 86455)}
    {literal}<style>
	.add-good-serv {float: left;width: 190px;	}
	.protective-film-input {
		float: left;
		width: 100%;
		background: url(http://allskins.ru/img/bg/input-bg-blue.gif) repeat-y 0 0;
		padding: 16px 0 10px;
		height: 50px;
	}
	.protective-film-input input {float: left;width:14px;height:14px;margin: 0 7px 0 6px;}
	.protective-film-input label {
		float: left;
		position: relative;
		width: 140px;
		margin: -3px 0 0;
		line-height: 16px;
		text-align: left;
		font-size: 13px;
	}	
	</style>{/literal}
	<div class="add-good-servs" style="display:none;">
		<div class="add-good-serv right-side"  style="border-top:none;">
		{* if ($category == 'touchpads' && $sizes.1) *}
			<div class="protective-film-input" style="height:35px;">
				<input type="checkbox" name="protective_film" id="protective_film" class="options good_stock_id" value="" />
				<label for="protective_film" style="font-size:11px">
				Защитная пленка экрана 
				<a href="http://www.allskins.ru/faq/#safetyskin" class="help-link" target="_blank">?</a ><br/> руб.</label>				
			</div>
		{* /if *}
		</div>
	</div>
{/if}

{*if $USER->user_id == 105091 || $USER->user_id == 86455 || $USER->user_id == 6199 || $USER->user_id == 27278}
	<div id="phones-select__2" class="styled-select">
	<span class="span-select"></span>
{/if*}

<!--noindex-->
<select id="touchpads-list" link_title=".good-tpl-touchpads .span-select">
	{foreach from=$styles.touchpads item="t" key="kk"}
	<option value="{$t.style_id}" hash="{$t.style_slug}">{if $modal}{$t.style_name}{/if}</option>
	{/foreach}
</select>
<!--/noindex-->

{*if $USER->user_id == 105091 || $USER->user_id == 86455 || $USER->user_id == 6199 || $USER->user_id == 27278}</div>{/if*}
{*<script type="text/javascript">
	/*$('select[link_title]').bind('change', function(){
		var selector = $(this).attr('link_title');
		if ($(selector).length>0)
			$(selector).text($('option:selected', this).text());
		else
			if ($(this).prev().hasClass('span-select'))
			$(this).prev().text($('option:selected', this).text());
	});*/
</script>*}

</div>