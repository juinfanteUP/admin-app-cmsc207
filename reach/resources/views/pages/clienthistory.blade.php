<!-- View client Info -->
<div class="modal fade" id="client-history-modal" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="min-width: 800px">
        <div class="modal-content modal-header-colored border-0">
            <div class="modal-header">
                <h5 class="modal-title text-white fs-16">
                    Client Message History 
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" ></button>
            </div>

            <div class="modal-body p-4">
                
                <div class="row">
                    <div class="col-sm-6">
                        <h4 class="card-title pt-1 text-muted">
                            Client: @{{ viewClient.label == '' ? viewClient.clientId :  viewClient.label  }}
                        </h4>
                    </div>
                    <div class="col-sm-6">
                        <input v-model="clientMessagePagination.search" type="text" class="form-control" placeholder="Enter to search messages">
                    </div>
                </div>
            
                <!-- Data History Report -->
                <div class="table-responsive mt-4">
                    <table class="table table-editable table-nowrap align-middle table-edits">
                        <thead>
                            <tr>
                                <th>Sender</th>
                                <th>Is Whisper</th>
                                <th style="width: 300px">Message Body</th>
                                <th>Attachment</th>
                                <th>Sent Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="message in resultClientMessages">                                                     
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
                            <tr v-show ="resultClientMessages.length == 0">
                                <td class="text-center" colspan="5">--- Message history is empty ---</td>
                            </tr>                    
                        </tbody>
                    </table>
                </div>

                <div class="row mt-2">
                    <div class="col-sm-6">
                        Page @{{ clientMessagePagination.currentPage }} of @{{ clientMessagePagination.totalPage }} <small class="mx-2 text-muted">(@{{ clientMessagePagination.totalRecord }}) total records</small>
                    </div>
                    <div class="col-sm-6" style="text-align: right">
                        <button class="btn btn-sm btn-success px-2 mx-2" type="button" @click="clientMessagePagination.currentPage--" :disabled='clientMessagePagination.currentPage==1'>
                            <i class="ri-arrow-left-s-line"></i>
                        </button>
                        <button class="btn btn-sm btn-success px-2" type="button" @click="clientMessagePagination.currentPage++" :disabled='clientMessagePagination.currentPage==clientMessagePagination.totalPage'>
                            <i class="ri-arrow-right-s-line"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>