<div id="home-pane" class="w-100 chat-bg main-page">
    <div class="container table-responsive pt-4"> 
        <div class="page-content">
                      
            <div class="container-fluid mt-4">
                <div class="row">     

                    <!-- Reach Welcome -->
                    <div class="col-sm-12 col-md-6">  
                        
                        
                        <div class="row">

                            <!-- Active User -->
                            <div class="col-sm-12 col-md-6">
                                <div class="card mt-3">
                                    <div class="card-body py-4 dashboard-meter">
                                        <div>
                                            <h6>Good Day,</h6>
                                            <h3>
                                                @{{ agent.nickname }}!
                                            </h3> 
                                        </div>
                                        <div>
                                            <i class="ri-user-line"></i>
                                        </div>   
                                    </div>
                                </div>
                            </div>


                            <!-- Message Volume Count -->
                            <div class="col-sm-12 col-md-6">
                                <div class="card mt-3">
                                    <div class="card-body py-4 dashboard-meter">
                                        <div>
                                            <h6>Current Time</h6>
                                            <h5>
                                                @{{ currentTime }}
                                            </h5> 
                                        </div>
                                        <div>                                 
                                            <i class="ri-sun-line"></i>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Main Introduction -->
                        <div class="card mt-3">
                            <div class="card-body">

                                <div class="text-center my-2">
                                    <img src="assets/images/brand/reach-128.png" width="110">
                                </div>
        
                                <h3 class="text-center mt-3 mb-4">
                                    Welcome to Reach!
                                </h3>
                                <p class="text-muted text-justify mt-3 mb-4 small">
                                    REACH or Residents Engagement Assistance Community Helper is a 
                                    chat application dedicated to help residents of a community to 
                                    connect with each other.  Send and receive messages between neighbors, 
                                    get to know your fellow residents, seek assistance, and build a friendly, 
                                    sociable, and supportive community with REACH app. REACH out now.
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

                                <p class="small text-center text-muted m-0 mt-1">
                                    A project for CMSC207 - UPOU SY 2022
                                </p>
                            </div>
                        </div>
                    </div>


                    <!-- Client Online -->
                    <div class="col-sm-12 col-md-6">        
                       
                        <div class="row">

                              <!-- Message Volume Count -->
                              <div class="col-sm-12 col-md-6">
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

                             <!-- Message Volume Count -->
                            <div class="col-sm-12 col-md-6">
                                <div class="card mt-3">
                                    <div class="card-body py-4 dashboard-meter">
                                        <div>
                                            <h6>Client Volume</h6>
                                            <h3>
                                                @{{ reports.clientCount }}
                                            </h3> 
                                        </div>
                                        <div>                                 
                                            <i class="ri-group-line"></i> 
                                        </div> 
                                    </div>
                                </div>
                            </div>


                            <!-- Chat Volume -->
                            <div class="col-md-12">          
                                <div class="card mt-3">
                                    <div class="card-body">
                
                                        <!-- Header and Search -->
                                        <h6 class="card-title pt-1 text-muted">
                                            Chat History Report
                                        </h6>
                
                                        <div class="my-4">
                                            <canvas id="reportCanvas" style="display: block; height: 400px" height="400"></canvas>
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