jQuery(document).ready(function ($) {

  /**
   * set datetimepicker logic
   */
  function rto_datepicker() {
    if ($.fn.datepicker) {

      $('.elebee-datepicker-wrapper').each(function () {
        var wrapper = $(this);
        var from = wrapper.find(".elebee-datepicker-past");
        var till = wrapper.find(".elebee-datepicker-future");
        from.prop("readonly", true);
        till.prop("readonly", true);
        from.datetimepicker({
          minDate: (from.attr("data-time") == 'future') ? '+0d' : null,
          maxDate: (from.attr("data-time") == 'past') ? '+0d' : null,
          onSelect: function (dateStr) {
            till.datepicker("option", {minDate: new Date(dateStr)});
            from.data('originalValue', $(this).val());
            wrapper.find('.elebee-datepicker-value').val($(this).val() + ' - ' + till.val());
          }
        });
        till.datetimepicker({
          minDate: (till.attr("data-time") == 'future') ? '+0d' : null,
          maxDate: (till.attr("data-time") == 'past') ? '+0d' : null,
          onSelect: function (dateStr) {
            from.datepicker("option", {maxDate: new Date(dateStr)});
            till.data('originalValue', $(this).val());
            wrapper.find('.elebee-datepicker-value').val( from.val() + ' - ' + $(this).val());
          }
        });
      })
    }
  }

  rto_datepicker();

});