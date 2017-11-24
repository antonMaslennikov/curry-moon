{if $MobilePageVersion && $fid}
{else}
	<h1>{$PAGE->title}</h1>
{/if}

{literal}
<script type="text/javascript">
	$(document).ready(function(){
		var l = location.hash;
		if (l && l.length>0) {
			var d = /faq-(\d+)-(\d+)$/.exec(location.hash);
			if (d && d[2])
				$('#faq-'+d[2]).slideToggle("fast").siblings("div:visible").slideUp("fast").toggleClass("active");			
		}
		
		$(".b-goback:first").hide();
	    $(".faq h4.open").click(function(){
		$(this).next("div.opendiv").slideToggle("fast").siblings("div:visible").slideUp("fast");
		$(this).toggleClass("active");
		$(this).siblings("h4").removeClass("active");	
	    });
	});
</script>

<style type="text/css">
	#h4 {margin:10px; color: #999}
	.b-goback {padding-top:20px;text-align:right;}
	.b-goback_link {color: #999;font-size:11px;}
	.b-faq-title {padding-top:20px;font-size:22px;width:100%;}
	#content {width: 725px;}
	{/literal}
	{if $MobilePageVersion}
	h1 {
		margin: 20px 0 10px 10px;
	}
	{/if}
	{literal}
</style>
{/literal}

{if !$MobilePageVersion}
<div class="faqtmw960" style="{if $PAGE->reqUrl.1 == 'designers' &&  !$MobilePageVersion}display:none;{/if}" >
	{if $MobilePageVersion}
		{foreach from=$faq_title key="k" item="fg"}
			<a href="{if $fgroup}/{$module}/group/{$k}/{else}#{$fg.slug}{/if}" style="color:#00a851;">{$fg.name}</a>
		{/foreach}
	{elseif $PAGE->reqUrl.1 != "designers"}
		<a href="#order" style="padding-left:25px;color:#00a851">Заказ</a>
		<a href="#delivery" style="padding-left:25px;color:#00a851">Доставка</a>
		<a href="#payments" style="padding-left:15px;color:#00a851">Оплата</a>
		<a href="#size" style="padding-left:25px;color:#00a851">Размерная сетка</a>
		<a href="#moneyback" style="padding-left:25px;color:#00a851">Обмен - Возврат</a>		
		<a href="#png_transparent" style="padding-left:25px;color:#00a851">Требования к работам</a>	
		<a href="/about/" style="padding-left:25px;color:#00a851">Контакты</a>	
		{*<strong><a href="#printquality" style="color:#00a851">Почему мы №1</a></strong>
		<a href="#waorkshop" style="padding-left:15px;color:#00a851">Уход за изделиями</a> 
		<a href="#printshop" style="padding-left:25px;color:#00a851">Как продавать свои работы</a>*}			
	{/if}
</div>
{/if}

{if !$PAGE->reqUrl.2 && $PAGE->reqUrl.1 != 'search'}
	{if $MobilePageVersion}
		<span class="FAKEselectbox filterRequirements">
			<div class="select" style="">
				<div class="text"></div>
				<b class="trigger"><i class="arrow"></i></b>
			</div>
			<div class="dropdown">
				<ul>
					<li class="aInside {if $PAGE->reqUrl.1 == 'buyers' || $PAGE->reqUrl.1 == ''}selected{/if}"><a rel="nofollow" href="/{$PAGE->module}/">Покупателям</a></li>
					<li class="aInside {if $PAGE->reqUrl.1 == 'designers'}selected{/if}"><a href="/{$PAGE->module}/designers/" rel="nofollow">Дизайнерам</a></li>
					<li class="aInside {if $PAGE->reqUrl.1 == 'partners'}selected{/if}"><a href="/{$PAGE->module}/partners/" rel="nofollow">Партнёрам</a></li>
				</ul>
			</div>
		</span>
	{else}
		<div class="tabz clearfix">
			<a href="/{$PAGE->module}/buyers/" {if $PAGE->reqUrl.1 == "buyers" || $PAGE->reqUrl.1 == ""}class="active"{/if} rel="nofollow">Покупателям</a>
			<a href="/{$PAGE->module}/designers/" {if $PAGE->reqUrl.1 == "designers"}class="active"{/if} rel="nofollow">Дизайнерам</a>
			<a href="/{$PAGE->module}/partners/" {if $PAGE->reqUrl.1 == "partners"}class="active"{/if} rel="nofollow">Партнёрам</a>
		</div>
	{/if}
{/if}

{if $USER->country!='RU'}
	<div id="ytWidget" class="faq" style="margin-top:-{if !$PAGE->reqUrl.2 && $PAGE->reqUrl.1 != 'search'}66{else}29{/if}px">{*yandex перводчик*}</div>
{/if}

{if $fgroup && !$MobilePageVersion}
<p style="color:#a6a6a6">&larr; <a href="/faq/" style="color:#a6a6a6" rel="nofollow">Вернуться в F.A.Q</a></p>
{/if}

{if $faq|count > 0}

	{foreach from=$faq key="k" item="fg"}
	<div id="{$fg.slug}" class="faq moduletable ttt">
		{if !$fgroup}
		<a href="#" onclick="$(this).next().show().select().focus();$(this).hide();return false" style="float: right;">ссылка</a><input style="float: right;display:none" class="" type="text" size="34" value="http://www.maryjane.ru/faq/group/{$k}/" rel="nofollow">
		{/if}
		<a name="group-{$k}" rel="nofollow"></a>
		
		{if $MobilePageVersion && $fid}
		{else}
	    <h2 class="b-faq-title">{$fg.name}</h2><br/>
	    {/if}
	    
		<div class="clr clearfix"></div>    
		
		{foreach from=$fg.items item="f"}
			<a name="faq-{$k}-{$f.id}"></a>
		    <h4 class="open clr clearfix">{$f.title}</h4>
		    <div class="opendiv" id="faq-{$f.id}" {if ($fgroup && $fg.items|count == 1) || $smarty.get.show == "full"}style="display:block"{/if}>
			{$f.text}
			<a href="#" onclick="$(this).next().show().select().focus();$(this).hide();return false">ссылка</a><input style="display:none" class="" type="text" size="40" value="http://www.maryjane.ru/faq/group/{$k}/view/{$f.id}/">
		    </div>
	    {/foreach}
	    
	</div>
	{/foreach}
{else}

	{if $search}
	<p class="center">К сожалению по данному запросу ничего не найдено</p>
	{/if}

{/if}

<div class="clr clearfix"></div>
