<div class="tab-pane show active" id="pills-chat" role="tabpanel">
    <!-- Start chats content -->
    <div>
        <div class="px-4 pt-4">
            <div class="d-flex align-items-start">
                <div class="flex-grow-1">
                    <h4 class="mb-4">
                        Client chat page
                    </h4>
                </div>
            </div>
            <form>
                <div class="input-group search-panel mb-3">
                    <input type="text" class="form-control bg-light border-0" id="searchChatUser" v-model="searchClient"
                        title="Enter something to search a client" placeholder="Enter a text to search client" autocomplete="off">
                    <button class="btn btn-light p-0" type="button" id="searchbtn-addon"><i
                            class='bx bx-search align-middle'></i></button>
                </div>
            </form>
        </div> 

        <div data-simplebar>

            <div class="d-flex align-items-center px-4 mt-4 mb-4">
                <div class="flex-grow-1">
                    <h5 class="mb-0 fs-11 text-muted text-uppercase">Client List</h5>
                </div>
            </div>

            <div class="chat-message-list">
                <ul class="list-unstyled chat-list chat-user-list mb-3" id="channelList">

                    <li class="chat-message-item pb-3"  @click="selectClient(client)" v-for="client in resultClientSearch" 
                    v-bind:id="client.clientId" :class="[client.clientId == selectedClientId ? 'selected-client' : '']">  
                                  
                        <a href="javascript:" >   
                            <img src="/assets/images/online.png" width="16" class="mx-3" v-show="isClientOnline(client.clientId)">                   
                            <img src="/assets/images/offline.png" width="16" class="mx-3" v-show="!isClientOnline(client.clientId)"> 

                            <span>
                                @{{ client.flag }}
                                    
                                <small class="mx-2">
                                    @{{ client.label ? client.label + ' - (' + client.clientId + ')' : client.domain + ' - (' + client.clientId + ')' }}
                                </small>

                                <span class="text-danger mx-3" v-show="client.isMute">
                                    <i class="ri-volume-mute-line"></i>
                                </span>
                            </span>
                            
                            <span class="mx-3" v-bind:value="unseenMessages.clientId" v-if="unseenMessages.clientId == client.clientId && unseenMessages.unseenCount > 0">
                                <i class="alert alert-danger">@{{ unseenMessages.unseenCount }}</i>
                            </span>
                        </a>  


                        <div class="dropdown">
                            <button class="btn btn-sm px-3">
                                <small class="mx-2">Options</small> <i class="ri-settings-5-line"></i> 
                            </button>
                            <div class="dropdown-content">
                              <a href="javascript:" class="px-3" @click="viewClientInfo(client)" >Client Info</a>
                              <a href="javascript:" class="px-3" @click="openChatWindow(client)" >Open in new window</a>
                              <a href="javascript:" class="px-3" @click="controlClientMute(client)" >@{{ client.isMute ? 'Unmute Client' : 'Mute Client' }}</a>
                              <a href="javascript:" class="px-3 text-danger" @click="banClient(client)" >Ban Client</a>
                              <a href="javascript:" class="px-3 text-danger" @click="endClientSession(client)" >End Session</a>
                            </div>
                        </div>
                        

                        <span style="padding-top: 3px;" hidden>
                            <a href="javascript:" class="client-info" style="font-size: 12px" @click="viewClientInfo(client)" 
                                title="Click to view client details">
                                <i class="ri-information-line"></i> 
                            </a>

                            <button class="btn btn-danger btn-xs mx-2" type="button" @click="banClient(client)" 
                                title="Click to ban the client based from the website and IP">
                                Ban <i class="ri-forbid-line text-danger"></i>
                            </button>


                            <button class="btn btn-danger btn-xs mx-2" type="button" @click="endClientSession(client.clientId)" 
                                title="Click to end the chatting session">
                                Close <i class="ri-close-circle-line text-danger"></i> 
                            </button>
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


