<div class="tab-pane show active" id="pills-chat" role="tabpanel">
    <!-- Start chats content -->
    <div>
        <div class="px-4 pt-4">
            <div class="d-flex align-items-start">
                <div class="flex-grow-1">
                    <h4 class="mb-4">
                        Chat page
                    </h4>
                </div>
            </div>
            <form>
                <div class="input-group search-panel mb-3">
                    <input type="text" class="form-control bg-light border-0" id="searchChatUser" v-model="searchClient"
                        title="Enter something to search a channel" placeholder="Search client" autocomplete="off">
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
                <ul class="list-unstyled chat-list chat-user-list mb-3">

                    <li class="chat-message-item pb-3"  @click="selectClient(client)"
                        v-bind:id="client.clientId" v-for="client in resultClientSearch" :class="[client.clientId == selectedClient.clientId ? 'bg-gray' : '']">                
                        <a href="javascript: void(0);" >                     
                            @{{ client.ipAddress }} @{{ client.regionName }}            
                        </a>  
                        
                        <span>
                            <button type="button" class="btn btn-info btn-sm" @click="viewClientInfo(client)">
                                Info
                            </button>
                        </span>
                    </li>

                    <li class="text-center" v-show="resultClientSearch.length == 0">
                        --- Client result list is empty ---
                    </li>

                </ul>
            </div>
        </div>
    </div>
</div>