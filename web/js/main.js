function getRequest(url) {
  if(window.XMLHttpRequest) {
    http=new XMLHttpRequest();
  } else if(window.ActiveXObject) {
      http=new ActiveXObject("Microsoft.XMLHTTP");
  }
  http.open("GET", url , true);
  http.onreadystatechange = function() {
    if (http.readyState == 4 && http.status==200) {
      if(http.responseText !== null) {
        return http.responseText;
      }
    } else {
      return false;
    }
  }
  http.send();
}
