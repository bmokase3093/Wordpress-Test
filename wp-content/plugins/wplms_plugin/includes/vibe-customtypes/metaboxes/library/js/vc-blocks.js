jQuery( 'body' ).delegate( '.radio_images', 'click', function(){
    var value = jQuery(this).attr('data-value');
    jQuery(this).parent().find('.clicked').removeClass('clicked');
    jQuery(this).parent().find('.image_value').val(value); 
    jQuery(this).addClass('clicked');
    jQuery(this).append('<span></span>');
});