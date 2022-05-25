// ***************** Update these Properties ***************** //

const sourceDomain = 'http://127.0.0.1:8000/';
const socketioUrl = "https://socketio.erickdelrey.rocks";
//const socketioLib = "http://localhost:5000";
const socketioLib = socketioUrl + "/socket.io/socket.io.js";


// *********************************************************** //

const localStorageName = 'reachapp_clientid';
const lurl = sourceDomain + 'widget/style.css';
const jurl = sourceDomain + 'widget/vendor.js';

const sendMessageApi = sourceDomain + "api/message/message";
const getAllMessageApi = sourceDomain + "api/message/getByClientId";
const validateClientApi = sourceDomain + "api/client/validate";


// ***************** Services ***************** //


// Emit message
var sendMessage = (message, cid) => {
	var dataParam = {
		clientId: cid,
        senderId: cid,
		body: message,
        isAgent: false,
        isWhisper: false
	};

	$.ajax({
		type: "POST",
		url: sendMessageApi,
		contentType: "application/json",
		dataType: 'json',
		data: dataParam,
		success: function(result) {

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
		success: function(result) {

			// Do something
			console.log(result);
		}
	});
}


// Checks the website and client id to proceed
var validateClientAndGetWidget = (cid) => {
	return $.ajax({
		type: "POST",
		url: validateClientApi,
		dataType: 'json',
		data: { clientId: cid },
		success: function(data) {

			// Do something
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
	var INDEX = 0;
	document.body.innerHTML += widget;


	const socket = io(socketioUrl);
	const room = getLocalClientData();
	const username = "client-name"; //update to client name

	socket.emit('join-room', {
		"room": room,
		"username": username
	});

	// Message from server
	socket.on('message', (message) => {
		generateMessage(message.text, true);
	});


	// Trigger click when user pressed enter
	$('#chat-input').keydown(function(e) {
		if (e.keyCode === 13) {
			$("#chat-submit").trigger('click');
		}
	});


	// Send message
	$("#chat-submit").click((e) => {
		e.preventDefault();

		var msg = $("#chat-input").val();

		if (msg.trim() == '') {
			return false;
		}

		socket.emit('send-message', msg);

		generateMessage(msg);
		setTimeout(() => {}, 1000)
	});


	// User opened the widget
	$("#chat-circle").click(() => {
		$("#chat-circle").toggle('scale');
		$(".chat-box").toggle('scale');
	});


	// User closed the widget
	$(".chat-box-toggle").click(() => {
		$("#chat-circle").toggle('scale');
		$(".chat-box").toggle('scale');
	});


	// Render chat message
	function generateMessage(msg, isSelf = true, sentDate = new Date()) {
		INDEX++;
		let dtm = sentDate.toLocaleString();
		let type = isSelf ? 'self' : 'user';
		let sender = isSelf ? `You sent this on ${dtm}` : `Sent by an agent on ${dtm}`
		var str = `<div id='cm-msg-${INDEX}' class="chat-msg ${type}"><div class="cm-msg-text"> ${msg} </div><small>${sender}</small></div>`;

		$(".chat-logs").append(str);
		$("#cm-msg-" + INDEX).hide().fadeIn(300);

		if (isSelf) {
			$("#chat-input").val('');
		}

		$(".chat-logs").stop().animate({
			scrollTop: $(".chat-logs")[0].scrollHeight
		}, 1000);
	}
};


// ***************** App Setup ***************** //


// Run app upon page load
(function() {

    // Add css style dependency
	var c = document.createElement('link');
	c.rel = 'stylesheet';
	c.type = 'text/css';
	c.href = lurl;
	c.media = 'all';
	document.head.appendChild(c);

	// Add socket.io js dependency
	var socketio = document.createElement("script");
	socketio.src = socketioLib;
	document.head.appendChild(socketio);

	// Add jquery and other dependencies
	if (!window.jQuery) {
		var s = document.createElement("script");
		s.src = jurl;
		document.head.appendChild(s);
	}

    // Send client id from local storage.
	setTimeout(() => {	 
        let params = {
            "clientId": getLocalClientData(),
            "domain": document?.title ?? "Unknown Site"
        }

		validateClientAndGetWidget(params).then((result) => {

			// If widget is empty, widget may be unavailable or the client is banned
			if (result && result?.widget && result?.clientId != 0) {
                if(result.isNew) {
                    setLocalClientData(result.clientId);
                }

                generateComponentwidget(result.widget);
			}
		});
	}, 1000)
})();