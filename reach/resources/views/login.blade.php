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
    
        <div class="col-xl-5 col-lg-8">
            <div class="authentication-page-content">
                <div class="d-flex flex-column h-100 px-4 pt-4">
                    <div class="row justify-content-center my-auto">
                        <div class="col-sm-8 col-lg-8 col-xl-9 col-xxl-8">     
                            <div class="py-md-5 py-3">
                                
                                <!-- Header -->
                                <div class="text-center mb-5">
                                    <img class="mb-4" src="assets/images/brand/reach-128.png" alt="logo" >
                                    <h3 class="mb-1">Welcome to REACH!</h3>
                                    <small class="text-muted">Login to start reaching with your clients</small>
                                </div>
                                
                                <div>
                                  
                                    <!-- Email -->
                                    <div class="mb-3">
                                        <label for="txtEmail" class="form-label">Email</label>
                                        <input type="email" name="email"  class="form-control" v-model="email.value"
                                            placeholder="Enter your email" title="Enter your email">
                                    </div>
                    
                                    
                                    <!-- Password -->
                                    <div class="mb-2">
                                        <label for="txtPassword" class="form-label">Password</label>
                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                            <input type="password" name="password" class="form-control pe-5" v-model="password.value"
                                                    placeholder="Enter your password" title="Enter your password" id="txtPassword">
                                            <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted" 
                                                    type="button" id="password-addon">
                                                <i class="ri-eye-fill align-middle"></i>
                                            </button>
                                        </div> 
                                    </div>


                                    <!-- Error Message -->
                                    <div class="text-center text-danger small pb-3">
                                        @{{ errorMessage }}
                                    </div>
    
    
                                     <!-- Login Button -->
                                    <div class="text-center mt-4" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="bottom" title="Click to login">
                                        <button class="btn btn-primary w-100" type="button" @click="login()" >Log In</button>
                                    </div>
    
                                    <div class="mt-4 text-center">
                                        <div class="signin-other-title">
                                            <h5 class="font-size-14 mb-4 title">or</h5>
                                        </div>
                                    </div>

                                </div>
    
    
                                 <!-- Go to Register -->
                                <div class="text-center text-muted">
                                    <p>Don't have an account ? 
                                        <a href="javascript:"  class="fw-medium text-decoration-underline" @click="redirect('register')"
                                         data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="bottom" title="Click to register new account"> Register</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
    
                     <!-- Footer -->
                     <footer class="row">
                        <div class="col-xl-12">
                            <div class="text-center text-muted p-4">
                                <small class="mb-0">© 2022 A project by Team REACH for the UPOU-CMSC207 SY-2022</small>
                            </div>
                        </div>
                    </footer>
                    
                </div>
            </div>
        </div>
    </div> 

</div>
@endsection