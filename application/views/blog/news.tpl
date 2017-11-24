<!-- p>/blog/news.tpl</p -->

{*<div style="position:absolute;width:100%;height:1300px;top:0;left:0;background: url(/images/_tmp/blog_mocup.png) no-repeat 50% 0;margin:0;opacity:0.5;display:none"></div>*}

{literal}
<style type="text/css">
.late-artic .b-one-artic h3 a{width: auto;}
#rightcol, .rightcol {width:288px!important;}
h1{font-size: 14px;margin-bottom: 20px;}
</style>
{/literal}

<br clear="all">
{include file="blog/news.menu.tpl"}

{if $theme != 'all' && $theme != 'other'}
	<h1>{$theme_name}</h1>
{/if}

{if $H1}
	<h1>{$H1}</h1>
{/if}

{if $PAGE->reqUrl.1 != 'search'}
	{if $MobilePageVersion}	
		<span class="insteadOfTabs FAKEselectbox">
			<div class="select">
				<div class="text"></div>
				<b class="trigger"><i class="arrow"></i></b>
			</div>
			<div class="dropdown">
				<ul>
					{if $myPosts > 0}<li><a href="/{$PAGE->module}/user/{$USER->id}/" rel="nofollow">Мои посты</a></li>{/if}					
					<li {if $PAGE->reqUrl.1 == ''}class="selected"{/if}><a href="/{$PAGE->module}/" rel="nofollow">Все посты</a></li>

					<li {if $PAGE->reqUrl.1 == 'teaching'}class="selected"{/if}><a href="/{$PAGE->module}/teaching/" rel="nofollow">Обучение</a></li>	
					
					<li {if $PAGE->reqUrl.1 == 'samorazvitie'}class="selected"{/if}><a href="/{$PAGE->module}/samorazvitie/" rel="nofollow">Саморазвитие:креативность</a>	
					<li {if $PAGE->reqUrl.1 == 'competitions'}class="selected"{/if}><a href="/{$PAGE->module}/competitions/" rel="nofollow">Конкурсы</a></li>
					
					<li {if $PAGE->reqUrl.1 == 'interview'}class="selected"{/if}><a href="/{$PAGE->module}/interview/" rel="nofollow">Интервью</a></li>					
					<li {if $PAGE->reqUrl.1 == 'portfolio'}class="selected"{/if}><a href="/{$PAGE->module}/portfolio/" rel="nofollow">Портфолио</a></li>
					
					{if $USER->meta->mjteam}
					<li {if $PAGE->reqUrl.1 == 'mail'}class="selected"{/if}><a href="/{$PAGE->module}/mail/" rel="nofollow" style="border:1px dashed orange;border-bottom:0;">Рассылки</a></li>
					{/if}
					
				</ul>
			</div>
		</span>	
	{else}
	<div class="tabz clearfix" style="margin-bottom:20px;width:980px">
		<!--noindex-->	
		{if $myPosts > 0}
		<a href="/{$PAGE->module}/user/{$USER->id}/" rel="nofollow">Мои посты</a>
		{/if}	
		<a href="/{$PAGE->module}/" class="{if $PAGE->reqUrl.1 == ''}active{/if}" rel="nofollow">Все посты</a>

		<a href="/{$PAGE->module}/teaching/" class="{if $PAGE->reqUrl.1 == 'teaching'}active{/if}" rel="nofollow">Обучение</a>
		
		{*<a href="/{$PAGE->module}/winners/" class="{if $PAGE->reqUrl.1 == 'winners'}active{/if}" rel="nofollow">Победители</a>*}	
		
		<a href="/{$PAGE->module}/samorazvitie/" class="{if $PAGE->reqUrl.1 == 'samorazvitie'}active{/if}" rel="nofollow" >Саморазвитие:креативность</a>	
		<a href="/{$PAGE->module}/competitions/" class="{if $PAGE->reqUrl.1 == 'competitions'}active{/if}" rel="nofollow">Конкурсы</a>
		<a href="/{$PAGE->module}/interview/" class="{if $PAGE->reqUrl.1 == 'interview'}active{/if}" rel="nofollow">Интервью</a>
		<a href="/{$PAGE->module}/portfolio/" class="{if $PAGE->reqUrl.1 == 'portfolio'}active{/if}" rel="nofollow">Портфолио</a>	
		
		{if $USER->meta->mjteam}
		<a href="/{$PAGE->module}/mail/" class="{if $PAGE->reqUrl.1 == 'mail'}active{/if}" rel="nofollow" style="border:1px dashed orange;border-bottom:0;">Рассылки</a>
		{/if}
		<!--/noindex-->
	</div>
	{/if}
{/if}

