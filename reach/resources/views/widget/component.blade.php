<!-- Note:   
    - Color should be set on API. Use style tag to enforce color changes depending on widget setting.
    - Logo URL should be hard-coded and must be set from API
    - Remove agent chat
-->


<div id="chat-body">

    <!-- Chat icon collapsed -->
    <div id="chat-circle" class="btn btn-raised" title="Click to chat">  
        <div id="chat-overlay"></div>
        <img id="widget-icon" src="assets/images/widget-icon.png" width="40"> 
    </div>
    <div class="chat-box">

        <!-- Chat header -->
        <div class="chat-box-header">  
            <span class="chat-box-title">
                <span class="material-icons title-icon">forum</span> <span id="widget-title">Reach App</span>
            </span>
            <span class="chat-box-toggle" title="Click to collapse chat box">
                <span class="material-icons">close</span>
            </span>
        </div>

        <!-- Chat conversation-->
        <div class="chat-box-body">
            <div class="chat-box-overlay"></div>
            <div id="chat-body" class="chat-logs">
            </div>
        </div>

        <input type="file" id="file-uploader" hidden/>

        <!-- Chat input and send -->
        <div class="chat-input"> 
            <input type="text" id="chat-input" placeholder="Send a message..." />

            <span id="file-upload" title="Click to upload attachment" onclick="$('#file-uploader').click();">
                <span class="material-icons">attach_file</span>
            </span>

            <button type="submit" class="chat-submit" id="chat-submit" title="Click to send message">  
                <span class="material-icons">send</span>
            </button>
        </div>
    </div>
</div>