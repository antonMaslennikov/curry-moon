{if $userInfo.user_id > 0}
<div class="selected-info">
	<a href="/profile/{$userInfo.user_id}/" rel="nofollow" class="avatar left" title="Дизайнер - {$userInfo.user_login}">{$userInfo.user_avatar}</a>	
	<div class="name left">
		<a href="/profile/{$userInfo.user_id}/" title="Дизайнер футболок - {$userInfo.user_login}">{$userInfo.user_login}</a>
		<span class="rating">
			{$userInfo.user_designer_level}
		</span>					
	</div>
	{if $userInfo.my_selected_works > 0 || $userInfo.me_liked > 0}
	<div class="author-liked left">Нравится&nbsp;
		{if $userInfo.my_selected_works > 0}
			<span title="мне нравится">{if $userInfo.my_selected_works >= 6}<a href="/selected/{$userInfo.user_id}/">{$userInfo.my_selected_works}</a>{else}{$userInfo.my_selected_works}{/if}</span> 
		{/if} 
		{if $userInfo.my_selected_works > 0 && $userInfo.me_liked > 0}
		/ 
		{/if}
		{if $userInfo.me_liked > 0}
			<span title="я нравлюсь">{$userInfo.me_liked}</span>
		{/if}
		
		{if $selected_authors > 0}
			<a href="/selected/{$userInfo.user_id}/authors/" style="margin-left:30px">Избранные авторы</a>
		{/if}
	</div>
	{/if}
	<div class="clearfix clr"></div>
</div>

{/if}

