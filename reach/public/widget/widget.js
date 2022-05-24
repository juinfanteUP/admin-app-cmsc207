// ***************** Data source ***************** //


const sourceDomain = 'http://127.0.0.1:8000/';
const localStorageName = 'reachapp_clientid';
const ipKey = '0191456905f9443caea05a9ca832da47';

const iurl = 'https://ipgeolocation.abstractapi.com/v1/?api_key=' + ipKey;
const lurl = sourceDomain + 'widget/style.css';
const jurl = sourceDomain + 'widget/vendor.js';

const socketioUrl = "https://socketio.erickdelrey.rocks";
//const socketioLib = "http://localhost:5000";
const socketioLib = socketioUrl + "/socket.io/socket.io.js";

const sendMessageApi = sourceDomain + "api/sendMessage";
const getAllMessageApi = sourceDomain + "api/getMessages";
const validateClientApi = sourceDomain + "api/validateClientAndGetWidget";


// ***************** Services ***************** //


// Emit message
var sendMessage = (message, cid) => {
    var dataParam = {
        clientId: cid,
        body: message
    };

    $.ajax({
        type: "POST",
        url: sendMessageApi+ "?clientId=" + cid,
        contentType: "application/json",
        dataType: 'json',
        data: dataParam,
        success: function(result){

            // Do something
            console.log(result);
        }
    });
}


// Get all message by client id
var getAllMessage = (cid) => {
    $.ajax({
        type: "GET",
        url: getAllMessageApi + "?clientId=" + cid,
        contentType: "application/json",
        dataType: 'json',
        success: function(result){

            // Do something
            console.log(result);
        }
    });
}


// Checks the website and client id to proceed
var validateClientAndGetWidget = (cid, clientInfo=null) => {
    var dataParam = {
        ipaddress: clientInfo?.ip,
        hostname: clientInfo?.hostname,
        city: clientInfo?.city,
        region: clientInfo?.region,
        country: clientInfo?.country,
        timezone: clientInfo?.timezone
    };

    return $.ajax({
        type: "POST",
        url: validateClientApi + "?clientId=" + cid,
        dataType: 'json',
        data: dataParam,
        success: function(data){

            // Do something
            return data.data;
        },
        fail: function(xhr, textStatus, errorThrown){
            console.log(errorThrown);
            return null;
        }
    });
}


// Get client id from stored cookies. Generate a new one if non-existent
var getLocalClientData = () => {
    let cid = localStorage.getItem(localStorageName) ?? 0;
    if(cid == 0) {
        setLocalClientData();
    }

    return cid;
}

// Save client id generated from the server
var setLocalClientData = (cid=null) => {
    if(!cid){
        cid = Date.now().toString(36) + Math.random().toString(36).substr(2);
    }

    localStorage.setItem(localStorageName, cid);
}


// Get client info by IP
var getUserInfoByIP = () => {
    return $.getJSON(iurl, (data) => {
        return JSON.stringify(data, null, 2);
    })
    .fail((jqXHR, textStatus, errorThrown) => { return null })
}


// ***************** Chat Widget ***************** //


var generateComponent = (widget) => {
    var INDEX = 0;
    document.body.innerHTML += widget;

    const socket = io(socketioUrl);
    const room = getLocalClientData();
    const username = "client-name"; //update to client name

    socket.emit('join-room', {"room":room, "username" : username});

    // Message from server
    socket.on('message', (message) => {
        generateMessage(message.text, true);
    });

    // Trigger click when user pressed enter
    $('#chat-input').keydown(function(e){
        if (e.keyCode === 13) {
            $("#chat-submit").trigger('click');
        }
    });

    // Send message
    $("#chat-submit").click((e) => {

        e.preventDefault();

        var msg = $("#chat-input").val();
        if(msg.trim() == ''){
            return false;
        }

        generateMessage(msg);

        socket.emit('send-message', msg);

        setTimeout(() => {}, 1000)
    });

    // User opened the widget
    $("#chat-circle").click(() => {

        // Emit an online trigger?

        $("#chat-circle").toggle('scale');
        $(".chat-box").toggle('scale');
    });

    // User closed the widget
    $(".chat-box-toggle").click(() => {
        $("#chat-circle").toggle('scale');
        $(".chat-box").toggle('scale');
    });


    // Render chat message
    function generateMessage(msg, isSelf=true, sentDate=new Date()) {
        INDEX++;
        let dtm = sentDate.toLocaleString();
        let type = isSelf ? 'self' : 'user';
        let sender = isSelf ? `You sent this on ${dtm}` : `Sent by an agent on ${dtm}`
        var str = `<div id='cm-msg-${INDEX}' class="chat-msg ${type}"><div class="cm-msg-text"> ${msg} </div><small>${sender}</small></div>`;

        $(".chat-logs").append(str);
        $("#cm-msg-"+INDEX).hide().fadeIn(300);

        if(isSelf){
            $("#chat-input").val('');
        }

        $(".chat-logs").stop().animate({ scrollTop: $(".chat-logs")[0].scrollHeight}, 1000);
    }
};


// ***************** App Setup ***************** //


// Run app upon page load
(function(){
    var c  = document.createElement('link');
    c.rel  = 'stylesheet';
    c.type = 'text/css';
    c.href = lurl;
    c.media = 'all';
    document.head.appendChild(c);

    // Add socket.io js dependency
    var socketio = document.createElement("script");
    socketio.src = socketioLib;
    document.head.appendChild(socketio);

    // Load jquery and other dependencies
    if (!window.jQuery) {
        var s = document.createElement("script");
        s.src = jurl;
        document.head.appendChild(s);
    }

    setTimeout(() => {
        // Send client id from local storage.
        let cid = getLocalClientData();

        // not possible in local environment for client ip inspection (need laravel to do this)
        var ipData = getUserInfoByIP(cid)?.data;
        validateClientAndGetWidget(cid, ipData).then((widget)=> {

            // If widget is empty, widget may be unavailable or the client is banned
            if(widget) {
                generateComponent(widget);
            }

        });
    }, 1000)
})();
