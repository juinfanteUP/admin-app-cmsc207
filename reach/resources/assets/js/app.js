/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

const { trimEnd } = require('lodash');

require('./bootstrap');

window.Vue = require('vue').default;


// ***************** Update these Properties ***************** //


const socketioUrl = process.env.SOCKET_SERVER_URL;


// ***************** Update these Properties ***************** //


 var app = new Vue({
	el: '#app',
	data: {

        // Agent
		agent: {
			firstname: '',
			lastname: '',
			nickname: '',
			email: ''
		},

        // Message/ widgets	
        widget: {
            name: 'Reach App',
            color: '#4eac6d',
            isActive: true,
            startTime: '',
            endTime: '',
            script: ''
        },
        reports: {
            clientCount: 0,
            messageVolumeCount: 0,
            historyList : []
        },

        // Ban components
        banList: [],
        banSelectionList: [
            {id: 'domain', labels:'Domain'}, {id: 'ipaddress', labels:'IP Address'}, 
            {id: 'country', labels:'Country'}, {id: 'city', labels:'City'}, 
        ],
        banInput: '',
        selectedBanKey: 'domain',

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
        this.getProfile();
		this.getClients();
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

        selectedBan: function () {
            return this.selectedBanKey;
        },

        disableSend: function disableSend() {
            return this.isSubmitting || !((this.chatbox && this.chatbox != "") || this.file?.name != "");
        }
	},
	methods: {
		
        // ************************ Subscribe to Socket Server ************************ //
		
        registerSocketServer: function registerSocketServer(cid) {
			var _this = this;

            const socket = io(socketioUrl);
            const room = cid;

            socket.emit('join-room', {
                "room": room,
                "username": _this.agent.nickname
            });
        
            // Message from server
            socket.on('message', (msg) => {
                _this.messages.push(msg);
                scrollToBottom();
            });
		},


        // ************************ Agent and Reports Helper ************************ //


		getProfile: function getProfile() {		
            var api = $`/api/agent/profile`;
            var _this = this;

			axios.get(api).then(function(response) {
				_this.agents = response.data;
			})["catch"](function(error) {
				handleError(error);
			});
		},

		getAgents: function getAgents() {		
            var api = $`/api/agent/list`;
            var _this = this;

			axios.get(api).then(function(response) {
				_this.agent = response.data;
			})["catch"](function(error) {
				handleError(error);
			});
		},

        getReports: function getAgents() {		
            var api = $`/api/message/report`;
            var _this = this;

            // Manipulate Data

			axios.get(api).then(function(response) {
				_this.reports = response.data;
			})["catch"](function(error) {
				handleError(error);
			});
		},


        // ************************ Client Helper ************************ //


		getClients: function getClients() {		
            var api = $`/api/client/list`;
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
            var api = `/api/widget/settings`;
			var _this = this;

			axios.get(api).then(function(response) {
                _this.widget = response.data.widget;
                _this.widget.script = _this.widget.script;
			})["catch"](function(error) {
				handleError(error);
			});
		},


		updateSettings: function updateSettings(is) {
            var api = `/api/widget/update`;
			var _this = this;

			if (confirm('Are you sure you want to update the widget settings?')) {
				showLoader();
				axios.put(api, {

					widgetId: 1,
					name: _this.widget.widgetName,
					isActive: _this.widget.isActive,
					color: _this.widget.widgetColor,
					starttime: _this.widget.startTime, 
					endtime: _this.widget.endTime,
                    // timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
					
                    // Supply data to these properties
                    domainBanList: [],
                    cityBanList: [],
                    ipBanList: [],
                    countryBanList: []

				}).then(function(response) {
					showLoader(false);
					var res = response.data;

					alert('Settings has been updated successfully.');
				})["catch"](function(error) {
					handleError(error);
				});
			}
		},

        addBanList: function addBanList() {
            console.log(this.banInput);
            console.log(this.selectedBanKey);

            // TODO: Manipulate this and push it to API

            this.updateSettings();
        },

        removeBanItem: function removeBanItem(item) {
            console.log(item);

            // TODO: manipulate this and push it to API

            this.updateSettings();
        },


		// ************************ Message Helper ************************ //


		getMessages: function getMessages() {
			var _this = this;
            let api = `/api/message/getByClientId?clientId=${this.selectedClient.clientId}`;

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
            var isWhisperChecked = document.getElementById("isWhisperChecked")?.checked ?? false;
            var sendApi = `/api/message/send`;
            var _this = this;      

			if (this.isSubmitting || !((this.chatbox && this.chatbox != "") || this.file?.name != "")) {
				return;
			}

			var msg = {
                "clientId": this.clientId,
				"body": this.chatbox,
				"senderId": 0,
				"isWhisper": isWhisperChecked,
				"isAgent": true,
				"createddtm": Date.now().toISOString().slice(0, 19).replace('T', ' '),
				"attachment": {
                    "referenceId": 0,
                    "size": "",
                    "type": "",
                    "filename": ""
                }
			}; 


            // Handle plain message
            if (!(this.file && this.file?.name != "")) {
                
                this.chatbox = "";
                socket.emit('send-message', msg);
                this.messages.push(msg);
                scrollToBottom();
                
                return axios.post(sendApi, {

                    clientId: msg.clientId,
                    body: msg.body,          
                    senderId: msg.senderId,
                    isWhisper: msg.isWhisper,
                    isAgent: msg.isAgent

                }).then(function(response) {
                    _this.$forceUpdate();
                })["catch"](function(error) {
                    handleError(error);
                });
            }

            // Handle message with attachment
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