{* 
<div class="b-user_info_blok">
	<a href="/profile/{$user_id}/">{$userInfo.user_avatar}</a>
	
	<div class="top-info-blok">
		
		<div class="infoline u-name-address" style="margin-top:10px;">
			<a class="nic" href="/catalog/{$userInfo.user_login}/" style="line-height:24px;">{$userInfo.user_login}</a>
			{$userInfo.user_designer_level}
		 	<span class="u-about">{if $userInfo.user_name != ''} {$userInfo.user_name}, {/if} {if $userInfo.user_age != ''}мне {$userInfo.user_age},{/if} {$userInfo.user_country}, {$userInfo.user_city}</span>		 	
		</div>
		<div class="infoline u-shoplink">{if $userInfo.ps_goods > 0}<a href="/catalog/{$userInfo.user_login}/">Мой магазин футболок на Maryjane.ru</a>{/if}</div>
		<div class="infoline u-site">{if $userInfo.user_url != ''}Сайт: <a href="{$userInfo.user_link}" target='_blank' rel='nofollow'>{$userInfo.user_url}</a>{/if}</div>
	
	
		<div class="b-carma-rating">			
			<div class="b-favorite">
				{if $USER->id != $userId}
					{if $add_to_selected}
					<a title="Добавить в избранные" alt="Добавить в избранные" class="selected51no ico selectedAjax" style="text-decoration:none" href="/selected/add/{$userInfo.user_id}">&nbsp;</a>
					{/if}
					
					{if $remove_from_selected}
					<a title="Удалить из избранных" alt="Удалить из избранных" class="selected51 ico selectedAjax" style="text-decoration:none" href="/selected/add/{$userInfo.user_id}">&nbsp;</a>
					{/if}
					
					<span class="favoriteCount{if $add_to_selected}No{/if}"><i></i>{$userInfo.me_selected}</span>
				{/if}
			</div>
			
			<!--div class="user-rating">
				<span>рейтинг</span>
				<h3>{$userInfo.user_raiting}</h3>
			</div>			
			
			<div class="blog-post-carma">
				{$userInfo.carma}				
			</div-->
		</div>
	</div>
	
	<div class="bottom-info-blok">
		Нравится                &mdash;&nbsp;<span title="мне нравится">{if $userInfo.my_selected_works > 0}<a href="/selected/{$userInfo.user_id}/" rel="nofollow">{$userInfo.my_selected_works}</a>{else}0{/if}</span> / <span title="я нравлюсь">{$userInfo.me_liked}</span>,
    	избранный автор 		&mdash;&nbsp;{if $userInfo.me_selected > 0}{$userInfo.me_selected} {else}0{/if},
		<!-- ожидают выпуска работ 	&mdash;&nbsp;{if $userInfo.me_selected_works > 0}<a href="/portfolio/{$userInfo.user_id}/" title="Ожидают выпуска работ" rel="nofollow">{$userInfo.me_selected_works}</a>{else}0{/if},-->
		постов 					&mdash;&nbsp;{if $userInfo.posts_count > 0}<a href="/blog/user/{$userInfo.user_login}/" rel="nofollow">{$userInfo.posts_count}</a>{else}0{/if},
		работ					&mdash;&nbsp;{if $userInfo.goods_count > 0}<a href="/catalog/{$userInfo.user_login}/" rel="nofollow">{$userInfo.goods_count}</a>{else}0{/if},
		фото					&mdash;&nbsp;{if $userInfo.photo_count > 0}<a href="/myphoto/{$userInfo.user_id}/" rel="nofollow">{$userInfo.photo_count}</a>{else}0{/if}
		<!--в избранном работ/авторов &mdash;&nbsp;{if $userInfo.my_selected_works > 0}<a href="/selected/{$userInfo.user_id}/" rel="nofollow">{$userInfo.my_selected_works}</a>{else}0{/if} / {if $userInfo.my_selected_authors > 0}<a href="/selected/{$userInfo.user_id}/authors/" rel="nofollow">{$userInfo.my_selected_authors}</a>{else}0{/if} -->
	</div>
</div>


{literal}
<style>
	.b-user_info_blok {float:left; width:968px; height:100px; padding:5px; border:1px solid #ced0cf; position:relative;margin-bottom:20px}
	.b-user_info_blok .avatar {float:left;}
	.b-user_info_blok .top-info-blok {float:left; width:856px; height:83px; margin:0 0 0 10px; color:#6b7172; font-size:13px; position:relative;}
	.b-user_info_blok .top-info-blok a {color:#50504f; text-decoration:underline; font-size:14px;}
	.b-user_info_blok .top-info-blok a.nic {float:left; margin:-4px 3px 0 0; font-weight:bold; font-size:15px;}
	.top-info-blok .infoline {float:left; width:100%; margin:0 0 11px;}
	/* нижняя линия с информацией*/
	.b-user_info_blok .bottom-info-blok {color:#bfc3c4; font-size:13px; float:left; width:850px; margin:0 0 0 10px;}
	.b-user_info_blok .bottom-info-blok a {color:#afadad; text-decoration:underline;}
	
	
	.top-info-blok .b-carma-rating {position:absolute; right:5px; top:5px; width:265px; height:45px; right:0; top:0;}
	.b-carma-rating .user-rating, .b-carma-rating .b-favorite {float:left;}
	.b-carma-rating .b-favorite {margin:0 10px 0 0; padding:14px 15px 0 0;}
	.b-carma-rating .user-rating {text-align:center;}
	.b-carma-rating .user-rating span {float:left; width:100%; font-size:10px; color:#CCC;}
	.b-carma-rating .user-rating h3 {color:#6b7172; font-size:15px;}
	
	.b-carma-rating .blog-post-carma {background-color: #F1F1F1; border-radius:10px; float: right; height: 28px; padding: 4px 0 12px; width: 120px; position:absolute; top:0; right:0;}
	.top-info-blok .blog-post-carma .btn-change-carma {position:absolute; width:20px; height:20px; text-align:center; line-height:18px; text-decoration:none; top:10px; color:#545758; font-size:16px;}
	.top-info-blok .blog-post-carma .btn-change-carma:hover {background-color:#6b7172; color:#FFF; border-radius:2px;}
	.blog-post-carma .btn-change-carma.carma-minus {left:10px;}
	.blog-post-carma .btn-change-carma.carma-plus {right:10px;}
	.blog-post-carma .carmaBP {position:absolute; top:10px; left:31px; color:green; font-size:18px; width:30px; text-align:center;}
	
</style>

<script>
	jQuery(document).ready(function($){
		
		$('.selectedAjax').click(function () {
			
			if (authorized)
			{
				var href = $(this).attr('href');
				var a = $(this);
				
				$.get(href, {'ajax' : true}, function(r) {
					if (r == 'added') {
						$('.selected51no').removeClass('selected51no').addClass('selected51');
						$('.favoriteCountNo').addClass('favoriteCount').removeClass('favoriteCountNo');
						$('.favoriteCount').html('<i></i>' + (parseInt($('.favoriteCount').text()) + 1));
						
					} else {
						$('.selected51').removeClass('selected51').addClass('selected51no');
						$('.favoriteCount').addClass('favoriteCountNo').removeClass('favoriteCount');
						$('.favoriteCountNo').html('<i></i>' + (parseInt($('.favoriteCountNo').text()) - 1));
					}
				});
			}
			
			return false;
		});
		
	});
</script>
{/literal}

*}