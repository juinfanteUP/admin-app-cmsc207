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
                        title="Enter something to search a client" placeholder="Enter client Id to search" autocomplete="off">
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
                            
                            {{-- <span class="badge missed-count" v-show="client.missedCount>0">@{{ client.missedCount }}</span>          --}}
                            @{{ client.domain }} - @{{ client.ipaddress }}
                            <span class="mx-3" v-bind:value="unseenMessages.clientId" v-if="unseenMessages.clientId == client.clientId && unseenMessages.unseenCount > 0"><i class="alert alert-danger">@{{ unseenMessages.unseenCount }}</i></span>
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


