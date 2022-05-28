// Utilities
(function () {
  
    // chat emojiPicker input
    new FgEmojiPicker({
      trigger: [".emoji-btn"],
      removeOnSelection: false,
      closeButton: true,
      position: ["top", "left"],
      preFetch: true,
      dir: "assets/libs/fg-emoji-picker/",
      insertInto: document.querySelector(".chat-input"),
    });
  
    
    // emojiPicker position
    var emojiBtn = document.getElementById("emoji-btn");
    if (emojiBtn) {
      emojiBtn.addEventListener("click", function () {
        setTimeout(function () {
          var fgEmojiPicker = document.getElementsByClassName("fg-emoji-picker")[0];
          if (fgEmojiPicker) {
            var leftEmoji = window.getComputedStyle(fgEmojiPicker)
              ? window.getComputedStyle(fgEmojiPicker).getPropertyValue("left")
              : "";
            if (leftEmoji) {
              leftEmoji = leftEmoji.replace("px", "");
              leftEmoji = leftEmoji - 40 + "px";
              fgEmojiPicker.style.left = leftEmoji;
            }
          }
        }, 0);
      });
    }
    
  
    // Password Peek
    var pwdEye = document.getElementById("password-addon");
    if(pwdEye){
      pwdEye.addEventListener("click", function() {
          var e = document.getElementById("txtPassword");
          "password" === e.type ? e.type = "text" : e.type = "password"
      });
    }
  
  
    // Chat Pane
    // var chatPane = document.getElementById("channelList");
    // var returnContacts = document.getElementById("return-contacts");
  
    // if(chatPane && returnContacts){
    //     chatPane.addEventListener("click", function() {
    //       var e = document.getElementById("chat-pane");
    //       e.classList.toggle("user-chat-show");
    //     });
  
    //     returnContacts.addEventListener("click", function() {
    //       var e = document.getElementById("chat-pane");
    //       e.classList.toggle("user-chat-show");
    //   });
    // }
  
  
    // Navbar Menu List
    var navList = document.getElementById("nav-item-list"); 
    if(navList){
        var nav_home = document.getElementById("nav-item-home");
        var nav_chat = document.getElementById("nav-item-chat");      
        var nav_widget = document.getElementById("nav-item-widget");
        var nav_logout = document.getElementById("nav-item-logout");
  
        var inner_navbar = document.getElementById("inner-navbar");
        var pane_home = document.getElementById("home-pane");
        var pane_chat = document.getElementById("chat-pane");
        var pane_widget = document.getElementById("widget-pane");
  
        inner_navbar.style.display = 'none';
        pane_home.style.display = 'block';
        pane_chat.style.display = 'none';
        pane_widget.style.display = 'none';
  

        nav_home.addEventListener("click", function() {
          inner_navbar.style.display = 'none';
          pane_home.style.display = 'block';
          pane_chat.style.display = 'none';
          pane_widget.style.display = 'none';
        });
  

        nav_chat.addEventListener("click", function() {
          inner_navbar.style.display = 'block';
          pane_home.style.display = 'none';
          pane_chat.style.display = 'block';
          pane_widget.style.display = 'none';
        });
  
        
        nav_widget.addEventListener("click", function() {
          inner_navbar.style.display = 'none';
          pane_home.style.display = 'none';
          pane_chat.style.display = 'none';
          pane_widget.style.display = 'block';
        });
  

        nav_logout.addEventListener("click", function() {
          if(confirm('Are you sure you want to logout?')){
              window.location.href = "/api/agent/logout";
          }
        });
    }


    console.log('App has been initiated...');
  
  })();

  
  function showConfirm(msg) {
      if(confirm(msg)) 
          return true;
      return false;
  }
  