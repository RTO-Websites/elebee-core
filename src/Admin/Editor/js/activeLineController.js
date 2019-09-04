/**
 * @param cm
 * @param sel
 *
 * Sets a cookie with current active line
 */
function newActiveLine(cm, sel) {
  document.cookie = "lastline=" + sel.ranges[0]['anchor']['line'] + '+' + sel.ranges[0]['anchor']['ch'];
  cm.save();
}

/**
 *Sets the Editor window to last active line and position
 */
function setActiveLine(cm) {
  let cookie = getCookie('lastline');
  if (cookie !== "") {
    cm.focus();
    let position = getPostition(cookie);
    cm.doc.setCursor(parseInt(position[0]), parseInt(position[1]));
    cm.scrollIntoView(null, (window.outerHeight / 2));
  }
}

/**
 * @param cname
 * @returns {string}
 *
 * Finds the value of a cookie
 */
function getCookie(cname) {
  let name = cname + "=";
  decodedCookie = decodeURIComponent(document.cookie);
  ca = decodedCookie.split(';');
  for (let i = 0; i < ca.length; i++) {
    let c = ca[i];
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
  let positionArray = query.split('+');
  return positionArray
}

//when codemirror is up
window.addEventListener('CodeMirrorRunning', function () {
  //get codemirror instance
  let cmActiveLineWatcher = document.querySelector(".CodeMirror").CodeMirror;

  //keep track of active line
  cmActiveLineWatcher.on("beforeSelectionChange", newActiveLine);

  if (ElebeeCodeMirrorGutenberg.gutenberg) {
    //check if reload
    if (performance.navigation.type == 1) {
      setActiveLine(cmActiveLineWatcher);
    }
    //check if saving...
    window.addEventListener('WPsaving', function () {
        setActiveLine(cmActiveLineWatcher);
      }
    )
  } else {
    let ref = document.referrer;
    if (ref === (window.location.href)
      || ref === (window.location.href + '&message=1')
      || ref === 'http://localhost:8000/wp-admin/post-new.php?post_type=elebee-global-css&wp-post-new-reload=true'
      || ref === 'http://localhost:8000/wp-admin/post-new.php?post_type=elebee-global-css') {
      setActiveLine(cmActiveLineWatcher);
    }
  }


});

