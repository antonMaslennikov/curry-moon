{*------------шаблон для catalog и good--------------*}
{literal}
<style>
	/*табы соц сетей*/
	.soc_div{clear:both;margin:20px auto 0;width: 255px;}
	.soc_tabs{background: #ffffff;clear: both;height: 30px;float: none!important;margin: 0!important;}	
	.soc_div .soc_tabs li {list-style: none;position: relative;float: left;font-size: 11px!important;color: #000000!important;width: auto!important;margin: 0;}
	.soc_div .soc_tabs li.tabs_fb{/*float: right;*/}
	.soc_tabs li.act_soc_tab {background-color: #d7d7d7;}
	.soc_tabs li i {
	background: url("/images/good/addTo.gif") repeat scroll 0 0 transparent;position: absolute;cursor: pointer;display: block;height: 16px;overflow: hidden;width: 16px;margin: 7px 0 0 10px;}
	.soc_tabs li.tabs_vk i {background-position: -17px 0 !important;}
	.soc_tabs li.tabs_fb i {background-position: -34px 0 !important;}
	.soc_tabs li a {font-size: 11px!important;color: #000000!important;position: relative;padding-left: 20px!important;
	text-decoration: underline;display: block;padding: 8px 10px 9px 30px!important;}
	.soc_tabs li.act_soc_tab a {cursor: default;text-decoration: none !important;}
	.soc_tabs_content{padding:10px;background-color: #d7d7d7;margin-top: -1px;}
	._4s7c {border-color:#d7d7d7!important;}
	
	.color3_bg {background-color: red;}
</style>

<script type='text/javascript'>
	$(document).ready(function(){
		$('.soc_tabs a').click(function(){
			$('.soc_tabs_content').hide();
			$('.soc_tabs li').removeClass('act_soc_tab');
			$(this).parent().addClass('act_soc_tab');
			$('#'+this.id+'_content').show();
			return false;
		});
		
	});
</script>
{/literal}

{if $module=='voting' && $good_id} {else}
	{literal}<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_EN/all.js#xfbml=1&appId=192523004126352";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	{/literal}
{/if}

<div class="soc_div"{if !empty($soc_width)} style="width:{$soc_width}px"{/if}>
   <ul class="soc_tabs">
		<li class="tabs_vk act_soc_tab">
			 <i></i>
			 <a id="soc_tabs_vk" rel="nofollow" href="#" title="ВКонтакте">ВКонтакте</a>
		</li>
		<li class="tabs_fb">
			 <i></i>
			 <a id="soc_tabs_fb" rel="nofollow" href="#" title="Facebook">Facebook</a>
		</li>
	</ul>
	<!--noindex-->
	<div id="soc_tabs_vk_content" class="soc_tabs_content">
		<div id="vk_groups"></div>
		<script type="text/javascript">
		VK.Widgets.Group("vk_groups", { mode: 0, width: "{if !empty($soc_width)}{$soc_width-20}{else}235{/if}", height: {if $filters.category == 'enduro' || $filters.category == 'jetski' || $filters.category == 'atv' || $filters.category == 'helmet' || $filters.category == 'helm'}"250"{else}"300"{/if} }, {if $SEARCH== 'мото' || $TAG.slug=='motocikl' || $category == 'moto' || $filters.category == 'enduro' || $filters.category == 'jetski' || $filters.category == 'atv'|| $filters.category == 'helmet' || $filters.category == 'helm'}69470333{else}1797113{/if});
		</script>
	</div>
	<div id="soc_tabs_fb_content"  class="soc_tabs_content" style="display:none;">
		<div class="fb-like-box" data-href="https://www.facebook.com/{if $SEARCH== 'мото' || $TAG.slug=='motocikl' || $category == 'moto' || $filters.category == 'enduro' || $filters.category == 'jetski' || $filters.category == 'atv'|| $filters.category == 'helmet' || $filters.category == 'helm'}Mjmotoru-1768931196756895{else}maryjaneisonmybrain{/if}" data-width="{if !empty($soc_width)}{$soc_width-20}{else}235{/if}" data-show-faces="true" data-header="true" data-stream="false" data-show-border="true"></div>
	</div>
	<!--/noindex-->
	<div style="clear:both;"></div>
</div>
<div style="clear:both;"></div>