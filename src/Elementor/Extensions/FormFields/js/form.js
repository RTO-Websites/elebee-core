

( function($) {

    $( window ).on( 'elementor:init', function() {
        /**
         * Render custom form field in editor
         */
        elementor.hooks.addFilter( 'elementor_pro/forms/content_template/field/rto-datepicker', function( html, item, i, settings ) {
            var placeholder = 'placeholder="'+item.placeholder+'"';
            var placeholder2 = 'placeholder="'+item.placeholder2+'"';
            var dataTime ='data-time="'+item.from_till_time+'"';

            var itemClasses = _.escape( item.css_classes );

            var required = '';
            if ( item.required ) {
                required = 'required';
            }

            var inputField = '<input size="1" type="text" class="elementor-field rto-datepicker elementor-field-textual elementor-size-' + settings.input_size + ' ' + itemClasses + '" name="form_field_' + i + '" id="form_field_' + i + '" ' + required + ' ' + placeholder + ' >';
            var inputField = '<div class="from-till-wrapper elementor-form-fields-wrapper">' +
                '<div class="elementor-field-group rto-datepicker-from-wrapper">' +
                '<input size="1" type="text" class="elementor-field elementor-size-' + settings.input_size + '  elementor-field-textual rto-datepicker elementor-column rto-datepicker-from hasDatepicker ' + itemClasses + '" name="form_field_' + i + '" id="form_field_' + i + '" ' + required + ' ' + placeholder + ' ' + dataTime + '">' +
                '</div>' +
                '<div class="elementor-field-group rto-datepicker-till-wrapper">' +
                '<input size="1" type="text" class="elementor-field elementor-size-' + settings.input_size + '  elementor-field-textual rto-datepicker elementor-column rto-datepicker-from hasDatepicker ' + itemClasses + '" name="form_field_' + i + '-till" id="form_field_' + i + '-till" ' + required + ' ' + placeholder2 + ' ' + dataTime + '">' +
                '</div>' +
                '</div>'
            
            return inputField;

        } );
    });

} )(jQuery);