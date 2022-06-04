

<div id="widget-pane" class="w-100 overflow-hidden chat-bg pt-3 main-page">

    <div class="container table-responsive pt-5"> 
        <div class="page-content">

            <div class="container-fluid">

                <div class="row">

                    <!-- Widget settings -->
                    <div class="col-md-6">        
                        <div class="card mt-3">
                            <div class="card-body">
        
                                <h4 class="card-title pt-1 text-muted">
                                    Widget Settings
                                </h4>
  
                                <div class="row mt-4">        
                                    <div class="col-sm-12 col-md-6" title="Widget name">

                                        <!-- Name -->
                                        <div class="mb-3">
                                            <label class="form-label small">Widget Name</label>
                                            <input v-model="widget.name" type="text" name="widgetName" class="form-control" 
                                            placeholder="Enter widget name" title="Enter widget name">
                                        </div>

                                        <!-- Color -->
                                        <div class="mb-3">
                                            <label class="form-label small">Widget Color</label>
                                            <input id="color-picker" type="color" class="form-control" value='#276cb8' v-model="widget.color"
                                                name="widgetColor" placeholder="Enter RGB color" title="Enter RGB color">
                                        </div> 
                                        
                                        <!-- Custom Logo -->
                                        <div class="mb-3">
                                            <label class="form-label small">Built-in Logo</small></label>
                                            <select v-model="widget.img_src" name="img_src" class="form-select">
                                                @foreach($widget_icons as $icon)
                                                {{-- <option value="1" >Leaves</option> --}}
                                                <option value="{{ $icon->img_src }}" >{{ $icon->name }}</option>
                                                @endforeach
                                                <option value="">Custom</option>
                                            </select>
                                        </div>  

                                    </div>           
                                    <div class="col-sm-12 col-md-6 text-center pt-3">

                                        <img id="widget-icon" :src="widget.img_src" width="150" height="150" alt="Logo"
                                            :style="{'background-color': widget.color }">

                                        <div class="mt-4" hidden>
                                            <button type="button" class="btn btn-success btn-sm mx-2">
                                                Upload
                                            </button>
                                            <button type="button" class="btn btn-secondary btn-sm mx-2">
                                                Reset
                                            </button>                        
                                        </div>
                                    </div>


                                    <!-- Status -->
                                    <div class="col-sm-12 mb-3">
                                        <hr>
                                    </div>

                            
                                      <!-- Socket Server -->
                                      <div class="col-sm-12 mb-3">
                                        <label class="form-label">Socket Server</label>
                                        <input v-model="socketServerUrl" type="text" class="form-control" disabled>
                                    </div>
                                    

                                    <hr>

                                    <!-- Widget Status Toggle-->
                                    <div class="col-sm-6 mb-3 widget-config">
                                        <label class="form-label">
                                            Widget Status
                                        </label>
                                        <button type="button" 
                                            v-bind:class="[widget.isActive ? 'btn-success' : 'btn-danger']"
                                            class="btn btn-success btn-sm mx-2 btoggleButton" 
                                            @click="widget.isActive = !widget.isActive">
                                            @{{ widget.isActive ? 'Enabled' : 'Disabled' }}
                                        </button>
                                    </div>


                                    <!-- White List Toggle-->
                                    <div class="col-sm-6 mb-3 widget-config">
                                        <label class="form-label">
                                            White List
                                        </label>
                                        <button type="button"
                                            v-bind:class="[widget.whiteListEnabled ? 'btn-success' : 'btn-danger']"
                                            class="btn btn-success btn-sm mx-2 btoggleButton" 
                                            @click="widget.whiteListEnabled = !widget.whiteListEnabled">
                                            @{{ widget.whiteListEnabled ? 'Enabled' : 'Disabled' }}
                                        </button>
                                    </div>

                                    <!-- Ban List Toggle -->
                                    <div class="col-sm-6 mb-3 widget-config">
                                        <label class="form-label">
                                            Ban List
                                        </label>
                                        <button type="button"
                                            v-bind:class="[widget.banListEnabled ? 'btn-success' : 'btn-danger']"
                                            class="btn btn-success btn-sm mx-2 "
                                            @click="widget.banListEnabled = !widget.banListEnabled">
                                            @{{ widget.banListEnabled ? 'Enabled' : 'Disabled' }}
                                        </button>
                                    </div>

                                    <!-- Schedule Toggle-->
                                    <div class="col-sm-6 mb-3 widget-config">
                                        <label class="form-label">
                                            Schedule
                                        </label>
                                        <button type="button" 
                                            v-bind:class="[widget.scheduleEnabled ? 'btn-success' : 'btn-danger']"
                                            class="btn btn-success btn-sm mx-2 "
                                            @click="widget.scheduleEnabled = !widget.scheduleEnabled">
                                            @{{ widget.scheduleEnabled ? 'Enabled' : 'Disabled' }}
                                        </button>
                                    </div>

                                    <hr>

                                    <div class="col-sm-12 text-center my-1 mt-3">
                                        <button class="btn btn-success" type="button" @click="updateSettings()">
                                            Save Changes
                                        </button>
                                    </div>

                                </div>    
                            </div>
                        </div>
                    </div>
            

                    <!-- Widget script tag -->
                    <div class="col-md-6">      
                        <div class="card mt-3">
                            <div class="card-body">
        
                                <h4 class="card-title pt-1 text-muted">
                                    Widget Script
                                </h4>
                                
                                <div class="py-4">
                                    <textarea id="widgetScriptText" class="w-100" rows="14" style="resize: none; padding: 1rem;" 
                                    disabled >@{{ widget.script }}</textarea>
                                </div>

                                <div class="text-center pb-3">
                                    <button class="btn btn-success" type="button" @click="copyWidgetScript()">
                                        Copy Widget Script
                                    </button>
                                </div>

                                <p class="m-0 mt-1 small text-muted">
                                    <b>Note:</b> Click the script body to copy it. Once copied, paste it to the <b>header tag</b> 
                                    to a designated website you want the chat widget to appear.
                                </p>
        
                            </div>
                        </div>
                    </div>


                    <!-- White list -->
                    <div class="col-md-12" v-if="widget.whiteListEnabled == true">          
                        <div class="card mt-3">
                            <div class="card-body">
                                      
                                <div class="row">

                                    <div class="col-md-3">
                                        <h4 class="card-title pt-1 text-muted">
                                            White List
                                        </h4>
                                    </div>


                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Enter data to allow" v-model="whiteInput">
                                            <div class="input-group-append">
                                                <select class="form-select" v-model="selectedWhiteKey">
                                                    <option v-for="white in whiteSelectionList" v-bind:value="white.id" >@{{ white.labels }}</option>
                                                </select>  

                                                <button class="btn btn-primary" type="button"  @click="updateSettings('addWhite')">
                                                    Allow
                                                </button>
                                            </div>
                                          </div>
                                    </div>

                                    <div class="col-md-12">

                                        <div class="table-responsive mt-3">
                                            <table class="table table-editable table-nowrap align-middle table-edits">
                                                <thead>
                                                    <tr>
                                                        <th>Allowed Value</th>
                                                        <th colspan="2">Allow Type</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-show ="whiteList.length == 0">
                                                        <td class="text-center" colspan="3">
                                                            --- Allow list is empty ---
                                                        </td>
                                                    </tr>
                                                    <tr v-for="(whiteItem, index) in whiteList">
                                                        <td>@{{ whiteItem.value }}</td>
                                                        <td>@{{ whiteItem.type }}</td>
                                                        <td class="ban-remove-button">
                                                            <button class="btn btn-sm btn-danger" type="button" @click="updateSettings('removeWhite', index)">
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
                    {{-- End of White List --}}

                    <!-- Ban list -->
                    <div class="col-md-12" v-if="widget.banListEnabled == true">               
                        <div class="card mt-3">
                            <div class="card-body">
                                        
                                <div class="row">

                                    <div class="col-md-3">
                                        <h4 class="card-title pt-1 text-muted">
                                            Ban List
                                        </h4>
                                    </div>


                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Enter data to ban" v-model="banInput">
                                            <div class="input-group-append">
                                                <select class="form-select" v-model="selectedBanKey">
                                                    <option v-for="ban in banSelectionList" v-bind:value="ban.id" >@{{ ban.labels }}</option>
                                                </select>  

                                                <button class="btn btn-danger" type="button"  @click="updateSettings('addBan')">
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
                                                        <th>Banned Value</th>
                                                        <th colspan="2">Ban Type</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-show ="banList.length == 0">
                                                        <td class="text-center" colspan="3">
                                                            --- Ban list is empty ---
                                                        </td>
                                                    </tr>
                                                    <tr v-for="(banItem, index) in banList">
                                                        <td>@{{ banItem.value }}</td>
                                                        <td>@{{ banItem.type }}</td>
                                                        <td class="ban-remove-button">
                                                            <button class="btn btn-sm btn-danger" type="button" @click="updateSettings('removeBan', index)">
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
                {{-- End of Ban List --}}

                <!-- Schedule -->
                <div class="col-md-12"  v-if="widget.scheduleEnabled == true">            
                    <div class="card mt-3">
                        <div class="card-body">
                                    
                            <div class="row">

                                <div class="col-md-6">
                                    <h4 class="card-title pt-1 text-muted">
                                        Schedule
                                    </h4>
                                </div>


                                <div class="col-md-12">

                                    <div class="table-responsive mt-3">
                                        {{-- <div>@{{ widget }}</div> --}}
                                        <table class="table table-editable table-nowrap align-middle table-edits">
                                            <thead>
                                                <tr>
                                                    <th>Day</th>
                                                    <th>Start Time</th>
                                                    <th>End Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-show ="widget.schedule.length == 0">
                                                    <td class="text-center" colspan="3">
                                                        --- Allow list is empty ---
                                                    </td>
                                                </tr>
                                                <tr v-for="(schedItem, index) in widget.schedule">
                                                    <td>@{{ schedItem.day }}</td>
                                                    <td><input type="time" v-model="schedItem.start_time"></td>
                                                    <td><input type="time" v-model="schedItem.end_time"></td>
                                                    <td class="ban-remove-button">
                                                        <button class="btn btn-sm btn-secondary" type="button" 
                                                        v-bind:class="[schedItem.enabled ? 'btn-success' : 'btn-danger']"
                                                         @click="schedItem.enabled = !schedItem.enabled">
                                                            @{{ schedItem.enabled ? 'Enabled' : 'Disabled' }}
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="col-sm-12 text-center my-1 mt-3">
                                <button class="btn btn-success" type="button" @click="updateSettings()">
                                    Save Changes
                                </button>
                            </div>
                            
                        </div>
                    </div>
                </div>

                {{-- End of Schedule --}}
            </div>
        </div>
    </div>

</div>
