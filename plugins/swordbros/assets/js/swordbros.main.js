const SordbrosBooking = {
    loaded: false
}
$(document).on('ajaxSuccess', function(event, context, data) {
    console.log(context.handler);
    if (context.handler === 'onUserDropDownChange') {
        $.each(data.fields, function(i, val) {
            if($(i).length){
                if($(i).val()){
                    return;
                }
                if($(i).is('input') ){
                    console.log(i, $(i).attr('type'));
                    if( $(i).attr('type')=='checkbox'){
                        $(i).attr('checked', true).change();
                    } else if( $(i).attr('type')=='radio' ){
                        $(i).attr('checked', true).change();
                    } else {
                        $(i).val(val);
                    }
                } else {
                    $(i).html(val);
                }
            }
        });
    }
});

$(document).on('ajaxError', function(event, context, data) {
    if (context.handler === 'onUserDropDownChange') {
        console.error('Bir hata oluştu:', data);
    }
});

$(document).ready(function () {
    if(SordbrosBooking.loaded == true){
        console.error('Already Loaded');
    }
    $('#Form-field-BookingModel-user_id').on('change', function(){
        $(this).data('request-data', $(this).val())
        console.log($(this).data('request-data'));
    })
    SordbrosBooking.loaded = true;
    console.log('SordbrosBooking Loaded');
});
