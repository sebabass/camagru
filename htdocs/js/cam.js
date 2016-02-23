(function() {

  var streaming    = false,
      video        = null,
      canvas       = null,
      photo        = null,
      startbutton  = null,
      savebutton   = null,
      radiobutton  = null,
      data         = null,
      png          = null,
      error        = null,
      success      = null,
      width        = 420,
      height       = 0,
      issave       = 0;

/*************************************************************
**************************************************************
                          LOAD
**************************************************************
*************************************************************/

  function startup() {
    video = document.getElementById('video');
    canvas = document.getElementById('canvas');
    photo = document.getElementById('photo');
    startbutton = document.getElementById('startbutton');
    savebutton = document.getElementById('savebutton');
    startbutton.enabled = savebutton.enabled = true;
    radiobutton = document.getElementsByName('imgpng');
    error = document.getElementById('errorpicture');
    success = document.getElementById('successpicture');

    navigator.getMedia = ( navigator.getUserMedia ||
                           navigator.webkitGetUserMedia ||
                           navigator.mozGetUserMedia ||
                           navigator.msGetUserMedia);

    navigator.getMedia({
        video: true,
        audio: false
      },
      function(stream) {
        if (navigator.mozGetUserMedia) {
          video.mozSrcObject = stream;
        } else {
          var vendorURL = window.URL || window.webkitURL;
          video.src = vendorURL.createObjectURL(stream);
        }
        video.play();
      },
      function(err) {
        error.textContent = err;
      }
    );

    video.addEventListener('canplay', function(ev){
      if (!streaming) {
        height = video.videoHeight / (video.videoWidth/width);
      
        if (isNaN(height)) {
          height = width / (4/3);
        }
      
        video.setAttribute('width', width);
        video.setAttribute('height', height);
        canvas.setAttribute('width', width);
        canvas.setAttribute('height', height);
        streaming = true;
      }
    }, false);

    for (var i = 0; i < radiobutton.length; i++) {
      radiobutton[i].addEventListener('change', function(ev) {
        changeImage(ev);
        ev.preventDefault();
      });
    }

    clearphoto();
  }

  /*************************************************************
  **************************************************************
                              PICTURE
  **************************************************************
  *************************************************************/

  function changeImage(event) {
    if (!event || !event.target || !event.target.value) {
      return;
    }
    png = event.target.value;
    if (startbutton.enabled) {
      startbutton.addEventListener('click', function(ev){
        takepicture();
        ev.preventDefault();
      }, false);
      startbutton.enabled = false;
    }
  }

  function clearphoto() {
    var context = canvas.getContext('2d');
    context.fillStyle = "#AAA";
    context.fillRect(0, 0, canvas.width, canvas.height);
  }

  function takepicture() {
    clearText();
    if (!png) {
      return;
    }
    var context = canvas.getContext('2d');
    var myimage = new Image();
    var srcpng = 'img/png/' + png + '.png';

    issave = 1;
    if (width && height) {
      canvas.width = width;
      canvas.height = height;
      //myimage.src = 'picture.php?mode=generate&image=' + video.src + '&srcimg=' + srcpng + '&w=100&h=100';
      context.drawImage(video, 0, 0, width, height);
      data = canvas.toDataURL("image/png");
    } else {
      clearphoto();
    }

    if (savebutton.enabled) {
      savebutton.addEventListener('click', function(ev){
        ev.preventDefault();
        ajaxSaveImage();
      }, false);
      savebutton.enabled = false;
    }
  }

  /*************************************************************
  **************************************************************
                            AJAX
  **************************************************************
  *************************************************************/

  function ajaxSaveImage() {
    clearText();
    if (!issave) {
      error.textContent = 'Image déjà sauvegarder';
      return;
    }
    if (!data) {
      error.textContent = 'Impossible de sauvegarder la photo';
      return;
    }
    var xhr=null;

    if (window.XMLHttpRequest) { 
        xhr = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        ajaxSaveComplete(xhr);
      } 
    };
 
    xhr.open("POST", "http://localhost:8080/camagru/save.php");
    xhr.setRequestHeader('Content-Type', 'application/upload');
    xhr.send(data);
  }

  function ajaxSaveComplete(xhr) {
    var docXML= xhr.responseXML;
    var errorxml = docXML.getElementsByTagName('error');
    var successxml = docXML.getElementsByTagName('success');
    if (errorxml) {
      for (i=0;i<errorxml.length;i++) {
        clearText();
        error.textContent = errorxml.item(i).firstChild.data;
      }
    }
    if (successxml) {
      for (i=0;i<successxml.length;i++) {
        clearText();
        success.textContent = successxml.item(i).firstChild.data;
      }
      issave = 0;
      getLastPicture();
    }
  }

  /*************************************************************
  **************************************************************
                            OTHER
  **************************************************************
  *************************************************************/

  function clearText() {
    error.textContent = '';
    success.textContent = '';
  }

  window.addEventListener('load', startup, true);
})();