var express = require('express');
var socket = require('socket.io');

//express könyvtár meghívása
var app = express();

//localhost felállítása - port:3000
var server = app.listen(3000);

//'public' mappa megadása statikusnak, hogy a server ezeket a fájlokat küldje csak majd ki a kliensnek
app.use(express.static('public_html'));

//socket.io könyvtárnak megadjuk, hogy melyik szerverhez akarjuk rendelni a socketeket
var io = socket(server);

//új kapcsolat létrejöttekor (eseményvezérelt) hív egy callbacket, paraméternek pedig átadja az új kapcsolatot, mint objektumot
io.sockets.on('connection', function(newSocket){

    console.log("Új kapcsolat:" + newSocket.id); //consolba (parancssorba) kiírja az új kapcsolat ID-jét
    
    //ha a szerver kap egy új adatcsomagot 'newMessage' névvel, akkor hív egy callbacket, paraméternek pedig átadja az adatcsomag tartalmát
    newSocket.on('newMessage', function(message){
        newSocket.broadcast.emit('newMessage', message); //a szerver minden socketnek kiküldi az adatcsomagot, kivéve annak, akitől kapta
        console.log(newSocket.id + " : " + message.text + " - " + message.time);
    });

});

