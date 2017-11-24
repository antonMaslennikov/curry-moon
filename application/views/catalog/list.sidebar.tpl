{literal}
<style>
	.info-author .autor-city,
	.info-author .author-views,
	.author-liked, .author-selected,
	.info-author .author-sharing-btn { height: 19px; }
	.author-sharing-btn .b-share__handle { margin-left: -25px !important;padding-left: 25px !important;}

	.info-author .links-panel { padding: 16px 12px; width: 157px; }
	.info-author .links-panel .questions a { text-decoration: underline; }
	.info-author .links-panel a:hover {text-decoration: none; }
	.info-author .links-panel a:active {color:red; }

	.sidebar_filter ul.service-icons li{width: auto !important;max-width: 150px !important;}
	.service-icons li a span {padding: 25px;font-weight: normal;width: 180px;}

	.ico-diz.red_he{background-position:-1px -162px!important;width:30px!important;height:30px!important;margin:0!important;color:#ffffff;font-size:11px;text-align:center;line-height:23px;background-image:url('/images/_tmp/people/people_sprite.png');background-repeat:no-repeat;display:inline-block;}
</style>
{/literal}

{if $USER->client->browser=='Opera' && $USER->client->version < 13}
<style>
	.pageTitle.table h1 { float:left;margin:0 }
</style>
{/if}

<div class="sidebar_filter">
	
{if $user_id}

	<div id="info" class="blockAuthor">
		<div class="info-author">
			<!--noindex-->
			<div class="right">
				{if $USER->id != $user.user_id}
					
					<div class="left newSelect51 notText {if $selected eq "true"}yes{/if}">
						{if $selected eq "true"}
							<a href="/selected/add/{$user.user_id}/" class="selected51 ico selectedAjax" title="Удалить автора из избранных" rel="nofollow">Уведомления</a>
						  {else}
							<a href="/selected/add/{$user.user_id}/" class="selected51no ico selectedAjax" title="Подписаться" rel="nofollow">Подписаться</a>
						{/if}
						<span class="favoriteCount{if $selected != 'true'}No{/if}"><i></i>{$user.selected}</span>
						<div class="hint2">
							<div class="wi"><div class="i"></div></div>
							<ul>	
								<li class="{if $USER->subscriptions.2 < 0}{else}a{/if}"><a href="/subscribe/{$USER->id}/{$subscribe_code}/{if $USER->subscriptions.2 < 0}2{else}-2{/if}/"rel="nofollow">Новые работы</a></li>
								{* <li class="{if $USER->subscriptions.10 < 0}{else}a{/if}"><a href="/subscribe/{$USER->id}/{$subscribe_code}/{if $USER->subscriptions.10 < 0}10{else}-10{/if}/"rel="nofollow">Победы</a></li> *}
								<li class="{if $USER->subscriptions.4 < 0}{else}a{/if}"><a href="/subscribe/{$USER->id}/{$subscribe_code}/{if $USER->subscriptions.4 < 0}4{else}-4{/if}/"rel="nofollow">Посты</a></li>
							</ul>	
						</div>						
					</div>				   
				{/if}
				
				{if $user.pretendents > 0}
					<br clear="all"/>
					<div class="left" style="padding-top:3px;margin-top: 10px;margin-left: 14px;">
						<a href='/people/designers/#designers' title="Побед" rel="nofollow"><span class="ico-diz red_he">{$user.pretendents}</span></a>
					</div>
				{/if}				
			</div>		
			<!--/noindex-->
			
			{if !$partner}
				<a href="/{if $filters.category}catalog/{$user.user_login}{else}profile/{$user.user_id}{/if}/" rel="nofollow" class="avatar" title="Дизайнер - {$user.user_login}">{$user.avatar}</a>
				
				<div class="user_support-link-show-wrap">
					<a href="/help_author/{$user.user_id}/?{* good_id=10444& *}width=685&height=415" {if $USER->authorized}class="thickbox"{else}onclick="return qLogin.showForm();"{/if} rel="nofollow">Поддержать автора</a>	
					<div class="arr-r"></div>
				</div>
					
				{if $user.meta_description}
				<p style="word-wrap:break-word">				
					{$user.meta_description_1}{if $user.meta_description_2!=''}<a title="подробнее" href="#" rel="nofollow" class="more_meta_description_2" onclick="$(this).next('span').show();$(this).remove();return false;"><br/>подробнее</a><span style="display:none">{$user.meta_description_2}</span>{/if}		
				
					{*<span style="margin-right:15px">{$user.meta_description}</span>*}
				</p>
				{/if}
			{/if}
			
			
			{if $filters.category == 'poster'}		
				<br clear="all"><br clear="all">
				{include file="catalog/list.sidebar.filters.poster.tpl"}
			{/if}
			
			{include file="catalog/list.sidebar.filters.drop-down.tpl"}
			
			{if $user_top_tags|count > 0}
				<div class="top10tags" style="margin:25px 0 0 0px">
					<div class="wrap">
					{foreach from=$user_top_tags item="t"}
						<a class="tag" rel="nofollow" href="/catalog/{if $partner}selected/{/if}{$user.user_login}/tag/{$t.slug}/{*ajax/*}">{$t.name}</a>
					{/foreach}
					</div>
				</div>
				
				{*Sergey Rodin 25 августа 2015 г., без аякса с перезагрузкой*}
				{*literal}				
				<script type="text/javascript">
				$(document).ready(function(){
					$(".top10tags div.wrap a").click(function(){
						if (!$(this).hasClass('active')) {
							$(".catalog_goods_list .list_wrap").append('<div class="loadding-cover" id="loadding-cover"></div>');
							setTimeout(function(){$("#loadding-cover").remove();}, 3000);
							
							$(".top10tags div.wrap a").removeClass('active');
							$(this).addClass('active');
							var gall_link = $(this).attr('href');
							$.get(gall_link, function(content){
								$(".list_wrap ul").html(content);							
								
								//двруг до этого был вид на людях
								$('.catalog_goods_list').addClass('neformat');
								$('.catalog_goods_list').removeClass('list_trjapki');
								$('.b-catalog_v2').removeClass('b-catalog_v3');
								$('.b-catalog_v2').removeClass('list_main_user');	
								//кнопке активность
								$('.b-filter_view a').removeClass('active');
								$(".b-filter_view a.view-other").addClass('active');
								
								$('.b-filter_tsh a').each(function(){
									var self=$(".b-filter_view a.view-other");
									var sort = $(this).attr('data-sort');
									var url = urlViews[$(self).index()].replace('|%sort%|', sort);
									$(this).attr('href', url);
								});								
								
								$("#loadding-cover").remove();
								initShowMore();
							});
						}				
						return false;
					});
				});
				</script>		
				{/literal*}				
			{/if}
			
			<div class="params">
				<div class="name clr">
					<a href="/profile/{$user.user_id}/" title="Дизайнер футболок - {$user.user_login}">{$user.user_login}</a>
					
					<span class="rating">
						{$user.user_designer_level}
					</span>
					
					{if $user.user_show_name == "true" && $user.user_name != ""}<div class="author_username">{$user.user_name}</div>{/if}

					
				</div>
				{if $user.user_url}
				<div class="author-link">
					<a href="/goto/{$user.user_url}" rel="nofollow" title="{$user.user_url}" target="_blank">{$user.user_url}</a>
				</div>
				{/if}	
				
				
				{if $user.user_city != ''}<div class="autor-city">{$user.user_city}</div>{/if}
				<div class="author-views">{$L.SHOPWINDOW_views} {$user.visits}</div>
				<div class="author-liked">{$L.SHOPWINDOW_like}&nbsp;<span title="мне нравится">{if $user.liked < 6}{$user.liked}{else}<a href="/selected/{$user.user_id}/">{$user.liked}</a>{/if}</span> / <span title="я нравлюсь">{$user.me_liked}</span></div>
			
				{if $PAGE->module == 'catalog.v3' && $user && !$good && !$filters.category}
					{*исключаем с новой витрины*}
				{else}
				<div class="author-sharing-btn">
					<script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script>
					<div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="link" data-yashareQuickServices=""></div> 					
				</div>
				{/if}
				
				
				<br/>
				<div class="clearfix iconAutSidebar">
					{if $designer->meta->user_facebook}
					<a target="_blank" href="https://www.facebook.com/{$designer->meta->user_facebook}/" target="_blank" rel="nofollow"><img border="0" style="vertical-align: middle;margin:0;margin-right: 8px;" src="/images/social/facebook.gif" title="" alt="F" width="16" height="16"/></a>						
					{/if}
					{if $designer->meta->user_vk}
					<a target="_blank" href="https://vk.com/id{$designer->meta->user_vk}" rel="nofollow" target="_blank"><img border="0" style="vertical-align: middle;margin:0;margin-right: 8px;" alt="Vk" title="" src="/images/social/vkontakte.png" width="16" height="16"/></a>		
					{/if}
					{if $designer->meta->user_gplus}
					<a target="_blank" href="https://plus.google.com/u/0/{$designer->meta->user_gplus}/" target="_blank" rel="nofollow"><img border="0" style="vertical-align: middle;margin:0;margin-right: 8px;" src="/images/social/google-plus.gif" title="" alt="g+"width="16" height="16"/></a>				
					{/if}
					{if $designer->meta->user_instagram && $designer->meta->user_instagram_name}
					<a target="_blank" href="https://www.instagram.com/{$designer->meta->user_instagram_name}/" target="_blank" rel="nofollow"><img border="0" style="vertical-align: middle;margin:0;margin-right: 8px;" src="/images/social/Instagram.jpg" title="Instagram" alt="Instagram"width="16" height="16"/></a>
					{/if}
					{if $designer->meta->user_lj}
					<a target="_blank" href="http://{$designer->meta->user_lj}.livejournal.com" rel='nofollow'><img border="0" style="vertical-align: middle;margin:0;margin-right: 8px;" src="/images/lj.png" title="lj" alt="lj"width="16" height="16"/></a>
					{/if}
					{if $designer->meta->user_lastfm}
					<a target="_blank" href="http://last.fm/user/{$designer->meta->user_lastfm}" rel='nofollow'><img border="0" style="vertical-align: middle;margin:0;margin-right: 8px;" src="/images/lastfm_ico.png" title="lastfm" alt="lastfm"width="16" height="16"/></a>						
					{/if}
					{if $designer->meta->user_flickr}
					<a target="_blank" href="http://flickr.com/photos/{$designer->meta->user_flickr}" rel='nofollow'><img border="0" style="vertical-align: middle;margin:0;margin-right: 8px;" src="/images/flickr.png" title="flickr" alt="flickr"width="16" height="16"/></a>
					{/if}
				</div>	
				
				{if $USER->meta->mjteam && $USER->meta->mjteam != 'fired'}
					<p class="write2user">
						<a href="#" style="border-color:orange;" class="dashed" onclick="return showMessenger('{$user.user_id}',0)" title="Написать сообщение"><span>Написать юзеру</span></a>
					</p>
				{/if}	
				
				<!--{if $user.selected > 0}
				<div class="author-selected">Работ в избранном {$user.selected_goods}</div>
				{/if}-->
				
				{*if $USER->user_id == $user.user_id || $USER->user_id == 27278 || $USER->user_id == 6199 || $USER->user_id == 86455 || $USER->user_id == 105091}
				<div class="links-panel">
					<div class="links">
					<a href="/stat/">{$L.SHOPWINDOW_selling_statistics}</a>
					<a href="/payback/">{$L.SHOPWINDOW_cash}</a>
					<a href="/blog/view/1246/" target="_blank">{$L.SHOPWINDOW_way_to_selmore}</a>
					</div>					
					<!--<div class="other-questions">
						<span>{$L.questions}</span>
						<a href="/feedback/?height=550&width=300" class="thickbox">{$L.SHOPWINDOW_right_us}</a>
					</div>-->
				</div>
				{/if*}				

			</div>
		</div>
	</div>
	
{else}

	{if $filters.category}
	
		<div class="productFilter" style="border:0 !important;padding:0">
		
			{if $filters.category == 'poster'}
			
				{include file="catalog/list.sidebar.filters.poster.tpl"}

			{elseif $filters.category == 'pillows'}
				
				<div class="cat-pillows">
					<a href="/{if $TAG}tag/{$TAG.slug}{else}catalog{/if}/pillows/" class="pillow-square {if $Style->style_slug == "pillow"}active{/if}">&nbsp;</a>
					<a href="/{if $TAG}tag/{$TAG.slug}{else}catalog{/if}/pillows/pillow-star/" class="pillow-star {if $Style->style_slug == "pillow-star"}active{/if}">&nbsp;</a>
					<a href="/{if $TAG}tag/{$TAG.slug}{else}catalog{/if}/pillows/pillow-round/" class="pillow-round {if $Style->style_slug == "pillow-round"}active{/if}">&nbsp;</a>
					<a href="/{if $TAG}tag/{$TAG.slug}{else}catalog{/if}/pillows/pillow-rectangle/" class="pillow-rect {if $Style->style_slug == "pillow-rectangle"}active{/if}">&nbsp;</a>
				</div>
				
			{elseif $filters.category == 'body'}
				
				<div class="cat-body">
					<a href="/{if $TAG}tag/{$TAG.slug}{else}catalog{/if}/body/full-printed-body-female-sleeveless" class="body-sleeveless {if $Style->style_slug == "full-printed-body-female-sleeveless"}active{/if}">&nbsp;</a>
					<a href="/{if $TAG}tag/{$TAG.slug}{else}catalog{/if}/body/full-printed-body-female-short-sleeves/" class="body-short {if $Style->style_slug == "full-printed-body-female-short-sleeves"}active{/if}">&nbsp;</a>
					<a href="/{if $TAG}tag/{$TAG.slug}{else}catalog{/if}/body/full-printed-body-female-medium-sleeves/" class="body-medium {if $Style->style_slug == "full-printed-body-female-medium-sleeves"}active{/if}">&nbsp;</a>
					<a href="/{if $TAG}tag/{$TAG.slug}{else}catalog{/if}/body/full-printed-body-female-long-sleeves/" class="body-long {if $Style->style_slug == "full-printed-body-female-long-sleeves"}active{/if}">&nbsp;</a>
				</div>
				
			{else}
				
				{if $filters.category != 'cup' && $filters.category != 'textile' && $filters.category != 'sumki' && $Fsizes|count > 0 && !$SEARCH}
					<h4 style="border:0 !important;">{$L.SHOPWINDOW_select_size}: {if !$fsize_not_selected}<a href="{if $TAG.slug != $module && !$SEARCH}/{$module}{/if}{if $SEARCH}/catalog{/if}{if $TAG.slug}/{$TAG.slug}{/if}/{$Style->category}/{$Style->style_slug}/{if $orderBy == 'new'}{$orderBy}/{/if}" title="Отменить фильтр" style="margin-left:10px"><img src="/images/icons/delete.gif" class="ico" /></a>{/if}</h4>

					<div class="select-size-box">	
						{foreach from=$Fsizes item="c"}	
							<a class="one-size {if $c.class=='on'}selected-size{/if}" {if $filters.sex == "kids"}style="font-size:11px"{/if} title="размер &mdash; {$c.size_name}" href="{if $TAG.slug != $module}/{$module}{/if}{if $TAG.slug}/{$TAG.slug}{/if}/{$Style->category}/{$Style->style_slug}/{$c.size_name}/{if $orderBy == 'new'}{$orderBy}/{/if}{if $SEARCH}?q={$SEARCH}{/if}">{$c.size_rus}<br><span>{$c.size_name}</span></a>
						{/foreach}
					</div>
									
					<br clear="all" style="clear: both;"/><br/>						
				{/if}				
				
				{if $Fcolors}
					{if $filters.category == "sweatshirts"}
						{if $filters.sex == "male"}
							{assign var="patterns_link" value="/catalog/patterns-sweatshirts/full-printed-sweatshirt-male/"}
						{elseif $filters.sex == "female"}
							{assign var="patterns_link" value="/catalog/patterns-sweatshirts/full-printed-sweatshirt-female/"}
						{elseif $filters.sex == "kids"}
							{assign var="patterns_link" value="/catalog/patterns-sweatshirts/full-printed-sweatshirt-kids/"}
						{/if}
					{elseif $filters.category == "futbolki"}
						{if $filters.sex == "male"}
							{assign var="patterns_link" value="/catalog/patterns/full-printed-t-shirt-male/"}
						{elseif $filters.sex == "female"}
							{assign var="patterns_link" value="/catalog/patterns/full-printed-t-shirt-female-short/"}
						{elseif $filters.sex == "kids"}
							{assign var="patterns_link" value="/catalog/patterns/full-printed-t-shirt-kids/"}
						{/if}
					{/if}
						
					{if ($Fcolors|count > 1 || $patterns_link) && !$SEARCH}				
						<h4 class="h4_select_color" style="border:0!important;margin-top:0">{$L.SHOPWINDOW_select_color}: {if !$fcolor_not_selected}<a href="{if $TAG.slug != $module && !$SEARCH}/{$module}{/if}{if $SEARCH}/catalog{/if}{if $TAG.slug}/{$TAG.slug}{/if}/{$Style->category}/{if $filters.size && !$fsize_not_selected}{$filters.size_name}/{/if}{if $orderBy == 'new'}{$orderBy}/{/if}" title="Отменить фильтр" style="margin-left:27px"><img src="/images/icons/delete.gif" class="ico" /></a>{/if}</h4>
						
						<div class="b-color-select clearfix">					
							{foreach from=$Fcolors item="c"}
								<div class="one-color s-{$s.status} {$c.class}" name="{$c.id}" group="{$c.group}" title="цвет &mdash; {$c.name}" style="background-color:#{$c.hex}">
									<a title="цвет &mdash; {$c.name}" href="{if $TAG.slug != $module}/{$module}{/if}{if $TAG}/{$TAG.slug}{/if}{if $task == 'top'}/top{/if}/{$filters.category}/{$c.style_slug}/{if !$fsize_not_selected}{$filters.size_name}/{/if}{if $orderBy != 'popular'}{$orderBy}/{/if}{if $SEARCH}?q={$SEARCH}{/if}">
										<span class="it {$c.name_en}"></span>
										{if $c.status == 'new'}
											<span class="fresh-icon"></span>
										{elseif $c.status == 'few'}
											<span class="sale-icon"></span>
										{/if}
									</a>
								</div>
							{/foreach}
							
							<div class="one-color">
								{if $patterns_link}
									<a href="{$patterns_link}" title="Полная запечатка" style="position: absolute; display: block; top: 5px; left: 5px;"><img src="/images/catalog/pattern-icon.png"></a>
								{/if}
							</div>
						</div>
						{/if}
					{/if}	
				{/if}
				
				{* if ($Style->category =='patterns' || $Style->category =='patterns-sweatshirts')}
					{if $Style->style_id != 883}
					<div id="input_man_woman" class="b-radio-manwomen radio-input">
						{if $Style->style_sex == "kids"}
							<a rel="nofollow" href="/{if $module=='search'}{$module}{else}{if $TAG && $module == 'tag'}{$module}/{$TAG.slug}{else}catalog{/if}{/if}/{$Style->category}/{if $Style->style_slug == 'full-printed-sweatshirt-kids' || $Style->style_slug == 'full-printed-sweatshirt-kids-female'}full-printed-sweatshirt-kids{else}full-printed-t-shirt-kids{/if}/popular/{if $SEARCH}?q={$SEARCH}{/if}" title="{$L.SIDEBAR_menu_men_sex}" class="type-select male {if $PAGE->reqUrl.2 && ($Style->style_slug == 'full-printed-t-shirt-kids' || $Style->style_slug == 'full-printed-sweatshirt-kids')}active{/if}"></a>
							<a rel="nofollow" href="/{if $module=='search'}{$module}{else}{if $TAG && $module == 'tag'}{$module}/{$TAG.slug}{else}catalog{/if}{/if}/{$Style->category}/{if $Style->style_slug == 'full-printed-sweatshirt-kids' || $Style->style_slug == 'full-printed-sweatshirt-kids-female'}full-printed-sweatshirt-kids{else}full-printed-t-shirt-kids{/if}-female/popular/{if $SEARCH}?q={$SEARCH}{/if}"  title="{$L.SIDEBAR_menu_female_sex}" class="type-select female {if $PAGE->reqUrl.2 && ($Style->style_slug == 'full-printed-t-shirt-kids-female' || $Style->style_slug == 'full-printed-sweatshirt-kids-female')}active{/if}"></a>
						{else}
							<a rel="nofollow" href="/{if $module=='search'}{$module}{else}{if $TAG && $module == 'tag'}{$module}/{$TAG.slug}{else}catalog{/if}{/if}/{$Style->category}/{if $Style->style_slug == 'full-printed-sweatshirt-male' || $Style->style_slug == 'full-printed-sweatshirt-female'}full-printed-sweatshirt{else}full-printed-t-shirt{/if}-male/popular/{if $SEARCH}?q={$SEARCH}{/if}" title="{$L.SIDEBAR_menu_men_sex}" class="type-select male {if $PAGE->reqUrl.2 && $Style->style_sex == 'male'}active{/if}"></a>
							<a rel="nofollow" href="/{if $module=='search'}{$module}{else}{if $TAG && $module == 'tag'}{$module}/{$TAG.slug}{else}catalog{/if}{/if}/{$Style->category}/{if $Style->style_slug == 'full-printed-sweatshirt-male' || $Style->style_slug == 'full-printed-sweatshirt-female'}full-printed-sweatshirt-female{else}full-printed-t-shirt-female-short{/if}/popular/{if $SEARCH}?q={$SEARCH}{/if}"  title="{$L.SIDEBAR_menu_female_sex}" class="type-select female {if $PAGE->reqUrl.2 && $Style->style_sex == 'female'}active{/if}"></a>
						{/if}
					</div>
					<div class="filter_hoodi_sweats{if $PAGE->reqUrl.2 == ''} opacity1{/if} clearfix">
						{if $Style->style_sex == "kids"}
							<a rel="nofollow" href="/{if $module=='search'}{$module}{else}{if $TAG && $module == 'tag'}{$module}/{$TAG.slug}{else}catalog{/if}{/if}/patterns/full-printed-t-shirt-kids{if $Style->style_slug == 'full-printed-t-shirt-kids-female' || $Style->style_slug == 'full-printed-sweatshirt-kids-female'}-female{/if}/popular/{if $SEARCH}?q={$SEARCH}{/if}" title="{$L.SIDEBAR_menu_men_sex}" class="futba {if $PAGE->reqUrl.2 && ($Style->style_slug == 'full-printed-t-shirt-kids' || $Style->style_slug == 'full-printed-t-shirt-kids-female')}active{/if}"></a>
							<a rel="nofollow" href="/{if $module=='search'}{$module}{else}{if $TAG && $module == 'tag'}{$module}/{$TAG.slug}{else}catalog{/if}{/if}/patterns-sweatshirts/full-printed-sweatshirt-kids{if $Style->style_slug == 'full-printed-t-shirt-kids-female' || $Style->style_slug == 'full-printed-sweatshirt-kids-female'}-female{/if}/popular/{if $SEARCH}?q={$SEARCH}{/if}" title="{$L.SIDEBAR_menu_men_sex}" class="sweatshirts {if $PAGE->reqUrl.2 && ($Style->style_slug == 'full-printed-sweatshirt-kids' || $Style->style_slug == 'full-printed-sweatshirt-kids-female')}active{/if}"></a>
						{else}
							<a rel="nofollow" href="/{if $module=='search'}{$module}{else}{if $TAG && $module == 'tag'}{$module}/{$TAG.slug}{else}catalog{/if}{/if}/patterns/full-printed-t-shirt-{if $PAGE->reqUrl.2 =='' || $PAGE->reqUrl.2 == 'popular'}female-short{else}{if $Style->style_sex == 'female'}female-short{else}male{/if}{/if}/popular/{if $SEARCH}?q={$SEARCH}{/if}" title="Футболка" class="futba {if $PAGE->reqUrl.2 && ($Style->style_slug == 'full-printed-t-shirt-male' || $Style->style_slug == 'full-printed-t-shirt-female' || $Style->style_slug == 'full-printed-t-shirt-female-short')}active{/if}"></a>
							<a rel="nofollow" href="/{if $module=='search'}{$module}{else}{if $TAG && $module == 'tag'}{$module}/{$TAG.slug}{else}catalog{/if}{/if}/patterns-sweatshirts/full-printed-sweatshirt-{if $PAGE->reqUrl.2 == '' || $PAGE->reqUrl.2 == 'popular'}female{else}{if $Style->style_sex == 'female'}female{else}male{/if}{/if}/popular/{if $SEARCH}?q={$SEARCH}{/if}"  title="Свитшот" class="sweatshirts {if $PAGE->reqUrl.2 && ($Style->style_slug == 'full-printed-sweatshirt-female' || $Style->style_slug == 'full-printed-sweatshirt-male')}active{/if}"></a>
						{/if}
					</div>
					{/if}
				{/if *}
					
				{if $PAGE->reqUrl.2 != '' && ($Style->category == 'patterns' || $Style->category == 'patterns-sweatshirts' || $Style->category == 'patterns-tolstovki' || $Style->category == 'body' || $Style->category =='bomber')}
					{if $USER->client->ismobiledevice == '1'}
						<iframe src="https://player.vimeo.com/video/113540583" width="174" height="120" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
					{else}
						<object width="174" height="120">
							<param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id=113540583">
							<param name="wmode" value="window">
							<param name="allowFullScreen" value="true">
							<embed src="http://vimeo.com/moogaloop.swf?clip_id=113540583" type="application/x-shockwave-flash" wmode="window" allowfullscreen="true" width="194" height="120">
						</object>	
					{/if}	
				{/if}
			
		</div>	
		<div class="clear"></div>
	{/if}
	
	{if $filters.category == 'enduro' || $filters.category == 'jetski' || $filters.category == 'atv' || $filters.category == 'snowmobile'|| $filters.category == 'helmet' || $filters.category == 'helm'}
		{include file="catalog/list.sidebar.filters.drop-down.tpl"}	
		<br clear="all"/><br clear="all"/>
		{*<iframe width="172" height="95" src="https://www.youtube.com/embed/D54OBhOYt4Y" frameborder="0" allowfullscreen></iframe>
		<br clear="all"/>*}
	{/if}
	
	{if $SEARCH== 'мото' || $TAG.slug=='motocikl' || $category == 'moto' || $filters.category == 'enduro' || $filters.category == 'jetski' || $filters.category == 'atv' || $filters.category == 'snowmobile'|| $filters.category == 'helmet' || $filters.category == 'helm'}
		<br clear="all"/>		
		<a class="link-img" href="/stickermize/style,467/"  rel="nofollow">
			<img height="118" width="172" alt="Конструктор наклеек на бак" title="Конструктор наклеек на бак" src="/images/catalog/bak_stickers3_172.jpg">
		</a>
		<br/><br/>
		<a class="link-img" href="/catalog/moto/disk/"  rel="nofollow">
			<img height="170" width="172" alt="Наклейки на диски" title="Наклейки на диски" src="/images/catalog/disk_ban.gif"/>
		</a>
		<br/><br/>
		{if $SEARCH== 'мото' || $TAG.slug=='motocikl'}
		<a class="link-img" href="/catalog/moto/"  rel="nofollow">
			<img height="170" width="172" alt="Наклейки на бак" title="Наклейки на бак" src="/images/catalog/bak_stickers.jpg"/>
		</a>		
		{else}		
		<a class="link-img" href="/tag/motocikl/"  rel="nofollow">
			<img height="170" width="172" alt="Наклейки на мотоцикл" title="Наклейки на мотоцикл" src="/images/catalog/bak_stickers2.jpg"/>
		</a>
		{/if}
		<br/><br/>
		{if $filters.category != 'enduro'}
		<a href="/catalog/enduro/" rel="nofollow" class="link-img">
			<img height="170" width="172" alt="Наклейки для мотоциклов" title="Наклейки для мотоциклов" src="/images/catalog/mjmoto.jpg">
		</a>
		<br/><br/>	
		{/if}
		{*<a class="link-img" href="/odnocvet/" title="mjforall" rel="nofollow">
			<img height="110" width="172" alt="mjforall" src="/images/odnocvet/150_2tshirt.gif"/>
		</a>*}
	{/if}	
	
	{if $filters.category != 'enduro' && $filters.category != 'jetski' && $filters.category != 'atv' && $filters.category != 'snowmobile' && $filters.category != 'helmet' && $filters.category != 'helm'}
		
		{if $PAGE->url == "/catalog/stickers/" || $category == 'phones' || $category == 'touchpads'  || $category == 'laptops' || $category == 'ipodmp3'}				
			{if !$Style->model_preview}
				{if $category == 'phones' || $category == 'touchpads'  || $category == 'laptops' || $category == 'ipodmp3'}<br clear="all" /><br clear="all" />{/if}
			{/if}
			{include file="catalog/list.sidebar.filters.tpl"}
		{/if}
	
		{include file="catalog/list.sidebar.filters.drop-down.tpl"}	
		
	{/if}	
	
	{if $filters.category=='boards' && $style_slug == 'snowboard'}
		<br clear="all"/><br clear="all"/>		
		<iframe src="https://player.vimeo.com/video/17316883" width="172" height="121" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
		<br clear="all"/>
		<iframe src="https://player.vimeo.com/video/35860887" width="172" height="121" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>		
		<br clear="all"/>
	{/if}
	
	<br clear="all" /><br/>
	
	{if  $stock.good_stock_quantity > 0 && $Style && ($USER->id == 27278 || $USER->id == 6199 || $USER->id == 105091)}	
	<a class="link-img" href="/customize/style,{$Style->id}/?utm_source=sidebar&utm_medium=banner&utm_term={$Style->style_name}&utm_campaign=customize" rel="nofollow" style="border:1px dashed orange;border-width:1px 0">
		<img height="118" width="172" alt="Конструктор" title="Конструктор" src="/images/catalog/constructor.gif">
	</a>	
	{/if}
	
	{if $filters.category != 'enduro' && $filters.category != 'jetski' && $filters.category != 'atv' && $filters.category != 'snowmobile' && $filters.category != 'helmet' && $filters.category != 'helm'}
	<br/><br/>
		<!--вкладки соц. сети list.sidebar.tpl-->
		{assign var=soc_width value=198}
		{include file="catalog/tabs.soc.vk_fb.tpl"}
	<br />
	{/if}
	
	{if $TAG.slug == 'detskie' || $task=='kids' || ($filters.category== "futbolki" && $filters.sex == 'kids')}
		<a href="/foto-na-futbolku/" title="Печать фото на футболках"><img src="/images/banners/bright.jpg" alt="Печать фото на футболках" width="200"></a>
	{/if}
	
	{if $TAG.details_value_more}
	    <div style="background:#ccc;padding:10px">
			<div style="color:white;margin-top:-5px;margin-bottom:-5px; float:left;width:100px;cursor:pointer" onclick="swichBlockVisible('catalog-block', this)">подробно</div>
			<div class="clr"></div>
		</div>
	
		<div class="moduletable" style="border:1px solid #ccc;width:148px; display:none;" id="catalog-block">
			{if $TAG}
				<h1>
				{if $TAG.title}
					{$TAG.title}
				{else}
					Футболки {$TAG.name}
				{/if}
				</h1> 
			{/if}
		
			<span style="font-size:12px;">{$TAG.details_value_more}</span>
			<br clear="all" />
			<br/><br />
		</div>
	{/if}
	
	{*if $PAGE->url != "/catalog/stickers/" && $filters.category != 'enduro' && $filters.category != 'jetski' && $filters.category != 'atv' && $filters.category != 'snowmobile'}
		<ul class="service-icons" style="margin:45px 0 24px">
			<li style="margin:0 0 6px 19px" rel="nofollow">
				<a href="/catalog/" class="icon2">
					<span style="left: -80px"><i></i>Магазин футболок №1. Нам 11 лет! Maryjane.ru 1-ый магазин футболок в Москве. Сервис №1, Качество №1. Оригинальность №1. <br/>Сделано в Москве. Конструктор футболок.</span>
				</a>
			</li>
			<li style="margin:0 0 12px 8px">
				<a href="#" class="icon3" rel="nofollow">
					<span style="left: -69px;"><i></i>Технология компании DuPont. При печати на футболках, мы используем только высококачественные водные краски DuPont (E.I. du Pont de Nemours and Company) с запатентованной технологией. DuPont — американская химическая компания, одна из крупнейших в мире.</span>
				</a>
			</li>
			<li style="margin:0 0 14px 45px;">
				<a href="#"  title="Магазин прикольных футболок" class="icon1" rel="nofollow">
					<span style="left: -106px;">Доставка 24 часа. Приём и обработка заказов происходит с 10 утра до 8 вечера, что позволяет нам отправлять заказы на следующий день даже при оформлении в 8 вечера !<i></i></span>
				</a>
			</li>
			<li style="margin:0 0 18px 34px;">
				<a href="#" class="icon4" rel="nofollow">
					<span style="left: -95px;"><i></i>Наша Гарантия 40 стирок! Если принт не продержался — мы вернём вам деньги!</span>
				</a>
			</li>
			<li style="margin:0 0 12px 42px;">
				<a href="/dealer/izgotovlenie-futbolok.php" class="icon5" rel="nofollow">
					<span style="left: -103px;"><i></i>100% хлопок, высший сорт. Для изготовления футболок, мы используем только лучший хлопок качества пенье!</span>
				</a>
			</li>
			<li style="margin:0 0 15px 42px;">
				<a href="#" class="icon6" rel="nofollow">
					<span style="left: -103px;"><i></i>Возврат обмен бесплатно. Не подошла футболка? Курьер привезёт вам другую футболку на замену. Мы &mdash; верим нашим клиентам и всегда вернём деньги оперативно. Если вы того пожелаете.</span>
				</a>
			</li>
			<li style="margin:0 0 20px 17px;">
				<a href="/customize/" class="icon7" rel="nofollow">
					<span style="left: -78px;"><i></i>Пропускает воздух. Технология MJHIGH, применяемая при печати на белых футболках Maryjane.ru, позволяет изделию пропускать воздух, даже при двухслойной печати, это позволяет вашему телу дышать.</span>
				</a>
			</li>
			<li style="margin-left:35px;">
				<a href="#" class="icon8" rel="nofollow">
					<span style="left:-96px;"><i></i>Доставка футболок по всему миру. Ускоренная курьерская служба DPD  или Почта России доставит Вашу футболку в любую точку мира.</span>
				</a>
			</li>
		</ul>
	{/if*}	
{/if}
</div>