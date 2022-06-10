


@extends('layout.main-layout')
@section('content')

    <div id="app" class="chat-bg">
        <div class="layout-wrapper d-lg-flex">

            <!-- Pages -->
            <div class="w-100">
                
                <div class="chat-content d-lg-flex">
                    <div class="w-100 overflow-hidden position-relative">
                        <div id="users-chat" class="position-relative">
                            <div class="py-3 user-chat-topbar">
                                <div class="row align-items-center">
                                    <div class="col-sm-12">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1 overflow-hidden chat-header">
                                                        <h6 class="text-truncate text-muted mb-0 fs- px-4 pt-2">
                                                            <a href="javascript:" class="user-profile-show text-reset" v-show="selectedClientId != 0">
                                                                @{{ selectedClientId }}
                                                            </a>
                                                        </h6>
                
                                                        <div class="text-small mx-4" v-show="selectedClientId != 0">
                                                            <small class="mx-2">Allow Client File Upload </small>
                                                            <input id="allow-client-upload" @click="allowClientUpload()" type="checkbox" data-toggle="toggle" data-on="on" data-off="off" 
                                                                title="Click to allow/disable file upload" data-onstyle="success" data-offstyle="secondary" data-size="xs">  
                                                        </div>
                
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                
                            <!-- Chat Conversation -->
                            <div class="chat-conversation window-chat p-3 p-lg-4 " id="chat-conversation" data-simplebar>
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
                                                    <div class="ctext-wrap-content" v-show ="message.attachmentId == '0'">
                                                        <p class="mb-0 ctext-content">@{{ message.body }}</p>
                                                    </div>
                
                                                    <!-- Message with Attachment -->
                                                    <div class="ctext-wrap-content" v-show ="message.attachmentId != '0'">
                                                        <p class="ctext-content" v-show ="message.body != ''">@{{ message.body }}</p>
                                                        <div class="p-1 rounded-1">
                                                            <div class="d-flex align-items-center attached-file">
                                                            <div class="flex-shrink-0 avatar-sm me-3 ms-0 attached-file-avatar">
                                                                <div class="avatar-title rounded-circle fs-20 bg-soft-dark">
                                                                <i class="ri-attachment-2"></i>
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1 overflow-hidden">
                                                                <div class="text-start">
                                                                <h6 class="fs-14 mb-1">@{{ message.fileName }}</h6>
                                                                <p class="text-truncate fs-13 mb-0">@{{ formatBytes(message.fileSize) }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="flex-shrink-0 ms-4">
                                                                <div class="d-flex gap-2 fs-20 d-flex align-items-start">
                                                                <div>
                                                                    <a @click="downloadAttachment(message.attachmentId)" href="javascript:" title="Click to download file" class="text-dark">
                                                                        <i class="bx bxs-download"></i>
                                                                    </a>
                                                                </div>
                                                                </div>
                                                            </div>
                                                            </div>
                                                        </div>
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
                                    <span id="istyping" class="text-mute small"></span>
                                    <span id="typing-client" class="text-success small fst-italic"></span>
                                    
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
                                                <div class="links-list-item text-center" data-bs-toggle="tooltip" data-bs-trigger="hover"
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
        </div>
    </div>

    <script src="{{ asset('assets/js/app.js') }}"></script>

@endsection





