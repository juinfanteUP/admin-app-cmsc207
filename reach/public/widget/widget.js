/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***************************************!*\
  !*** ./resources/assets/js/widget.js ***!
  \***************************************/
// *********************************************************** //
var localStorageName = 'reachapp_clientid';
var sourceUrl = new URL(document.currentScript.src).origin;
var themeColor = "#111"; // ***************** Endpoint Services ***************** //
// Send message 

function sendMessage(msg) {
  $.ajax({
    type: "POST",
    url: "".concat(sourceUrl, "/api/message/send"),
    dataType: 'json',
    data: msg,
    success: function success(result) {}
  });
} // Checks the website and client id to proceed


function validateClientAndGetWidget(cid) {
  return $.ajax({
    type: "POST",
    url: "".concat(sourceUrl, "/api/client/validate"),
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


var generateComponent = function generateComponent(widget) {
  console.log('Widget component generated.');
  var client = widget.client;
  var messages = widget.messages;
  var settings = widget.settings;
  var links = widget.links;
  var INDEX = 0;
  document.body.innerHTML += widget.widget;
  UpdateWidgetSettings(settings);
  var socket = io(links.socketurl);
  var room = getLocalClientData(); // Generate message history

  for (var i = 0; i < messages.length; i++) {
    generateMessage(messages[i], messages[i].isAgent == 'false', messages[i].created_at, i == messages.length - 1);
  } // ***************************** Socket Support ***************************** //
  // If new user, jump in and join the 


  socket.emit('join-room', {
    "room": client.clientId,
    "clientId": client.clientId
  }); // Message from server. If messaged is whispered, do not generate the line

  socket.on('message', function (msg) {
    if (msg.isWhisper == 'false' && msg.clientId == room) {
      generateMessage(msg, msg.isAgent == 'false', msg.created_at);
    }
  }); // Listen to end session

  socket.on('end-session', function (clientId) {
    if (room == clientId) {
      alert('You session with the agent has ended.');
      $("#chat-body").remove();
      setLocalClientData();
    }
  }); // Listen to end session

  socket.on('allow-upload', function (conf) {
    if (conf.clientId == room) {
      if (conf.willAllow) $("#file-upload").show();else $("#file-upload").hide();
    }
  }); // ***************************** UI Component Controls ***************************** //

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
    };
    sendMessage(message);
    socket.emit('send-message', message);
    generateMessage(message);
  } // Render chat message


  function generateMessage(msg) {
    var isSelf = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
    var sentDate = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : new Date();
    var willScroll = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : true;
    INDEX++;
    var dtm = new Date(sentDate).toISOString().slice(0, 19).replace('T', ' ');
    var type = isSelf ? 'self' : 'user';
    var sender = isSelf ? "You sent this on ".concat(dtm) : "Sent by an agent on ".concat(dtm);
    var str = "";

    if (msg.attachmentId == '0' || msg.attachmentId == '' || msg.attachmentId == null) {
      str = "<div id='cm-msg-".concat(INDEX, "' class=\"chat-msg ").concat(type, "\"><div class=\"cm-msg-text\"> ").concat(msg.body, " </div><small>").concat(sender, "</small></div>");
    } else {
      var uri = "".concat(sourceUrl, "/api/message/download?id=").concat(msg.attachmentId);
      var label = "".concat(msg.fileName, " (").concat(formatBytes(msg.fileSize), ")");
      str = "<div id='cm-msg-".concat(INDEX, "' class=\"chat-msg ").concat(type, "\"><div class=\"cm-msg-text\"><span class=\"file-name\">").concat(label, "</span><a class=\"chat-download-link ").concat(type, "\" href=\"").concat(uri, "\" target=\"_blank\"><span title=\"Click to download\" class=\"material-icons chat-download\">download</span></a></div><small>").concat(sender, "</small></div>");
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
  } // ***************************** UI Component Controls ***************************** //
  // Send message


  $("#chat-submit").click(function (e) {
    e.preventDefault();
    initiateMessageSending();
  }); // User typing

  $("#chat-input").keyup(function () {
    var msg = $("#chat-input").val();
    var message = {
      'body': msg,
      'senderId': room,
      'clientId': room,
      'createddtm': Date.now()
    };
    socket.emit("client-typing", message);
  }); // User press enter

  $('#chat-input').keypress(function (e) {
    if (e.which == '13') {
      initiateMessageSending();
    }
  }); // User opened the widget

  $("#chat-circle").click(function () {
    $("#chat-circle").toggle('scale');
    $(".chat-box").toggle('scale');
  }); // User clicked header to close

  $(".chat-box-header").click(function () {
    $("#chat-circle").toggle('scale');
    $(".chat-box").toggle('scale');
  }); // Add attachment and immediately sendjs

  $("#file-uploader").change(function () {
    var input = document.getElementById('file-uploader');

    if (!input || !input.files) {
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
    };
    var file = input.files[0];
    var formData = new FormData();
    formData.append('file', file);
    formData.append('document', JSON.stringify(msg));
    $.ajax({
      url: "".concat(sourceUrl, "/api/message/send"),
      type: 'post',
      data: formData,
      contentType: false,
      processData: false,
      success: function success(response) {
        $("#file-uploader").val('');
        msg.attachmentId = response.attachmentId;
        msg.fileName = response.fileName;
        msg.fileSize = response.fileSize;
        socket.emit('send-message', msg);
        generateMessage(msg);
      },
      fail: function fail(xhr, textStatus, errorThrown) {
        console.log(errorThrown);
      }
    });
  }); // ***************************** Apply Settings ***************************** //

  function UpdateWidgetSettings(settings) {
    themeColor = settings.color;
    $("#file-upload").hide();
    $("#chat-circle").css("background-color", settings.color);
    $(".chat-submit").css("background-color", settings.color);
    $(".chat-box-header").css("background-color", settings.color);
    $("#widget-title").text(settings.name);
    $("#widget-icon").attr("src", "".concat(sourceUrl, "/").concat(settings.img_src));
  }
}; // ***************** App Setup ***************** //
// Run app upon page load


(function () {
  // Add css style dependency
  var c = document.createElement('link');
  c.rel = 'stylesheet';
  c.type = 'text/css';
  c.href = "".concat(sourceUrl, "/widget/style.css");
  c.media = 'all';
  document.head.appendChild(c); // Add jquery and other dependencies

  if (!window.jQuery) {
    var s = document.createElement("script");
    s.src = "".concat(sourceUrl, "/widget/vendor.js");
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
        } // Add socket.io js dependency


        var socketio = document.createElement("script");
        socketio.src = result.links.socketioLib;
        document.head.appendChild(socketio); // Generate Component

        setTimeout(function () {
          generateComponent(result);
        }, 500);
      } else {
        console.log(result.status);
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

function formatBytes(bytes) {
  if (!(bytes || bytes > 0)) return '0 Bytes';
  var k = 1024;
  var i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'][i];
}
/******/ })()
;