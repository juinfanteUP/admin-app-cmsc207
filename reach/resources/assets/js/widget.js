// *********************************************************** //

const localStorageName = 'reachapp_clientid';
const sourceUrl = (new URL(document.currentScript.src)).origin;
var themeColor = "#111";
var isWidgetOpen = false;
var missedCount = 0;


// ***************** Endpoint Services ***************** //


// Send message 
function sendMessage (msg) {
	$.ajax({
		type: "POST",
		url: `${sourceUrl}/api/message/send`,
		dataType: 'json',
		data: msg,
		success: function(result) {
		}
	});
}


// Checks the website and client id to proceed
function validateClientAndGetWidget (cid) {
	return $.ajax({
		type: "POST",
		url: `${sourceUrl}/api/client/validate`,
		dataType: 'json',
		data: cid,
		success: function(data) {
			return data.data;
		},
		fail: function(xhr, textStatus, errorThrown) {
            console.log(errorThrown);
			return null;
		}
	});
}


// Get client id from stored cookies. Geenerate a new one if non existent
var getLocalClientData = () => {
	return localStorage.getItem(localStorageName) ?? 0;
}

// Save client id generated from the server
var setLocalClientData = (cid = null) => {
	localStorage.setItem(localStorageName, cid);
}


// ***************** Chat Widget ***************** //


var generateComponent = (widget) => {
    console.log('Widget component generated.');
    $("#missed-counter").hide();

    if (checkNotificationCompatibility()) {
        Notification.requestPermission(function(permission){
            console.log('notification permission: '+permission);
        })
    }

    var client = widget.client;
    var messages = widget.messages;
    var settings = widget.settings;
    const links = widget.links;

    

	var INDEX = 0;
	document.body.innerHTML += widget.widget;

    UpdateWidgetSettings(settings);
	const socket = io(links.socketurl);
	const room = getLocalClientData();

    // Generate message history
    for(var i=0; i<messages.length; i++){
        generateMessage(messages[i], messages[i].isAgent == 'false', messages[i].created_at, i == messages.length-1);  
    }

    // ***************************** Socket Support ***************************** //


    // If new user, jump in and join the 
    socket.emit('join-room', {
        "room": client.clientId,
        "clientId": client.clientId
    });

	// Message from server. If messaged is whispered, do not generate the line
	socket.on('message', (msg) => {
        if(msg.isWhisper == 'false' && msg.clientId == room){
            generateMessage(msg, msg.isAgent == 'false', msg.created_at);

            if (!isWidgetOpen){
                missedCount++;
                $("#missed-counter").show();
                $("#missed-counter").text(missedCount);
            }
            
            if (checkNotificationCompatibility() && Notification.permission === 'granted') {
                notify = new Notification("REACH", {
                    icon: `${sourceDomain}/assets/images/brand/reach-64.png`,
                    body: msg.body
                });
            }
        }	
	});

    // Listen to end session
    socket.on('end-session', (clientId) => {  
        if (room == clientId) {  
            alert('You session with the agent has ended.');
            $("#chat-body").remove();
            setLocalClientData();
        }
    });

    // Listen to end session
    socket.on('allow-upload', (conf) => {  
        if (conf.clientId == room) {
            if (conf.willAllow) $("#file-upload").show();
            else $("#file-upload").hide();
        }
    });


    // ***************************** UI Component Controls ***************************** //


    function initiateMessageSending() {
        var msg = $("#chat-input").val();

		if (msg.trim() == '') {
			return false;
		}

        var message = {
            'body': msg,
            'isWhisper': false,
            'isAgent': false,
            'senderId': room,
            'clientId': room,
            'attachmentId': '0',
            'fileSize': 0,
            'fileName': '',
            'createddtm': Date.now()
        }

        sendMessage(message);
		socket.emit('send-message', message);
		generateMessage(message);
    }


	// Render chat message
	function generateMessage(msg, isSelf = true, sentDate = new Date(), willScroll=true) {
		INDEX++;
		let dtm = new Date(sentDate).toISOString().slice(0, 19).replace('T', ' ')
		let type = isSelf ? 'self' : 'user';
		let sender = isSelf ? `You sent this on ${dtm}` : `Sent by an agent on ${dtm}`
        var str = "";

        if (msg.attachmentId == '0' || msg.attachmentId == '' || msg.attachmentId == null) {
            str = `<div id='cm-msg-${INDEX}' class="chat-msg ${type}"><div class="cm-msg-text"> ${msg.body} </div><small>${sender}</small></div>`;
        }
        else {
            let uri =  `${sourceUrl}/api/message/download?id=${msg.attachmentId}`;
            let label = `${msg.fileName} (${formatBytes(msg.fileSize)})`;
            str = `<div id='cm-msg-${INDEX}' class="chat-msg ${type}"><div class="cm-msg-text"><span class="file-name">${label}</span><a class="chat-download-link ${type}" href="${uri}" target="_blank"><span title="Click to download" class="material-icons chat-download">download</span></a></div><small>${sender}</small></div>`;
        }

		$(".chat-logs").append(str);
        $(".chat-msg.self>.cm-msg-text").css("background-color", themeColor);
		$("#cm-msg-" + INDEX).hide().fadeIn(300);

		if (isSelf) {
			$("#chat-input").val('');
		}
        
        if (willScroll) {
            $(".chat-logs").stop().animate({
                scrollTop: $(".chat-logs")[0].scrollHeight
            }, 1000);
        }
	}


    // ***************************** UI Component Controls ***************************** //


	// Send message
	$("#chat-submit").click((e) => {
		e.preventDefault();
		initiateMessageSending() 
	});

    // User typing
	$("#chat-input").keyup(function() {
		var msg = $("#chat-input").val();
		var message = {
            'body': msg,
            'senderId': room,
            'clientId': room,
            'createddtm': Date.now()
        }
		 socket.emit("client-typing", message);		
	});
    
    // User press enter
    $('#chat-input').keypress(function (e) {
        if (e.which == '13') {
            initiateMessageSending() 
        }
    });

	// User opened the widget
	$("#chat-circle").click(() => {
		$("#chat-circle").toggle('scale');
		$(".chat-box").toggle('scale');

        isWidgetOpen = true;
        missedCount = 0;
        $("#missed-counter").hide();
	});

    // User clicked header to close
	$(".chat-box-header").click(() => {
		$("#chat-circle").toggle('scale');
		$(".chat-box").toggle('scale');

        isWidgetOpen = false;
        
        if(missedCount > 0){
            $("#missed-counter").text(missedCount);
            $("#missed-counter").show();
        }
	});

    // Add attachment and immediately sendjs
    $("#file-uploader").change(function () {
        var input = document.getElementById('file-uploader');

        if(!input || !input.files){
           return;
        }

        var msg = {
            'body': "",
            'isWhisper': false,
            'isAgent': false,
            'senderId': room,
            'clientId': room,
            'attachmentId': '0',
            'fileSize': 0,
            'fileName': '',
            'createddtm': Date.now()
        }

        var file = input.files[0];
        let formData = new FormData();

        formData.append('file', file);
        formData.append('document', JSON.stringify(msg));
    
        $.ajax({
            url: `${sourceUrl}/api/message/send`,
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response){    
                
                $("#file-uploader").val('')
                msg.attachmentId = response.attachmentId;
                msg.fileName = response.fileName;
                msg.fileSize = response.fileSize;
                socket.emit('send-message', msg);
                generateMessage(msg);
            }, 	
            fail: function(xhr, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });
    });


    // ***************************** Apply Settings ***************************** //


    function UpdateWidgetSettings(settings) {
        themeColor = settings.color;
        $("#file-upload").hide();
        $("#chat-circle").css("background-color", settings.color);
        $(".chat-submit").css("background-color", settings.color);
        $(".chat-box-header").css("background-color", settings.color);
        $("#widget-title").text(settings.name);
        $("#widget-icon").attr("src", `${sourceUrl}/${settings.img_src}`);
    }

    function checkNotificationCompatibility() {
        if (typeof Notification === 'undefined') {
            console.log("Notification is not supported by this browser");
            return false;
        }
        return true;
    }

};


