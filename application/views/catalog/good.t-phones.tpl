<div class="good-tpl-phones">
    <ul>
		<li>{$L.GOOD_auto_text1}</li>
		<li>{$L.GOOD_auto_text2}</li>
		<li>{$L.GOOD_auto_text3}</li>
		<li>{$L.GOOD_auto_text4}</li>
		<li>{$L.GOOD_auto_text5}</li>
		<li>{$L.GOOD_auto_text6}</li>
		<li>{$L.GOOD_auto_text7}</li>
		{*<li>Наклейки напечатаны на профессиональной плёнке 3м</li>
		<li>Наклеиваются без пузырьков и легко удаляется</li>
		<li>Все наклейки вырезаны по контуру</li>
		<li>Высококачественная печать</li>
		<li>Ламинация зашишает от выцветания и царапин</li>
		<li>Гарантия на ламинацию 3 года</li>
		<li>Не портит лако-красочное покрытие</li>*}
    </ul>
    {if $styles.phones|count > 1}
    <div id="phones-select"><!--noindex-->
		<select link_title=".span-select">
			{assign var="prev" value=""}
			
			{foreach from=$styles.phones item="p" name="phones"}				
				{if $p.phones_category != $prev}
					{if $prev}
					<option value=""></option>
					{/if}
					{if $p.phones_category == 'cases'}
						{if $modal}<option style="font-weight:bolder"><b>&mdash; Чехлы</b></option>{/if}
					{elseif $p.phones_category == 'phones'}
						{if $modal}<option style="font-weight:bolder"><b>&mdash; Наклейки</b></option>{/if}
					{/if}
				{/if}
				{assign var="prev" value=$p.phones_category} 
				<option value="{$p.style_id}" {if $p.style_id == 333}selected="selected"{/if} hash="{$p.style_slug}" id="{$p.style_id}">{if $modal}{$p.category} {* $prev *} {$p.style_name}{/if}</option>
			{/foreach}
		</select><!--/noindex-->
	</div>
	{/if}
	
	<!--noindex-->
	<div class="img-phones clearfix">
        <div class="phone_sum phone-active" phone="1" gender="224" gender5="333" size="27" title="телефон" >
            <img style="display:none" src="/images/catalog/aphone1-1.gif" alt="телефон" title="телефон"  />
            {*<span style="display:none">{if $modal}Наклейка 690 руб{else}<br replaces="Наклейка 690 руб"/>{/if}</span>*}
        </div>		
		<div class="vertikal"></div>
		<div class="phone_sum" phone="4" gender="579" gender5="580" size="27">
            <img style="display:none" src="/images/catalog/phone1-2.gif" alt="Смола" title="Смола" />
            {*<span style="display:none">{if $modal}Смола{else}<br replaces="Смола"/>{/if}</span>*}
        </div>
		<div class="vertikal"></div>
        <div class="phone_sum" phone="2" gender="315" gender5="354" size="67">
           <img style="display:none" src="/images/catalog/phone1-2.gif" alt="чехол" title="чехол" />
            {* <span style="display:none">{if $modal}Чехол матовый 890 руб{else}<br replaces="Чехол матовый 890 руб"/>{/if}</span>*}
			<i class="s"></i>
        </div>
		<div class="vertikal"></div>
		<div class="phone_sum" phone="5" gender="" gender5="" size="">
            <img style="display:none" src="/images/catalog/phone1-2.gif" alt="чехол" title="чехол" />
            {*<span style="display:none">{if $modal}Чехол глянцевый 890 руб{else}<br replaces="Чехол глянцевый 890 руб"/>{/if}</span>*}
			<i class="n"></i>
        </div>
		<div class="vertikal"></div>
        <div class="phone_sum" phone="3" gender="359" gender5="360" size="68">
            <img style="display:none" src="/images/catalog/phone1-3.gif" height="80" alt="бампер" title="бампер" />
            {*<span style="display:none">{if $modal}Бампер 690 руб{else}<br replaces="Бампер 690 руб"/>{/if}</span>*}
        </div>
    </div><!--/noindex-->
	
