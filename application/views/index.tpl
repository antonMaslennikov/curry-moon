<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" lang="ru-ru"  class="frontpage" >
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# product: http://ogp.me/ns/product#">
    
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0">
    
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    
    <meta name="keywords" content="{$PAGE->keywords}" />
    <meta property="og:type" content="product" />
    <meta property="og:title" content="Интернет-магазин одежды и украшений - Curry Moon" />
    <meta property="og:url" content="https://curry-moon.com/ru" />
    <meta property="og:site_name" content="Curry Moon" />
    <meta property="og:description" content="{$PAGE->description}" />
    <meta name="description" content="{$PAGE->description}" />
    
    {if $PAGE->noindex || $appMode != 'production'}
		<meta name="robots" content="noindex">
	{/if}
   
    <title>{$PAGE->title} - Curry Moon</title>
    
    <link href="/public/images/favicon/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    
    <link href="/public/css/uikit.almost-flat.min.css" rel="stylesheet" type="text/css" />
    
    <link href="/public/css/k2.fonts.css?v2.7.1" rel="stylesheet" type="text/css" />
    <link href="/public/css/k2.css?v2.7.1" rel="stylesheet" type="text/css" />
    <link href="/public/css/rokbox.css" rel="stylesheet" type="text/css" />
    <link href="/public/css/modal.css" rel="stylesheet" type="text/css" />
    
    <link href="/public/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href="/public/css/normalize.css" rel="stylesheet" type="text/css" />
    <link href="/public/css/layout.css" rel="stylesheet" type="text/css" />
    <link href="/public/css/joomla.css" rel="stylesheet" type="text/css" />
    <link href="/public/css/system/system.css" rel="stylesheet" type="text/css" />
    <link href="/public/css/template.css" rel="stylesheet" type="text/css" />
    <link href="/public/css/menu/menu.css" rel="stylesheet" type="text/css" />
    <link href="/public/css/gk.stuff.css" rel="stylesheet" type="text/css" />
    <link href="/public/css/vm.css?v10" rel="stylesheet" type="text/css" />
    <link href="/public/css/style3.css" rel="stylesheet" type="text/css" />
    <link href="/public/css/typography/typography.style3.css" rel="stylesheet" type="text/css" />
    
    <link href="//fonts.googleapis.com/css?family=Open+Sans+Condensed:300&subset=latin,cyrillic" rel="stylesheet" type="text/css" />
    <link href="/public/css/module_default.css" rel="stylesheet" type="text/css" />
    <link href="/public/css/facebox.css?vmver=a30bd70d" rel="stylesheet" type="text/css" />
   
    <script src="/public/packages/jquery/jquery.min.js" type="text/javascript"></script>
    <script src="/public/js/jquery-noconflict.js" type="text/javascript"></script>
    <script src="/public/js/jquery-migrate.min.js" type="text/javascript"></script>
    
    <script src="/public/js/k2.frontend.js?v2.7.1&amp;sitepath=/" type="text/javascript"></script>
    <script src="/public/js/mootools-core.js" type="text/javascript"></script>
    <script src="/public/js/core.js" type="text/javascript"></script>
    <script src="/public/js/mootools-more.js" type="text/javascript"></script>
    <script src="/public/js/rokbox.js" type="text/javascript"></script>
    
    <script src="/public/js/bootstrap.min.js" type="text/javascript"></script>
    
    <script src="/public/js/modal.js" type="text/javascript"></script>
    <script src="/public/js/gk.scripts.js" type="text/javascript"></script>
    <script src="/public/js/gk.menu.js" type="text/javascript"></script>
    <script src="/public/js/fitvids.jquery.js" type="text/javascript"></script>
    <!--[if lt IE 9]><script src="/public/js/polyfill.event.js" type="text/javascript"></script><![endif]-->
    <script src="/public/js/acymailing_module.js" type="text/javascript"></script>
    <script src="/public/js/gk_shop_and_buy/engine.js" type="text/javascript"></script>
   
    {foreach $PAGE->js item="path" name="jsforeach"}
        <script type='text/javascript' src="{$path}"></script>
    {/foreach}
    
    {foreach $PAGE->css item="path" name="cssforeach"}
         <link href="{$path}" rel="stylesheet" type="text/css" />
    {/foreach}
   
    <script type="text/javascript">
        
        {if $appMode == 'production'}
        {literal}
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-64589637-1', 'auto');
            ga('send', 'pageview');
        {/literal}
        {/if}
         
        {literal}
         
        if (typeof RokBoxSettings == 'undefined') RokBoxSettings = {pc: '100'};

        jQuery(function($) {
            SqueezeBox.initialize({});
            SqueezeBox.assign($('a.modal').get(), {
                parse: 'rel'
            });
        });

        window.jModalClose = function () {
            SqueezeBox.close();
        };

        $GKMenu = { height:true, width:false, duration: 250 };
        $GK_TMPL_URL = "/templates/gk_instyle";

        $GK_URL = "/";

        try {$Gavick;}catch(e){$Gavick = {};};$Gavick["gkIs-gk-is-682"] = { "anim_speed": 1000, "anim_interval": 6000, "autoanim": 1, "slide_links": 1 };
        {/literal}
         
    </script>
    
    <link rel="apple-touch-icon" href="/public/images/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon-precomposed" href="/public/images/apple-touch-icon-120x120.png">

    <link rel="stylesheet" href="/public/css/small.desktop.css" media="(max-width: 1130px)" />
    <link rel="stylesheet" href="/public/css/tablet.css?v1" media="(max-width: 840px)" />
    <link rel="stylesheet" href="/public/css/small.tablet.css" media="(max-width: 800px)" />
    <link rel="stylesheet" href="/public/css/mobile.css" media="(max-width: 600px)" />
    <link rel="stylesheet" href="/public/css/override.css" />

    <!--[if IE 9]>
    <link rel="stylesheet" href="/public/css/ie/ie9.css" type="text/css" />
    <![endif]-->

    <!--[if IE 8]>
    <link rel="stylesheet" href="/public/css/ie/ie8.css" type="text/css" />
    <![endif]-->

    <!--[if lte IE 7]>
    <link rel="stylesheet" href="/public/css/ie/ie7.css" type="text/css" />
    <![endif]-->

    <!--[if lte IE 9]>
    <script type="text/javascript" src="/public/js/ie.js"></script>
    <![endif]-->

    <!--[if (gte IE 6)&(lte IE 8)]>
    <script type="text/javascript" src="/public/js/respond.js"></script>
    <script type="text/javascript" src="/public/js/selectivizr.js"></script>
    <![endif]-->

    {if $PAGE->canonical}
		<link rel="canonical" href="{$PAGE->canonical}" />
	{/if}
    
