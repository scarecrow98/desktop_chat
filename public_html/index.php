<?php

    session_start();

    if( !isset($_SESSION["username"]) ){
        header("Location: login.php?login=failed");
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Desktop Chat App</title>
        <link rel="stylesheet" href="css/main.css"/>
        <link rel="stylesheet" href="css/animations.css"/>
        <link rel="stylesheet" href="css/ionicons.min.css"/>
        <link rel="stylesheet" href="css/modals.css"/>
        <link rel="stylesheet" href="css/profile.css"/>
        <meta charset="utf-8" lang="hu"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <script src="js/jquery.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.3/socket.io.js"></script>
        <script src="js/message.js"></script>
        <script src="js/script.js"></script>
        <script src="js/functions.js"></script>
        <script src="js/emoticons.js"></script>
        <script src="js/videostream.js"></script>
        <script src="js/ajax.js"></script>
        <script src="js/notifications.js"></script>
    </head>
    <body>

<!-- kinagyított kép -->
        <div class="page-overlay">
            <div id="image-zoom-container">
                <img src="" id="image-zoom">
                <i class="clickable-icon ion-close-round"></i>
            </div>
        </div>

<!-- videókamera felvétele -->
        <div class="page-overlay">
            <div id="video-capture-container">
                <video id="user-webcam-video" src=""></video>
                <canvas id="captured-image" style="background: #666;"></canvas>
                <div id="video-capture-buttons">
                    <i class="clickable-icon ion-camera"></i>
                    <i class="clickable-icon ion-android-send" id="send-captured-image"></i>
                </div>
            </div>
        </div>

<!-- felhasználó profilja -->
        <div class="page-overlay">
            <div id="profile-container">
                <i class="clickable-icon ion-close-round"></i>
                <div id="profile-header">
                    <div id="pic-container">
                        <img id="user-profile-pic" src="img/<?php echo $_SESSION["avatar"]?>">
                        <div id="pic-overlay"><span>Profilkép frissítése</span></div>
                    </div>
                    <div id="user-name-container">
                        <span><?php echo $_SESSION["first"]." ".$_SESSION["last"] ?></span>
                    </div>
                </div>
                <div id="info-container">
                    <div id="info-line">
                       <label for="">Lakhely:</label> 
                       <div id="editable-info-field" contenteditable="true">Dunaföldvár</div>
                    </div>
                    <div id="info-line">
                       <label for="">Munkahely/iskola:</label> 
                       <div id="editable-info-field" contenteditable="true">AlfaBeta Kft</div>
                    </div>
                    <div id="info-line">
                       <label for="">Születési idő:</label> 
                       <div id="editable-info-field" contenteditable="true">1998. 07. 31</div>
                    </div>
                    <div id="info-line">
                       <label for="">Születési hely:</label> 
                       <div id="editable-info-field" contenteditable="true">Dunaújváros</div>
                    </div>
                    <div id="info-line">
                       <label for="">Oldal tagja:</label> 
                       <div id="editable-info-field" contenteditable="true">2015. 04. 12. óta</div>
                    </div>
                </div>
                <div id="profile-buttons">
                    <button class="profile-button">Szerkesztés</button>
                    <button class="profile-button">Mentés</button>
                </div>
            </div>
        </div>

<!-- oldal fejléce -->
        <div id="chat">
            <div id="chat-header">
                <div id="chat-navigation">
                    <div id="user-info">
                        <img id="user-profile-pic" src="img/<?php echo $_SESSION["avatar"]?>">
                        <span id="user-name"><?php echo $_SESSION["first"]." ".$_SESSION["last"] ?></span>
                    </div>
                    <div id="menu-container">
                        <i id="hamburger-icon" class="ion-navicon-round"></i>
                        <ul id="main-menu">
                            <li><a href="#" class="ion-person">Saját profil</a></li>
                            <li><a href="logout.php" class="ion-log-out">Kijelentkezés</a></li>
                        </ul>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>

<!-- chat szekció -->
            <div id="chat-box">

                <!-- megjelenített üzenetek helye -->
                <div id="section-messages">
                    <p></p>
                    <!--<div class="message-wrapper message-partner">
                        <div class="message-bubble">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus erat mi, mattis pharetra scelerisque quis, pellentesque nec neque. Integer viverra tortor non fringilla mattis. Etiam aliquam, mauris et mattis vestibulum, elit mi cursus tortor, eget pharetra eros eros sed lectus.
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="message-wrapper message-own">
                        <div class="message-bubble">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus erat mi, mattis pharetra scelerisque quis, pellentesque nec neque. Integer viverra tortor non fringilla mattis. Etiam aliquam, mauris et mattis vestibulum, elit mi cursus tortor, eget pharetra eros eros sed lectus.
                        </div>
                        <div class="clear"></div>
                    </div>-->
                </div>
                
                <!-- ikonok, szövegmező helye -->
                <div id="section-inputs">
                    <div id="input-icons-container">
                        <div class="icon-wrapper">
                            <i class="clickable-icon ion-android-happy"></i>
                            <div class="popup-panel emoji-panel">
                                <!--dinamikusan elhelyezett emojik-->
                            </div>
                        </div>
                        <div class="icon-wrapper">
                            <i class="clickable-icon ion-happy"></i>
                            <div class="popup-panel sticker-panel">
                                <ul id="tab-indexes">
                                    <li>&nbsp;</li>
                                    <li>&nbsp;</li>
                                    <li>&nbsp;</li>
                                    <li>&nbsp;</li>
                                </ul>
                                <div class="tab-1 sticker-tab active-tab"><!--matrica ikonok--></div>
                                <div class="tab-2 sticker-tab"><!--matrica ikonok--></div>
                                <div class="tab-3 sticker-tab"><!--matrica ikonok--></div>
                                <div class="tab-4 sticker-tab"><!--matrica ikonok--></div>
                            </div>
                        </div>
                        <div class="icon-wrapper">
                            <i class="clickable-icon ion-android-image"></i>
                            <form id="image-upload-form" name="image-upload-form" action="php/image-upload.php" method="POST" enctype="multipart/form-data">
                                <input type="file" id="image-upload" name="image-upload" accept="image/*" onchange="uploadImage();">
                            </form>
                        </div>
                        <div class="icon-wrapper">
                            <i class="clickable-icon ion-android-camera"></i>
                        </div>
                        <div class="icon-wrapper">
                            <i class="clickable-icon ion-ios-mic"></i>
                        </div>
                    </div>

                    <div id="textfield-container">
                        <input type="text" id="textfield" autocomplete="off" placeholder="Az üzeneted helye...">
                        <i class="clickable-icon ion-paper-airplane" id="send-icon"></i>
                    </div>
                </div>
            </div>
        </div>  

    </body>
</html>