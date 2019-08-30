/**
 * @param cm
 * @param sel
 *
 * Sets a cookie with current active line
 */
function newActiveLine(cm, sel) {
  document.cookie = "lastline=" + sel.ranges[0]['anchor']['line'] + '+' + sel.ranges[0]['anchor']['ch'];
  //window.editor.save; //.toTextArea ....
}

/**
 *Sets the Editor window to last active line
 */
function setActiveLine(cm) {
  cm.focus();
  var position = getPostition(getCookie('lastline'));
  cm.doc.setCursor(parseInt(position[0]), parseInt(position[1]));
  cm.scrollIntoView(null, cm.getScrollInfo()['clientHeight'] / 2);
}

/**
 * copy from: https://www.w3schools.com/js/js_cookies.asp
 * @param cname
 * @returns {string}
 *
 * Finds the value of a cookie
 */
function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

/**
 * splits cookie int line and char position
 * @param query
 * @returns {never|string[]}
 */
function getPostition(query) {
  var positionArray = query.split('+');
  return positionArray
}

//when codemirror is up
window.addEventListener('CodeMirrorRunning', function () {
  //get codemirror instance
  var cmActiveLineWatcher = document.querySelector(".CodeMirror").CodeMirror;
  //check if reload
  if (performance.navigation.type == 1) {
    setActiveLine(cmActiveLineWatcher);
  }
  //var ref = document.referrer;
  // if (ref === (window.location.href)
  //   || ref === (window.location.href + '&message=1')
  //   || ref === 'http://localhost:8000/wp-admin/post-new.php?post_type=elebee-global-css&wp-post-new-reload=true'
  //   || ref === 'http://localhost:8000/wp-admin/post-new.php?post_type=elebee-global-css') {
  //   console.log('!!!!')
  //   setActiveLine(cmActiveLineWatcher);
  // }

  //keep track of active line
  cmActiveLineWatcher.on("beforeSelectionChange", newActiveLine);


  //check if saving...
  wp.data.subscribe(function () {
    var isSavingPost = wp.data.select('core/editor').isSavingPost();
    var isAutosavingPost = wp.data.select('core/editor').isAutosavingPost();
    if (isSavingPost && !isAutosavingPost) {
      setActiveLine(cmActiveLineWatcher);
    }
  })
});

