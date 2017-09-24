function userConnectedNotification(username) {


    if (!Notification){
        console.log("A böngésződ nem támogatja értesítések megjelenítését!");
    }
    else if (Notification.permission !== "denied"){
        Notification.requestPermission(function (permission) {
            if (permission == "granted"){
                var notif = new Notification(username + " csatlakozott a beszélgetéshez.");
            }
        });
    }
    else if (Notification.permission == "granted"){
        var notif = new Notification(username + " csatlakozott a beszélgetéshez.");
    }
}