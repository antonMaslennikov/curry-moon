jQuery(document).ready(function(){
    
    // подписка на рассылки
    jQuery('#formAcymailing84821').submit(function() {

        if (jQuery(this).find('input[name=user\\\[email\\\]]').val().length == 0) {
            alert('Укажите адрес электронной почты');
            return false;
        }

        jQuery.post(jQuery(this).attr('action'), jQuery(this).serialize(), function(r) {
            r = jQuery.parseJSON(r);
            if (r.status == 'ok') {
                alert('Ваша подписка оформлена. Спасибо');
                jQuery('#formAcymailing84821').find('input[name=user\\\[email\\\]]').val('');
            } else {
                alert(r.message);
            }
        });
        return false;
    }); 
    
});