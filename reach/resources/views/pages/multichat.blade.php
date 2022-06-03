<div id="multichat-pane" class="w-100 overflow-hidden chat-bg main-page">
    <div class="card multichat-list">
        <div class="card-body p-4">   
            <div class="row">
                <div class="col-sm-4">

                    <h4 class="mb-4">
                        Multi-chat page
                    </h4>

                    <div class="input-group search-panel mb-3">
                        <input type="text" class="form-control bg-light border-0" id="searchChatUser" v-model="searchClient"
                            title="Enter something to search a client" placeholder="Enter client Id to search" autocomplete="off">
                        <button class="btn btn-light p-0" type="button" id="searchbtn-addon"><i
                                class='bx bx-search align-middle'></i></button>
                    </div>

                </div>
                <div class="col-sm-8">
                    
                    <ul class="list-unstyled chat-list chat-user-list multichat-clients">
        
                        <li class="chat-message-item pb-1 small"  @click="addClientToMultiWindow(client.clientId)" v-for="client in resultClientSearch" 
                        v-bind:id="client.clientId">  
                                      
                            <a href="javascript:" >   
                                <img src="/assets/images/online.png" width="16" class="mx-3" v-show="isClientOnline(client.clientId)">                   
                                <img src="/assets/images/offline.png" width="16" class="mx-3" v-show="!isClientOnline(client.clientId)"> 
                                @{{ client.domain }} - @{{ client.ipaddress }}          
                            </a>  
                            
                            <span style="padding-top: 3px;">
                                <a href="javascript:" class="client-info" @click="viewClientInfo(client)" 
                                title="Click to view client details">
                                    <i class="ri-information-line"></i> 
                                </a>
    
                                <a href="javascript:" class="client-info" @click="endClientSession(client.clientId)" 
                                title="Click to end the chatting session">
                                    <i class="ri-close-circle-line text-danger"></i> 
                                </a>
                            </span>
                        </li>
                        
    
                        <li class="text-center" v-show="resultClientSearch.length == 0">
                            --- Client result is empty ---
                        </li>
    
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <!-- Chat windows container -->
    <div class="multichat-container">

        <div :id="w.clientId" class="live-chat" v-for="w in multiWindowList">
            <header>                
                <a href="javascript:" class="chat-close" @click="removeClientFromWindow(w.clientId)">X</a>
                <h4>@{{ w.label }}</h4>                 
            </header>
    
            <div :id="w.windowId" class="chat">                
                <div class="chat-history">
                                    
                    <div class="chat-message" v-for="message in w.messages">
                        <hr>
                        <div class="chat-message-content">                                  
                            <span class="chat-time">@{{ message.created_at }}</span>   
                            <h5>@{{ message.isAgent ? 'Agent' : 'Client' }}</h5>
                            
                            <p class="mt-2" v-show ="message.attachmentId == '0'">
                                @{{ message.body }}
                            </p>

                            <div class="chat-attachment mt-2" v-show ="message.attachmentId != '0'">
                                <p class="pt-1">
                                    @{{ message.fileName }} <br> (@{{ formatBytes(message.fileSize) }})
                                </p>
                                <div>
                                    <a href="javascript:" class="text-success" @click="downloadAttachment(message.attachmentId)" title="Click to download">
                                        <i class="ri-download-line"></i>
                                    </a>
                                </div>
                            </div>
                        
                        </div>                        
                    </div>             
                </div>
     
                <div class="input-form">                
                    <input type="text" placeholder="Enter a message to send" v-model="w.body" />
                    <button class="btn btn-success btn-sm" type="button" @click="sendMessageFromMultiChat(w)" title="Click to send message">
                        <i class="ri-send-plane-2-fill"></i>
                    </button>               
                </div>                
            </div>             
        </div>

    </div>



</div>
