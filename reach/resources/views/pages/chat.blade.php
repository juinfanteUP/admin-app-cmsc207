
<!-- <div id="chat-pane" class="user-chat w-100 overflow-hidden user-chat-show"> -->
<div id="chat-pane" class="w-100 overflow-hidden chat-bg main-page">
    <div class="chat-content d-lg-flex">
        <div class="w-100 overflow-hidden position-relative">
            <div id="users-chat" class="position-relative">
                <div class="py-3 user-chat-topbar">
                    <div class="row align-items-center">
                        <div class="col-sm-12">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 d-block d-lg-none me-3">
                                    <a href="javascript: void(0);" id="return-contacts" class="btn-primary user-chat-remove fs-18 p-1">
                                        <i class="bx bx-chevron-left align-middle"></i>
                                    </a>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h6 class="text-truncate mb-0 fs- px-4">
                                                <a href="javascript:" class="user-profile-show text-reset">
                                                    @{{ selectedClientId }}
                                                </a>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                

                <!-- Chat Conversation -->
                <div class="chat-conversation p-3 p-lg-4 " id="chat-conversation" data-simplebar>
                    <ul class="list-unstyled chat-conversation-list" id="users-conversation">


                        <!-- Message Entry -->
                        <li class="chat-list" v-bind:class="[agent.agentId == message.senderId ? 'right' : 'left']" v-for="message in messages">
                            <div class="chat-avatar">
                                <img src="assets/images/chat-avatar.png" alt="profile">
                            </div>
                            <div class="conversation-list">
                                <div class="user-chat-content">
                                    <div class="ctext-wrap">

                                        <!-- Plain Message -->
                                        <div class="ctext-wrap-content">
                                            <p class="mb-0 ctext-content">@{{ message.body }}</p>
                                        </div>

    
                                    </div>
                                    <div class="conversation-name">
                                        <sub class="text-muted time">
                                            Sent by @{{ agent.agentId == message.senderId ? 'you' : message.isAgent == 'true' ? 'another agent' : 'the client' }} at @{{ message.created_at }}
                                            <span class="px-2"  v-show="message.isWhisper == 'true'"> (whisper)</span>
                                        </sub> 
                                        <span class="text-success check-message-icon"><i class="bx bx-check-double"></i></span>
                                    </div>
                                </div>
                            </div>
                        </li>


                        <!-- Empty Message Indicator -->
                        <li class="text-center" v-show="messages.length == 0 && clients.length > 0">
                            <span class="w-100 text-muted">--- Enter a message to start a conversation now! ---</span>
                        </li>


                    </ul>
                </div>
            </div>


            <div id="empty-chat" class="text-center" v-show="selectedClientId == 0">
                <img src="assets/images/brand/reach-128.png" width="128">
                <p>
                    Please select a client to begin the chat
                </p>
            </div>


            <!-- Chat Input components -->
            <div v-show="selectedClientId != 0 && clients.length > 0">


                <!-- start chat input section -->
                <div class="position-relative">
                    <div class="chat-input-section p-4 border-top">
                        <div class="row g-0 align-items-center">
                            
                            <div class="col-auto text-center px-4">
                                <div class="chat-input-links me-md-2 mb-2">
                                    
                                    <!-- Emojis -->
                                    <div class="links-list-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                        data-bs-placement="top" title="Emoji">
                                        <button type="button"
                                            class="btn btn-link text-decoration-none btn-lg waves-effect emoji-btn"
                                            id="emoji-btn">
                                            <i class="bx bx-smile align-middle"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Agent Whisper -->
                                <div>
                                    <input id="isWhisperChecked" type="checkbox" data-toggle="toggle" data-on="on" data-off="off" 
                                    data-onstyle="success" data-offstyle="secondary" data-size="sm">  
                                </div>
                                <p class="text-muted small mt-2">Agent Whisper</p>      
                            </div>


                            <!-- Chat Text Input -->
                            <div class="col">
                                <div class="position-relative">
                                    <div class="chat-input-feedback">
                                        Please Enter a Message
                                    </div>

                                    <textarea v-model="chatbox" type="text" placeholder="Type your message" id="chat-input" rows="3" style="resize: none"
                                    class="form-control form-control-lg bg-light border-0 chat-input" autofocus></textarea>
                                </div>
                            </div>


                            <!-- Submit Chat Button -->
                            <div class="col-auto">
                                <div class="chat-input-links ms-2 gap-md-1">
                                    <div class="links-list-item">
                                        <button type="button" @click="postMessage()" 
                                            class="btn btn-primary btn-lg chat-send waves-effect waves-light">
                                            <i class="bx bxs-send align-middle" id="submit-btn"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div> 
        </div>
    </div>
</div>


