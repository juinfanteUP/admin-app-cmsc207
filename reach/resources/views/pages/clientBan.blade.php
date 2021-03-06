

<div id="clientban-pane" class="w-100 chat-bg pt-3 main-page">

    <div class="container table-responsive pt-5"> 
        <div class="page-content">

            <div class="container-fluid">

                <div class="card mt-3">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-sm-6">
                                <h4 class="card-title pt-1 text-muted">
                                    Client Ban List
                                </h4>
                            </div>
                            <div class="col-sm-6">
                                <input v-model="clientBanPagination.search" type="text" class="form-control" placeholder="Enter to search banned clients">
                            </div>
                        </div>
                        

                        <!-- Data Client Ban -->
                        <div class="table-responsive mt-4">
                            <table class="table table-editable table-nowrap align-middle table-edits">
                                <thead>
                                    <tr>
                                        <th>Client Id</th>
                                        <th>Label</th>
                                        <th>IP Address</th>
                                        <th>Domain</th>
                                        <th>Country</th>
                                        <th>Banned By</th>
                                        <th>Banned Date</th>
                                        <th class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="client in resultClientBanList">                                                     
                                        <td>@{{ client.clientId }}</td>
                                        <td>@{{ client.label ? client.label : '---' }}</td>
                                        <td>@{{ client.ipaddress }}</td>
                                        <td>@{{ client.domain }}</td>
                                        <td>@{{ client.country }}</td>
                                        <td>@{{ client.bannedBy }}</td>
                                        <td>@{{ client.created_at }}</td>
                                        <td>
                                            <button class="btn btn-danger btn-sm px-2" @click="removeClientBan(client.clientId)">Unban</button>
                                        </td>
                                    </tr>
                                    <tr v-show ="resultClientBanList.length == 0">
                                        <td class="text-center" colspan="8">--- Ban list is empty ---</td>
                                    </tr>                    
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-2">
                            <div class="col-sm-6">
                                Page @{{ clientBanPagination.currentPage }} of @{{ clientBanPagination.totalPage }} <small class="mx-2 text-muted">(@{{ clientBanPagination.totalRecord }}) total records</small>
                            </div>
                            <div class="col-sm-6" style="text-align: right">
                                <button class="btn btn-sm btn-success px-2 mx-2" type="button" @click="clientBanPagination.currentPage--" :disabled='clientBanPagination.currentPage==1'>
                                    <i class="ri-arrow-left-s-line"></i>
                                </button>
                                <button class="btn btn-sm btn-success px-2" type="button" @click="clientBanPagination.currentPage++" :disabled='clientBanPagination.currentPage==clientBanPagination.totalPage'>
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