</div>
<script type="text/javascript">

    phoneRemindMe = {
        init: function(){
            if ($('#b-good-phone-remind').length > 0) { this.html = $('#b-good-phone-remind'); return; }
            this.html = $('<div class="b-good-price-submit" id="b-good-phone-remind" style="display:none;background:none;padding-bottom:10px;margin-bottom:10px;border-bottom: 1px dotted #888;">'+
                    '<input type="button" title="Сообщить мне" value="" style="background:url(/images/buttons/btn_remindme.gif) no-repeat 0 0;float: right;width: 170px;height: 40px;border: none;cursor: pointer;margin: 0px 27px 0 3px;" />'+
                    '<div class="good-price" style="border-left:3px solid #00a952;line-height:19px;padding:0px 0px 0px 11px;">'+
                    '<div style="font-family:MyriadPro-CondIt;font-size:18px;font-weight:bold;">Чехлы для iPhone 4</div>'+
                    '<div style="font-size:11px;color:#7c7c7c;">Скоро в продаже</div>'+
                    '</div>'+
                    '</div>');

            var self = this;
            this.html.find('input').click(function(){ self.showBox(1); });

            $('.price-order').prepend(this.html);
        },
        showBox: function(step){
            var stepHtml = '';
            if (step == 1)
                stepHtml = '<div style="font-family:MyriadPro-CondIt;font-size:24px;color:#747474;">Чехлы для iPhone 4</div>'+
                        '<div style="margin-top:5px;"><input style="width:372px;height:33px;border 1px solid #c6c6c6;" placeholder="введите адрес своей электронной почты" /></div>'+
                        '<div style="margin-top:10px;float: right;margin-right: 33px;"><a href="#" style="color:#9f9f9f;line-height: 39px;text-decoration:underline;float:left;">отмена</a><a href="#" style="text-align:center;line-height: 39px;color:white;float:left;display:block;margin-left:15px;width:152px;height:39px;background-color:#00a952;">Отправить</a></div>';
            else if (step == 2)
                stepHtml = '<div style="font-family:MyriadPro-CondIt;font-size:24px;color:#747474;">Спасибо!</div>'+
                        '<div style="margin-top:5px;color:#b3b3b3;width: 221px;font-size: 14px;">Мы сообщим вам, когда чехлы для iPhone 4 появятся в продаже</div>'+
                        '<div style="margin-top:10px;margin-right: 33px;"><a href="#" style="color:#00a952;line-height: 39px;text-decoration:underline;float:left;">закрыть</a></div>';

            if (this.box) this.box.remove();

            this.box = $('<div id="TB_overlay" class="TB_overlayBG" style="display:none;"></div>'+
                    '<div id="TB_window" style="z-index:2000;width:443px;height:235px;margin-left:-222px;margin-top: -117px;background:url(/images/catalog/phone-remind-bg.jpg)no-repeat right bottom white;">'+
                    '<div style="padding:52px 0px 0px 33px;">' + stepHtml + '</div>' +
                    '</div>');
            $(document).find('body').append(this.box);

            var self = this;
            this.box.find('input').focus(function(){ $(this).attr('placeholder',''); }).blur(function(){ $(this).attr('placeholder','введите адрес своей електронной почты'); });
            this.box.find('a').unbind('click').bind('click', function(){
                if ($(this).text() == 'Отправить') {
                    if (!self.submit()) return false;
                } else self.box.remove();
                return false;
            });

            this.box.show();
        },
        submit: function(){
            if (!isValidEmail(this.box.find('input').val())) { alert('То, что Вы ввели, не похоже на адрес электронной почты.'); this.box.find('input').focus().select(); return false; }
            var self = this;
            $.get('/ajax/announce_me/?email='+this.box.find('input').val(), function(request){
                if (request == '1')
                    self.showBox(2);
                else if (request == '-1') alert('Вы уже подписаны.');
                else alert('Произошла ошибка на сервере, попробуйте ещё.');
            });
        },
        hideBox: function(){ if (this.box) this.box.remove(); },
        hideAll: function(){ $('.price-order .b-good-price-submit, .price-order .m23').hide(); },
        showBtn: function(){ this.init(); this.hideAll(); this.html.show(); },
        hideBtn: function(){ if (this.html) { this.html.remove(); this.html=null; }  $('.price-order .b-good-price-submit, .price-order .m23').show(); }
    }

	function ChangeTitleCombo() {
		var sel = $('.good-tpl-phones #phones-select select')[0];
		if ($(sel).prev().hasClass('span-select'))
			$(sel).prev().text($('option:selected',sel).text());
	}
	
    $(document).ready(function(){ 
        goodForm.callback.push(function(a, b){
			if (a == 'setcategory' || a == 'initphone') {
				if (b.curCategory == 'phones') {
					//if (b.curGender == '315') phoneRemindMe.showBtn(); else phoneRemindMe.hideBtn();
					//чехол
					if (b.curGender == '315')
						$('#phone_delivery_8mart').show();
					else $('#phone_delivery_8mart').hide();
					
				} else  {
					phoneRemindMe.hideAll();
					phoneRemindMe.hideBox();
					phoneRemindMe.hideBtn();
				}
			}			
		});
		window.setCurrentPhone = function(c){
			if(typeof c == 'undefined'){
				c = false;
				var hash = window.location.hash.substr(1);
				if(hash != ''){
					for(var key in goodForm.designData[goodForm.curCategory]){
						if(goodForm.designData[goodForm.curCategory][key].style_slug == hash){
							c = key;
							break;
						}
					}
				}
				if(!c){
					c = $('.good-tpl-phones #phones-select select').val();
				}
			}
			
			
			$('.good-tpl-phones #phones-select select').val(c);
			//ищем все возможные связи
			var relations = {};
			var count = 0;
			var data = goodForm.designData[goodForm.curCategory];
			//if (data[c] && data[c].style_slug.indexOf('iphone') >= 0) {
				for(var i in data){
					var key = 'phone';
					if(data[i].style_slug.indexOf('case') >= 0) key = 'case';
					if(data[i].style_slug.indexOf('bumper') >= 0) key = 'bumper';
					if(data[i].style_slug.indexOf('resin') >= 0) key = 'resin';
					if(data[i].style_slug.indexOf('glossy') >= 0) key = 'case_glossy';
					if(i == c){
						relations[key] = parseInt(i);
						count++;
						if(data[i].relations != null){
							for(var a in data[i].relations){
								if(typeof data[data[i].relations[a]] != "undefined"){
									relations[a] = data[i].relations[a];
									count++;
								}
							}
						}
					}
					
					if(data[i].relations != null){
						for(var a in data[i].relations){
							if(data[i].relations[a] == c){
								relations[key] = parseInt(i);
								count++;
							}
						}
					}
				}
			//}
			//$('.good-tpl-phones .img-phones').show().find('.phone_sum, .vertikal, .vidCase').hide().removeClass('phone-active');
			$('.good-tpl-phones .img-phones').show().find('.phone_sum, .vertikal, .phone_sum>i').hide().removeClass('phone-active');
			$('.good-tpl-phones .img-phones').show().find('.phone_sum>i').removeClass('n s');
			for(var i in relations){
				var attr = '';
				if(i == 'phone') attr = '1';
				if(i == 'resin') attr = '4';
				if(i == 'case') attr = '2';
				if(i == 'case_glossy') attr = '5';
				if(i == 'bumper') attr = '3';
				if(typeof data[relations[i]] != 'undefined'){
					if(attr == '2'){
						var nsClass = '';
						try{
							for(var ss in data[relations['case']].sizes){
								for(var cc in data[relations['case']].sizes[ss].colors){
									nsClass = (data[relations['case']].sizes[ss].colors[cc].status == 'few') ? 's' : (data[relations['case']].sizes[ss].colors[cc].status == 'new') ? 'n' : '';
									break;
								}
								break;
							}
						}catch(e){}
						$('.good-tpl-phones .img-phones .phone_sum[phone=2]').find('i').addClass(nsClass).show();
					}
					if(attr == '5'){
						nsClass = '';
						try{
							for(var ss in data[relations['case_glossy']].sizes){
								for(var cc in data[relations['case_glossy']].sizes[ss].colors){
									nsClass = (data[relations['case_glossy']].sizes[ss].colors[cc].status == 'few') ? 's' : (data[relations['case_glossy']].sizes[ss].colors[cc].status == 'new') ? 'n' : '';
									break;
								}
								break;
							}
						}catch(e){}
						$('.good-tpl-phones .img-phones .phone_sum[phone=5]').find('i').addClass(nsClass).show();
					}
					if(false && attr == '2'){
						var id = '';
						if((typeof relations['case'] != 'undefined') && (relations['case'] == c)){
							id = relations['case'];
							$.cookie('case_type','mat');
						}
						if((typeof relations['case_glossy'] != 'undefined') && (relations['case_glossy'] == c)){
							id = relations['case_glossy'];
							$.cookie('case_type','gloss');
						}
						if(id == ''){
							//тут мы потому что чехол не выбран, у нас может быть связь как с матовым так и глянцевым вместе, а место для них одно
							//поэтому если связи две, берем например матовый
							id = (typeof relations['case'] != 'undefined') ? relations['case'] : relations['case_glossy'];
							if(($.cookie('case_type') == 'gloss') && (typeof relations['case_glossy'] != 'undefined')){
								id = relations['case_glossy'];
							}
						}
						$('.good-tpl-phones .img-phones').find('div[phone='+attr+']').show().attr('gender',id);//relations[i]
					}else{
						$('.good-tpl-phones .img-phones').find('div[phone='+attr+']').show().attr('gender',relations[i]);
					}
					$('.good-tpl-phones .img-phones').find('div[phone='+attr+']').prev().show();
					if(c == relations[i]){
						$('.good-tpl-phones .img-phones').find('div[phone='+attr+']').addClass('phone-active');
						
						if(false && attr == '2'){
							//чехол, смотрим, есть ли связь с другим типом чехла? если есть, значит показываем доп менюшку, и подсвечиваем текущий
							if((typeof relations['case'] != 'undefined') && (typeof relations['case_glossy'] != 'undefined')){
								var t = 'm';
								t = relations['case_glossy'] == c ? 'g' : 'm';
								if(relations['case'] == c) t = 'm';
								if(relations['case_glossy'] == c) t = 'g';
								$('.good-tpl-phones .img-phones .vidCase').show().find('div').removeClass('activ').filter('.'+t).addClass('activ');
								$('.good-tpl-phones .img-phones .vidCase .m').attr('gender',relations['case']).find('i').removeClass('n s');
								$('.good-tpl-phones .img-phones .vidCase .g').attr('gender',relations['case_glossy']).find('i').removeClass('n s');
								
								var nsClass = '';
								try{
									for(var ss in data[relations['case']].sizes){
										for(var cc in data[relations['case']].sizes[ss].colors){
											nsClass = (data[relations['case']].sizes[ss].colors[cc].status == 'few') ? 's' : (data[relations['case']].sizes[ss].colors[cc].status == 'new') ? 'n' : '';
											break;
										}
										break;
									}
								}catch(e){}
								$('.good-tpl-phones .img-phones .vidCase .m').find('i').addClass(nsClass);
								nsClass = '';
								try{
									for(var ss in data[relations['case_glossy']].sizes){
										for(var cc in data[relations['case_glossy']].sizes[ss].colors){
											nsClass = (data[relations['case_glossy']].sizes[ss].colors[cc].status == 'few') ? 's' : (data[relations['case_glossy']].sizes[ss].colors[cc].status == 'new') ? 'n' : '';
											break;
										}
										break;
									}
								}catch(e){}
								$('.good-tpl-phones .img-phones .vidCase .g').find('i').addClass(nsClass);
							}
						}
					}
				}
			}
			//исправляем первые разделительные линии, последние и так не отображаются
			if($('.good-tpl-phones .img-phones .vertikal:visible').first().index() < $('.good-tpl-phones .img-phones .phone_sum:visible').first().index())
				$('.good-tpl-phones .img-phones .vertikal:visible').first().hide();
			
			//проверяем, если связей только одна на себя, или нет вообще, то скрываем менюшку
			//банально проходим по объекту, и устанавливаем флаг "не прятать", если находим связи не на нас.
			var not_hide = false;
			for(var i in relations)
				if(relations[i] != c) not_hide = true;
			if(!not_hide)
				$('.good-tpl-phones .img-phones').hide();
			
			goodForm.curGender = c;
			for(var i in data[c].sizes){
				goodForm.curSize = i;
				break;
			}
			
			var d = $('.good-tpl-phones');
			d.find('ul').remove();
			d.prepend(data[c].style_composition);
			
			location.hash = '#'+data[c].style_slug;
			
			goodForm.initVisibleGalleryItems();
			goodForm.reloadImg();
            goodForm.changePrice();
			goodForm.makeBreadCrumps();
			
			ChangeTitleCombo();
		}
		//перестраиваем меню после buildFirst и прочего
		//сперва определяемся со стартом, берем якорь, ищем в массиве его, если находим, отталкиваемся от него, если нет, то от выбранного в селекте
		if(goodForm.curCategory == 'phones'){
			setCurrentPhone();
		}
		
		$('.good-tpl-phones #phones-select select').unbind('change').bind('change',function(){ 
			//var id = (isNaN(parseInt($(this).val()))?0:parseInt($(this).val()));//.find('option:selected').attr('id');
			var id = $(this).val();
			if(typeof goodForm.designData[goodForm.curCategory][id] == "undefined"){
				var invalid_id = this.selectedIndex?this.selectedIndex:-1;
				var options = $(this).find('option');
				for(var i = 0; i < options.length; i++){
					if((invalid_id < 0) && (options.eq(i).val() == id)){
						invalid_id = i;
					} 
					if((invalid_id != -1) && (invalid_id < i) && (typeof goodForm.designData[goodForm.curCategory][id] == "undefined")){
						id = options.eq(i).val();
						$(this).val(id);
					}
				}
			}
			if(typeof goodForm.designData[goodForm.curCategory][id] != "undefined"){
				setCurrentPhone(id);
			}
			return;
			
			
			var data = goodForm.designData[goodForm.curCategory][id];
			
			if (data && data.style_slug.indexOf('iphone') >= 0) {
				$('.good-tpl-phones .img-phones').show();
				location.hash = '#'+data.style_slug;
			} else {
				$('.good-tpl-phones .img-phones').hide(); location.hash='';
			}
			
			//debugger;
			/*if (data && data.relations) {
			//if (
			//	id == 333 || // Apple iPhone 5
			//	//id == 354 || // Apple Чехол для iPhone 5				
			//	id == 224 //|| // Apple iPhone 4S, 4				
			//	//id == 275 || // Apple iPhone 4S, 4 (с яблоком)
			//	//id == 222    // Apple iPhone 3G, 3Gs
			//	) { //Apple
				//$('.good-tpl-phones #phones-select').hide();
				$('.good-tpl-phones .img-phones').show();
				location.hash = '#'+data.style_slug;
			} else { $('.good-tpl-phones .img-phones').hide(); location.hash=''; }*/
			
			//if ($(this).prev().hasClass('span-select'))
			//	$(this).prev().text($('option:selected',this).text());
			
			//if (data && data.relations && data.relations.phone>0)
			//	goodForm.curGender = data.relations.phone;
			//else 
				goodForm.curGender = $(this).val();
            goodForm.buildFirst();
            goodForm.changePrice();
			ChangeTitleCombo();
		});	
		var d = $('.good-tpl-phones #phones-select select')
		if (d.prev().hasClass('span-select'))
			d.prev().text($('option:selected',d).text());
		
		$('.good-tpl-phones .img-phones .phone_sum').add('.good-tpl-phones .img-phones .phone_sum .vidCase div').unbind('click').bind('click',function(){
			setCurrentPhone($(this).attr('gender'));
			return;
			$('.img-phones .phone_sum').removeClass('phone-active');
            $(this).addClass('phone-active');

            $('.img-phones .phone_sum img').each(function(){
                var src = $(this).attr('src');
                $(this).attr('src', src.replace('aphone','phone'));
            });

            var src = $(this).find('img').attr('src');
            $(this).find('img').attr('src', src.replace('phone','aphone'));

            goodForm.curSize = $(this).attr("size");
            //goodForm.curGender = $(this).attr("gender");
			
            var hash = $('#phones-select select option:selected').attr('hash');
			if (hash.indexOf('5')>0)
				goodForm.curGender = $(this).attr('gender5');
			else
				goodForm.curGender = $(this).attr('gender');
			$('#phones-select select').val(goodForm.curGender);
			
			var data = goodForm.getDesignData();
			if (data) location.hash = '#'+data.style_slug; 
			
			if (data.sizes[goodForm.curSize] == null) {
				for(var n in data.sizes) { goodForm.curSize = n; break; }
			}
			
			$('#voting-img-wrap .bumper').remove();
			
			if ($(this).attr('phone') == '2')
				$('#phone_delivery_8mart').show();
			else $('#phone_delivery_8mart').hide();
			
            //goodForm.buildFirst();
			goodForm.reloadImg();
            goodForm.changePrice();
			goodForm.makeBreadCrumps();
			
			ChangeTitleCombo();
			return;
			
			/* БОЮСЬ УДАЛЯТЬ, ПОКА ПЕРЕПИСАЛ. СВАЛКА ИЗ_ЗА 50РАЗ ПЕРЕДЕЛОК*/
			
			var isBumper 	= window.location.hash.indexOf('bumper') > 0;
			var isCase 		= window.location.hash.indexOf('case') > 0;
			var isPhone4 	= window.location.hash.indexOf('iphone-4') > 0;
			var isPhone5 	= window.location.hash.indexOf('iphone-5') > 0;
			if (typeof window.initGenderPhone != 'undefined' && $('#phones-select select').length > 0) {
				isPhone4 = ($('#phones-select select').val() == '224');
				isPhone5 = ($('#phones-select select').val() == '333');
			} else {
				if (isPhone4) { goodForm.curGender = 224; $('#phones-select select').val(224); }
				if (isPhone5) { goodForm.curGender = 333; $('#phones-select select').val(333); }
			}

			goodForm.curSumTo = 0;
			var getPhone = function(name) {
				for(var n in goodForm.designData.phones)
					if (goodForm.designData.phones[n].style_slug.toLowerCase() == name)
						return goodForm.designData.phones[n];
				return null;
			}
			if ($(this).attr('phone') == '2') { //чехол			
				goodForm.curGender = $('.good-tpl-phones #phones-select select').val();
				var data = goodForm.designData[goodForm.curCategory][goodForm.curGender];
				//var p = getPhone('case-iphone-'+(isPhone4?4:(isPhone5?5:0)));
				if (data && data.relations && data.relations['case'])
					var p = goodForm.designData[goodForm.curCategory][data.relations['case']];
				else
					var p = getPhone('case-iphone-'+(isPhone4?4:(isPhone5?5:0)));
				if (p)
					goodForm.curGender = p.style_id; 
			} else
			if ($(this).attr('phone') == '3') { //бампер
				goodForm.curGender = $('.good-tpl-phones #phones-select select').val();
				var data = goodForm.designData[goodForm.curCategory][goodForm.curGender];
				if (data && data.relations && data.relations.bumper)
					var p = goodForm.designData[goodForm.curCategory][data.relations.bumper];
				else
					var p = getPhone('iphone-'+(isPhone4?4:(isPhone5?5:0)));
				if (p) {
					goodForm.curGender = p.style_id; 
					for(var n in p.sizes) { goodForm.curSize = n; break; }
					$.cookie('catalog_size', goodForm.curSize); 
					/*var p1 = getPhone('iphone-'+(isPhone4?4:(isPhone5?5:0)));
					//var curr = p.sizes[goodForm.curSize].colors[goodForm.curColor];
					if (!p1)
						p1 = (data && data.relations && data.relations.phone?p:goodForm.designData[goodForm.curCategory][goodForm.curGender]);
					
					var curr1 = (p1.sizes[27]?p1.sizes[27].colors[goodForm.curColor]:null);
					if (p1 && curr1) goodForm.curSumTo = curr1.price;*/
				}
			} else
			if ($(this).attr('phone') == '4') { //смола
				goodForm.curGender = $('.good-tpl-phones #phones-select select').val();
				var data = goodForm.designData[goodForm.curCategory][goodForm.curGender];
				if (data && data.relations && data.relations.resin)
					var p = goodForm.designData[goodForm.curCategory][data.relations.resin];
				else
					var p = getPhone('iphone-'+(isPhone4?4:(isPhone5?5:0)));
				if (p) {
					goodForm.curGender = p.style_id; 
					for(var n in p.sizes) { goodForm.curSize = n; break; }
					$.cookie('catalog_size', goodForm.curSize); 
				}
			}
			else { //наклейка
				goodForm.curGender = $('.good-tpl-phones #phones-select select').val();
				var data = goodForm.designData[goodForm.curCategory][goodForm.curGender];
				if (data && data.relations && data.relations.phone)
					var p = goodForm.designData[goodForm.curCategory][data.relations.phone];
				else
					var p = getPhone('iphone-'+(isPhone4?4:(isPhone5?5:0)));
				if (p) goodForm.curGender = p.style_id;
				else goodForm.curGender = $('#phones-select select').val();
			}
			
			var data = goodForm.getDesignData();
			if (data) {
				isPhone5 = (data.style_slug == "iphone-5");
				isPhone4 = (data.style_slug == "iphone-4");
				if (typeof data.sizes[goodForm.curSize] == 'undefined')
					for(var n in data.sizes) { goodForm.curSize = n; break; }
				$.cookie('catalog_size', goodForm.curSize); 
				var g = $('.good-tpl-phones #phones-select select').val();
				if (parseInt(g) != parseInt(goodForm.curGender))
					$('.good-tpl-phones #phones-select select').val(goodForm.curGender);
				
				location.hash = '#'+data.style_slug;
			}
			
			//goodForm.initPhones();
			/*
			var isPhone5 = window.location.hash.indexOf('iphone-5') > 0;
			if ($('#phones-select select').is(':visible')) {
				isPhone5 = ($('#phones-select select').val() == '333');
			}*/
			
			//if (isPhone5) {
			//	goodForm.curGender = $(this).attr("gender5");
				/*goodForm.curGender = 333;
				goodForm.curSize = 27;
				if (window.location.hash.indexOf('case') >= 0) {
					goodForm.curGender = 354;
					goodForm.curSize = 27;
				}
				if (window.location.hash.indexOf('bumper') >= 0) {
					goodForm.curGender = 333;
					goodForm.curSize = 68;
				}*/
			//}
			//console.log(goodForm.curGender);
			//if ($(this).attr('phone') == '3')
			//	goodForm.curSize = $(this).attr("size");

            //if (goodForm.curGender == '315') phoneRemindMe.showBtn(); else phoneRemindMe.hideBtn();

            /*goodForm.curSumTo = /([\d\.\,]+)/.exec($(this).find('span').text())[1];
            if (goodForm.curSumTo) {
                goodForm.curSumTo = goodForm.curSumTo.toString().replace(',','.');
                goodForm.curSumTo = parseFloat(goodForm.curSumTo);
            }*/
						
			$('#voting-img-wrap .bumper').remove();
			/*if ($(this).attr('phone') == '3') {
				var img = '/images/catalog/bumper-fron-back.png';
				var css = { 'top':'44px',left:'-2px' };
				if (isPhone5) { img = '/images/catalog/iphone5_bumper.png'; css = { 'top':'0px',left:'0px' }; }
				$('#big-img').parent().append($('<img class="bumper" src="'+img+'" alt="" title="">').css(css));
			}*/
			
			if ($(this).attr('phone') == '2')
				$('#phone_delivery_8mart').show();
			else $('#phone_delivery_8mart').hide();
			
            //goodForm.buildFirst();
			goodForm.reloadImg();
            goodForm.changePrice();
			goodForm.makeBreadCrumps();
			
			ChangeTitleCombo();
        });
		
		$('.good-tpl-phones .vidCase div').click(function(){
			$(this).parent().find('div').removeClass('activ');
			$(this).addClass('activ');
			return false;
		});
    });
</script>