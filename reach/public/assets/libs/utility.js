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
    
  
    // Chat Pane
    var chatPane = document.getElementById("channelList");
    var returnContacts = document.getElementById("return-contacts");
  
    if(chatPane && returnContacts){
        chatPane.addEventListener("click", function() {
          var e = document.getElementById("chat-pane");
          e.classList.toggle("user-chat-show");
        });
  
        returnContacts.addEventListener("click", function() {
          var e = document.getElementById("chat-pane");
          e.classList.toggle("user-chat-show");
      });
    }

  
    // Navbar Menu List
    var navList = document.getElementById("nav-item-list"); 
    if(navList){
        var nav_home = document.getElementById("nav-item-home");
        var nav_chat = document.getElementById("nav-item-chat");      
        var nav_widget = document.getElementById("nav-item-widget");
        var nav_multichat = document.getElementById("nav-item-multichat");      
        var nav_history = document.getElementById("nav-item-history"); 
        var nav_clientBan = document.getElementById("nav-item-clientban"); 
        var nav_logout = document.getElementById("nav-item-logout");
  
        var inner_navbar = document.getElementById("inner-navbar");
        var pane_home = document.getElementById("home-pane");
        var pane_chat = document.getElementById("chat-pane");
        var pane_widget = document.getElementById("widget-pane");
        var pane_history = document.getElementById("history-pane");
        var pane_clientBan = document.getElementById("clientban-pane");
        var pane_multichat = document.getElementById("multichat-pane");
  
        inner_navbar.style.display = 'none';
        pane_home.style.display = 'block';
        pane_chat.style.display = 'none';
        pane_widget.style.display = 'none';
        pane_multichat.style.display = 'none';
        pane_history.style.display = 'none';
        pane_clientBan.style.display = 'none';      
        pane_home.classList.add("active");

        nav_home.addEventListener("click", function() {
          inner_navbar.style.display = 'none';
          pane_home.style.display = 'block';
          pane_chat.style.display = 'none';
          pane_widget.style.display = 'none';
          pane_history.style.display = 'none';
          pane_multichat.style.display = 'none';
          pane_clientBan.style.display = 'none';

          nav_home.classList.add("active");
          nav_chat.classList.remove("active");
          nav_widget.classList.remove("active");
          nav_multichat.classList.remove("active");
          nav_history.classList.remove("active");
          nav_clientBan.classList.remove("active");
        });
  

        nav_chat.addEventListener("click", function() {
          inner_navbar.style.display = 'block';
          pane_home.style.display = 'none';
          pane_chat.style.display = 'block';
          pane_widget.style.display = 'none';
          pane_history.style.display = 'none';
          pane_multichat.style.display = 'none';
          pane_clientBan.style.display = 'none';

          nav_home.classList.remove("active");
          nav_chat.classList.add("active");
          nav_widget.classList.remove("active");
          nav_multichat.classList.remove("active");
          nav_history.classList.remove("active");
          nav_clientBan.classList.remove("active");
        });
  
        
        nav_widget.addEventListener("click", function() {
          inner_navbar.style.display = 'none';
          pane_home.style.display = 'none';
          pane_chat.style.display = 'none';
          pane_widget.style.display = 'block';
          pane_history.style.display = 'none';
          pane_multichat.style.display = 'none';
          pane_clientBan.style.display = 'none';

          nav_home.classList.remove("active");
          nav_chat.classList.remove("active");
          nav_widget.classList.add("active");
          nav_multichat.classList.remove("active");
          nav_history.classList.remove("active");
          nav_clientBan.classList.remove("active");
        });

        nav_history.addEventListener("click", function() {
            inner_navbar.style.display = 'none';
            pane_home.style.display = 'none';
            pane_chat.style.display = 'none';
            pane_widget.style.display = 'none';
            pane_history.style.display = 'block';
            pane_multichat.style.display = 'none';
            pane_clientBan.style.display = 'none';
  
            nav_home.classList.remove("active");
            nav_chat.classList.remove("active");
            nav_widget.classList.remove("active");
            nav_multichat.classList.remove("active");
            nav_history.classList.add("active");
            nav_clientBan.classList.remove("active");
          });

        nav_multichat.addEventListener("click", function() {
            inner_navbar.style.display = 'none';
            pane_home.style.display = 'none';
            pane_chat.style.display = 'none';
            pane_widget.style.display = 'none';
            pane_history.style.display = 'none';
            pane_multichat.style.display = 'block';
            pane_clientBan.style.display = 'none';
  
            nav_home.classList.remove("active");
            nav_chat.classList.remove("active");
            nav_widget.classList.remove("active");
            nav_multichat.classList.add("active");
            nav_history.classList.remove("active");
            nav_clientBan.classList.remove("active");
        });

        nav_clientBan.addEventListener("click", function() {
            inner_navbar.style.display = 'none';
            pane_home.style.display = 'none';
            pane_chat.style.display = 'none';
            pane_widget.style.display = 'none';
            pane_history.style.display = 'none';
            pane_multichat.style.display = 'none';
            pane_clientBan.style.display = 'block';
  
            nav_home.classList.remove("active");
            nav_chat.classList.remove("active");
            nav_widget.classList.remove("active");
            nav_multichat.classList.remove("active");
            nav_history.classList.remove("active");
            nav_clientBan.classList.add("active");
        });

        nav_logout.addEventListener("click", function() {
          if(confirm('Are you sure you want to logout?')){
              window.location.href = "/api/agent/logout";
          }
        });      
    }

    console.log('App has been initiated...');
  
  })();

