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
      width        = 420,
      height       = 0;

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
        console.log("An error occured! " + err);
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
    if (!png) {
      return;
    }
    var context = canvas.getContext('2d');
    var myimage = new Image();
    var srcpng = 'img/png/' + png + '.png';
    if (width && height) {
      canvas.width = width;
      canvas.height = height;
      myimage.src = 'picture.php?mode=generate&srcimg=' + srcpng + '&w=100&h=100';
      context.drawImage(video, 0, 0, width, height);
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
    if (!data) {
      return;
    }
    var xhr=null;

    if (window.XMLHttpRequest) { 
        xhr = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xhr.onreadystatechange = function() {
      if (xhr.readyState === 4) {
        alert_ajax(xhr);
      } 
    };
 
    xhr.open("POST", encodeURI("http://localhost:8080/camagru/save.php"));
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(encodeURI("data=" + data));
  }

  function alert_ajax(xhr) {
    var docXML= xhr.responseXML;
    var items = docXML.getElementsByTagName("data");
    for (i=0;i<items.length;i++) {
      alert (items.item(i).firstChild.data);
    }
  }

  window.addEventListener('load', startup, true);
})();