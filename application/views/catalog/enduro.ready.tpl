
	<link href="/js/photoswipe/photoswipe.css" rel="stylesheet" type='text/css'>
	<link href="/js/photoswipe/default-skin.css" rel="stylesheet" type='text/css'>   
	<script type='text/javascript' src="/js/photoswipe/photoswipe.min.js"></script>
	<script type='text/javascript' src="/js/photoswipe/photoswipe-ui-default.min.js"></script>

	<div class="my-gallery" {if !$MobilePageVersion}style="height:{$ULheight}px"{/if}>		
		{foreach from=$models item="m"}				
			<figure {if !$MobilePageVersion}style="left:{$m.x}px;top:{$m.y}px"{/if}>
				<a href="/images/enduro/Big/{$m.big}" data-size="{$m.w}x{$m.h}" rel="nofollow">
					<img width="{if $MobilePageVersion}320{else}184{/if}" src="/images/enduro/Small_{if $MobilePageVersion}320{else}184{/if}/{$m.big}" alt="{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}"/>
				</a>
			{if $m.link &&  $m.link!=''}<figcaption class="wrap-goto"><a href="{$m.link}" class="goto" rel="nofollow">{$m.name}</a></figcaption>{/if}                                     
		</figure>
		{/foreach}			
	</div>	
	
	<!-- Root element of PhotoSwipe. Must have class pswp. -->
	<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
		<!-- Background of PhotoSwipe. 
			 It's a separate element, as animating opacity is faster than rgba(). -->
		<div class="pswp__bg"></div>
		<!-- Slides wrapper with overflow:hidden. -->
		<div class="pswp__scroll-wrap">
			<!-- Container that holds slides. PhotoSwipe keeps only 3 slides in DOM to save memory. -->
			<!-- don't modify these 3 pswp__item elements, data is added later on. -->
			<div class="pswp__container">
				<div class="pswp__item"></div>
				<div class="pswp__item"></div>
				<div class="pswp__item"></div>
			</div>
			<!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
			<div class="pswp__ui pswp__ui--hidden">
				<div class="pswp__top-bar">
					<!--  Controls are self-explanatory. Order can be changed. -->
					<div class="pswp__counter"></div>
					<button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
					<button class="pswp__button pswp__button--share" title="Share"></button>
					<button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
					<button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
					<!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
					<!-- element will get class pswp__preloader--active when preloader is running -->
					<div class="pswp__preloader">
						<div class="pswp__preloader__icn">
						  <div class="pswp__preloader__cut">
							<div class="pswp__preloader__donut"></div>
						  </div>
						</div>
					</div>
				</div>
				<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
					<div class="pswp__share-tooltip"></div> 
				</div>

				<button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
				</button>

				<button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
				</button>

				<div class="pswp__caption">
					<div class="pswp__caption__center"></div>
				</div>

			  </div>
		  </div>
	</div>
	{literal}<script>initPhotoSwipeFromDOM('.my-gallery');</script>{/literal}

{if 1==2}		
	{literal}<style>
	.list-auto-wrap { border:0;width:776px; }	
	.ug-gallery-wrapper.ug-theme-default.ug-under-480:not(.ug-fullscreen) .ug-slider-wrapper,
	.ug-gallery-wrapper.ug-under-480.ug-theme-default:not(.ug-fullscreen) .ug-slider-wrapper .ug-item-wrapper{  min-height: 178px; }		
	.MPV .ug-fullscreen .ug-slider-wrapper,
	.MPV .ug-fullscreen .ug-slider-wrapper .ug-slider-inner,
	.MPV .ug-fullscreen .ug-slider-wrapper .ug-slide-wrapper,
	.MPV .ug-fullscreen .ug-slider-wrapper .ug-item-wrapper{  height: 100%!important;}	
	.MPV .ug-gallery-wrapper.ug-theme-default .ug-theme-panel { top: auto!important;  bottom: -143px; }
	.MPV .ug-gallery-wrapper.ug-theme-default .ug-default-button-fullscreen {  top: -52px!important; }
	.MPV .ug-gallery-wrapper.ug-theme-default .ug-zoompanel,
	.MPV .ug-gallery-wrapper.ug-theme-default .ug-slider-control{ display: none;}	
	@media only screen and (min-device-width : 320px) and (max-device-width : 667px) and (orientation : landscape) {
		/*.MPV .header-mobile .head,
		.MPV #wrapper {width:480px;}*/		
		.MPV div .list-auto-wrap { width: 480px;  margin-right: -80px;}		
		.ug-gallery-wrapper.ug-theme-default.ug-under-480:not(.ug-fullscreen) .ug-slider-wrapper,
		.ug-gallery-wrapper.ug-under-480.ug-theme-default:not(.ug-fullscreen) .ug-slider-wrapper .ug-item-wrapper{  min-height: 267px; }	
	}	
	</style>{/literal}	
	<script type='text/javascript' src='/js/jquery-11.0.min.js'></script>
	<script type='text/javascript' src='/js/unitegallery/unitegallery.min.js'></script>		
	<link rel='stylesheet' href='/js/unitegallery/unite-gallery.css' type='text/css' />	
	<script type='text/javascript' src='/js/unitegallery/ug-theme-default.js'></script>
	<link rel='stylesheet' href='/js/unitegallery/ug-theme-default.css' type='text/css' />
	
	<div id="galleryEnduro" style="margin:0px auto;display:none;">		
		{foreach from=$models item="m"}				
		<img alt="" data-description=""
			 src="/images/enduro/Small/{$m.big}"
			 data-image="/images/enduro/Big/{$m.big}">
		{/foreach}			
	</div>	
	{literal}<script type="text/javascript">	
		jQuery(document).ready(function(){	
			jQuery("#galleryEnduro").unitegallery();	
			
			//$( ".MPV .ug-default-button-fullscreen:first" ).trigger( "click" );
		});			
	</script>{/literal}
{/if}	
	
