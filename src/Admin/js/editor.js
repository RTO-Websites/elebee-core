(function ($) {
  'use strict';

  let $window,
    $document;

  /**
   *
   */
  function init() {

    $window = $(window);
    $document = $(document);

    $window.on('elementor:init', elementorInit);

  }

  /**
   *
   */
  function elementorInit() {

    printBrand();

    elementor.hooks.addAction('panel/open_editor/widget', function (panel, model, view) {
      $('#elementor-panel-footer-responsive').ready(initResponsiveToggle);
    });

  }

  /**
   *
   */
  function printBrand() {

    let text = '',
      style = '';

    if (-1 !== navigator.userAgent.search('Firefox')) {

      let asciiText = [
        '__/\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\__________________/\\\\\\\\\\\\\\\\\\______/\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\_______/\\\\\\\\\\______        ',
        ' _\\/\\\\\\///////////_________________/\\\\\\///////\\\\\\___\\///////\\\\\\/////______/\\\\\\///\\\\\\____       ',
        '  _\\/\\\\\\___________________________\\/\\\\\\_____\\/\\\\\\_________\\/\\\\\\_________/\\\\\\/__\\///\\\\\\__      ',
        '   _\\/\\\\\\\\\\\\\\\\\\\\\\______/\\\\\\\\\\\\\\\\\\\\\\_\\/\\\\\\\\\\\\\\\\\\\\\\/__________\\/\\\\\\________/\\\\\\______\\//\\\\\\_     ',
        '    _\\/\\\\\\///////______\\///////////__\\/\\\\\\//////\\\\\\__________\\/\\\\\\_______\\/\\\\\\_______\\/\\\\\\_    ',
        '     _\\/\\\\\\___________________________\\/\\\\\\____\\//\\\\\\_________\\/\\\\\\_______\\//\\\\\\______/\\\\\\__   ',
        '      _\\/\\\\\\___________________________\\/\\\\\\_____\\//\\\\\\________\\/\\\\\\________\\///\\\\\\__/\\\\\\____  ',
        '       _\\/\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\_______________\\/\\\\\\______\\//\\\\\\_______\\/\\\\\\__________\\///\\\\\\\\\\/_____ ',
        '        _\\///////////////________________\\///________\\///________\\///_____________\\/////_______'
      ];

      text += '%c' + asciiText.join('\n') + '\n';
      style = 'color: #C42961';

    }
    else {

      text += '%c0000';
      style = 'line-height: 1.6; font-size: 50px; background-image: url("' + themeVars.themeUrl + '/vendor/rto-websites/elebee-core/src/Admin/assets/elementor-rto.png"); color: transparent; background-repeat: no-repeat;';

    }

    text += '%c\nElementor RTO! Powered by: %chttps://www.rto.de';

    setTimeout(console.log.bind(console, text, style, 'color: #9B0A46', ''));

  }

  /**
   *
   */
  function initResponsiveToggle() {

    let $responsiveToggle = $('#elementor-panel-footer-responsive-toggle');

    if (!$responsiveToggle.length) {

      $('<div id="elementor-panel-footer-responsive-toggle" class="elementor-panel-footer-tool" title="Responsive Hide/Show">' +
        '<i class="eicon-device-mobile"></i>' +
        '</div>')
        .insertAfter('#elementor-panel-footer-responsive')
        .on('click', toggleResponsive);
    }

  }

  /**
   *
   */
  function toggleResponsive() {

    let toggleClass = 'responsive-toggle';
    $(this).toggleClass(toggleClass);
    $('#elementor-preview-iframe').contents().find('body').toggleClass(toggleClass);

  }

  init();

})(jQuery);