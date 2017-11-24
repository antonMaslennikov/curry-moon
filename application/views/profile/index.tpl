{include file="profile/tabz.tpl"}

{literal}
<style type="text/css">
#content {width: 980px!important;}

/*лидеры продаж и просмотров*/
.ico-diz{background-image: url('/images/_tmp/people/people_sprite.png');background-repeat: no-repeat;
display: inline-block;margin:0 15px 20px 5px;}
.lidery .prodaj .ico-diz{background-position: 0 0;width: 55px;height: 32px;}
.lidery .vizit .ico-diz{margin:0 10px 23px -3px;background-position: 0px -35px;width: 43px;height:26px;}
.ico-diz.up{background-position: -9px -111px!important;width: 9px!important;height: 12px!important;margin:0!important;}
.ico-diz.dow{background-position: 1px -111px!important;width: 9px!important;height: 12px!important;margin:0!important;}
.ico-diz.mikrofon{background-position: 0px -126px!important;width: 21px!important;height: 34px!important;margin:0!important;}
.ico-diz.red_he{background-position: -1px -162px!important;width: 30px!important;height: 30px!important;margin:0!important;color:#ffffff;font-size:11px;text-align: center;line-height: 23px;}
.ico-diz.my_l{background-position: -22px -122px!important;width: 33px!important;height: 32px!important;margin:0!important;font-size:11px;text-align: center;line-height: 32px;}

.ico-lj, .ico-lastfm, .ico-flickr {vertica-align:middle;padding-left:25px;margin-right:20px}
</style>
{/literal}

{if $USER_NOT_FOUND}
	
	<div class="noaccess border" style="padding:200px 0;">Пользователь с номером &laquo; {$USER_NOT_FOUND.user_id} &raquo; не найден, <a href="/feedback/?height=550&width=300" class="dashed thickbox">обратитесь к администрации</a>.</div>


{elseif $USER_NOT_ACTIVATED}


	<style type="text/css">#content{ldelim}width:100%{rdelim}</style>
	<div class="noaccess border" style="padding:200px 0">
		<p>Пользователь &laquo; {$USER_NOT_ACTIVATED.user_login} &raquo; ещё не активировал свой профиль.</p>
		{if $USER->user_id == $USER_NOT_ACTIVATED.user_id}
			<script type="text/javascript" src="/js/registration.v2.js"></script>
			<form action="/registration/resend/" method="get">
				<h5 class="error" style="display:none;" id="resendEmailError"></h5>
				<h5 class="green" id="resendEmailSuccess"></h5>
				<input type="hidden" class="inputbox" name="resendemail" id="resendregemail" value="{$USER_NOT_ACTIVATED.user_email}" />
				<input type="button" name="button" id="resendFormButton" value="отправить код активации повторно" style="font-size:14px;padding-left:10px;padding-right:10px" />
			</form>
		{/if}
	</div>

{else}

	{literal}
	<style type="text/css">
	#content {width:100%}
	.tag a {color:#cdcdcd;}
	.tag {height:200px; overflow:scroll;}
	.sml-orden { background: url("http://maryjane.ru/images/icons/bg-orden-sml.gif") no-repeat scroll 50% 50% transparent; float: right; height: 25px; margin: 0; position: absolute;; width: 37px; top:10px; left:10px;}
	
	.write2user a {text-transform:uppercase;text-decoration:none;font-size:10px;color:#808080}
	.write2user a img 
	{
	    background: url("/images/icons/write2user.gif");
	    height: 10px;
	    margin: 5px 2px 0 0;
	    vertical-align: top;
	    width: 15px;
	}
	
	.selected51no {
		background:url(/images/to-vote-min-gray.png) no-repeat 0 -22px !important;
	}
	.selected51no:hover {
		background:url(/images/to-vote-min-gray.png) no-repeat 0 -22px !important;
	}
	.selected51 { 
		background:url(/images/to-vote-min.png) no-repeat 0 -22px !important;
	}
	.selected51:hover { 
		background:url(/images/to-vote-min.png) no-repeat 0 -22px !important;
	}
	
	</style>
	<script>
		var FileAPI = {
			  debug: false
			, pingUrl: false 
			
			// @required
			, staticPath: '/js/file-upload/' // @default: "./"

			// @optional
			, flashUrl: '/js/file-upload/FileAPI.flash.swf' // @default: FileAPI.staticPath + "FileAPI.flash.swf"
			, flashImageUrl: '/js/file-upload/FileAPI.flash.image.swf' // @default: FileAPI.staticPath + "FileAPI.flash.image.swf"
			//,support: { html5: false }
		};
	</script>
	<script src="/js/file-upload/FileAPI.min.js"></script>
	<script src="/js/file-upload/FileAPI.id3.js"></script>
	<script src="/js/file-upload/FileAPI.exif.js"></script>
	<script src="/js/file-upload/FileAPI.framework.js"></script>
	<script>
		$(document).ready(function(){
			
			$('#addavatar').FileAPI({
				url: $('#addavatar').parents('form:first').attr('action'),
				data: {name: $('#addavatar').attr('name')},
				fileExt: '.png,.jpg,.jpeg,.gif',
				select: function(file){
					$('#addavatar').hide();
					$('#addavatarprogress').show();
					$('img.avatar').attr('src','/images/empty.gif'+'?p='+(Math.random()*10000).toString()).addClass('loading');
				},
				complete: function(file, response){
					$('#addavatarprogress').hide();
					$('#addavatar').show();
					
					var e = eval('('+response+')');
					if (e.error) { alert(e.error); }
					else if (e.ok) {
						$('img.avatar').attr('src', e.ok+'?p='+(Math.random()*10000).toString()).removeClass('loading');
						$('#addavatar').parent().hide();
						$('a#remavatar').show();
					}
				}
			});
			
			$('a#remavatar').click(function(){
				$.get($(this).attr('href'),function(response){ 
					var e = eval('('+response+')');
					if (e.error) { alert(e.error); }
					else if (e.status == 'ok') {
						$('img.avatar').attr('src', '/images/designers/nophoto100.gif?p='+(Math.random()*10000).toString());
						$('#addavatar').parent().show();
						$('a#remavatar').hide();
					}
				});
				return false;
			});
		});
	</script>
	{/literal}

	<div style="width:740px;float:left">

		<div class="moduletable">
						
		<table width="100%" id="profile" class="tlf" cellpadding="0">
		<tr valign="top">
			<td style="width:120px;" align="center">
				<!--noindex-->
				
				{include file="profile/addavatar.tpl"}
	
				{$avatar}
	
				<br />
				{if $USER->user_id == $User.user_id}
					<form action="/editprofile/adduseravatar/" method="post" enctype="multipart/form-data">
						<a href='#' class='green dashed' style="cursor:pointer;display: {if $USER->user_picture < 1}block{else}none{/if};width: 100px;"><input id="addavatar" name="avatar" type="file" />Добавить аватар</a>
						<a href='/editprofile/deleteuseravatar/' id="remavatar" style="display: {if $USER->user_picture < 1}none{else}block{/if}">Удалить</a>
					</form>
				{/if}
				
				<br />
				<br />
					
				{if $USER->user_id != $User.user_id}
					<div class="write2user">
						<a href="#" onclick="return showMessenger('{$User.user_id}',0)" title="Написать сообщение"><img alt="Написать сообщение" src="/images/reborn/0.gif"></a>
						<a href="#" onclick="return showMessenger('{$User.user_id}',0)" title="Написать сообщение"><span>Написать</span></a>
					</div>
				{/if}
				<!--/noindex-->
			</td>
			<td class="nowrap1" width="585px">
				<div style="float:left">
					
					<div style="width:315px">                    
						
						<div class="left" style="width:210px">
						
							<div><span style="font-size:16px; font-weight:bold;">{$User.user_login}</span> {$User.user_designer_level}</div>
							{$User.user_name}
						</div>
						
						{if $USER->user_id != $User.user_id}
							<div class="left" style="padding-top:5px">
								{if $add_to_selected}
									<a href='/selected/add/{$User.user_id}' class='selected51no ico' title="Добавить в избранные">&nbsp;</a>
								{/if}
								
								{if $remove_from_selected}
									<a href='/selected/remove/{$User.user_id}' class='selected51 ico' title="Избранный автор. Удалить.">&nbsp;</a>
								{/if}
								
								{if $WHO_SEL_MEcount > 0}
								<span class="favoriteCount"><i></i>{$WHO_SEL_MEcount}</span>
								{/if}
							</div>
						
							{if $User.pretendents > 0}
								<div class="right" style="padding-top:3px">
									<a href='/people/designers/#designers' rel="nofollow"><span class="ico-diz red_he">{$User.pretendents}</span></a>
								</div>
							{/if}
						
						{/if}
						
						<br clear="all" />
					</div>
					
					<br />
					
					{if $printshop_link}
					<h1 style="font-size:14px;">&#9658;  <a href="/catalog/{$User.user_login}/" title="Магазин футболок {$User.user_login}">Мой магазин футболок на Maryjane.ru</a></h1>
					{/if}
					
					<div class="clr clearfix"></div>
					
					<div style="font-size:14px; margin-bottom:20px;white-space: normal;">
						Нравится        &mdash;&nbsp;<span title="мне нравится">{if $selected_goods_count >= 6}<a href="/selected/{$userInfo.user_id}/" rel="nofollow" >{$selected_goods_count}</a>{else}{$selected_goods_count}{/if}</span> / <span title="я нравлюсь">{$userInfo.me_liked}</span>,
						работ			&mdash;&nbsp;{if $userInfo.goods_count > 0}<a href="/catalog/{$userInfo.user_login}/">{$userInfo.goods_count}</a>{else}0{/if},
						просмотров моих работы &mdash;&nbsp;{$userInfo.good_visits},
						избранный автор &mdash;&nbsp;{if $userInfo.me_selected > 0}{$userInfo.me_selected}{else}0{/if},
						постов 			&mdash;&nbsp;{if $userInfo.posts_count > 0}<a href="/blog/user/{$userInfo.user_login}/" rel="nofollow">{$userInfo.posts_count}</a>{else}0{/if},
						фото			&mdash;&nbsp;{if $userInfo.photo_count > 2}<a href="/myphoto/{$userInfo.user_id}/" rel="nofollow">{$userInfo.photo_count}</a>{else}{$userInfo.photo_count}{/if},
						комментариев	&mdash;&nbsp;{$userInfo.comments_count},
						{* оценок 			&mdash;&nbsp;{$userInfo.likes_count}, *}
						работы были дизайном дня (раз) &mdash;&nbsp;{$userInfo.toptees_count},
						сколько раз проголосовал &mdash;&nbsp;{$userInfo.all_vote},
						средняя оценка на голосовании &mdash;&nbsp;{$userInfo.avg_vote},
						скольким работам помог выиграть &mdash;&nbsp;{$userInfo.help_to_win},
					</div>
					
					<div style="width: 575px;border-bottom:1px solid #e3e3e3;margin-top:10px;"></div>
					
					{if $User.url != ''}
					<!--noindex-->
					<div>
						<span style="font-size:14px">{$User.url}</span>
					</div>
					<!--/noindex-->
					{/if}
					
					{*
					<div class="clr left rating-carma mr10 mt5" align="center">
						&nbsp;
						<h6 align="center" style="margin:0">рейтинг</h6>
						<span style="font-size:150%;font-weight:700;white-space:nowrap;">{$User.user_raiting}</span>
					</div>
					*}
					
					{if $USER->meta->mjteam}
					<div class="left rating-carma mr10" align="center" style="margin-top:11px;border:1px dashed orange">
						{$User.user_carma}
					</div>
					{/if}
					
					{*
						<div class="left rating-carma mr10 mt5" align="center" title="карма-поинты">
							<h6 align="center" style="margin:0">кп</h6>
							<span style="font-size:150%;font-weight:700;white-space:nowrap;">({$User.carma_points})</span>
						</div>
						<div class="left mr10 mt5" align="center"><sup class="help"><a style="font-size: 9px;" href="http://www.maryjane.ru/faq/group,2/?TB_iframe=true&height=500&width=600" rel="nofollow" class="thickbox">?</a></sup></div>
					*}

				{*
				{if $printshop_link && $USER->user_id == $User.user_id}
					<div style="float:right">
						<div style="padding:5px 0;border-bottom: 1px solid rgb(222, 222, 222);">&rarr; <a href="/catalog/{$userInfo.user_login}/" class="gray" style="font-size:12px; text-transform:uppercase" target="_blank" rel="nofollow">Мои Работы</a></div>
					</div>
				{/if}
				*}
				
				<div class="mr10 clr" style="padding-top:10px">
					<!--noindex-->
                    Откуда: {$User.country}, {$User.city}<br/>
					Пол: {$User.user_sex}<br/>
					Дата рождения: {$User.birthday}<br/>
					С нами с: {$User.registerDate} {if $User.registered_years > 0}(полных лет - {$User.registered_years}){/if}<br/>
					Заходил: {$User.lastVisit}<!--/noindex-->

				</div>
				
				
				<div class="clearfix"  style="border-top:1px solid #e3e3e3;margin-top:10px;padding-top:10px">
					
					{if $USER->id == $User.user_id}
					
												
						{if $USER->meta->user_facebook}
							<a href="https://www.facebook.com/{$U->meta->user_facebook}/" rel="nofollow" target="_blank"><img border="0" style="vertical-align: middle;margin:0;margin-right: 8px;" src="/images/social/facebook.gif" title="" alt="F" width="16" height="16"/></a>
							<span style="margin-right:10px;vertical-align: middle; color:green"><!--привязан--></span>
						{else}
							<img border="0" style="vertical-align: middle;margin:0;margin-right: 8px;" src="/images/social/facebook.gif" title="" alt="F" width="16" height="16"/>
							<span id="topLineFBLogin" style="margin-left:0;margin-right:20px;vertical-align: middle;">привязать</span>
						{/if}
						
						
						{if $USER->meta->user_vk}
							<a href="https://vk.com/id{$U->meta->user_vk}" rel="nofollow" target="_blank"><img border="0" style="vertical-align: middle;margin:0;margin-right: 8px;" alt="Vk" title="" src="/images/social/vkontakte.png" width="16" height="16"/></a>
							<span style="margin-right:10px;vertical-align: middle; color:green"><!--привязан--></span>
						{else}
							<img border="0" style="vertical-align: middle;margin:0;margin-right: 8px;" alt="Vk" title="" src="/images/social/vkontakte.png" width="16" height="16"/>
							<span id="topLineVKLogin" style="margin-left:0;margin-right:20px;vertical-align: middle;">привязать</span>
						{/if}

												
						{if $USER->meta->user_gplus}
							<a href="https://plus.google.com/u/0/{$U->meta->user_gplus}/" rel="nofollow" target="_blank"><img border="0" style="vertical-align: middle;margin:0;margin-right: 8px;" src="/images/social/google-plus.gif" title="" alt="g+"width="16" height="16"/></a>
							<span style="margin-right:10px;vertical-align: middle; color:green"><!--привязан--></span>
						{else}
							<img border="0" style="vertical-align: middle;margin:0;margin-right: 8px;" src="/images/social/google-plus.gif" title="" alt="g+"width="16" height="16"/>
							<span id="topLineGplusLogin" style="margin-left:0;margin-right:20px;vertical-align: middle;">привязать</span>
						{/if}
						
						
						{if $USER->meta->user_instagram}
							<a href="https://www.instagram.com/{$U->meta->user_instagram_name}/" rel="nofollow" target="_blank"><img border="0" style="vertical-align: middle;margin:0;margin-right: 8px;" src="/images/social/Instagram.jpg" title="Instagram" alt="Instagram"width="16" height="16"/></a>
							<span style="margin-right:10px;vertical-align: middle; color:green"><!--привязан--></span>
						{else}
							<img border="0" style="vertical-align: middle;margin:0;margin-right: 8px;" src="/images/social/Instagram.jpg" title="Instagram" alt="Instagram"width="16" height="16"/>
							<span id="topLineInstagramLogin" style="margin-left:0;margin-right:20px;vertical-align: middle;cursor: pointer;">привязать</span>
						{/if}
						
					{else}
					
						{if $U->meta->user_facebook}
							<a href="https://www.facebook.com/{$U->meta->user_facebook}/" rel="nofollow"><img border="0" style="vertical-align: middle;margin:0;margin-right: 8px;" src="/images/social/facebook.gif" title="" alt="F" width="16" height="16"/></a>
						{/if}
						
						{if $U->meta->user_vk}
							<a href="https://vk.com/id{$U->meta->user_vk}" rel="nofollow"><img border="0" style="vertical-align: middle;margin:0;margin-right: 8px;" alt="Vk" title="" src="/images/social/vkontakte.png" width="16" height="16"/></a>
						{/if}
						
						{if $U->meta->user_gplus}
							<a href="https://plus.google.com/u/0/{$U->meta->user_gplus}/" rel="nofollow"><img border="0" style="vertical-align: middle;margin:0;margin-right: 8px;" src="/images/social/google-plus.gif" title="" alt="g+"width="16" height="16"/></a>
						{/if}
						
						{if $U->meta->user_instagram_name}
							<a href="https://www.instagram.com/{$U->meta->user_instagram_name}/" rel="nofollow"><img border="0" style="vertical-align: middle;margin:0;margin-right: 8px;" src="/images/social/Instagram.jpg" title="Instagram" alt="Instagram"width="16" height="16"/></a>
						{/if}
						
					{/if}
					
					{if $User.user_lj}<span class='ico-lj'><a href="http://{$User.user_lj}.livejournal.com" rel='nofollow'>{$User.user_lj}</a></span>{/if} 
					{if $User.user_lastfm}<span class='ico-lastfm'><a href="http://last.fm/user/{$User.user_lastfm}" rel='nofollow'>{$User.user_lastfm}</a></span>{/if} 
					{if $User.user_flickr}<span class="ico-flickr"><a href="http://flickr.com/photos/{$User.user_flickr}" rel='nofollow'>{$User.user_flickr}</a></span>{/if}
				</div>
				
				
				
				{if $i_participate_in_competition}
					{foreach from=$i_participate_in_competition item="c"}
					<div class="clearfix"  style="border-bottom:1px solid #e3e3e3;margin-top:10px;"></div>
					<div class="mr10 clr" style="padding-top:10px">
						Я участвую в конкурсе "<a href="/voting/competition/{$c.competition_slug}/">{$c.competition_name}</a>"
					</div>
					{/foreach}
				{/if}
	
				{if $i_participate_in_competition_archive}
					{foreach from=$i_participate_in_competition_archive item="c"}
					<div class="clearfix"  style="border-bottom:1px solid #e3e3e3;margin-top:10px;"></div>
					<div class="mr10 clr" style="padding-top:10px">
						Я участвовал в конкурсе "<a href="/voting/competition/{$c.competition_slug}/">{$c.competition_name}</a>"
					</div>
					{/foreach}
				{/if}
			
				<div style="border-bottom:1px solid #e3e3e3;margin-bottom:10px;margin-top:10px;"></div>
	
			</td>
		</tr>
		</table>
		</div>
		
		
		{*if $good_onhudsovet}
		<div class="moduletable">
			<h4 style="border-bottom:1px solid #e3e3e3;padding-bottom:10px;">Работы на худсовете</h4>
			<p><em>Работа будет выставлена на голосование или отклонена в течении 48 часов. <sup class="help"><a style="font-size: 9px;" href="http://www.maryjane.ru/faq/group,12/?TB_iframe=true&height=500&width=600" rel="nofollow" class="thickbox">?</a></sup></em></p>
			<div class="clearfix onhudsovet">
				{foreach from=$good_onhudsovet item="g"}
				<div title='{$g.good_name}' class="border pa5 left mrb5">
					<img src='{$g.picture_path}' alt='{$g.good_name}' width="100" height="80" /><br />
					<small style="font-size:10px">Голосов ХС: {$g.votes}</small><br />
					<small style="font-size:10px">Осталось: {$g.timetoend} ч. <sup class="help"><a style="font-size: 9px;" href="http://www.maryjane.ru/faq/20/?TB_iframe=true&height=500&width=600" rel="nofollow" class="thickbox">?</a></sup></small><br />
				</div>
				{/foreach}
			</div>
		</div>
		{/if*}
		
		
		<script type="text/javascript">
			$(document).ready(function(){ 
				var myprofile = {if $USER->user_id == $User.user_id}true{else}false{/if};
				
				
				//добавим лайканье на новые элементы
				$('.vote').each(function(){
					this.vote = new vote({ div: this, id: parseInt($(this).attr('_id')), handler: function(r,s){								
						if (s == 'deleted') if (myprofile) $(r).parents('.m12:first').remove();
					} });
				});

				// если Избранное, то обновим обработчик для лайканья
				if (typeof addHandlerToVote == 'function') addHandlerToVote();
				
				$('.m12').hover(
					function(){ 
						var btn = $(this).find('.btn_editing').css('visibility','visible').show();				
						if (btn.length == 0)
							$(this).find('.price').css('visibility','visible').show(); 
					}, 
					function(){ $(this).find('.btn_editing, .price').css('visibility','hidden').hide(); }
				);
			
			});
		</script>


		{literal}
		<style type="text/css">
			.profile-pic-selected div { float: none; border:0px; margin:0px; } 
			.m12 .item .title { padding: 9px 0 0; }
			.list_wrap ul { border-top:2px solid #D7D7D7; }
			
			div.moduletable {padding: 10px 15px 10px 0px;}
			.moduletable .list_wrap{margin-right: 2px;width: 740px;}
			.list_wrap ul li.m12 { padding: 25px 25px 25px 30px;}
			.list_wrap	.infobar {width: 230px;}
			.relative{position: relative;border:none!important;}
			.relative .m12{position: absolute!important;width: 235px!important;height: auto!important;border:2px solid #d7d7d7;padding: 12px 8px 23px 1px !important;}
			.relative .wrap{position: relative!important;padding-left: 5px;}
			.relative .wrap a.im {width: auto!important;height: auto!important;font-size: 0;}
			.relative .m12 .item .price {bottom: 25px;left: 10px;}
			.relative  li.m12:hover {
				border:2px solid #ffffff!important;
				-moz-box-shadow: 0 0 15px 0 rgba(0,0,0,.5);
				-webkit-box-shadow: 0 0 15px 0 rgba(0,0,0,.5);
				box-shadow: 0 0 15px 0 rgba(0,0,0,.5);
				-moz-transition: box-shadow .15s ease;
				-webkit-transition: box-shadow .15s ease;
				transition: box-shadow .15s ease;
				z-index: 10;
				}				
			</style>
		{/literal}

		
		{if $author_goods}
			<div class="moduletable">
				<h4>Купить мои футболки (<a href="/catalog/{$User.user_login}/">{$userInfo.goods_count}</a>)</h4>
				<div class="clearfix profile-pic-selected list_wrap">
				<ul style="border-left:2px solid #D7D7D7;height:{$ULheight}px;" class="relative">
				{foreach from=$author_goods item="g" key="k"}						
					<li class="m12 {$k}" style="left:{$g.x}px;top:{$g.y}px;padding-bottom:22px!important;">				
						<div class="wrap">				
							<a href="/catalog/{$g.user_login}/{$g.good_id}/" class="im" rel="nofollow" title="{$g.good_name}" target="_blank">
								<span class="list-img-wrap">
								<img src="{$g.picture_path}" alt="Купить мою футболку {$g.good_name}" style="background-color:#{$g.bg};"/>
								</span>
							</a>
							<div class="infobar">
								{if $g.good_status != "voting" && $g.good_status != "new" && $g.good_visible == "true"}
								<div class="vote_count vote_count_id_{$g.good_id} {if $g.place > 0} red_vote{/if}">
									<span>{$g.likes}</span>
								</div>
								{/if}
								
								{if $USER->user_id == $g.user_id && $g.good_status == "voting"}
									<div class="help_for_author">
										<span class="help"><a href="/faq/168/?height=500&amp;width=600&amp;positionFixed=true" rel="nofollow" class="help thickbox">?</a></span>
									</div>
								{/if}
								
								<div class="preview_count">{$g.visits}</div>
								{if $USER->user_id == $g.user_id || $USER->user_id == 27278 || $USER->user_id == 6199}
								<a class="btn_editing" href="/senddrawing.design/{$g.good_id}/"></a>
								{/if}
							</div>
							<div class="vote {if $g.liked}select{/if} {if $g.place > 0} heart_red {/if}" title="{if $g.liked}Мне нравится{/if}"_id="{$g.good_id}"><span></span></div>
							<div class="item">
								<span class="title">{$g.good_name}</span>
								<!--зелёный блок-->
								<!--noindex--><span style="background-color:#00a851; color: #fff;bottom:82px;" class="price">от 60&nbsp;руб.</span> 	<!--/noindex-->
								<!--noindex-->
								<span class="author">
									{if $g.good_status != "new" && $g.good_visible == "true"}
										<a rel="nofollow" title="Дизайнер {$g.user_login}" href="/catalog/{$g.user_login}/" style="height: auto;">{$g.user_login}</a>
									{else}
										<span class="author" style="color:#F00; font-style:italic; font-size:11px; font-weight:bold">
										{if $USER->authorized && (($g.good_status == "new" && $g.good_visible == "true") || $g.good_visible == "modify")}
											{if $g.good_visible == 'modify'}После правок работа на XC{else}работа на худсовете{/if}
										{else}
											{if $USER->user_id == $user_id || $USER->user_id == 27278 || $USER->user_id == 6199}
											работа не дооформлена
											{/if}
										{/if}
										</span>
									{/if}
								</span><!--/noindex-->
								
								{*if $g.place>0}
								<a rel="nofollow" href="/blog/view/1511/" class="b-ordensml">
									<img alt="Победитель принтшопа" src="/images/icons/bg-orden-sml.gif">
								</a>
								{/if*}
							</div>
						</div>
					</li>
				{/foreach}
				</ul>
				</div>
			</div>			
		{/if}
			
		
			
		{if $PICTUREScount > 0}
		<div class="moduletable">
				<h4>Новые фото&nbsp;({if $PICTUREScount >= 3}<a href='/myphoto/{$User.user_id}/'>{$PICTUREScount}</a>{else}{$PICTUREScount}{/if})</h4>
				<div class="clearfix profile-pic-selected list_wrap" style="width: 723px;">
				<ul style="border-left:2px solid #D7D7D7;">
				{foreach from=$PICTURES item="g"}					
					<li class="m12 {$k}" style="height:190px">				
					<a href="/gallery/view/{$g.gallery_picture_id}/" rel="nofollow" target="_blank">
					<span class="list-img-wrap">
						<img src="{$g.picture_path}" alt="Фото в футболках Maryjane" style="background-color:#{$g.bg};max-height: 184px;"/>
					</span></a>
					</li>
				{/foreach}
				</ul>
				</div>
			</div>
		{/if}				
			
			
		{if $selected_goods}
			<div class="moduletable">
				<h4>Мне нравится  ({if $selected_goods_count >= 6}<a href="/selected/{$userInfo.user_id}/" rel="nofollow" >{$selected_goods_count}</a>{else}{$selected_goods_count}{/if})</h4>
				<div class="clearfix profile-pic-selected list_wrap">
				<ul style="border-left:2px solid #D7D7D7;height:{$ULheight2}px;" class="relative">
				{foreach from=$selected_goods item="g" key="k"}
					<li class="m12 {$k}" style="left:{$g.x}px;top:{$g.y}px;">				
						<div class="wrap">					
							<a href="/catalog/{$g.user_login}/{$g.good_id}/" class="im" rel="nofollow" title="{$g.good_name}" target="_blank">
								<span class="list-img-wrap">
									<img src="{$g.picture_path}" alt="Избранные работы {$g.good_name}" style="background-color:#{$g.bg};"/>
								</span>
							</a>
							<div class="infobar">
								{if $g.good_status != "voting"}
								<div class="vote_count vote_count_id_{$g.good_id} {if $g.place > 0} red_vote{/if}">
									<span>{$g.likes}</span>
								</div>
								{/if}
								<div class="preview_count">{$g.visits}</div>
								{if $USER->user_id == 27278 || $USER->user_id == 6199}
								<a class="btn_editing" href="/senddrawing.design/{$g.good_id}/"></a>
								{/if}
							</div>
							{if $USER->id == $userInfo.user_id}
							<div class="vote select {if $g.place > 0} heart_red {/if}" _id="{$g.good_id}"><span></span></div>
							{/if}
							<div class="item">
								<!--span class="title">{$g.good_name}</span-->
								<!--зелёный блок-->
								<!--noindex--><span style="background-color:#00a851; color: #fff;" class="price">от 60&nbsp;руб.</span> 	<!--/noindex-->
								<!--noindex--><!--span class="author"><a rel="nofollow" title="Дизайнер {$g.user_login}" href="/catalog/{$g.user_login}/" style="height: auto;">{$g.user_login}</a></span--><!--/noindex-->
								
								{if $g.disabled && ($USER->user_id == $user_id || $USER->user_id == 27278 || $USER->user_id == 6199)}
								<span class="author" style="color:#F00; font-style:italic; font-size:11px; font-weight:bold">работа отключена</span>
								{/if}
								
								{if $g.no_src && ($USER->user_id == $user_id || $USER->user_id == 27278 || $USER->user_id == 6199)}
								<span class="author" style="color:#F00; font-style:italic; font-size:11px; font-weight:bold">отсутствует исходник</span>
								{/if}
								
								{*if $g.place}
								<a rel="nofollow" href="/blog/view/1511/" class="b-ordensml">
									<img alt="Победитель принтшопа" src="/images/icons/bg-orden-sml.gif">
								</a>
								{/if*}
							</div>
						</div>
					</li>
				{/foreach}
				</ul>
				</div>
			</div>
		{/if}
	
		
		{*if $BLOGcount > 0}
		<div class="moduletable">
			<h4>Последние посты&nbsp;({if $BLOGcount >= 3}<a href='/blog/user/{$User.user_login}/' rel="nofollow">{$BLOGcount}</a>{else}{$BLOGcount}{/if}) </h4>
			<div style="border-bottom:1px solid #e3e3e3;margin-bottom:10px;"></div>
			{include file="/blog/list.post_row.tpl"}
			</div>
		{/if*}
		
		{if $BLOGcount > 0 || $USER->user_id == 105091}
		<div class="late-artic" style="width: 725px;">		
			{foreach from=$posts item="p"}
			<div class="b-one-artic" style="width: 725px;">
				<a class="artic-imglink" href="/blog/view/{$p.id}/" rel="nofollow">
					<span class="img-wrap-white"><img src="{if $p.path}{$p.path}{else}/images/noimg.gif{/if}" width="100" /></span>
					<span class="comm-num">{$p.comments}</span>
				</a>				
				{*<div class="b-category">
					{$p.post_theme}
				</div>*}
				<h3 style="margin-bottom: 20px;"><a href="/blog/view/{$p.id}/">{$p.post_title}</a></h3>
				<!--noindex-->
				<ul class="artic-details">
					<li class="li"><a href="/profile/{$p.post_author}/" rel="nofollow">{$p.user_login}</a></li>
					<li class="li">{$p.post_date}</li>
					<li class="li">Комментариев: {$p.comments}</li>			
				</ul>	
				<!--/noindex-->	
				{if $USER->authorized && $USER->user_id == $p.post_author || $USER->user_id == "6199"}
				<div class="edit-link"><a href="/blog/edit/{$p.id}/" class="artic_edit">ред.</a>&nbsp;<a href="/{$module}/delete/{$p.id}/" class="artic_del">удалить</a></div>
				{/if}
			</div>			
			{/foreach}
		</div>		
		{/if}
		
		{if $PICTUREScount > 0}
		{*
		<div class="moduletable clr">
			<h4>Новые фото&nbsp;(<a href='/myphoto/{$User.user_id}/'>{$PICTUREScount}</a>)</h4>
			<div style="border-bottom:1px solid #e3e3e3;margin-bottom:10px;"></div>
			<div class="clearfix profile-pic">
			{foreach from=$PICTURES item="p"}
			<a href='/gallery/view/{$p.gallery_picture_id}/' rel="nofollow"><img src='{$p.picture_path}' alt='Фото в футболках Maryjane' /></a>
			{/foreach}
			</div>
		</div>
		*}
		{/if}
		
		{if $WHO_SEL_MEcount > 0}
		<!--noindex-->
		<div class="moduletable clr">
			<h4>Я нравлюсь ({if $WHO_SEL_MEcount > 5}<a href="/selected/{$User.user_id}/authors/" rel="nofollow">{$WHO_SEL_MEcount}</a>{else}{$WHO_SEL_MEcount}{/if})</h4>
			<div style="border-bottom:1px solid #e3e3e3;margin-bottom:10px;"></div>
			{foreach from=$WHO_SEL_ME item="a" name="wsm_foreach"}
			<a href='/profile/{$a.user_id}/' rel="nofollow">{$a.user_login}</a> {$a.user_designer_level}{if !$smarty.foreach.wsm_foreach.last},{/if}
			{/foreach}
		</div>
		<!--/noindex-->
		{/if}
		
		{if $selected_authors_count > 0}
		<!--noindex-->
		<div class="moduletable">
			<h4>Мне нравятся (<a href="/selected/{$User.user_id}/authors/" rel="nofollow">{$selected_authors_count}</a>)</h4>
			<div style="border-bottom:1px solid #e3e3e3;margin-bottom:10px"></div>
			<div class="clearfix clr">
			{foreach from=$selected_authors item="a" name="sa_foreach"}
			<a href="/profile/{$a.user_id}/" rel="nofollow">{$a.user_login}</a> {$a.user_designer_level}{if !$smarty.foreach.sa_foreach.last},{/if}
			{/foreach}
			</div>
		</div>
		<!--/noindex-->
		{/if}		
		
		{if $friends_count > 0}
		<!--noindex-->
		<div class="moduletable">
			<h4>Взаимно (<a href="/selected/{$User.user_id}/authors/" rel="nofollow">{$friends_count}</a>)</h4>
			<div style="border-bottom:1px solid #e3e3e3;margin-bottom:10px;"></div>
			<div class="clearfix clr">
			{foreach from=$friends item="f" name="f_foreach"}
			<a href="/profile/{$f.user_id}/" rel="nofollow">{$f.user_login}</a> {$f.user_designer_level}{if !$smarty.foreach.f_foreach.last},{/if}
			{/foreach}
			</div>
		</div>
		<!--/noindex-->
		{/if}

	</div>
	
	<div>{include file="profile/sidebar.tpl"}</div>

{/if}