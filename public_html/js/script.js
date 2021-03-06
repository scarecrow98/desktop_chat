﻿//csatlakozás a webszerverhez
var socket = io.connect('192.168.64.8:3000');

$(document).ready(function () {

    //newMessage nevű adatcsomag érkezésekor DOM elem generálása az üzenetből 
    socket.on('newMessage', function (message) {
        createNewMessage(message, "partner");
    });

    //emoji panel feltöltése
    for (var i = 0; i < emoticons.length; i++) {
        $(".emoji-panel").append(
            '<span class="emoji-icon" id="' + emoticons[i].code + '">' + emoticons[i].code + '</span>'
        );
    }
    //emoji panel nyitása/zárása klikkel
    $(".ion-android-happy").click(function () {
        $(".emoji-panel").toggleClass("anim-popup");
    });

    //matrica panel feltöltése
    for (var i = 1; i <= 12; i++) {
        $(".sticker-panel .tab-1").append(
            '<img src="stickers/mouth_' + i + '.png" class="sticker-icon">'
        );
    }
    for (var i = 1; i <= 9; i++) {
        $(".sticker-panel .tab-2").append(
            '<img src="stickers/face_' + i + '.png" class="sticker-icon">'
        );
    }
    for (var i = 1; i <= 8; i++) {
        $(".sticker-panel .tab-3").append(
            '<img src="stickers/text_' + i + '.png" class="sticker-icon">'
        );
    }
    for (var i = 1; i <= 48; i++) {
        $(".sticker-panel .tab-4").append(
            '<img src="stickers/emoji' + i + '.png" class="sticker-icon">'
        );
    }
    
    //hamburgermenu megnyitása
    $("#hamburger-icon").click(function () {
        let menu = $("#main-menu");
        let isOpen = menu.hasClass("menu-open");

        if (isOpen) {
            menu.removeClass("menu-open").slideUp(300);;
        } else {
            menu.addClass("menu-open").slideDown(300);;
        }
    });
    
    //matrica panel nyitása/zárása klikkel
    $(".ion-happy").click(function () {
        $(".sticker-panel").toggleClass("anim-popup");
    });

    //emoji ikonok hozzáadása a szövegmezőhözaz emoji panelból
    $(".emoji-icon").click(function (e) {
        appendEmoji(e.target.id);
    });

    //matrica panelben matricak közti valtas
    $("#tab-indexes li").click(function () {
        var tabIndex = $(this).index() + 1;
        $(".tab-" + tabIndex).addClass("active-tab");
        $(".tab-" + tabIndex).siblings().removeClass("active-tab");
    });

    //matrica küldése üzenetként klikk eseménykor
    $(".sticker-icon").click(function (e) {
        sendSticker(e.target.getAttribute("src"));
    });

    //textfield focuskor a megnyitott panelek zárása
    $("#textfield").focus(function () {
        $(".sticker-panel").removeClass("anim-popup");
        $(".emoji-panel").removeClass("anim-popup");
    });

    //képfeltöltés megnyitása
    $(".ion-android-image").click(function () {
        $("#image-upload").click();
    });
    
    //kép küldése php-nek
    var fr = new FileReader();
    fr.onloadend = function () {
        uploadImage();
    }

    //képre kattintáskor kép kinagyítása
    $(document).on("click", ".image", function (e) {
        var imageSource = e.target.getAttribute("src");

        $("#image-zoom").attr("src", imageSource);
        $(".page-overlay:first-child").fadeIn(300);
    });

    //x ikonra kattintáskor épp megnyitott modal zárása
    $(".ion-close-round").click(function () {
        $(".page-overlay").fadeOut(300);
    });

    $("#main-menu .ion-person").click(function () {
        $(".page-overlay:nth-child(3)").fadeIn(300);
        $(".page-overlay:nth-child(3)").css("display", "flex");
        $("#main-menu").removeClass("menu-open").slideUp(300);
    });

    //küldés gombra kattintáskor üzenet küldése
    $("#send-icon").click(sendMessage);

    // gomblenyomások vizsgálata
    $(document).keydown(function(e){
        if(e.keyCode == 13 || e.which == 13){ //ENTER lenyomásakor üzenetküldés
            sendMessage();
        }
        if(e.keyCode == 27 || e.which == 27){ //megnyitott elemek bezárása ESC-kor
            $(".popup-panel").removeClass("anim-popup");
        }
    });

});
