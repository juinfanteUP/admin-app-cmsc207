<div id="home-pane" class="w-100 chat-bg main-page">
    <div class="container table-responsive pt-5"> 
        <div class="page-content">
                      
            <div class="container-fluid mt-5">
                <div class="row">     

                    <!-- Reach Welcome -->
                    <div class="col-sm-12 col-md-5">      
                        <div class="card mt-3">
                            <div class="card-body">

                                <div class="text-center">
                                    <img src="assets/images/brand/reach-128.png" width="128">
                                </div>
        
                                <h3 class="text-center my-4">
                                    Welcome to Reach!
                                </h3>
                                <p class="text-muted justify mt-3 mb-4">
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
                                    sed do eiusmod tempor incididunt ut labore et dolore magna 
                                    aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                                </p>

                                <b>Contributors:</b>
                                <div class="row">
                                    <div class="col">
                                        <ul class="small">
                                            <li>Alquin Candelaria</li>
                                            <li>Bernard Kenneth Lim</li>
                                            <li>Chantal Mendoza</li>
                                            <li>Charlen Mei Pabalinas</li>
                                            <li>Dave Infante</li>
                                            <li>Erick Paul Jaucian</li>
                                        </ul>
                                    </div>
                                    <div class="col">
                                        <ul class="small">
                                            <li>Gerard Potato</li>
                                            <li>Herminio B Liegen Jr</li>
                                            <li>Isis Alva</li>
                                            <li>Lhexelyn Aleyn Hilario</li>
                                            <li>Luicito Dela Cruz Jr.</li>
                                            <li>Michelle Garcia</li>
                                        </ul>
                                    </div>
                                </div>

                                <p class="small text-center text-muted m-0 mt-3">
                                    A project for CMSC207 - UPOU SY 2022
                                </p>
                            </div>
                        </div>
                    </div>


                    <!-- Client Online -->
                    <div class="col-sm-12 col-md-7">        
                       
                        <div class="row">

                            <!-- Active User -->
                            <div class="col-sm-6">
                                <div class="card mt-3">
                                    <div class="card-body py-4 dashboard-meter">
                                        <div>
                                            <h6>Good Day,</h6>
                                            <h3>
                                                @{{ agent.nickname }}!
                                            </h3> 
                                        </div>
                                        <div>
                                            <i class="ri-sun-line"></i>
                                        </div>   
                                    </div>
                                </div>
                            </div>

                             <!-- Message Volume Count -->
                            <div class="col-sm-6">
                                <div class="card mt-3">
                                    <div class="card-body py-4 dashboard-meter">
                                        <div>
                                            <h6>Message Volume</h6>
                                            <h3>
                                                @{{ reports.messageVolumeCount }}
                                            </h3> 
                                        </div>
                                        <div>                                 
                                            <i class="ri-chat-3-line"></i>
                                        </div> 
                                    </div>
                                </div>
                            </div>

                            <!-- Chat Volume -->
                            <div class="col-md-12">          
                                <div class="card mt-3">
                                    <div class="card-body">
                
                                        <!-- Header and Search -->
                                        <h4 class="card-title pt-1">
                                            Recent History Report
                                        </h4>
                
                                        <!-- Data History Report -->
                                        <div class="table-responsive mt-4">
                                            <table class="table table-editable table-nowrap align-middle table-edits">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Client Count</th>
                                                        <th>Message Volume Count</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="summary in reports.historyList">
                                                        <td>@{{ summary.date }}</td>
                                                        <td>@{{ summary.clientCount }}</td>
                                                        <td>@{{ summary.messageVolumeCount }}</td>
                                                    </tr>
                                                    <tr v-show ="reports.historyList.length == 0">
                                                        <td class="text-center" colspan="3">--- Data not available ---</td>
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