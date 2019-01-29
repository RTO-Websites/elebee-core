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

  function initResponsiveToggle() {

    let toggleID = 'elementor-panel-footer-responsive-toggle',
      $panel = $('#tmpl-elementor-panel-footer-content'),
      $responsiveButton = $(`<div id="${toggleID}" class="elementor-panel-footer-tool tooltip-target" data-tooltip="Responsive Hide/Show">` +
        '<i class="eicon-device-mobile" aria-hidden="true" data-tooltip="Responsive Hide/Show"/>' +
        '<span class="elementor-screen-only">Responsive Hide/Show</span>' +
        '</div>');

    if (!$panel.length)
      return;

    renderTmpTemplate($panel, $responsiveButton);

    $document.on('click', `#${toggleID}`, toggleResponsiveListener);

  }

  /**
   *
   */
  function renderTmpTemplate($panel, $responsiveButton) {

    let $tmpTemplate = $('<div hidden></div>');

    $tmpTemplate.html($panel.html());
    $('body').append($tmpTemplate);
    $responsiveButton.insertAfter($('#elementor-panel-footer-responsive'));
    $panel.html($tmpTemplate.html());
    $tmpTemplate.remove();

  }

  /**
   *
   */
  function toggleResponsiveListener() {

    let toggleClass = 'responsive-toggle';
    $(this).toggleClass(toggleClass);
    $('#elementor-preview-iframe').contents().find('body').toggleClass(toggleClass);


  }

  /**
   *
   */
  function elementorInit() {

    printBrand();
    initResponsiveToggle();

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
      style = 'line-height: 1.6; font-size: 50px; background-image: url("' + themeVars.themeUrl + '/vendor/rto-websites/elebee-core/src/Elementor/assets/img/elementor-rto.png"); color: transparent; background-repeat: no-repeat;';

    }

    text += '%c\nElementor RTO! Powered by: %chttps://www.rto.de';

    setTimeout(console.log.bind(console, text, style, 'color: #9B0A46', ''));

  }

  init();

})(jQuery);