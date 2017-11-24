{literal}
<style type="text/css">
	#content {width: 730px;}
	div.printshopBuy a {display: block; height:40px; margin: auto; background-color:#17B3E8;width:100px; text-transform:none;font-size:10px; font-weight:normal; color:#FFFFFF}
	div.printshopBuy a span {text-transform: uppercase;} 
	.tag-goods { height:100px }
	.tag-goods a img { width:100px;height:80px;display:block; }

	.one_tag {color:#ccc}

	.tags-page, .tags-page_all-tags {float:left;width:730px}
	.tags-page_tags-title {float:left; width:100%; font-family: 'MyriadPro-CondIt'; color:#504F4F; font-size:28px; margin:15px 0;}

	.tags-page .tags-list .li {list-style:none; padding:0;  display: inline; margin: 0 7px 7px 0;}
	.tags-list .li .designer {display: inline; line-height: 1.5em; margin:0; padding: 1px 3px; vertical-align: baseline; color: #999;}
	.tags-list .li .designer:hover {color:#FFF; text-decoration: none; background:#00A851;background-color: #00A851;border-radius: 2px; cursor: pointer;}
</style>
{/literal}

{include file="blog/news.menu.tpl"}

<div class="clr clearfix"></div>

{if $TAGS|count>0}

	<div class="tags-page">
	
		<div class="tabz" style="clear:none;float: left">
			<a href="#" class="active" rel="nofollow">теги постов</a>
			<a href="/catalog/authors/" rel="nofollow">все авторы</a>
			<a href="/catalog/tags/" rel="nofollow">теги работ</a>
						
		</div>
	
		<div id="tags" class="tags-page_all-tags">
			
			<h1 class="tags-page_tags-title">Теги</h1> 
		<ul class="tags-list">
	        {foreach from=$TAGS item="item"}
			<li class="li">
		    <a href="/blog/tag/{$item.slug}/" class="designer" title="Работ: {$item.count}" rel="nofollow" style="font-size:{$item.font}px; white-spance:nowrap"><span><nobr>{$item.name}</nobr></span></a>	
		    </li>
			{/foreach}
	        </ul>
		</div>
		
		<p style="clear: both"><a href="/alltag/" style="color:#CCC">все теги</a></p>
	
	</div>

{/if}


{if $POSTS || $POSTS_short}

<h1 class="left tag-title"><span>{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}</span></h1>

<div class="tags_details_wrap">
	<p class="tag-details_p">{$tag_info.details_value}</p>
</div>

<div class="b-tags-list">
	
	{foreach from=$POSTS item="POST"}
	<div class="b-one-artic">
		<a class="artic-imglink" href="/blog/view/{$POST.post_slug}/">
			<img src="{$POST.PIC}" alt="{$POST.NAME}" />				
		</a>
		
		<div class="b-category">
			{$POST.post_theme}
		</div>
		
		<h3><a href="/blog/view/{$POST.post_slug}/">{$POST.NAME}</a></h3>
		<!--noindex-->
		<ul class="artic-details">
			<li class="li"><a href="/profile/{$POST.AUTHORID}/" rel="nofollow">{$POST.AUTHORLOGIN}</a></li> <li class="li">|</li>
			<li class="li">{$POST.DATE}</li> <li class="li">|</li>
			<li class="li comm_count" title="Комментариев">{$POST.COMMENTS}</li>
		</ul>		
		<!--/noindex-->
	</div>
	{/foreach}
		
	<div class="b-tags-list late-artic">
		{foreach from=$POSTS_short item="POST_short"}
		<div class="b-one-artic">
			<a class="artic-imglink" href="/profile/{$POST_short.AUTHORID}/"title="{$POST_short.AUTHORLOGIN}" rel="nofollow">{$POST_short.AVATAR}</a>
			
			
			<div class="b-category">
				{$POST_short.post_theme}
			</div>
			
			<h3><a href="/blog/view/{$POST_short.post_slug}/">{$POST_short.NAME}</a></h3>
			
			<!--noindex-->
			<ul class="artic-details">
				<li class="li"><a href="/profile/{$POST_short.AUTHORID}/" rel="nofollow">{$POST_short.AUTHORLOGIN}</a></li> <li class="li">|</li>
				<li class="li">{$POST_short.DATE}</li> <li class="li">|</li>
				<li class="li">Комментариев: {$POST_short.COMMENTS}</li>
			</ul>
			<!--/noindex-->	
		</div>
		{/foreach}
	</div>	
	
	{if $tag_info.details_value_more != ''}
	<p style="clear:both">{$tag_info.details_value_more}</p>
	{/if}
	
</div>
{else}
<p>Не обнаружено постов с данным тегом</p>
{/if}