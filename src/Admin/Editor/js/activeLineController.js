/**
 * @param cm
 * @param sel
 *
 * Sets a cookie with current active line
 */
function newActiveLine(cm, sel) {
  document.cookie = "lastline=" + sel.ranges[0]['anchor']['line'];
}

/**
 *Sets the Editor window to last active line
 */
function setActiveLine() {
  cmActiveLineWatcher.focus();
  cmActiveLineWatcher.doc.setCursor(parseInt(getCookie('lastline')))
  cmActiveLineWatcher.skipToEnd();
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

var cmActiveLineWatcher = document.querySelector(".CodeMirror").CodeMirror;
var ref = document.referrer;
if (ref === (window.location.href)
  || ref === (window.location.href + '&message=1')
  || ref === 'http://localhost:8000/wp-admin/post-new.php?post_type=elebee-global-css&wp-post-new-reload=true'
  || ref === 'http://localhost:8000/wp-admin/post-new.php?post_type=elebee-global-css') {
  setActiveLine()
}
cmActiveLineWatcher.on("beforeSelectionChange", newActiveLine);
