<!-- footer.html -->

{if ($module == 'catalog' || $module == 'catalog.dev' || $module == 'catalog.v2') && $good}
{else}
	{include file="footer.top_menu.tpl"}
{/if}
<!-- FOOTER версия от 16-03-2012 -->
<div class="wrap-b-footer_v2 clearfix">
	<div class="b-footer_v2">
		
			<div class="b-three-block">
				<div class="social_black clearfix" style="margin:0 auto;">
					<a class="so-fb" target="_blank" href="/goto/?target=https://www.facebook.com/maryjaneisonmybrain" title="Facebook" ></a>
					<a class="so-vk" target="_blank" href="/goto/?target=http://vk.com/club1797113" title="ВКонтакте" ></a>
					{*<a class="so-tw" target="_blank" href="https://twitter.com/maryjaneru" title="twitter" ></a>
					<a class="so-gg" target="_blank" href="https://plus.google.com/110170198345311125008/" title="google" ></a>*}
					<a class="so-in" target="_blank" href="/goto/?target=http://instagram.com/maryjane_ru" title="Instagram" ></a>
				</div>
				
				{*
				<div class="b-link2social"></div>
				<!--noindex--><ul class="left-block">
					<li class="item">1. {$L.FOOTER_share}</li>
					<li class="item">2. {$L.FOOTER_send_link}</li>
					<li class="item">3. {$L.FOOTER_get_100}</li>
				</ul><!--/noindex-->
				<!--noindex-->
				<div class="center-block">
					<div class="soc_btns">
					{literal}
						<!-- ВКОНТАКТЕ --><noindex >
						<div class="left" style="width:83px;" class="vk_like_footer">
							<script type="text/javascript" src="http://vk.com/js/api/share.js?11" charset="windows-1251"></script>
							<script type="text/javascript"><!--
							document.write(VK.Share.button('http://www.maryjane.ru/',{type: "round", text: "+"}));
							--></script>
						</div></noindex >				
						<!--для аналитики сколько кликов на вк-->
						<script>
							$(document).ready(function(){
								$(".vk_like_footer a").click(function(e){
								debugger;
									trackUser('лайк VK в футере','лайк VK в футере');//трек гугл аналитик
									/*if (typeof yaCounter265828 != 'undefined')
										yaCounter265828.reachGoal('vk_like_footer','лайк VK в футере');//трекинг события яша	
									else { console.log('yaCounter265828 не найден');}*/
								});
							 });
						</script>				
						<!-- FACEBOOK -->
						<div class="left">
							<div class="fb-like" style="margin-right:4px;"  data-send="false" data-href="http://www.maryjane.ru/" data-layout="button_count" data-width="450" data-show-faces="true"></div>
							<!--div class="fb-like" style="margin-right:4px;" data-ref="footer" data-href="http://www.maryjane.ru/" data-send="false" data-layout="button_count" data-width="90" data-show-faces="false"></div -->
							<!--fb:like ref="footer" send="false" width="75" show-faces="false" layout="button_count" href="http://www.maryjane.ru/"></fb:like -->
							<style>
								.fb_edge_widget_with_comment span.fb_edge_comment_widget iframe.fb_ltr { display: none !important; }			
								.fb_edge_comment_widget {
								  display: none !important;
								}
							</style>
							<div id="fb-root" style="display:none;"></div>
							<script>(function(d, s, id) {
							  var js, fjs = d.getElementsByTagName(s)[0];
							  if (d.getElementById(id)) return;
							  js = d.createElement(s); js.id = id;
							  js.src = "//connect.facebook.net/en_EN/all.js#xfbml=1&appId=192523004126352";
							  fjs.parentNode.insertBefore(js, fjs);
							}(document, 'script', 'facebook-jssdk'));</script>			
							
							<style>	
								.fb_edge_widget_with_comment {float:left;max-width:80px;}	
								.twitter-share-button {width:106px!important;}
							</style>				
						</div>
						
						<!-- twitter -->
						<div class="left">
							<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-url="http://www.maryjane.ru/" >Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
						</div>
						
						<div class="left">
							{literal}
							<script type="text/javascript">
							  (function() {
							    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
							    po.src = 'https://apis.google.com/js/plusone.js';
							    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
							  })();
							  window.___gcfg = {
							    lang: 'ru',
							    parsetags: 'onload'
							  };
							</script>
							{/literal}
						</div>

						<!--LiveInternet counter-->
						<script type="text/javascript"><!--
						document.write("<a rel=\"nofollow\" href='http://www.liveinternet.ru/click' "+"target=_blank><img src='//counter.yadro.ru/hit?t45.6;r"+
						escape(document.referrer)+((typeof(screen)=="undefined")?"":
						";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
						screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
						";h"+escape(document.title.substring(0,80))+";"+Math.random()+
						"' alt='' title='LiveInternet' "+
						"border='0' width='1' height='1'><\/a>")
						</script>
						//--></script><!--/LiveInternet-->
					{/literal}
					</div>
				</div>
				<!--/noindex-->
				*}
			</div>
		
		<div class="page-signature">
			<style type="text/css">
				.footer-section-left {
					float: left;
					width: 220px;
					padding: 15px 20px 0;
					border-right: 1px solid #ccc; 
					min-height: 150px;
				}
				.footer-section-left:nth-of-type(2) {
					width: 200px;
				}
				.footer-section-left:nth-of-type(3) {
					width: 165px;
				}
				.footer-section-left:last-child {
					width: 230px;
					border-right: none;
					padding-right: 0;
				}
				.page-signature .logo-link {
					display: block;
					float: none;
					margin: 0 0 15px 0;
				}
				.page-signature .description {
					float: none;
					width: auto;
					margin-bottom: 10px;
				}
			</style>
			<!--noindex-->
			<div class="footer-section-left">
				<a class="logo-link clearfix" title="{$L.FOOTER_slogan_0}" href="http://www.maryjane.ru/"><img border="0" alt="{$L.FOOTER_slogan_0}" src="/images/logo_mj_small.gif"></a>			
				
				<div class="description">
					<p>&copy;2003-{$datetime.year} {$L.FOOTER_slogan_3}</p>
					<p>{$L.FOOTER_copyrights}</p>		
				</div>

				<div class="terms-of-use">
					<img border="0" alt="16" width="31" height="31" valign="middle" src="/images/16plus.png">
					<a title="{$L.FOOTER_rules_of_using}" href="#" _href="/agreement/" onclick="location.href=$(this).attr('_href');return false;" style="margin-top:4px;color:#CCC;padding-left: 10px;">{$L.FOOTER_rules_of_using}</a>
					<a title="privacy policy" href="#" _href="/privacy-policy/" onclick="location.href=$(this).attr('_href');return false;" style="margin-top:4px;color:#CCC;margin-left:5px;">(en)</a>	
				</div>
			</div>

			<div class="footer-section-left">
				<p>Москва:</p>

				<p>ул. Малая Почтовая, д. 12, стр. 3,<br> корп. 20, 5 этаж.<br> Самовывоз. Перед приездом проверьте приходила ли вас смс о готовности</p>
			</div>

			<div class="footer-section-left">
				<p>Санкт-Петербург:</p>

				<p>Операционный офис<br> ул. Жуковского, д. 12<br> <b>Самовывоз не возможен.</b></p>
			</div>

			<div class="footer-section-left">
				<p>Телефон для связи:</p>

				<div>Москва.........................+7 (495) 229-30-73<br> 
					Санкт-Петербург..........+7 (812) 385-72-37<br>
				
					{* if $contact_phone}
						{$L.FOOTER_phohe}:.............<span class="ya_phone">{$contact_phone}</span><br />
					{/if *}				
					
					{* $operation_time *}
					
					Пн.-Птн.:............................ {$operation_time_1}<br />
					Cб., Вс.:............................ выходной<br />
					<br />
					<a class="b-footer-nav_item" href="/contacts/" title="{$L.FOOTER_where_buy_shirt}" >{$L.FOOTER_contacts}</a><br />
					<a class="b-footer-nav_item" href="/contact_us/" title="{$L.FOOTER_where_buy_shirt}" >Отзывы и предложения</a>
				</div>
			</div>
			<!--/noindex-->
		</div>
		
	</div>

{* не доделано. Сергей хотел выезжающий блок и в нем участники группы 
<div id="fb-root"></div>
 
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1&appId=2530096808";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
	
	<div class="right_panel_fb"> 
		<!--div class="fb-facepile" data-app-id="2361831622" data-href="http://www.maryjane.ru/" data-width="300" data-height="100" data-max-rows="2" data-colorscheme="light" data-size="medium" data-show-count="true"></div -->
		<div class="fb-like-box" data-href="https://www.facebook.com/maryjaneisonmybrain" data-width="250" data-show-faces="true" data-header="true" data-stream="false" data-show-border="true"></div>
		<a class="right_trigger_fb" href="#" ></a>
		<script type="text/javascript">
		$(window).scroll(function(){
			if ($(document).height() - $(window).height() - $(window).scrollTop() <= 250)
				ShowFBModal();
			else 
				HideFBModal()
		});
		
		//$(document).ready( function() {
			$(".right_trigger_fb").click(function(){
				$(".right_panel_fb").toggle("slow");
				$(this).toggleClass("active");
				return false;
			});
			//if (dev)
			//	setTimeout("ShowFBModal()",100);
		//});
		function ShowFBModal() {
			if ($(".right_trigger_fb.active").length > 0) return;
				$(".right_panel_fb").toggle("slow");
				$(".right_trigger_fb").toggleClass("active");
		}
		function HideFBModal() {
			if ($(".right_trigger_fb.active").length == 0) return;
				$(".right_panel_fb").toggle("slow");
				$(".right_trigger_fb").toggleClass("active");
		}
		</script>    
	</div>
	*}	
	
	<div style="clear:both"></div>
</div>

<!-- BEGIN noindex -->
<!--/noindex-->
<!-- END noindex -->

<!-- FOOTER end -->