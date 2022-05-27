
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
                                                    @{{ selectedClient.ipAddress }}
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
                        <li class="chat-list" v-bind:class="[false ? 'right' : 'left']" v-for="message in messages">
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

                                        <!-- Message with Attachment -->
                                        {{-- <div class="ctext-wrap-content" v-show ="message.attachment_id != 0">
                                            <p class="ctext-content" v-show ="message.body != ''">@{{ message.body }}</p>
                                            <div class="p-1 rounded-1">
                                                <div class="d-flex align-items-center attached-file">
                                                <div class="flex-shrink-0 avatar-sm me-3 ms-0 attached-file-avatar">
                                                    <div class="avatar-title rounded-circle fs-20" v-bind:class="[true ? 'bg-soft-light' : 'bg-soft-dark']">
                                                    <i class="ri-attachment-2"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <div class="text-start">
                                                    <h5 class="fs-14 mb-1">@{{ message.file_name }}</h5>
                                                    <p class="text-truncate fs-13 mb-0">@{{ formatBytes(message.mb_size) }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex-shrink-0 ms-4">
                                                    <div class="d-flex gap-2 fs-20 d-flex align-items-start">
                                                    <div>
                                                        <a @click="downloadAttachment(message.attachment_id)" href="javascript:" title="Click to download file" 
                                                            v-bind:class="[true ? 'text-white' : 'text-dark']">
                                                            <i class="bx bxs-download"></i>
                                                        </a>
                                                    </div>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        </div> --}}

                                    </div>
                                    <div class="conversation-name">
                                        <sub class="text-muted time">
                                            Sent by @{{ message.name }} at @{{ message.created_dtm }}
                                            <b class="px-2"> (whisper)</b>
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


            <div id="empty-chat" class="text-center" v-show="selectedClient.clientId == 0">
                <img src="assets/images/brand/reach-128.png" width="128">
                <p>
                    Please select a client to begin the chat
                </p>
            </div>


            <!-- Chat Input components -->
            <div v-show="selectedClient.clientId != 0 && clients.length > 0">

                <!-- Attachment -->
                <div class="attachment-tab row" v-show ="file.name != ''">
                    <div class="col-sm-10 text-left">
                        <b>@{{ isSubmitting ? 'Uploading File:' : 'Attachment Name:' }}</b> <span class="mx-1"> @{{ file.name }} </span>
                    </div>
                    <div class="col-sm-2 attachment-tab-close">
                        <a href="javascript:" class="text-white " @click="cancelUpload()" v-show ="!isSubmitting" title="Remove attachment">
                            <i class="bx bx-x align-middle"></i>
                        </a>
                    </div>
                </div>


                <!-- start chat input section -->
                <div class="position-relative">
                    <div class="chat-input-section p-4 border-top">
                        <div class="row g-0 align-items-center">
                            
                            <div class="col-auto text-center px-4">
                                <div class="chat-input-links me-md-2 mb-2">
                                    
                                    <!-- Attachments -->
                                    <input type="file" id="file-uploader" ref="file" v-on:change="handleFileUpload()" hidden/>
                                    <div class="links-list-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Attachments">
                                        <button type="button" onclick="document.getElementById('file-uploader').click()"
                                            class="btn btn-link text-decoration-none btn-lg waves-effect">
                                            <i class="bx bx-paperclip align-middle"></i>
                                        </button>
                                    </div>

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