// ***************** App Setup ***************** //


// Run app upon page load
(function() {

     // Add css style dependency
     var c = document.createElement('link');
     c.rel = 'stylesheet';
     c.type = 'text/css';
     c.href = `${sourceUrl}/widget/style.css`;
     c.media = 'all';
     document.head.appendChild(c);

	// Add jquery and other dependencies
	if (!window.jQuery) {
		var s = document.createElement("script");
		s.src = `${sourceUrl}/widget/vendor.js`;
		document.head.appendChild(s);
	}

    // Send client id from local storage.
	setTimeout(() => {	 

        let domain = window.location.hostname;
        let params = {
            clientId: getLocalClientData(),
            domain: domain ?? "Unknown Site"
        }
  
        console.log(`ClientId: ${params.clientId}`);
		validateClientAndGetWidget(params).then((result) => {

            // If widget is empty, widget may be unavailable or the client is banned
			if (result && result?.widget && result?.clientId != 0) {

                if(result.isNew) {
                    setLocalClientData(result.client.clientId);
                }

                // Add socket.io js dependency
                var socketio = document.createElement("script");
                socketio.src = result.links.socketioLib;
                document.head.appendChild(socketio);

                // Generate Component
                setTimeout(() => {
                    generateComponent(result);
                }, 3000);
			}
            else {
                console.log(result.status)
            }
		});
	}, 1000)
})();


function scrollToBottom() {
    setTimeout(function() {
        var div = document.getElementById("chat-body");
        div.scrollTop = div.scrollHeight;
    }, 200);
}


function formatBytes(bytes) {
    if (!(bytes || bytes > 0)) return '0 Bytes';    
                          
    const k = 1024;
    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'][i];
}