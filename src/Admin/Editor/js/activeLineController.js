/**
 * @param cm
 * @param sel
 *
 * Sets a cookie with current active line
 */
function newActiveLine(cm, sel) {
  console.log(sel.ranges[0]['anchor'])
  document.cookie = "lastline=" + sel.ranges[0]['anchor']['line']+'+'+sel.ranges[0]['anchor']['ch'];
}

/**
 *Sets the Editor window to last active line
 */
function setActiveLine() {
  cmActiveLineWatcher.focus();
  var position = getPostition(getCookie('lastline'));
  cmActiveLineWatcher.doc.setCursor(parseInt(position[0]),parseInt(position[1]));
  console.log(cmActiveLineWatcher.getScrollInfo());
  cmActiveLineWatcher.scrollIntoView(null, cmActiveLineWatcher.getScrollInfo()['clientHeight']/2);

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
function getPostition(query){
    var positionArray =query.split('+');
    return positionArray
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