<div class="b-blog-list {if $PAGE->url == '/blog/' || $PAGE->reqUrl.1=='news' || $SEARCH}mainBlog{/if}">
	{if $PAGE->reqUrl.1 == 'search'}
	
		{if $POSTS|count == 0}		
			По данному запросу не найдено ни одного поста. <a href="/search/?q={$smarty.get.q}">поискать везде</a>		
		{/if}
		
	{/if}

	{foreach from=$POSTS.big item="p" key="k"}
	<div class="b-one-artic" {if ($PAGE->url == '/blog/' || $PAGE->reqUrl.1=='news' || $SEARCH) &&($k ==2 || $k ==4)}style="margin-right:0"{/if}>
		<a class="artic-imglink" href="/{$PAGE->module}/view/{$p.post_slug}/"  rel="nofollow">
			<img src="{$p.path}" width="300" height="200" />
			<div class="infobar main">
				<div class="preview_count">{$p.visits}</div>
				<div class="comm_count">{$p.comments}</div>
			</div>
			{*else}
			<span class="comm-num">{$p.comments}</span>
			{/if*}
		</a>
		<div class="b-category">
			{if $theme == 'all'}{$p.post_theme}{/if}
			
			{if $USER->authorized && $USER->user_id == $p.post_author || $USER->user_id == "6199"} 
			<div class="edit-link">
				<a href="/{$PAGE->module}/edit/{$p.id}/" class="artic_edit">ред.</a>&nbsp;<a href="/{$PAGE->module}/delete/{$p.id}/" class="artic_del">удалить</a>
			</div>
			{/if}
		</div>
		<h3><a href="/{$PAGE->module}/view/{$p.post_slug}/">{$p.post_title}</a></h3>
		{if $p.post_tizer}
			<p>{$p.post_tizer}</p>
		{/if}
		<!--noindex-->
		<ul class="artic-details">
			<li class="li"><a href="/blog/user/{$p.user_login}/" rel="nofollow">{$p.user_login}</a></li>
			<li class="li">{$p.post_date}</li>
			{*<li class="li">Комментариев: {$p.comments}</li>*}
		</ul>		
        <!--/noindex-->
	</div>
	{/foreach}
	
	{if $USER->authorized && !$SEARCH && ($PAGE->url == "/blog/" || $PAGE->reqUrl.1=='news')}	
		<div class="butt_plus_post">
			<a href="/blog/new/" rel="nofollow">Добавить пост</a>
		</div>
	{/if}

	{if $PAGE->url != "/blog/" && $PAGE->reqUrl.1!='news' && !$SEARCH}<style>#content{ width:690px }</style>{/if}
	<div style="clear:both"></div>
</div>

<div class="late-artic">	
	{foreach from=$POSTS.post item="p"}
	
	<div class="b-one-artic">
		<a class="artic-imglink" href="/{$PAGE->module}/view/{$p.post_slug}/" rel="nofollow">
			<span class="img-wrap-white"><img src="{$p.path}" width="100" /></span>
			<span class="comm-num visits">{$p.visits}</span>
		</a>
		
		<div class="b-category">
			{if $theme == 'all'}{$p.post_theme}{/if}
		</div>
		<h3><a href="/{$PAGE->module}/view/{$p.post_slug}/">{$p.post_title}</a></h3>
		
		{if $p.post_tizer}
			<p>{$p.post_tizer}</p>
		{/if}
		
		<!--noindex-->
		<ul class="artic-details">
			<li class="li"><a href="/blog/user/{$p.user_login}/" rel="nofollow">{$p.user_login}</a></li>
			<li class="li">{$p.post_date}</li>
			<li class="li">Комментариев: {$p.comments}</li>			
		</ul>	
        <!--/noindex-->	
		{if $USER->authorized && $USER->user_id == $p.post_author || $USER->user_id == "6199"}
		<div class="edit-link"><a href="/{$PAGE->module}/edit/{$p.id}/" class="artic_edit">ред.</a>&nbsp;<a href="/{$PAGE->module}/delete/{$p.id}/" class="artic_del">удалить</a></div>
		{/if}
	</div>
	
	{/foreach}
</div>

{if $PAGE->url == "/blog/" || $PAGE->reqUrl.1=='news' || $SEARCH}
<div id="rightcol">
	<div id="rightcol-inner">				
		{include file="blog/news.sidebar.tpl"}				
	</div>
</div>
{/if}

{if $tpages > 1}
<div class="pages">
	<div class="listing">
		<div>Страницы: </div>{$PAGES}
	</div>
</div>
{/if}