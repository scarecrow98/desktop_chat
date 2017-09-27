//új üzenetbuborék létrehozása a  DOM-ban
function createNewMessage(message, classType){
    var date = new Date();

    if($("#section-messages").children().length > 0){ //ha már van üzenetbuborék (tehát nem most kezdődik a beszélgetés)
        var lastMessageTime = $(".message-bubble").last().attr("data-time-millisec"); //utolsó üzenet ideje

        if((date.getTime() - lastMessageTime) < 120000){ //ha az utolsó üzenet kevesebb, mint 2 perce volt, akkor...
            if($(".message-wrapper").last().hasClass("message-"+classType)){ //ha az utolsó message-wrapper és az új classType-ja is egyforma, akkor az utolsóhoz simán hozzáadjuk az üzenetbuborékot
                $(".message-wrapper").last().append(
                    '<div class="message-bubble">'+ message.text +'</div>'+
                    '<div class="clear"></div>'
                );
            }
            else{
                $("#section-messages").append( //...egyébként, ha az utolsó és az új message-wrapper típusa különböző, akkor csinalunk egy új message-wrappert
                    '<div class="message-wrapper message-'+ classType +'">'+
                        '<div class="message-bubble">'+ message.text +'</div>'+
                        '<div class="clear"></div>'+
                    '</div>'
                ); 
            }
        }
        else{ //egyébként, ha több, mint 2 perces az utolsó üzenet, csinalunk egy új message-wrappert
            $("#section-messages").append(
                '<div class="message-wrapper message-'+ classType +'">'+
                    '<div class="message-bubble">'+ message.text +'</div>'+
                    '<div class="clear"></div>'+
                '</div>'
            );
        }
        
    }
    else{//...egyébként csinalunk egy új message-wrappert (első üzenet a beszélgetésben)
        $("#section-messages").append(
            '<div class="message-wrapper message-'+ classType +'">'+
                '<div class="message-bubble">'+ message.text +'</div>'+
                '<div class="clear"></div>'+
            '</div>'
        );
    }

    //ha a message.sticker nem null, tehát van tartalma
    if(message.sticker != null){
        $(".message-bubble").last().append(
            '<img src="'+ message.sticker +'" class="sticker">'
        );
    }
    
    //ha a message.image nem null, tehát van tartalma
    if(message.image != null){
        $(".message-bubble").last().append(
            '<img src="img/uploaded_images/'+ message.image +'" class="image">'
        );
    }
    
    //üzenetek attribútumainak beállítása
    setMessageAtrributes(message.time, date.getTime());
    
    //üzenetek autoscrollozása a div aljára animációval
    $("#section-messages").animate({
        scrollTop: $(this).height()
    }, 700);
}

//paraméterként megadott emojit beszúrja a szövegmezőbe
function appendEmoji(emojiId){
    var value = $("#textfield").val();
    value += emojiId;
    $("#textfield").val(value);
}

//elküldi az üzenetet
function sendMessage(){
    var messageText = replaceEmojis($("#textfield").val());
    
    var message = new Message({
        text: messageText,
        time: getMessageTime(),
        sticker: null,
        image: null
    });

    if(message.text == ""){
        return false;
    }
    else{
        createNewMessage(message, "own");
        phpStoreMessage(message);
        $("#textfield").val("");
    }

    //üzenet kiküldése a webszervernek
    socket.emit('newMessage', message);
}

//emojikban található speciális karakterek kimentése regExp-hez
function regExpEscape(text) {
    return text.replace(/[-[\]{}()*+!<=:?.\/\\^$|#\s,]/g, '\\$&');
}

//a paraméterként adott szövegben emotikonokra cseréli a megadott stringeket regEx segítségével
function replaceEmojis(text){
    for(var i = 0; i < replace_emoticons.length; i++){
        var find = regExpEscape(replace_emoticons[i].string);
        var replace = replace_emoticons[i].code;
        var regex = new RegExp(find, "g");
        text = text.replace(regex, replace);
    }
    return text;
}

//meghíváskor visszaadja az aktuális időt (óra:perc)
function getMessageTime(){
    var date = new Date();
    return ((date.getHours() < 10)?"0":"") + date.getHours() +":"+ ((date.getMinutes() < 10)?"0":"") + date.getMinutes();
}

//beállítja az új üzenetek attribútumait --> üzenete idejét óra:perc formátumban, és az idejüket miliszekundumban
function setMessageAtrributes(time, millisec){
    $(".message-bubble").last().attr({
        "data-time": time,
        "data-time-millisec": millisec
    });
}

//létrehoz egy üzenet buborákot a kiválasztott matricával
function sendSticker(sticker_src){
    var message = new Message({
        text: null,
        time: getMessageTime(),
        sticker: sticker_src,
        image: null
    });
    createNewMessage(message, "own");
    socket.emit('newMessage', message);
}

function loadMessages() {
    $.ajax({
        type: "POST",
        url: "../php/load-message.php",
        succes: function (response) {
            
        },
        error: function (err) {
            alert("Nem sikerült betölteni az üzeneteket! :(");
        }
    });
}

