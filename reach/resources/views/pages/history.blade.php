

<div id="history-pane" class="w-100 overflow-hidden chat-bg pt-3 main-page">

    <div class="container table-responsive pt-5"> 
        <div class="page-content">

            <div class="container-fluid">

                <div class="card mt-3">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-sm-6">
                                <h4 class="card-title pt-1 text-muted">
                                    Message History
                                </h4>
                            </div>
                            <div class="col-sm-6">
                                <input v-model="searchMessage" type="text" class="form-control" placeholder="Enter to search messages">
                            </div>
                        </div>
                        

                        <!-- Data History Report -->
                        <div class="table-responsive mt-4">
                            <table class="table table-editable table-nowrap align-middle table-edits">
                                <thead>
                                    <tr>
                                        <th>Client Id</th>
                                        <th>Client Label</th>
                                        <th>Sender</th>
                                        <th>Is Whisper</th>
                                        <th>Message Body</th>
                                        <th>Sent Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="message in resultMessageHistory">                                                     
                                        <td>@{{ message.clientId }}</td>
                                        <td>@{{ message.label ? message.label ? '---' }}</td>
                                        <td>@{{ message.isAgent ? 'Agent' : 'Client' }}</td>
                                        <td>@{{ message.isWhisper ? 'Yes' : 'No' }}</td>
                                        <td>@{{ message.body }}</td>
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
                                Page @{{ currentHistoryPage }} of @{{ totalHistoryPage }} <small class="mx-2 text-muted">(@{{ totalHistoryRecord }}) total records</small>
                            </div>
                            <div class="col-sm-6" style="text-align: right">
                                <button class="btn btn-sm btn-success px-2 mx-2" type="button" @click="currentHistoryPage--" :disabled='currentHistoryPage==1'>
                                    <i class="ri-arrow-left-s-line"></i>
                                </button>
                                <button class="btn btn-sm btn-success px-2" type="button" @click="currentHistoryPage++" :disabled='currentHistoryPage==totalHistoryPage'>
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
