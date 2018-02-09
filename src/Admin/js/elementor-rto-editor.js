(function($) {
	$(window).on('elementor:init', function () {
		var text = '',
			style = '';

		if ( -1 !== navigator.userAgent.search( 'Firefox' ) ) {
			var asciiText = [
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

			text += '%c' + asciiText.join( '\n' ) + '\n';

			style = 'color: #C42961';
		} else {
			text += '%c0000';

			style = 'line-height: 1.6; font-size: 50px; background-image: url("' + elementor_rto_url + '/vendor/rto-websites/elebee-core/src/Admin/assets/elementor-rto.png"); color: transparent; background-repeat: no-repeat;';
		}

		text += '%c\nElementor RTO! Powered by: %chttps://www.rto.de';

		setTimeout( console.log.bind( console, text, style, 'color: #9B0A46', '' ) );

		elementor.hooks.addAction( 'panel/open_editor/widget', function( panel, model, view ) {
			$('#elementor-panel-footer-responsive').ready(function() {

				var responsiveToggle = '<div id="elementor-panel-footer-responsive-toggle" class="elementor-panel-footer-tool" title="Responsive Hide/Show">' +
					'<i class="eicon-device-mobile"></i>' +
					'</div>';

				toggelDiv = $('#elementor-panel-footer-responsive-toggle');

				if(!toggelDiv.length){
					$('#elementor-panel-footer-responsive').after(responsiveToggle);
					$('#elementor-panel-footer-responsive-toggle').on('click', function () {
						$('#elementor-preview-iframe').contents().find('body').toggleClass('responsive-toggle');
						$('#elementor-panel-footer-responsive-toggle').toggleClass('responsive-toggle');
					})
				}

			});
		} );
	});
})(jQuery);