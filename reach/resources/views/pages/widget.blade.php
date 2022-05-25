

<div id="widget-pane" class="w-100 overflow-hidden chat-bg pt-3 main-page">

    <div class="container table-responsive pt-5"> 
        <div class="page-content">

            <div class="container-fluid">

                <div class="row">

                    <!-- Widget settings -->
                    <div class="col-md-5">        
                        <div class="card mt-3">
                            <div class="card-body">
        
                                <h4 class="card-title pt-1 text-muted">
                                    Widget Settings
                                </h4>
  
                                <div class="row mt-4">

                                    <!-- Name -->
                                    <div class="col-sm-12 mb-3" title="Widget name">
                                        <label class="form-label small">Widget Name</label>
                                        <input type="text" name="widgetName" class="form-control" 
                                        placeholder="Enter widget name" title="Enter widget name">
                                    </div>


                                    <!-- Color -->
                                    <div class="col-sm-12 mb-3">
                                        <label class="form-label small">Widget Color <small>(e.g. #000)</small></label>
                                        <input type="text" name="widgetColor" class="form-control"
                                        placeholder="Enter RGB color" title="Enter RGB color">
                                    </div>


                                    <!-- Status -->
                                    <div class="col-sm-12 mb-3">
                                        <label class="form-label">Widget Status</label>
                                        <select class="form-select" placeholder="Show Weekly Report">
                                            <option value="Active" selected >Active</option>
                                            <option value="Disabled" >Disabled</option>
                                        </select>
                                    </div>


                                    <!-- Start time -->
                                    <div class="col-sm-12 mb-3">
                                        <label class="form-label small">Availability Start Time</label>
                                        <input type="date" name="password" class="form-control" title="Select availability time">
                                    </div>


                                     <!-- End time -->
                                     <div class="col-sm-12 mb-3">
                                        <label class="form-label small">Availability End Time</label>
                                        <input type="date" name="password" class="form-control" title="Select availability time">
                                    </div>


                                    <div class="col-sm-12 text-center my-1">
                                        <button class="btn btn-success" type="button">
                                            Save Changes
                                        </button>
                                    </div>

                                </div>    
        
                            </div>
                        </div>
                    </div>
            

                    <!-- Widget script tag -->
                    <div class="col-md-6 offset-md-1">      
                        <div class="card mt-3">
                            <div class="card-body">
        
                                <h4 class="card-title pt-1 text-muted">
                                    Widget Script
                                </h4>

                               
                                <div class="py-4">
                                    <textarea class="w-100" rows="14" style="resize: none;" v-model="widgetScript" disabled ></textarea>
                                </div>

                                <p class="m-0 small text-muted">
                                    <b>Note:</b> Click the script body to copy it. Once copied, paste it to the <b>header tag</b> 
                                    to a designated website you want the chat widget to appear.
                                </p>
        
                            </div>
                        </div>
                    </div>


                    <!-- Ban list -->
                    <div class="col-md-12">          
                        <div class="card mt-3">
                            <div class="card-body">
                                      
                                <div class="row">

                                    <div class="col-md-6">
                                        <h4 class="card-title pt-1 text-muted">
                                            Ban List
                                        </h4>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Enter data to ban">
                                            <div class="input-group-append">
                                                <select class="form-select">
                                                    <option value="IPAddress" selected >IP Address</option>
                                                    <option value="Country">Country</option>
                                                    <option value="City">City</option>
                                                    <option value="Domain">Domain</option>
                                                </select>
                                                <button class="btn btn-danger" type="button">
                                                    Add to Ban
                                                </button>
                                            </div>
                                          </div>
                                    </div>

                                    <div class="col-md-12">

                                        <div class="table-responsive mt-3">
                                            <table class="table table-editable table-nowrap align-middle table-edits">
                                                <thead>
                                                    <tr>
                                                        <th>Banned Data</th>
                                                        <th colspan="2">Ban Type</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>[Entry]</td>
                                                        <td>[Type]</td>
                                                        <td class="ban-remove-button">
                                                            <button class="btn btn-sm btn-danger" type="button">
                                                                Remove
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
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
