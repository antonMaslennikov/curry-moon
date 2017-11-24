$(document).ready(function(){
	basket.init();
});

var basket = {

	totalPriceID: 	'#total-price, b.sum',
	totalRefoundID: '#total-refound',
	totalSumID: 	'.one-line input.g-total',
	totalLabelSumID: 	'.one-line div.g-total.edit',

	btn_plusID: 	'a.one-more',
	btn_lessID: 	'a.one-less',
	
	btn_sertPlusID: 	'.sert a.one-more',
	btn_sertLessID: 	'.sert a.one-less',
	
	label_count:	'.g-count .cnt',
	count:			'.g-count .cntedit',

	to_box: 'a.obertka',
	
	init: function(){
		this.initChangeCount();
		this.initChangeSum();
		this.initToBox();
	},
	
	initToBox: function(){
		$(this.to_box).click(function(){
			var self = $(this);
			$.post($(this).attr('href'), function(r){
				if (self.parent().hasClass('off')) {
					self.parent().removeClass('off').addClass('on-js').removeClass('off-js');//картинка коробки при кликнутом
					//self.html('Эта футболка будет<br/>упакована в коробку');
					self.attr('title','Эта футболка будет упакована в коробку');					
					$('.help-obertka').hide();
				} else {
					self.parent().addClass('off').removeClass('on-js').addClass('off-js');
					//self.html('Упаковать бесплатно');
					self.attr('title','Упакуйте эту футболку в красивую подарочную коробку');	
					$('.help-obertka').show();
				}
				
				var d = self.parents('.one-line').find('.g-total');
				var sumobertka = 50 * parseInt(d.attr('quantity'));
				//if (self.parent().hasClass('off')) sumobertka *= -1;
				//d.text(parseInt(d.attr('quantity')) * parseInt(d.attr('price')) + sumobertka);
				if (self.parent().hasClass('off'))
					d.text(parseInt(d.attr('quantity')) * parseInt(d.attr('price')));
				else 
					d.text(parseInt(d.attr('quantity')) * parseInt(d.attr('price')) + sumobertka);
				
				basket.calcTotal();
			});
			return false;
		}).mouseout(function(){
			$(this).parent().removeClass('on-js').removeClass('off-js');		
		});
	},
	
	initChangeSum: function(){
		var self = this;
		$(window).bind('click', function(){
			$(self.totalLabelSumID).each(function(){
				$(this).parent().find('input.g-total').hide();
				$(this).parent().find('.g-count').hide();
				$(this).show();
			});
		});
		$(self.count).keyup(function(){
			if(event.keyCode==13){
				$(this).parent().find('input.cntedit, .g-co').hide();
				$(this).parent().find('.cnt.text').show();
			}
		})
		
		$(this.totalLabelSumID).click(function(){ 
			$(this).hide();
			$(this).parent().find('.g-count').show();
			$(this).parent().find('input.g-total').show().first().select().focus();
			return false;
		});
		$(this.totalSumID)
		/*.focus(function(){ }, function(){
			$(this).parent().find('input.cntedit, .g-co').hide();
			$(this).parent().find('span.cnt').show();
		})*/
		.click(function(){ return false; })
		.keypress(function(e) {
			  e = e || event;
			  
			  if (e.ctrlKey || e.altKey || e.metaKey) return;  

			  var chr = null;
			  
				if (e.which == null) {
					if (e.keyCode < 32) return null;
					chr = String.fromCharCode(e.keyCode) // IE
				  }

				  if (e.which!=0 && e.charCode!=0) {
					if (e.which < 32) return null;
					chr = String.fromCharCode(e.which);   // остальные
				  }

			  // с null надо осторожно в неравенствах, 
			  // т.к. например null >= '0' => true
			  // на всякий случай лучше вынести проверку chr == null отдельно
			  if (chr == null) return;
			  
			  if (chr < '0' || chr > '9') {
				return false;
			  }
		})
		.change(function(){
			var label = $(this).parent().find('div.g-total');
			var val = $(this).val();
			var self = $(self).val();
			var good = $(this).parents('.one-line');
			$.post($(this).attr('ajax').replace('[price]',val), function(response){
				var response = eval('('+response+')');
				if (response.success) {
					label.text(val);
					good.find('.g-name-wrap .good-link').text('Подарочный сертификат на '+val+' руб.');
					self.calcTotal();
					if ($(self).attr('nohide') != '1') {
						good.find('input.g-total').hide();
						good.find('div.g-total').show();
					}
				} else { alert('Ошибка при сохранении суммы. '+response.error); }
			});
		});
	},
	
	initChangeCount: function(){
		var self = this;
		$(window).bind('click', function(){
			$(self.label_count).each(function(){
				$(this).parent().find('input.cntedit, .g-co').hide();
				$(this).show();
			});
		});
		$(this.label_count).click(function(){ 
			$(this).hide();
			$(this).parent().find('input.cntedit, .g-co').show().first().select().focus();
			return false;
		});
		$(this.count)
		/*.focus(function(){ }, function(){
			$(this).parent().find('input.cntedit, .g-co').hide();
			$(this).parent().find('span.cnt').show();
		})*/
		.click(function(){ return false; })
		.keypress(function(e) {
			  e = e || event;
			  
			  if (e.ctrlKey || e.altKey || e.metaKey) return;  

			  var chr = null;
			  
				if (e.which == null) {
					if (e.keyCode < 32) return null;
					chr = String.fromCharCode(e.keyCode) // IE
				  }

				  if (e.which!=0 && e.charCode!=0) {
					if (e.which < 32) return null;
					chr = String.fromCharCode(e.which);   // остальные
				  }

			  // с null надо осторожно в неравенствах, 
			  // т.к. например null >= '0' => true
			  // на всякий случай лучше вынести проверку chr == null отдельно
			  if (chr == null) return;
			  
			  if (chr < '0' || chr > '9') {
				return false;
			  }
		})
		.change(function(){ 
			$(this).parent().find('span.cnt').text($(this).val()+'шт.'); 
			var good = $(this).parents('.one-line');
			if (good.attr('plusminus') != '1')
				self.calc(good); 
		});

		// Плюс Минус один к товару
		$(this.btn_plusID+", "+this.btn_lessID).click(function(){
			$(this).parents('.one-line').attr('plusminus','1');
			self.calc($(this).parents('.one-line'), this);
			$(this).parents('.one-line').removeAttr('plusminus');
			return false;
		});
		
		// Плюс Минус один Подарочный сертификат
		$(this.btn_sertPlusID+", "+this.btn_sertLessID).unbind('click').click(function(){
			var d = $(this).hasClass('one-more');
			var v = $(this).parents('.g-count').find('input[name=sert_value]');
			var r = (isNaN(parseInt(v.val()))?0:parseInt(v.val()));
			var g = (d?parseInt(v.val())+500:parseInt(v.val())-500);
			if (g<0)g=0;
			v.val(g);
			$(this).parents('.add-good').find('.sum').text(g+' руб.');
			$(this).parents('.add-good').find('.cnt.text').text(g+' руб.');
			
			$(this).parents('td').find('.g-total font').text(g);
			$(this).parents('td').find('.sum-p-sert').text(g);
			
			$(this).parents('td').find('input.g-total').attr('nohide','1').change().removeAttr('nohide');
			
			return false;
		});
		$('.sert ' + this.count).unbind('change').change(function(){ 
			var v = $(this).parents('.g-count').find('input[name=sert_value]');
			var g = (isNaN(parseInt(v.val()))?0:parseInt(v.val()));
			if (g<0)g=0;
			v.val(g);
			$(this).parents('.add-good').find('.sum').text(g+' руб.');
			$(this).parents('.add-good').find('.cnt.text').text(g+' руб.');
			return false;
		});
		
	},
	
	//Посчитать общую сумму
	calcTotal: function(){
	
		$.get('/ajax/getBasketSum/',function(response){ 
			var total = eval('('+response+')')
			$('#total-price').text(total['final']);
			$('.first-price strike').text(total.total);
			$('.price-back .price:first').text(total.partical);
			$('.price-back #total-refound').text(total.back);
			if (typeof qBask != 'undefined') if (qBask.recalcTotal) qBask.recalcTotal(total);
		});
		
		/*var total_sum = 0;
		$(".g-total").each(function(i, val){
			var sm = parseFloat($(this).text());
			if (isNaN(sm)) sm = 0;
			total_sum += sm;
		});
		
		$(basket.totalPriceID).text(total_sum);
		// и пересчитать сскидку
		var mjb_persent = $("#bbpercent").val()-1;
		
		if (mjb_persent > 0 ) {
			var total_mjb = Math.round(total_sum/100*mjb_persent);
			$("#total-refound").text(total_mjb);
		} else {
			alert('пусто');
		}*/
	},
	
	calc: function(good_line, plusminus) { 
			var goot_cost_input	= $(good_line).find('.g-total[price]')	// Получаем ячейку со стоимтостью
			var goot_cost		= $(goot_cost_input).text();		// Берем текущую стоимсоть 
				goot_cost		= parseFloat(goot_cost);			// Выпарсиваем именно сумму
				if (isNaN(goot_cost)) goot_cost = 1;
								
			var good_count 	= good_line.find('input.cntedit');		// Получаем текущее количество				
			if (isNaN($(good_count).val())) $(good_count).val(1);
			if ($(good_count).val() == 0 || $(good_count).val() == '0') $(good_count).val(1);
			
			var good_price = goot_cost_input.attr('price');
			//var good_price = goot_cost/$(good_count).val();			//Теперь посчитаем цену, и потом стоимость этого			
			if (plusminus) {
				if ($(plusminus).hasClass("one-more")) {
					var good_val 	= $(good_count).val()*1+1;			// и делаем плюс один		
				} else {
					var good_val 	= $(good_count).val()*1-1;			// или минус один
				}
			} else var good_val 	= $(good_count).val();
			
			if (plusminus)				
				var basket_change_link = $(plusminus).attr("href");
			else {
				var basket_change_link = good_count.attr('ajax');				
				basket_change_link = basket_change_link.replace('[count]',good_count.val());
			}
			if (good_val<1) { good_val = 1;}
			else {
				$.get(basket_change_link, function(response) {
					if (response.substr(0, 2) == 'ok' || response == '') {
						if (typeof plusminus != 'undefined') {
							$(good_count).val(good_val);//.change();
						}
						$(good_count).parent().find('span.cnt').text(good_val+'шт.'); 
						$(goot_cost_input).text(Math.round(good_price*good_val,0));
					} else { //if (response.substr(0, 5) == 'error') {
						var pos         = response.indexOf(":");
						var state       = response.substring(0, pos);
						var message     = response.substring(pos+1);
						alert(message);
					}
					
					//Посчитать общую сумму
					basket.calcTotal();
					
				});
			}			
	
	}
	
};
