<div id="vimeo-embed-<?php echo $cfsName; ?>">Loading video...</div>
<script>
  // This function puts the video on the page
  function embedVideo(video) {
    document.getElementById('vimeo-embed-<?php echo $cfsName; ?>').innerHTML = unescape(video.html);
  }

  document.addEventListener("DOMContentLoaded", function () {
    // This is the URL of the video you want to load
    var videoUrl = '<?php echo $videoUrl; ?>';
    // This is the oEmbed endpoint for Vimeo (we're using JSON)
    // (Vimeo also supports oEmbed discovery. See the PHP example.)
    var endpoint = 'https://www.vimeo.com/api/oembed.json';
    // Tell Vimeo what function to call
    var callback = 'embedVideo';
    // double padding
    var padding = themeVars.isMobile ? 20 : 60;
    var width = document.getElementById('vimeo-embed-<?php echo $cfsName; ?>').offsetWidth - padding;
    // Put together the URL
    var url = endpoint + '?url=' + encodeURIComponent(videoUrl) + '&callback=' + callback + '&width=' + width;

    // This function loads the data from Vimeo
    function init() {
      var js = document.createElement('script');
      js.setAttribute('type', 'text/javascript');
      js.setAttribute('src', url);
      document.getElementsByTagName('head').item(0).appendChild(js);
    }

    // Call our init function when the page loads
    window.onload = init;

  });
</script>