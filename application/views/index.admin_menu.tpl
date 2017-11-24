{literal}<style>
/*alex меню ad*/
#alex-menu{position:fixed;left:0px;z-index: 999;bottom: 22px;border: 1px dashed orange;
border-left:0;padding:2px 2px;}
#alex-menu a{clear: both;display: block;background:url("/images/_tmp/alex-menu4.png") no-repeat;}
#alex-menu hr{clear:both;float:none;width:48px;position:relative;top:3px;margin-bottom:3px;}
#alex-menu .pageversion{background-position:0  0px;width:30px;height:47px;background-position: -7px 0px;}
#alex-menu .pageversion.activ{opacity: 0.5;}
#alex-menu .alertTrackUser{float: left;clear: none;width:24px;height:49px;}
#alex-menu .alertTrackUser.temporarily{background-position: -45px -45px;}
#alex-menu .alertTrackUser.temporarily.on{background-position: -70px -45px;}
#alex-menu .alertTrackUser.always{float: right;background-position: -92px -45px;}
#alex-menu .alertTrackUser.always.on{background-position: -115px -45px;}
#alex-menu .foamImgs{width:40px;height:40px;background-position: 0px -50px;}

#alex-menu .flagMoscow{width:33px;height:28px;margin:0 0 4px 0;background-position:0px -93px;}
#alex-menu .flagRegion{width:33px;height:28px;margin:0 0 4px 0;background-position:-33px -93px;}

[lang="en"] #alex-menu .flag{background-position:1px -93px;}
#alex-menu .experiment{background: none;text-align: center;}
#alex-menu .experiment.active{color:red}
#alex-menu .experimentCustomize{background: none;text-align: center;}
#alex-menu .experimentCustomize.active{color:red}
</style>{/literal}


<div id="alex-menu">	
	
	<a href="/set_location/{if $USER->city == 'Москва'}1212/{/if}" class="{if $USER->city == 'Москва'}flagMoscow{else}flagRegion{/if} {if $MobilePageVersion}activ{/if} {if $USER->client->ismobiledevice == '1' && $USER->client->istablet == 0}phone{/if}"></a>
	
	<a href="" class="pageversion {if $MobilePageVersion}activ{/if} {if $USER->client->ismobiledevice == '1' && $USER->client->istablet == 0}phone{/if}" title="мобильная версия сайта"></a>	
	
	{if $USER->id == 6199 && ($PAGE->module=="customize" || $PAGE->module=="customize.v2" ||$PAGE->module=="stickermize" || $PAGE->module=="stickermize.v2") && $style_id}	
		<hr/>
		<a href="#" class="experimentCustomize null" title="Обнулить эксперимент">обнулить</a>	
		<a href="#" class="experimentCustomize usual {if $smarty.cookies.experimentCustomize==1}active{/if}" title="Включить обычный конструктор в эксперименте">обычный</a>	
		<a href="#" class="experimentCustomize unusual {if $smarty.cookies.experimentCustomize==2}active{/if}" title="Включить конструктор V2 в эксперименте">v2</a>	
	{/if}
	
	{if $USER->id == 27278 || $USER->id == 6199}	
		<hr/>
		<a href="#" class="alertTrackUser temporarily" style="{if $smarty.cookies.alertTrackUser}display:none{/if}" title="Треки до перезагрузки"></a>				
		<a href="#" class="alertTrackUser always {if $smarty.cookies.alertTrackUser}on{/if}" title="Треки работают пока не выключить"></a>
	{/if}
	
	{if $USER->id == 105091}	
		{if $MobilePageVersion}
			<hr/>			
			<a href="/language/{if $PAGE->lang != 'ru'}ru{else}en{/if}/" class="flag" title="язык"></a>
		{/if}
		{if !$MobilePageVersion && $module == 'catalog'}
			<hr/>
			<a href="#" class="foamImgs" title="мыльная картинка"></a>
			{literal}<style>#wrapper #containerwrap.foamIm #content .list_wrap ul li.m12 a span.list-img-wrap img{width:auto!important;height:auto!important}</style>{/literal}	
		{/if}
		
		{*literal}<script type="text/javascript">	
		var textMobile={
			mobile:{},
			init: function(){
				document.ontouchmove = document.ontouchstart = document.ontouchend =textMobile.touchHandler;
			},
			touchHandler: function (e){					
				if (e.type == "touchstart") {

					// If there's only one finger touching
					if (e.touches.length == 1) {
						//alert('touchstart'); 
					}else{						
						//alert('touchstart 2'); 
					}
					
					
				} else if (e.type == "touchmove") {

					// If there's only one finger touching
					if (e.touches.length == 1) {
						//var touch = e.touches[0];							
						alert('1 touchmove');
					} else if (e.touches.length == 2){
						alert('2 touchmove');
					}
					
				// If the user has removed the finger from the screen
				} else if (e.type == "touchend" || e.type == "touchcancel") {
					
					alert('touchend || touchcancel');

				} else {
					alert('Nothing');
				}
			}
		}
		
		if(!!navigator.userAgent.match(/iPhone|iPod|iPad|Android/i))
			textMobile.init();			
		</script>{/literal*}			
	{/if}
</div>