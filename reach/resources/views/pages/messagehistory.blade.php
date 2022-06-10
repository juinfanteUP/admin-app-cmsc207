

<div id="history-pane" class="w-100 chat-bg pt-3 main-page">
    <div class="container table-responsive pt-5"> 
        <div class="page-content">
            <div class="container-fluid">

                <div class="card my-3">
                    <div class="card-body">                
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <h5 class="card-title pt-1 text-muted">
                                    Search Client
                                </h5>

                                <input class="form-control mt-2" type="text" v-model="messageHistoryPagination.searchMessageClient" 
                                placeholder="Enter text to search client" />                           
                            </div>
                            <div class="col-md-5">
                                <h5 class="card-title pt-1 text-muted">
                                    Filter Client <small class="mx-1">(<b class="text-success">@{{ resultClientFilterSearch.length }}</b>)</small>
                                </h5>

                                <select class="form-select mt-2" v-model="selectedSearchMessageClient">   
                                    <option :value="''" selected>--- All Clients ---</option>
                                    <option v-for="clientMessage in resultClientFilterSearch" :value="clientMessage.clientId" >
                                        @{{ clientMessage.label == null ? clientMessage.clientId : clientMessage.label }} - (@{{ clientMessage.ipaddress }} @ @{{ clientMessage.domain }})
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3 pt-4">
                                <button class="btn btn-success w-100 mt-1" type="button" @click="messageClientId = selectedSearchMessageClient">Search Message</button>
                            </div>
                        </div>
                    </div>
                </div>

                <br>

                <div class="card my-3">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-sm-6">
                                <h4 class="card-title pt-1 text-muted">
                                    Message History Result <small class="mx-1">(<b class="text-success">@{{ messageClientId == '' ? 'All Clients' : messageClientId }}</b>)</small>
                                </h4>
                            </div>
                            <div class="col-sm-6">
                                <input v-model="messageHistoryPagination.search" type="text" class="form-control" placeholder="Enter to search messages">
                            </div>
                        </div>
                    
                        <!-- Data History Report -->
                        <div class="table-responsive mt-4">
                            <table class="table table-editable table-nowrap align-middle table-edits">
                                <thead>
                                    <tr>
                                        <th>Client Id</th>
                                        <th>Sender</th>
                                        <th>Is Whisper</th>
                                        <th style="width: 300px">Message Body</th>
                                        <th>Attachment</th>
                                        <th>Sent Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="message in resultMessageHistory">                                                     
                                        <td>@{{ message.clientId }}</td>
                                        <td>@{{ message.isAgent == 'true' ? 'Agent' : 'Client' }}</td>
                                        <td>@{{ message.isWhisper == 'true' ? 'Yes' : 'No' }}</td>
                                        <td>@{{ message.body ? message.body : '---' }}</td>
                                        <td>
                                            @{{ message.attachmentId == '0' ? 'N/A' : '' }}
                                            <button class="btn btn-success btn-sm" type="button" title="Click to download"
                                                v-show="message.attachmentId != '0'" @click="downloadAttachment(message.attachmentId)">
                                                Download
                                            </button>
                                        </td>
                                        <td>@{{ message.created_at }}</td>
                                    </tr>
                                    <tr v-show ="resultMessageHistory.length == 0">
                                        <td class="text-center" colspan="6">--- Message history is empty ---</td>
                                    </tr>                    
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-2">
                            <div class="col-sm-6">
                                Page @{{ messageHistoryPagination.currentPage }} of @{{ messageHistoryPagination.totalPage }} <small class="mx-2 text-muted">(@{{ messageHistoryPagination.totalRecord }}) total records</small>
                            </div>
                            <div class="col-sm-6" style="text-align: right">
                                <button class="btn btn-sm btn-success px-2 mx-2" type="button" @click="messageHistoryPagination.currentPage--" :disabled='messageHistoryPagination.currentPage==1'>
                                    <i class="ri-arrow-left-s-line"></i>
                                </button>
                                <button class="btn btn-sm btn-success px-2" type="button" @click="messageHistoryPagination.currentPage++" :disabled='messageHistoryPagination.currentPage==messageHistoryPagination.totalPage'>
                                    <i class="ri-arrow-right-s-line"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
