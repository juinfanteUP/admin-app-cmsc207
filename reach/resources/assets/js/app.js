/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


 var app = new Vue({
	el: '#app',
	data: {

        // Agent
		agent: {
            _id: '',
			firstname: '',
			lastname: '',
			nickname: '',
			email: ''
		},

        // Message/ widgets	
        widget: {},
        widgetScript: '',
        reports: {
            clientCount: 0,
            messageVolumeCount: 0,
            historyList : []
        },

        // Message Inputs
		chatbox: '',
		file: { name: '' },
		isSubmitting: false,
        messages: [],

        // Clients
        clients: [],
		searchClient: '',
        selectedClient: {},
        viewClient: {}
	},

	mounted: function mounted() {
		// this.getClients();
		// this.getAgents();
        // this.getReports();
        this.getUserInput();
		this.getWidgetSettings();
       
        this.registerSocketServer();
	},

	computed: {
		resultClientSearch: function resultClientSearch() {
			var _this = this;

			if (this.searchClient) {
				return this.clients.filter((i)=>{
					return _this.searchClient.toLowerCase().split(' ').every(function(v) {
						return i.ipaddress.toLowerCase().includes(v);
					});
				});
			}

			return this.clients;
		},
        disableSend: function disableSend() {
            return this.isSubmitting || !((this.chatbox && this.chatbox != "") || this.file?.name != "");
        }
	},
	methods: {
		
        // ************************ Subscribe to Socket Server ************************ //
		
        registerSocketServer: function registerSocketServer(cid) {
			var _this = this;

            // Register SOCKET IO
         
		},


        // ************************ Agent and Reports Helper ************************ //


		getAgents: function getAgents() {		
            var api = $`/api/agent/getAgents`;
            var _this = this;

			axios.get(api).then(function(response) {
				_this.agents = response.data;
			})["catch"](function(error) {
				handleError(error);
			});
		},

        getReports: function getAgents() {		
            var api = $`/api/message/getReport`;
            var _this = this;

			axios.get(api).then(function(response) {
				_this.reports = response.data;
			})["catch"](function(error) {
				handleError(error);
			});
		},


        // ************************ Client Helper ************************ //


		getClients: function getClients() {		
            var api = $`/api/client/getClients`;
            var _this = this;

			axios.get(api).then(function(response) {
				_this.clients = response.data;

				if (_this.clients.length > 0) {
                    _this.selectClient(_this.clients[0]);
					_this.$forceUpdate();
				}


                // Hide/Disable chat box if no client exists


			})["catch"](function(error) {
				handleError(error);
			});
		},

        selectClient: function selectClient(client) {
            this.selectedClient = client;
		},

		viewClientInfo: function viewClientInfo(client) {
			this.viewClient = {
				ipaddress: client.ipaddress,
				domain: client.domain,
                country: client.country,
                region: client.region,
				city: client.city
			};
			$('#view-client-modal').modal('show');
		},


		// ************************ Widget Helper ************************ //


		getWidgetSettings: function getWidgetSettings() {
            var api = `/api/widget/getSettings`;
			var _this = this;

			axios.get(api).then(function(response) {
                _this.widget = response.data.widget;
                _this.widgetScript = response.data.script;

			})["catch"](function(error) {
				handleError(error);
			});
		},

		updateWidget: function updateWidget(submitEvent) {
            var api = `/api/widget/update`;
			var _this = this;

			if (confirm('Are you sure you want to update the widget settings?')) {
				showLoader();
				axios.put(api, {

					name: submitEvent.target.elements.name.value,
					email: submitEvent.target.elements.email.value,
					contact_no: submitEvent.target.elements.contact_no.value

                    // Change all of these

				}).then(function(response) {
					showLoader(false);
					var res = response.data;

					alert('Settings has been updated successfully.');
				})["catch"](function(error) {
					handleError(error);
				});
			}
		},


		// ************************ Message Helper ************************ //


		getMessages: function getMessages() {
			var _this = this;
            let api = `/api/message?channel_id=${this.selectedClient.clientId}`;

			showLoader();
			axios.get(api).then(function(response) {    
				_this.messages = response.data;

				_this.$forceUpdate();
				scrollToBottom();
                showLoader(false);

			})["catch"](function(error) {
				handleError(error);
			});
		},

		postMessage: function postMessage() {
            //var uploadApi = `/api/message/upload?channel_id=${this.selectedChannel.id}`;
            var sendApi = `/api/message/send`;
            var _this = this;

			if (!(this.chatbox && this.chatbox != "" || this.isSubmitting)) {
				return;
			}

			var msg = {
                "clientId": this.clientId,
				"body": this.chatbox,
				"senderId": 0,
				"isWhisper": false,
				"isClient": false,
				"createddtm": new Date().toISOString().slice(0, 19).replace('T', ' '),
				"attachment": {
                    "referenceId": 0,
                    "size": "",
                    "type": "",
                    "filename": ""
                }
			}; 

            // Handle message with attachment
			// if (this.file && this.file?.name != "") {
        
			// 	var formData = new FormData();
			// 	formData.append('file', this.file);
			// 	formData.append('document', JSON.stringify(msg));
			// 	this.isSubmitting = true;
			// 	this.$forceUpdate();

			// 	axios.post(uploadApi, formData, {
			// 		headers: {
			// 			'Content-Type': 'multipart/form-data'
			// 		}
			// 	}).then(function(response) {
			// 		_this.isSubmitting = false;
			// 		_this.chatbox = "";

			// 		_this.messages.push(response.data);

			// 		_this.cancelUpload();

			// 		scrollToBottom();
			// 	})["catch"](function(error) {
			// 		handleError(error);
			// 	});

            //     return;
			// } 
            
            // Handle plain message
            this.chatbox = "";
            this.messages.push(msg);
            scrollToBottom();
            
            axios.post(sendApi, {
                clientId: msg.clientId,
                body: msg.body,          
                senderId: msg.senderId,
                isWhisper: msg.isWhisper,
                isClient: msg.isClient
            }).then(function(response) {
                _this.$forceUpdate();
            })["catch"](function(error) {
                handleError(error);
            });
		},


        // ************************ File Helper ************************ //


        handleFileUpload: function handleFileUpload() {
			this.file = this.$refs.file.files[0];
		},

		cancelUpload: function cancelUpload() {
			this.$refs.file.value = null;
			this.file = { name: '' };
		},

		downloadAttachment: function downloadAttachment(referenceId) {
			window.open(`/api/message/download?id=${referenceId}`, '_blank');
		},


		// ************************ Utility Functions ************************ //


		getUserInput: function getUserInput() {
			var _this = this;
			setInterval(function() { 
                 inp = $("#chat-input").val(); 
                _this.chatbox = inp;
			}, 200);
		},
	}
});


// *********** Helper Methods *********** //

function hideModal() {
    $('.modal').modal('hide');
    $('.modal-backdrop').remove();
}

function showLoader(willShow = true) {
    let loader = document.getElementById("loader");
    if (loader){
        loader.style.display = willShow ? 'block' : 'none';
    }
}

function handleError(e) {
    console.log(e);
    showLoader(false);
}

function scrollToBottom() {
    setTimeout(function() {
        let parentContainer = document.getElementById("users-conversation")?.parentNode;
        if(parentContainer){
            parentContainer.style.overflowX = 'hidden';
            parentContainer.style.overflowY = 'auto';
            $("#" + parentContainer.id).scrollTop(parentContainer.scrollHeight);
        }
    }, 200);
}

function formatBytes(bytes) {
    if (!(bytes || bytes > 0)) return '0 Bytes';
    var k = 1024;
    var i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'][i];
}