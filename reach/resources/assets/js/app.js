/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue').default;


// ***************** Update these Properties ***************** //


const socketioUrl = process.env.SOCKET_SERVER_URL;
const socket = io(socketioUrl);


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
            hasSchedule: false,
            starttime: '',
            endtime: '',
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
        socketServerUrl: socketioUrl, 

        // Allow components
        whiteList: [],
        whiteSelectionList: [
            {id: 'domain', labels:'Domain'}, {id: 'ipaddress', labels:'IP Address'}, 
            {id: 'country', labels:'Country'}, {id: 'city', labels:'City'}, 
        ],
        whiteInput: '',
        selectedWhiteKey: 'domain',

        // Message Inputs
		chatbox: '',
		file: { name: '' },
		isSubmitting: false,
        messages: [],
        allMessages: [],

        // Clients
        clients: [],
        onlineClientIds: [],
		searchClient: '',
        selectedClientId: 0,
        viewClient: {}
	},

	mounted: function mounted() {
        this.getProfile();
		this.getClients();
		// this.getAgents();
        this.getReports();
        this.getMessages();
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
						return i.domain.toLowerCase().includes(v);
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
		
        registerSocketServer: function registerSocketServer() {
			var _this = this;
           
            socket.on('client-join-room', (clientId) => {
                console.log(`client join room ${clientId}`);
                _this.onlineClientIds.push(clientId);
                _this.reports.clientCount++;
                _this.getClients();
            });
         
            // Message from server
            socket.on('message', (msg) => {
                console.log(msg);
                _this.reports.messageVolumeCount++;

                msg.created_at = new Date().toISOString().slice(0, 19).replace('T', ' ');

                _this.allMessages.push(msg);
                if (msg.clientId === _this.selectedClientId) {
                    _this.messages.push(msg);
                }
                _this.$forceUpdate();
                scrollToBottom();
            });

            socket.on('listen-client-type', (msg) => {
                console.log(msg.body);
            });
		},

        isClientOnline: function (cid) {
            return this.onlineClientIds.indexOf(cid) >= 0;
        },


        // ************************ Agent and Reports Helper ************************ //


		getProfile: function getProfile() {		
            var api = `/api/agent/profile`;
            var _this = this;

			axios.get(api).then(function(response) {
				_this.agent = response.data;
			})["catch"](function(error) {
				handleError(error);
			});
		},

		getAgents: function getAgents() {		
            var api = `/api/agent/list`;
            var _this = this;

			axios.get(api).then(function(response) {			
                _this.agents = response.data;
			})["catch"](function(error) {
				handleError(error);
			});
		},

        getReports: function getReports() {		
            var api = `/api/message/report`;
            var _this = this;

			axios.get(api).then(function(response) {
				_this.reports = response.data;
                 console.log(response.data)

			})["catch"](function(error) {
				handleError(error);
			});
		},


        // ************************ Client Helper ************************ //


		getClients: function getClients() {		
            var api = `/api/client/list`;
            var _this = this;
        
			axios.get(api).then(function(response) {
				_this.clients = response.data;
                _this.reports.clientCount = _this.clients.length;
                _this.$forceUpdate();
			})["catch"](function(error) {
				handleError(error);
			});
		},

        selectClient: function selectClient(client) {
            this.selectedClientId = client.clientId;   
            this.messages = [];

            socket.emit('join-room', {
                "room": this.selectedClientId,
                "clientId": "agent" //replace with agent id
            }); 

            this.allMessages.forEach(msg => {
                if(msg.clientId == this.selectedClientId){
                    this.messages.push(msg);
                }
            });

            this.$forceUpdate();
            scrollToBottom();
		},

		viewClientInfo: function viewClientInfo(client) {
			this.viewClient = {
				ipaddress: client?.ipaddress,
				domain: client?.domain,
                country: client?.country,
                clientId: client?.clientId,
				city: client?.city,
                createddtm: client?.createddtm
			};
			$('#view-client-modal').modal('show');
		},


		// ************************ Widget Helper ************************ //


		getWidgetSettings: function getWidgetSettings() {
            var api = `/api/widget/settings`;
			var _this = this;

			axios.get(api).then(function(response) {
                _this.widget = response.data.widget;
                _this.widget.script = response.data.script;
                _this.widget.domainBanList?.forEach(ban => _this.banList.push({ type: 'domain', value: ban })) ?? [];
                _this.widget.ipBanList?.forEach(ban => _this.banList.push({ type: 'ipaddress', value: ban })) ?? [];
                _this.widget.countryBanList?.forEach(ban => _this.banList.push({ type: 'country', value: ban })) ?? [];
                _this.widget.cityBanList?.forEach(ban => _this.banList.push({ type: 'city', value: ban })) ?? [];
                _this.widget.domainWhiteList?.forEach(white => _this.whiteList.push({ type: 'domain', value: white })) ?? [];
                _this.widget.ipWhiteList?.forEach(white => _this.whiteList.push({ type: 'ipaddress', value: white })) ?? [];
                _this.widget.countryWhiteList?.forEach(white => _this.whiteList.push({ type: 'country', value: white })) ?? [];
                _this.widget.cityWhiteList?.forEach(white => _this.whiteList.push({ type: 'city', value: white })) ?? [];
			})["catch"](function(error) {
				handleError(error);
			});
		},


		updateSettings: function updateSettings(action='', removeByIndex=-1) {
            var api = `/api/widget/update`;
			var _this = this;
            
            if(action == 'addBan'){         
                if(_this.banInput == null || _this.banInput == ''){
                    alert('Please provide a value that needs to be banned');
                    return;
                }

                switch(_this.selectedBanKey){
                    case 'domain':
                        if(!validateDomain(_this.banInput)){
                            alert('Please provide a valid domain name');
                            return;
                        }
                        break;
                    case 'ipaddress':
                        if(!validateIP(_this.banInput)){
                            alert('Please provide a valid IP Address');
                            return;
                        }
                        break;
                }
            }

            if(action == 'addWhite'){         
                if(_this.whiteInput == null || _this.whiteInput == ''){
                    alert('Please provide a value that needs to be allowed');
                    return;
                }

                switch(_this.selectedWhiteKey){
                    case 'domain':
                        if(!validateDomain(_this.whiteInput)){
                            alert('Please provide a valid domain name');
                            return;
                        }
                        break;
                    case 'ipaddress':
                        if(!validateIP(_this.whiteInput)){
                            alert('Please provide a valid IP Address');
                            return;
                        }
                        break;
                }
            }

			if (confirm('Are you sure you want to update the widget settings?')) {
                showLoader();
                switch(action){
                    case 'addBan':
                        _this.banList.push({ type: _this.selectedBanKey, value: _this.banInput });
                        break;
                    case 'removeBan':
                        _this.banList.splice(removeByIndex, 1);
                        break;
                    case 'addWhite':
                        _this.whiteList.push({ type: _this.selectedWhiteKey, value: _this.whiteInput });
                        break;
                    case 'removeWhite':
                        _this.whiteList.splice(removeByIndex, 1);
                        break;
                }

                var dataParams = {
					name: _this.widget.name,
					isActive: _this.widget.isActive,
					color: _this.widget.color,
                    img_src: _this.widget.img_src,
					hasSchedule: _this.widget.hasSchedule, 
					starttime: _this.widget.starttime, 
					endtime: _this.widget.endtime,
                    domainBanList: [],
                    cityBanList: [],
                    ipBanList: [],
                    countryBanList: [],
                    domainWhiteList: [],
                    cityWhiteList: [],
                    ipWhiteList: [],
                    countryWhiteList: []
				};
          
                _this.banList.forEach(ban => {
                    switch(ban.type){
                        case 'domain':
                            dataParams.domainBanList.push(ban.value);
                            break;
                        case 'ipaddress':
                            dataParams.ipBanList.push(ban.value);
                            break;
                        case 'country':
                            dataParams.countryBanList.push(ban.value);
                            break;
                        case 'city':
                            dataParams.cityBanList.push(ban.value);
                            break;
                    }
                });
				
                _this.selectedBanKey = '';        
				axios.put(api, dataParams).then(function(response) {
					showLoader(false);
					alert('Settings has been updated successfully.');
				})["catch"](function(error) {
					handleError(error);
				});

                _this.whiteList.forEach(white => {
                    switch(white.type){
                        case 'domain':
                            dataParams.domainWhiteList.push(white.value);
                            break;
                        case 'ipaddress':
                            dataParams.ipWhiteList.push(white.value);
                            break;
                        case 'country':
                            dataParams.countryWhiteList.push(white.value);
                            break;
                        case 'city':
                            dataParams.cityWhiteList.push(white.value);
                            break;
                    }
                });
				
                _this.selectedWhiteKey = '';     
                console.log(api);
                console.log(dataParams);   
				axios.put(api, dataParams).then(function(response) {
					showLoader(false);
					alert('Settings has been updated successfully.');
				})["catch"](function(error) {
					handleError(error);
				});
			}
		},

        copyWidgetScript: function copyWidgetScript() {  
            var dummy = document.createElement("textarea");
            document.body.appendChild(dummy);
            dummy.value = this.widget.script;
            dummy.select();
            document.execCommand("copy");
            document.body.removeChild(dummy);
            alert('Copied successfully!');
        },


		// ************************ Message Helper ************************ //


		getMessages: function getMessages() {
			var _this = this;
            let api = '/api/message/list';
            _this.reports.messageVolumeCount++;

			showLoader();
			axios.get(api).then(function(response) {    
				_this.allMessages = response.data;

                _this.allMessages.forEach(m => {
                    m.created_at = new Date(m.created_at).toISOString().slice(0, 19).replace('T', ' ');
                });

                showLoader(false);
			})["catch"](function(error) {
				handleError(error);
			});
		},

		postMessage: function postMessage() {
            var isWhisperChecked = document.getElementById("isWhisperChecked")?.checked ?? false;
            var sendApi = `/api/message/send`;
            var _this = this;      

			if (this.isSubmitting || !((this.chatbox && this.chatbox != "") || this.file?.name != "")) {
                return;
			}

			var msg = {
                "clientId": this.selectedClientId,
				"body": this.chatbox,
				"senderId": this.agent.agentId,
				"isWhisper": (isWhisperChecked).toString(),
				"isAgent": 'true',
                "created_at": new Date().toISOString().slice(0, 19).replace('T', ' ')
			}; 

            // Handle plain message
            if (!(this.file && this.file?.name != "")) {           
                this.chatbox = "";
                this.allMessages.push(msg);
                this.messages.push(msg);
                scrollToBottom();
                
                socket.emit('send-message', msg);
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

function validateIP(str) {
    return /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(str);
}  

function validateDomain(str) {
    return /\S+\.\S+/.test(str);
  }