</head>


<body class="frontpage"  data-tablet-width="840" data-mobile-width="600" data-smoothscroll="1">	

    {if $appMode == 'production'}
        <script type="text/javascript">var _gaq = _gaq || []; _gaq.push(['_setAccount', 'UA-64589637-1']); _gaq.push(['_trackPageview']);(function() { var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s); })();</script>	
    {/if}

    {include file="header.tpl"}
    
    <div id="gkPageContent">
        <div class="gkPage">
            <section id="gkContent">					
                <div id="gkContentWrap" {if $PAGE->sidebar_tpl}class="gkSidebarLeft"{/if}>
                   
                    {if $PAGE->breadcrump|count > 0}
                    <section id="gkBreadcrumb">
                        <div class="krizalys_breadcrumb">
                            <span itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb" class="breadcrumb-container">
                                <a href="/" itemprop="url" class="pathway">
                                    <span itemprop="title">CurryMoon</span>
                                </a> 
                                / 
                                <span itemprop="child" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb" class="breadcrumb-container">
                                    {foreach from=$PAGE->breadcrump item="bc" name="bc_foreach"}
                                        {if $smarty.foreach.bc_foreach.last}
                                            <span class="pathway">{$bc.caption}</span>
                                        {else}
                                            <a href="{$bc.link}" itemprop="url" class="pathway">
                                                <span itemprop="title">{$bc.caption}</span>
                                            </a> / 
                                        {/if}
                                    {/foreach}
                                    
                                </span>
                            </span>
                        </div>
                    </section>
                    {/if}
                    
                    {include file="flashMessage.tpl"}
                    
                    {if count($flashMessages)}
                        {foreach from=$flashMessages key="typeFlash" item="messageFlash"}
                            
                            {if $typeFlash == "alert-success"}
                                <div class="successMessage">{$messageFlash}</div>
                            {/if}
                            
                        {/foreach}
                    {/if}
                    
                    {if $PAGE->tpl} 
                        {include file=$PAGE->tpl}
                    {else}
                        Не указан шаблон страницы
                    {/if}
                </div>
            
                {if $PAGE->sidebar_tpl}
                    {include file=$PAGE->sidebar_tpl}
                {/if}
            </section>
        </div>
    </div>
   
    {include file="footer.tpl"}  	


    <div id="gkfb-root"></div>

    {literal}
    <script type="text/javascript">

        //<![CDATA[
        window.fbAsyncInit = function() {
        FB.init({ appId: '', 
        status: true, 
        cookie: true,
        xfbml: true,
        oauth: true
        });

        function updateButton(response) {
            var button = document.getElementById('fb-auth');

            if(button) {   
                if (response.authResponse) {
                // user is already logged in and connected
                button.onclick = function() {
                    if(jQuery('#login-form').length > 0){
                    jQuery('#modlgn-username').val('Facebook');
                    jQuery('#modlgn-passwd').val('Facebook');
                    jQuery('#login-form').submit();
                    } else if(jQuery('#com-login-form').length > 0) {
                    jQuery('#username').val('Facebook');
                    jQuery('#password').val('Facebook');
                    jQuery('#com-login-form').submit();
                    }
                    }
                } else {
                    //user is not connected to your app or logged out
                    button.onclick = function() {
                        FB.login(function(response) {
                        if (response.authResponse) {
                        if(jQuery('#login-form').length > 0){
                        jQuery('#modlgn-username').val('Facebook');
                        jQuery('#modlgn-passwd').val('Facebook');
                        jQuery('#login-form').submit();
                        } else if(jQuery('#com-login-form').length > 0) {
                        jQuery('#username').val('Facebook');
                        jQuery('#password').val('Facebook');
                        jQuery('#com-login-form').submit();
                        }
                        } else {
                        //user cancelled login or did not grant authorization
                        }
                        }, {scope:'email'});   
                    }
                }
            }
        }
        // run once with current status and whenever the status changes
        FB.getLoginStatus(updateButton);
        FB.Event.subscribe('auth.statusChange', updateButton);	
        };
        //      
        jQuery(window).load(function() {
            (function(){
            if(!document.getElementById('fb-root')) {
                var root = document.createElement('div');
                root.id = 'fb-root';
                document.getElementById('gkfb-root').appendChild(root);
                var e = document.createElement('script');
                e.src = document.location.protocol + '//connect.facebook.net/ru_RU/all.js';
                e.async = true;
                document.getElementById('fb-root').appendChild(e);
            }  
            }());
        });
        //]]>
    </script>
    {/literal}
    
    <div id="gkPopupCart">        
        <div class="gkPopupWrap">        
            <div id="gkAjaxCart"></div>
        </div>
    </div>

    {include file="login\quick.tpl"}

    <div id="gkPopupOverlay"></div>   		

    {literal}
    <script>
    jQuery(document).ready(function(){
    // Target your .container, .wrapper, .post, etc.
    jQuery("body").fitVids();
    });
    </script>

    <!-- BEGIN JIVOSITE CODE {literal} -->
    <script type='text/javascript'>
    (function(){ var widget_id = 'HXa1kqOonQ';
    var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);})();</script>
    <!-- {/literal} END JIVOSITE CODE -->

    <script src="/public/js/uikit.min.js" type="text/javascript"></script>
    {/literal}
    
</body>
</html>