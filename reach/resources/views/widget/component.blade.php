<!-- Note:   
    - Color should be set on API. Use style tag to enforce color changes depending on widget setting.
    - Logo URL should be hard-coded and must be set from API
    - Remove agent chat
-->


<div id="chat-body">

    <!-- Chat icon collapsed -->
    <div id="chat-circle" class="btn btn-raised" title="Click to chat" style="background: %COLOR%">  
        <div id="chat-overlay"></div>
        <img src="%DOMAIN%/assets/images/widget-icon.png" width="40"> 
    </div>
    <div class="chat-box">

        <!-- Chat header -->
        <div class="chat-box-header" style="background: %COLOR%">  
            <span class="chat-box-title">
                %NAME%
            </span>
            <span class="chat-box-toggle" title="Click to collapse chat box">
                <i class="material-icons">close</i>
            </span>
        </div>

        <!-- Chat conversation-->
        <div class="chat-box-body">
            <div class="chat-box-overlay"></div>
            <div class="chat-logs">

                <!-- Representation of agent's chat -->
                {{-- <div id='cm-msg-1000' class="chat-msg user">
                    <div class="cm-msg-text">
                        Sample message
                    </div>
                    <small>Sent by an agent on 1/1/2022</small>
                </div> --}}

                <!-- User/Client chat will be generated here -->
            </div>
        </div>

        <!-- Chat input and send -->
        <div class="chat-input">
            <input type="text" id="chat-input" placeholder="Send a message..." />
            <button type="submit" class="chat-submit" id="chat-submit" title="Click to send message" style="background: %COLOR%">   <!-- Change Color -->
                Send
            </button>
        </div>
    </div>
</div>