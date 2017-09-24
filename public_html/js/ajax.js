//függvény, amely a felhasználó által feltöltött kiépet elküldi a PHP-nak ellenőrésre
//PHP ellenőrzi, hogy valóban csak PNG, JPG vagy GIF fájl kerül feltöltésre
function uploadImage(){

    var file = $("#image-upload")[0].files[0];
    var formData = new FormData();
    formData.append("image", file);

    $.ajax({
        url: "php/image-upload.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function(response){
            console.log(response);
            if(response.result == "success"){

                var message = new Message({
                    text: "",
                    time: getMessageTime(),
                    sticker: null,
                    image: response.imageName
                });
                
                createNewMessage(message, "own");
                socket.emit('newMessage', message);
            }
            else{
                alert(response.result);
            }
        },
        error: function(){
            alert("Hoppá! Valamiért nem sikerült feltölteni a képed :(");
        }
    });
}

//a webkamera által készített base64 kódolású képet átküldi a PHP-nak, hogy az png fájlt csináljon belőle, és az upload mappába rakja
function phpBase64Encode(base64image){
    $.ajax({
        type: "POST",
        url: "php/base64-encode.php",
        data: { image: base64image },
        success: function(response){

            var message = new Message({
                text: "",
                time: getMessageTime(),
                sticker: null,
                image: response.imageName 
            });

            createNewMessage(message, "own");
            socket.emit('newMessage', message);
        },
        error: function(){
            alert("Hoppá! Valamiért nem sikerült feltölteni a képed :(");
        }
    });
}

function initSessionData(){
    $.ajax({
        type: "POST",
        url: "php/init-session-data.php",
        success: function(response){
            $("#user-profile-pic").attr("src", "img/avatars/"+ response.profpic);
            $("#user-name").html(response.fullname);
            socket.emit("userConnected", response.fullname);

        },
        error: function(){
            alert("Felhasználói adatok lekérése a szerverről sikertelen! :(");
        }
    });
}

function phpStoreMessgage(message) {
    $.ajax({
        type: "POST",
        url: "../php/store-messages.php",
        data: message,
        succes: function (response) {
            
        },
        error: function (error) {
            alert("Nem sikerült az üzenetek elküldése! :(");
        }
    });
}