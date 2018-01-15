
jQuery(document).ready(function($) {

    /**
     * add datetimepicker and set logic
     */
    function rto_datepicker(){
        if($.fn.datepicker) {

            $('.from-till-wrapper').each(function(){
                var wrapper = $(this);
                var from = wrapper.find(".rto-datepicker-from-wrapper input");
                var till = wrapper.find(".rto-datepicker-till-wrapper input");
                from.prop("readonly", true);
                till.prop("readonly", true);
                from.datetimepicker({
                    minDate: (from.attr("data-time") == 'future') ? '+0d' : null,
                    maxDate: (from.attr("data-time") == 'past') ? '+0d' : null,
                    onSelect: function(dateStr)
                    {
                        till.datepicker("option",{ minDate: new Date(dateStr)});
                        from.data('originalValue',$(this).val());
                    }
                });
                till.datetimepicker({
                    minDate: (till.attr("data-time") == 'future') ? '+0d' : null,
                    maxDate: (till.attr("data-time") == 'past') ? '+0d' : null,
                    onSelect: function(dateStr)
                    {
                        from.datepicker("option",{ maxDate: new Date(dateStr)});
                        till.data('originalValue',$(this).val());
                    }
                });
            })
        }
    }
    rto_datepicker();

    /**
     * Submit workaround till we able to add fields programmatically
     */
    $('.elementor-form').submit( function(){
        $(this).find('.from-till-wrapper').each(function() {
            var from = $(this).find('.rto-datepicker-from').data('originalValue');
            var till = $(this).find('.rto-datepicker-till').data('originalValue');

            oldFromValue = from;

            $(this).find('.rto-datepicker-from-wrapper .rto-datepicker-from').val(from + ' - ' + till);
        })
    });

    $('.elementor-field-type-rto-datepicker').show();
});