(function( $ ) {
	'use strict';

	let WidgetImprintHandler = function( $scope, $ ) {
		console.log( $scope );
	};

	let $window,
	  $document;

  /**
   *
   */
	function init() {

		$window = $(window);
		$document = $(document);

		$window.on('load', initLinkClicks);

  }

  /**
   *
   */
  function initLinkClicks() {

    $('a').each(function () {
      $(this).on('click', handleLinkClick)
    })

	}

  /**
   *
   * @param event
   */
	function handleLinkClick(event) {

		if(!event.ctrlKey || !$('html').hasClass('elementor-html')) {
			return;
		}

    event.preventDefault();

		$.ajax({
			type:"POST",
			url: ajax_object.ajax_url,
			data: {
				'action': 'get_post_id_by_url',
				'url': $(this).attr('href')
			},
			success: responseSuccess,
			error: responseError
		});

  }

  /**
	 *
   * @param data
   */
  function responseSuccess(data) {
    window.top.location.href = data.path + '/wp-admin/post.php?post='+ data.postId +'&action=elementor';
  }

  /**
	 *
   * @param error
   */
  function responseError(error) {
    alert(error);
  }

	init();

})( jQuery );
