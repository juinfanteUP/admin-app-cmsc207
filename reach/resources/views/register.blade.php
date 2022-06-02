@extends('layout.auth-layout')

@section('content')

<div class="auth-bg">

    <div class="row g-0">
        <div class="col-xl-3 offset-xl-2 col-lg-4">
            <div class="p-4 pb-0 p-lg-5 pb-lg-0 auth-logo-section">
                <div class="mt-auto">
                    <img src="assets/images/auth-img.png" alt="" class="auth-img">
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-8">
            <div class="authentication-page-content">
                <div class="d-flex flex-column h-100 px-4 pt-4">
                    <div class="row justify-content-center my-auto">
                        <div class="col-sm-8 col-lg-8 col-xl-9">     
                            <div class="pt-4">
                                
                                <!-- Header -->
                                <div class="text-center mb-4">
                                    <h3 class="mb-1">Register Agent Account</h3>
                                    <small class="text-muted">
                                        Fill up the required details below
                                    </small>
                                </div>

                                <div>

                                    <!-- Email -->
                                    <div class="mb-3" title="Enter your email">
                                        <label for="txtEmail" class="form-label">Email <small class="text-danger">*</small></label>
                                        <input type="email" name="email" class="form-control" v-model="email.value"
                                                placeholder="Enter your email" title="Enter your email">  
                                        <small class="text-danger">
                                            @{{ email.error }}
                                        </small>      
                                    </div>
                                    

                                    <div class="mt-3 text-center">
                                        <div class="signin-other-title">
                                            <h5 class="font-size-14 mb-3 title"></h5>
                                        </div>
                                    </div>


                                    <div class="row">


                                        <!-- Password -->
                                        <div class="col-sm-12 col-md-6 mb-3" title="Enter your password">
                                            <label for="txtPassword" class="form-label">Password <small class="text-danger">*</small></label>
                                            <input type="password" name="password" class="form-control" v-model="password.value"
                                                    placeholder="Enter your password" title="Enter your password" id="txtPassword">
                                            <small class="text-danger">
                                                @{{ password.error }}
                                            </small>  
                                        </div>


                                        <!-- Confirm Password -->
                                        <div class="col-sm-12 col-md-6 mb-3" title="Confirm password">
                                            <label for="txtPassword" class="form-label">Confirm Password <small class="text-danger">*</small></label>
                                            <input type="password" name="passwordConfirm" class="form-control" v-model="passwordConfirm.value"
                                                    placeholder="Enter your password" title="Enter your password">
                                        </div>


                                        <div class="col-sm-12">
                                            <div class=" mt-3 text-center">
                                                <div class="signin-other-title">
                                                    <h5 class="font-size-14 mb-3 title"></h5>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- First Name -->
                                        <div class="col-sm-12 col-md-6 mb-3" title="Enter your first name">
                                            <label for="txtFirstName" class="form-label">First Name <small class="text-danger">*</small></label>
                                            <input type="text" name="firstname" class="form-control" v-model="firstName.value"
                                                    placeholder="Enter your first name" title="Enter your first name">  
                                            <small class="text-danger">
                                                @{{ firstName.error }}
                                            </small>  
                                        </div>


                                         <!-- Last Name -->
                                         <div class="col-sm-12 col-md-6 mb-3" title="Enter your last name">
                                            <label for="txtLastName" class="form-label">Last Name <small class="text-danger">*</small></label>
                                            <input type="text" name="lastname" class="form-control" v-model="lastName.value"
                                                    placeholder="Enter your last name" title="Enter your last name">  
                                            <small class="text-danger">
                                                @{{ lastName.error }}
                                            </small>  
                                        </div>
                                    </div>

                                    <div class="row">

                                        <!-- Nick Name -->
                                        <div class="col-sm-12 col-md-12 mb-3" title="Enter your nickname">
                                            <label for="txtNickName" class="form-label">Nick Name <small class="text-danger">*</small></label>
                                            <input type="text" name="nickname" class="form-control" v-model="nickName.value"
                                                    placeholder="Enter your nickname" title="Enter your nickname">  
                                            <small class="text-danger">
                                                @{{ nickName.error }}
                                            </small>    
                                        </div>

                                    </div>
                        
                                    
                                    <div class="text-center my-3">
                                        <div class="signin-other-title">
                                            <h5 class="font-size-14 mb-3 title"></h5>
                                        </div>
                                    </div>


                                    <!-- Register Button -->
                                    <div title="Click to create new account">
                                        <button class="btn btn-primary w-100 waves-effect waves-light" type="button" @click="register()"
                                        data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="bottom" title="Click to register your new account">
                                            Register
                                        </button>
                                    </div>

                                </div>

                                <!-- Return to Login -->
                                <div class="mt-4 text-center text-muted" title="Click to go back to login page">
                                    <p>Already have an account ? 
                                        <a href="javascript:" @click="redirect('login')" class="fw-medium text-decoration-underline"
                                            data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="bottom" title="Click to go back to login page">
                                            Login
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <footer class="row">
                        <div class="col-xl-12">
                            <div class="text-center text-muted p-4">
                                <small class="mb-0">© 2022. A project by Team REACH for the UPOU-CMSC207 SY-2022</small>
                            </div>
                        </div>
                    </footer>

                </div>
            </div>
        </div>                 
    </div>  

</div>
@endsection