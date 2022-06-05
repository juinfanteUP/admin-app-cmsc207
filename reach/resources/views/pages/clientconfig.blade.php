<!-- View client Info -->
<div class="modal fade" id="view-client-modal" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="min-width: 800px">
        <div class="modal-content modal-header-colored border-0">
            <div class="modal-header">
                <h5 class="modal-title text-white fs-16">
                    Client Configuration 
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" ></button>
            </div>

            <div class="modal-body p-4">
                <div class="table-responsive mt-1">
                    <table class="table table-editable table-nowrap align-middle table-edits">
                        <tbody>
                            <tr>
                                <th>Client Id</th>
                                <td>@{{ viewClient.clientId }} </td>
                            </tr>   
                            <tr>
                                <th>Client label</th>
                                <td>
                                    <input type="text" v-model="viewClient.label" class="form-control" placeholder="Enter to provide client label" />
                                </td>
                            </tr>  
                            <tr>
                                <th>Agent Notes</th>
                                <td>
                                    <textarea class="form-control" v-model="viewClient.notes" rows=3 placeholder="Enter to provide notes"></textarea>
                                </td>
                            </tr>  
                            <tr>
                                <th>IP Address</th>
                                <td>@{{ viewClient.ipaddress }} </td>
                            </tr>
                            <tr>
                                <th>Site Source</th>
                                <td>@{{ viewClient.source }} </td>
                            </tr>
                            <tr>
                                <th>Domain</th>
                                <td>@{{ viewClient.domain }} </td>
                            </tr>
                            <tr>
                                <th>Country</th>
                                <td>@{{ viewClient.country }} <span class="mx-2">@{{ viewClient.flag }}</span></td>
                            </tr>
                            <tr>
                                <th>City</th>
                                <td>@{{ viewClient.city }} </td>
                            </tr>              
                        </tbody>
                    </table>

                    <div class="text-center mt-2">
                        <button class="btn btn-success px-3" type="button" @click="updateClient()">Save Changes</button>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>