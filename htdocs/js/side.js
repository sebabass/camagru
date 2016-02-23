var mypicture = null;

function getLastPicture () {
	mypicture = document.getElementById('side-mypicture');
	var xhr=null;

    if (window.XMLHttpRequest) { 
        xhr = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        lastPictureComplete(xhr);
      } 
    };
 
    xhr.open("GET", "http://localhost:8080/camagru/side_picture.php");
    xhr.send(null);
}

function lastPictureComplete(xhr) {
	var response = JSON.parse(xhr.responseText);
	var i;
	var out = '';

	for (i = 0; i < response.length; i++) {
		out += '<li>' +
		'<img src="'+ response[i].src +'" alt="'+ response[i].alt +'" width=70 height=70 >' +
		'<span class="likes">'+ response[i].likes +'<span>' +
		'</li>';
	}
	if (out) {
		document.getElementById('side-mypicture').innerHTML = out;
	}
}

window.addEventListener('load', getLastPicture, true);