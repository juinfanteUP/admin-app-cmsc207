/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***************************************!*\
  !*** ./resources/assets/js/widget.js ***!
  \***************************************/
// ***************** Update these Properties ***************** //
var sourceDomain = "http://127.0.0.1:8000";
var socketioUrl = "http://localhost:5000";
var socketioLib = "https://socketio.erickdelrey.rocks/socket.io/socket.io.js"; // *********************************************************** //

var localStorageName = 'reachapp_clientid';
var lurl = sourceDomain + '/widget/style.css';
var jurl = sourceDomain + '/widget/vendor.js';
var sendMessageApi = sourceDomain + "/api/message/send";
var validateClientApi = sourceDomain + "/api/client/validate"; // ***************** Services ***************** //
// Send message 

function sendMessage(msg) {
  $.ajax({
    type: "POST",
    url: sendMessageApi,
    dataType: 'json',
    data: msg,
    success: function success(result) {
      // Do something
      console.log(result);
    }
  });
} // Checks the website and client id to proceed


function validateClientAndGetWidget(cid) {
  return $.ajax({
    type: "POST",
    url: validateClientApi,
    dataType: 'json',
    data: cid,
    success: function success(data) {
      return data.data;
    },
    fail: function fail(xhr, textStatus, errorThrown) {
      console.log(errorThrown);
      return null;
    }
  });
} // Get client id from stored cookies. Geenerate a new one if non existent


var getLocalClientData = function getLocalClientData() {
  var _localStorage$getItem;

  return (_localStorage$getItem = localStorage.getItem(localStorageName)) !== null && _localStorage$getItem !== void 0 ? _localStorage$getItem : 0;
}; // Save client id generated from the server


var setLocalClientData = function setLocalClientData() {
  var cid = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
  localStorage.setItem(localStorageName, cid);
}; // ***************** Chat Widget ***************** //


var generateComponent = function generateComponent(widget, client, messages) {
  var INDEX = 0;
  document.body.innerHTML += widget;
  var socket = io(socketioUrl);
  var room = getLocalClientData();
  console.log(client); // If new user, jump in and join the 

  socket.emit('join-room', {
    "room": client.clientId,
    "clientId": client.clientId
  }); // Message from server. If messaged is whispered, do not generate the line

  socket.on('message', function (msg) {
    if (msg.isWhisper == 'false') {
      console.log(msg);
      generateMessage(msg.body, msg.isAgent == 'false', msg.created_at);
    }

    console.log(msg);
  }); // Generate message history

  messages.forEach(function (msg) {
    generateMessage(msg.body, msg.isAgent == 'false', msg.created_at, true);
  }); // Send message

  $("#chat-submit").click(function (e) {
    e.preventDefault();
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
      'createddtm': Date.now()
    };
    sendMessage(message);
    socket.emit('send-message', message);
    generateMessage(msg);
  });
  $("#chat-input").keyup(function () {
    var msg = $("#chat-input").val();
    var message = {
      'body': msg,
      'senderId': room,
      'clientId': room,
      'createddtm': Date.now()
    };
    socket.emit("client-typing", message);
  }); // User opened the widget

  $("#chat-circle").click(function () {
    $("#chat-circle").toggle('scale');
    $(".chat-box").toggle('scale');
  }); // User closed the widget

  $(".chat-box-toggle").click(function () {
    $("#chat-circle").toggle('scale');
    $(".chat-box").toggle('scale');
  }); // Render chat message

  function generateMessage(msg) {
    var isSelf = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
    var sentDate = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : new Date();
    var isChatHistory = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : false;
    INDEX++;
    var dtm = new Date(sentDate).toISOString().slice(0, 19).replace('T', ' ');
    var type = isSelf ? 'self' : 'user';
    var sender = isSelf ? "You sent this on ".concat(dtm) : "Sent by an agent on ".concat(dtm);
    var str = "<div id='cm-msg-".concat(INDEX, "' class=\"chat-msg ").concat(type, "\"><div class=\"cm-msg-text\"> ").concat(msg, " </div><small>").concat(sender, "</small></div>");
    $(".chat-logs").append(str);
    $("#cm-msg-" + INDEX).hide().fadeIn(isChatHistory ? 0 : 300);

    if (isSelf) {
      $("#chat-input").val('');
    }

    $(".chat-logs").stop().animate({
      scrollTop: $(".chat-logs")[0].scrollHeight
    }, isChatHistory ? 0 : 1000);
  }
}; // ***************** App Setup ***************** //
// Run app upon page load


(function () {
  // Add css style dependency
  var c = document.createElement('link');
  c.rel = 'stylesheet';
  c.type = 'text/css';
  c.href = lurl;
  c.media = 'all';
  document.head.appendChild(c); // Add socket.io js dependency

  var socketio = document.createElement("script");
  socketio.src = socketioLib;
  document.head.appendChild(socketio); // Add jquery and other dependencies

  if (!window.jQuery) {
    var s = document.createElement("script");
    s.src = jurl;
    document.head.appendChild(s);
  } // Send client id from local storage.


  setTimeout(function () {
    var domain = window.location.hostname;
    var params = {
      clientId: getLocalClientData(),
      domain: domain !== null && domain !== void 0 ? domain : "Unknown Site"
    };
    console.log("ClientId: ".concat(params.clientId));
    validateClientAndGetWidget(params).then(function (result) {
      // If widget is empty, widget may be unavailable or the client is banned
      if (result && result !== null && result !== void 0 && result.widget && (result === null || result === void 0 ? void 0 : result.clientId) != 0) {
        if (result.isNew) {
          setLocalClientData(result.client.clientId);
        }

        console.log(result.messages);
        generateComponent(result.widget, result.client, result.messages);
      }
    });
  }, 1000);
})();

function scrollToBottom() {
  setTimeout(function () {
    var div = document.getElementById("chat-body");
    div.scrollTop = div.scrollHeight;
  }, 200);
}
/******/ })()
;