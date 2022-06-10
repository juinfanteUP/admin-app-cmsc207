require('./bootstrap');
window.Vue = require('vue').default;


new Vue({
	el: '#app',
	data: {
        email: { value: '', error: '' },
        password: { value: '', error: '' },
        passwordConfirm: { value: '', error: '' },
        firstName: { value: '', error: '' },
        lastName: { value: '', error: '' },
        nickName: { value: '', error: '' },

        errorMessage: '',
	},

    mounted() {
        this.clearValues();

        $(window).on("load", function () {
            'use strict';
            $('[data-loader="circle-side"]').fadeOut();
            $('#preloader').delay(100).fadeOut('slow');
            $(window).scroll();
        })
    },

	methods: {


        // ******************** API Service ******************** //


		login: function login() {		
            var api = `/api/agent/login`;
            var _this = this;

            if (!this.validateLogin()) {
                return;
            }

            var params = {
                email: this.email.value, 
                password: this.password.value
            }

            showLoader();
			axios.post(api, params).then(function(response) {
                showLoader(false);

                _this.clearValues();
                window.location.href = '/';

			})["catch"](function(error) {
                _this.errorMessage = error.response.data;
				console.log(error);
			});
		},

		register: function register() {		
            var api = `/api/agent/register`;
            var _this = this;
            _this.error = '';

            if (!this.validateRegistration()) {
                return;
            }

           if (confirm('Are you sure you want to register a new user?')) {
                
                var params = {
                    email: this.email.value, 
                    password: this.password.value,
                    firstname: this.firstName.value,
                    lastname: this.lastName.value,
                    nickname: this.nickName.value
                }
                         
                showLoader();
                axios.post(api, params).then(function(response) {                
                    showLoader(false);

                    _this.clearValues();
                    alert('User has been registered successfully!')
                    window.location.href = '/login';

                })["catch"](function(error) {
                    _this.errorMessage = error.response.data;
                    console.log(error);
                });
           }
		},


        // ******************** Validation Service ******************** //


        validateLogin: function validateLogin() {
            this.errorMessage = '';
            
            if( this.email.value == '' || this.password.value == '' ) {
                this.errorMessage = 'Please fill up the required fields';
                return false;
            }

            return true;
        },


        validateRegistration: function validateRegistration() {
            this.clearErrors();
            let errorCount = 0;
            
            if ( this.email.value == '' ) {
                this.email.error = 'Email is empty';
                errorCount++;
            }
            else if (!(/\S+@\S+\.\S+/.test(this.email.value)) ) {
                this.email.error = 'Invalid email value';
                errorCount++;
            }

            if ( this.firstName.value == '' ) {
                this.firstName.error = 'First name is empty';
                errorCount++;
            }

            if ( this.lastName.value == '' ) {
                this.lastName.error = 'Last name is empty';
                errorCount++;
            }

            if ( this.nickName.value == '' ) {
                this.nickName.error = 'Nick name is empty';
                errorCount++;
            }

            if ( this.password.value == '' ) {
                this.password.error = 'Password is empty';
                errorCount++;
            }
            else if ( this.password.value.length < 6 ) {
                this.password.error = 'Password must be at least 6 characters';
                errorCount++;
            }
            else if ( this.password.value != this.passwordConfirm.value ) {
                this.password.error = 'Password confirmation does not match';
                errorCount++;
            }

            return errorCount == 0;
        },


        redirect: function redirect(route) {
            this.clearValues();

            switch(route){
                case 'login':
                    window.location.href = "/login";
                    break;

                case 'register':
                    window.location.href = "/register";
                    break;
            }          
        },


        clearValues: function clearValues() {
            this.email = { value: '', error: '' };
            this.password = { value: '', error: '' };
            this.passwordConfirm = { value: '', error: '' };
            this.firstName = { value: '', error: '' };
            this.lastName = { value: '', error: '' };
            this.nickName = { value: '', error: '' };
            this.errorMessage='';
        },


        clearErrors: function clearErrors() {
            this.email.error = "";
            this.password.error = "";
            this.passwordConfirm.error = "";
            this.firstName.error = "";
            this.lastName.error = "";
            this.nickName.error = "";
            this.errorMessage='';
        }

	}
});


function showLoader(willShow = true) {
    let loader = document.getElementById("loader");
    if (loader){
        loader.style.display = willShow ? 'block' : 'none';
    }
}

var pwdEye = document.getElementById("password-addon");
if(pwdEye){
  pwdEye.addEventListener("click", function() {
      var e = document.getElementById("txtPassword");
      "password" === e.type ? e.type = "text" : e.type = "password"
  });
}