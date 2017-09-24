window.onload = function(){

    var video = $("#user-webcam-video")[0];
    var canvas = $("#captured-image")[0];
    var videoStream = null;
    var captureWidth = 450;
    var captureHeight = 0;
    var isFirstStream = true;
    var ctx = canvas.getContext("2d");
    var mediaStream = window.MediaStream;

    //kamera ikonra kattintva videostream elindítása
    $(".ion-android-camera").click(function(){
        $(".page-overlay:nth-child(2)").fadeIn(300);
        startVideoStream();
    });

    //elkezdi a video streamet (engedélykéréssel együtt)
    function startVideoStream(){

        navigator.mediaDevices.getUserMedia({
            audio: false,
            video: true
        }).then(function(stream){

            videoStream = stream;
            video.srcObject = videoStream;
            video.play();

            video.oncanplay = function(){
                captureHeight = video.videoHeight / (video.videoWidth / captureWidth);

                video.style.width = captureWidth;
                video.style.height = captureHeight;
                canvas.width = captureWidth;
                canvas.height = captureHeight;
            }

        }).catch(function(err){
            alert("Valami gond történt a kamerád inicializálása közben! :(" + err.message);
        });

        $(".ion-camera").click(function(){
            captureImage();
        });
    
        $("#send-captured-image").on("click",function(){
            console.log("elköldve php-nak");
            var base64 = canvas.toDataURL("image/png"); //base64 kódolású kép a canvas tartalmából
            phpBase64Encode(base64); //base64 kép küldése php-nak
        });

        $(document).keydown(function(e){
            if(e.keyCode == 27){ 
                stopVideoStream(); 
                $(".page-overlay").fadeOut(300);                
            }
        });
    }

    //képet készít a video aktuális frame-jéből és a canvas-ra rajzolja
    function captureImage(){
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    }

    //megállítája a video streamet (hardvert is leválasztja)
    function stopVideoStream(){

        ctx.clearRect(0, 0, canvas.width, canvas.height);
        video.pause();
        video.srcObject = null;
        videoStream.stop();
    }
}