{if 1==4}
	<script type='text/javascript' src='/js/jquery-1.11.2.min.js'></script>
	<style>.list-auto-wrap { border:0;width:776px; }	
	 .fotorama img{ max-width: 776px; }
	 .MPV .fotorama img{ max-width: 320px; }	</style>	
	<link  href="/js/fotorama-4.6.3/fotorama.css" rel="stylesheet">	
	<script type="text/javascript" src="/js/fotorama-4.6.3/fotorama.js"></script>

	<div class="fotorama" {if !$MobilePageVersion}data-width="776" id="keyboard" data-nav="thumbs"{/if} data-ratio="3/2" data-loop="true" >
		{foreach from=$models item="m"}	
			<img src="/images/enduro/Big/{$m.big}" small="/images/enduro/Small/{$m.big}"/>
		{/foreach}
	</div>
{/if}	
	
{if 1==3}
	<link rel="stylesheet" href="/css/jquery.fancybox-1.3.4.css" type="text/css" media="all"/>
	<script type="text/javascript" src="/js/jquery.fancybox-1.3.4.pack"></script>
	<script type="text/javascript">
		$(document).ready(function() {		
			$("a.fancybox").fancybox({
				//'transitionIn'  :   'elastic',
				//'transitionOut' :   'elastic',
				//'speedIn'       :   400,
				//'speedOut'      :   300,
				//'padding'		:	0,				
				'overlayShow'   :   true,
				'titleShow'		:	false
			});
		});
	</script>
	<style>#fancybox-left, #fancybox-right {  width: 50%; } </style>
	{foreach from=$models item="m"}		
		<li class="m12 ready">
			<a href="/images/enduro/Big/{$m.big}" class="fancybox" rel="groop">
				<span class="list-img-wrap">				
					<img src="/images/enduro/Small/{$m.big}" _small={$m.small} />
				</span>
				<span class="zoom"></span>
			</a>			
			{*<div class="infobar"></div>*}
			{*<div class="item">
			  { *<span class="author">{$filters.category}</span>
			  <a href="#" class="buy" rel="nofollow" title="Заказать"></a>* }
			  <!--noindex--><span class="price">12000&nbsp;руб.</span><!--/noindex-->
			</div>*}
		</li>		
	{/foreach}	
{/if}
	
