(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note that this assume you're going to use jQuery, so it prepares
	 * the $ function reference to be used within the scope of this
	 * function.
	 *
	 * From here, you're able to define handlers for when the DOM is
	 * ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * Or when the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and so on.
	 *
	 * Remember that ideally, we should not attach any more than a single DOM-ready or window-load handler
	 * for any particular page. Though other scripts in WordPress core, other plugins, and other themes may
	 * be doing this, we should try to minimize doing that in our own work.
	 */

	var WidgetImprintHandler = function( $scope, $ ) {
		console.log( $scope );
	};

	var cntrlIsPressed = false;

	$(document).keydown(function(event){
		if(event.which=="17")
			cntrlIsPressed = true;
	});

	$(document).keyup(function(){
		cntrlIsPressed = false;
	});

	$( window ).load(function() {
		$('a').each(function () {
			$(this).on('click', function (e) {
				console.log(window.ajax_object);
				if (cntrlIsPressed && $('html').hasClass('elementor-html')) {
					e.preventDefault();
					var href = $(this).attr('href');

					$.ajax({
						type:"POST",
						url: ajax_object.ajax_url,
						data: {
							'action': 'get_post_id_by_url',
							'url': href
						},
						success:function(data){
							window.top.location.href = data.path + '/wp-admin/post.php?post='+ data.postId +'&action=elementor';
						},
						error: function(errorThrown){
							alert(errorThrown);
						}

					});
				}
			})
		})
	});

})( jQuery );