{if 1==2}
<div class="BIG-model-items">
	<div class="item">
		<div class="titl">Галерея готовых дизайнов</div>
		{*<div class="right" style="float: right;width: 431px;">
			<div class="titl">КТМ SX 450 2010-2012</div>
			<div class="name-author">Red 1595 - автор mike</div>	
		</div><div style="clear:both;"></div>*}				
		<div class="blog-slider" align="center">
			<img src="/images/enduro/Big/m01.png"/>
			<img src="/images/enduro/Big/m02.png"/>
			<img src="/images/enduro/Big/m03.png"/>
			<img src="/images/enduro/Big/m04.png"/>
			<img src="/images/enduro/Big/m05.png"/>
			<img src="/images/enduro/Big/m06.png"/>
			<img src="/images/enduro/Big/m07.png"/>
			<img src="/images/enduro/Big/m08.jpg"/>
			<img src="/images/enduro/Big/m09.jpg"/>
			<img src="/images/enduro/Big/m10.png"/>
			<img src="/images/enduro/Big/m11.png"/>
			<img src="/images/enduro/Big/m12.jpg"/>
			<img src="/images/enduro/Big/m13.png"/>
			<img src="/images/enduro/Big/m14.png"/>
			<img src="/images/enduro/Big/m15.png"/>
			<img src="/images/enduro/Big/m16.png"/>
			<img src="/images/enduro/Big/m17.png"/>
			<img src="/images/enduro/Big/m18.png"/>
			<img src="/images/enduro/Big/m19.png"/>
			<img src="/images/enduro/Big/m20.png"/>
			<img src="/images/enduro/Big/m21.png"/>
			<img src="/images/enduro/Big/m22.png"/>
			<img src="/images/enduro/Big/m23.png"/>		
						
			<img src="/images/enduro/Big/BhTuz8x35XU.jpg" width="604" height="340" title="" alt=""/>
			<img src="/images/enduro/Big/EuasL9L3DXc.jpg" width="604" height="340" title="" alt=""/>
			<img src="/images/enduro/Big/GjmXvLvAbhw.jpg" width="604" height="340" title="" alt=""/>
			{*<img src="/images/enduro/Big/HCw9fNIiYC8.jpg" width="604" height="340" title="" alt=""/>*}
			<img src="/images/enduro/Big/ImhJ8zsIHK4.jpg" width="" height="340" title="" alt=""/>
			<img src="/images/enduro/Big/RPCpGaAVlUc.jpg" width="604" height="340" title="" alt=""/>
			<img src="/images/enduro/set3.jpg" width="431" height="305" title="" alt=""/>
			
			<img src="/images/enduro/Big/1380299_889987671045784_6564117502928594866_n.jpg" width="" height="" title="" alt=""/>
			<img src="/images/enduro/Big/1620642_867386803305871_668270458644279159_n.jpg" width="" height="" title="" alt=""/>
			{*<img src="/images/enduro/Big/1661986_896794240365127_8025024188648830286_n.jpg" width="" height="" title="" alt=""/>*}
			<img src="/images/enduro/Big/10157110_889987667712451_1010214156227539134_n.jpg" width="" height="" title="" alt=""/>
			{*<img src="/images/enduro/Big/10341514_867386699972548_5069302472364236529_n.jpg" width="" height="" title="" alt=""/>*}
			<img src="/images/enduro/Big/10377015_870732516304633_857787386216737679_n.jpg" width="" height="" title="" alt=""/>
			{*<img src="/images/enduro/Big/10424308_867392163305335_6172869154997985140_n.jpg" width="" height="" title="" alt=""/>
			<img src="/images/enduro/Big/10425458_889182111126340_3519306076088596031_n.jpg" width="" height="" title="" alt=""/>			
			<img src="/images/enduro/Big/10458217_900798096631408_3512995499223004790_n.png" width="" height="" title="" alt=""/>
			<img src="/images/enduro/Big/10478199_870479322996619_6507065293437623365_n.jpg" width="" height="" title="" alt=""/>
			<img src="/images/enduro/Big/10549268_904645219580029_6131454844868629941_o.png" width="" height="" title="" alt=""/>
			<img src="/images/enduro/Big/10644389_904645226246695_4818380917485408817_o.png" width="" height="" title="" alt=""/>*}
			<img src="/images/enduro/Big/10689838_867386646639220_1203002468863490461_n.jpg" width="" height="" title="" alt=""/>
			<img src="/images/enduro/Big/10881665_867424259968792_5218642826648310256_n.png" width="" height="" title="" alt=""/>
			<img src="/images/enduro/Big/10885363_867392159972002_8639213915891387382_n.jpg" width="" height="" title="" alt=""/>
			<img src="/images/enduro/Big/10885467_867383733306178_6084606337736661850_n.jpg" width="" height="" title="" alt=""/>
			<img src="/images/enduro/Big/10885545_867386649972553_7480084172111547816_n.jpg" width="" height="" title="" alt=""/>
			<img src="/images/enduro/Big/10897047_870510956326789_2132328543639953550_n.jpg" width="" height="" title="" alt=""/>
			<img src="/images/enduro/Big/10959485_891221050922446_1201572203639163220_n.jpg" width="" height="" title="" alt=""/>
			{*<img src="/images/enduro/Big/10959489_896794237031794_8072321817240484863_n.jpg" width="" height="" title="" alt=""/>
			<img src="/images/enduro/Big/10984195_903707896340428_4772784952233235148_o.png" width="" height="" title="" alt=""/>
			<img src="/images/enduro/Big/10985229_896793887031829_1553744009695585001_n.jpg" width="" height="" title="" alt=""/>
			<img src="/images/enduro/Big/11021504_903707893007095_5426721633399797264_o.png" width="" height="" title="" alt=""/>*}
		</div>
		<div class="shop">
			<div class="prs">
				{*<strike>10 000</strike>&nbsp;&nbsp;&nbsp;*}
				<span class="sum">12 000 руб.</span>
			</div>			
			<a href="#" class="buy" rel="nofollow" title="Заказать"></a>
			<div style="clear:both;"></div>					
		</div>
	</div>	
	<div id="orderReadyDesign" style="display:none;">
		<div class="orderReadyDesign">
			<form method="post" action="/ajax/buyEnduroSticker/">
				<input type="hidden" name="img" value="">
				<input type="text" name="email" value="{if $USER->authorized && $USER->user_email}{$USER->user_email}{/if}" placeholder="{if $USER->authorized && $USER->user_email}{$USER->user_email}{else}Введите e-mail{/if}">
				<input type="text" name="phone" value="{if $USER->authorized && $USER->user_phone}{$USER->user_phone}{/if}" placeholder="{if $USER->authorized && $USER->user_phone}{$USER->user_phone}{else}Введите телефон{/if}">						
				<input type="submit" name="submit" value="Заказать" title="Заказать">
			</form>
		</div>	
	</div>
</div>
{/if}
