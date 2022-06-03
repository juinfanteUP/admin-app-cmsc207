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
            startTime: '',
            endTime: '',
            script: '',
            img_src: 'assets/images/widget-icon.png'
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
        typingmsg: [],

        // Multiwindow
        multiWindowList: [],

        // Clients
        clients: [],
        onlineClientIds: [],
		searchClient: '',
        selectedClientId: 0,
        viewClient: {},
        allowedClientUpload: [],

        // Utilities
        currentTime: ''

	},

	mounted: function mounted() {
        this.getProfile();
		this.getClients();
		// this.getAgents();
        this.getReports();
        this.getMessages();
        this.TimeTrigger();
		this.getWidgetSettings();
       
        this.registerSocketServer();        
	},

	computed: {
		resultClientSearch: function resultClientSearch() {
			var _this = this;

			if (this.searchClient) {
				return this.clients.filter((i)=>{
					return _this.searchClient.toLowerCase().split(' ').every(function(v) {
						return i.clientId.toLowerCase().includes(v);
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
        },

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
                msg.isSeen = false;
                
                _this.allMessages.push(msg);

                if (msg.clientId === _this.selectedClientId) {
                    _this.messages.push(msg);
                }

                let windowIndex = _.findIndex(_this.multiWindowList, (w) => { return w.clientId == msg.clientId });
                if(windowIndex>=0){
                    _this.multiWindowList[windowIndex].messages.push(msg);
                }

                let clientIndex = _.findIndex(_this.clients, (c) => { return c.clientId == msg.clientId });
                if(clientIndex>=0 && _this.selectedClientId != msg.clientId){
                    _this.clients[clientIndex].missedCount++;
                }

                _this.$forceUpdate();
                scrollToBottom();

                $("#typing-client").text("");
                $("#istyping").text("");


            
                if (checkNotificationCompatibility() && Notification.permission === 'granted') {
                    console.log('incoming message, creating notification')
                    notify = new Notification("REACH", {
                        icon: 'assets/images/brand/reach-64.png',
                        body: msg.body
                    });
                }

                alertTitle();
            });

            socket.on('listen-client-type', (msg) => {   
                if (_this.selectedClientId == msg.isWhisper) {  
                    $("#istyping").text("Client is typing this: ");
                    $("#typing-client").text(msg.body);
                }
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
                window.location.href = "/login";
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
			})["catch"](function(error) {
				handleError(error);
			});
		},


        // ************************ Client Helper ************************ //


		getClients: function getClients() {		
            var api = `/api/client/list`;
            var _this = this;
        
			axios.get(api).then(function(response) {        
                _this.clients = [];
                response.data.forEach(c => {
                    c.missedCount = 0;
                    _this.clients.push(c)
                });

                _this.reports.clientCount = _this.clients.length;
                _this.$forceUpdate();
			})["catch"](function(error) {
				handleError(error);
			});
		},

        selectClient: function selectClient(client) {
            this.selectedClientId = client.clientId;   
            this.messages = [];
            var _this = this;

            socket.emit('join-room', {
                "room": this.selectedClientId,
                "clientId": "agent" //replace with agent id
            }); 

            this.allMessages.forEach(msg => {
                if(msg.clientId == this.selectedClientId){
                    msg.isSeen = true;
                    this.messages.push(msg);
                }
            });

            let clientIndex = _.findIndex(_this.clients, (c) => { return c.clientId == client.clientId });
            if(clientIndex>=0){
                _this.clients[clientIndex].missedCount = 0;
            }

            var api = `/api/message/setSeen`;
            axios.post(api, { clientId: client.clientId }).then(function() {
            })["catch"](function(error) {
                handleError(error);
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

        endClientSession: function endClientSession(clientId){
            var api = `/api/client/endSession`;
            var _this = this;
            
            if(confirm('Are you sure you want to end the session for this client?')) {
                socket.emit('end-session', clientId);

                let ind = _.findIndex(_this.clients, (c) => { return c.clientId == clientId });    
                let windowInd = _.findIndex(_this.multiWindowList, (c) => { return c.clientId == clientId });
                if(windowInd>=0) _this.multiWindowList.splice(windowInd, 1);
                if (ind>=0) _this.clients.splice(ind, 1);

                axios.post(api, { clientId: clientId }).then(function() {
				})["catch"](function(error) {
					handleError(error);
				});
            }
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
                $('#color-picker').val(_this.widget.color); 
                
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

                let rgb = document.getElementById("color-picker").value; 
                var dataParams = {
					name: _this.widget.name,
					isActive: _this.widget.isActive,
					color: rgb,
                    img_src: _this.widget.img_src,
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
				axios.put(api, dataParams).then(function(response) {
					showLoader(false);
					
				})["catch"](function(error) {
					handleError(error);
				});

                alert('Settings has been updated successfully.');
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
                console.log(response.data);

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

            if (!((this.chatbox && this.chatbox != "") || this.file?.name != "") || this.isSubmitting) {
                console.log('Disabled')
                return;
            }

			var msg = {
                "clientId": this.selectedClientId,
				"body": this.chatbox ?? "",
				"senderId": this.agent.agentId,
				"isWhisper": (isWhisperChecked).toString(),
				"isAgent": 'true',
                "attachmentId": '0',
                'fileName': '',
                'fileSize': 0,
                "created_at": new Date().toISOString().slice(0, 19).replace('T', ' ')
			}; 

            // Handle plain message
            if (_this.file && _this.file?.name != "") {      
                 // Handle message with attachment
                let formData = new FormData();
                formData.append('file', _this.file);
                formData.append('document', JSON.stringify(msg));

                _this.isSubmitting = true;
                _this.$forceUpdate();

                return axios.post(sendApi, formData, {headers: { 'Content-Type': 'multipart/form-data'} })
                .then(function(response) {
                    let newMsg = response.data;

                    console.log(newMsg);

                    msg.attachmentId = newMsg.attachmentId;
                    msg.fileName = newMsg.fileName;
                    msg.fileSize = newMsg.fileSize;

                    socket.emit('send-message', msg);

                    _this.isSubmitting = false;
                    _this.chatbox = "";
                    _this.messages.push(msg);
                    _this.allMessages.push(msg);

                    _this.cancelUpload();
                    scrollToBottom();

                }).catch(function(error) {
                    handleError(error);
                });
            }
                
            _this.chatbox = "";
            _this.allMessages.push(msg);
            _this.messages.push(msg);
            scrollToBottom();
            
            socket.emit('send-message', msg);
            _this.isSubmitting = true;
            return axios.post(sendApi, {

                clientId: msg.clientId,
                body: msg.body,          
                senderId: msg.senderId,
                isWhisper: msg.isWhisper,
                isAgent: msg.isAgent,
                attachmentId: '0'

            }).then(function(response) {

                _this.isSubmitting = false;

                _this.$forceUpdate();
            })["catch"](function(error) {
                handleError(error);
            });
		},


        // ***************************** UI Component Controls ***************************** //


        addClientToMultiWindow: function addClientToMultiWindow (clientId) {
            let index = _.findIndex(this.multiWindowList, (w) => { return w.clientId == clientId });

            if (index >= 0) {
                return;
            }

            let msgs = _.filter(this.allMessages, (m) => { return m.clientId == clientId });

            let entity = {
                windowId: `mw-${clientId}`,
                label: clientId,
                clientId: clientId,
                body: '',
                messages: msgs
            }
      
            socket.emit('join-room', {
                "room": clientId,
                "clientId": "agent"
            }); 

            this.multiWindowList.push(entity);
        },

        removeClientFromWindow: function removeClientFromWindow (clientId) {
            let index = _.findIndex(this.multiWindowList, (w) => { return w.clientId == clientId });
            if (index >= 0) {
                this.multiWindowList.splice(index, 1);
            }
        },

        sendMessageFromMultiChat: function sendMessageFromMultiChat(w) {
            var _this = this;
            var sendApi = `/api/message/send`;
            
            if (w.body == ""){
                return;
            }

            var msg = {
                "clientId": w.clientId,
				"body": w.body ?? "",
				"senderId": _this.agent.agentId,
				"isWhisper": 'false',
				"isAgent": 'true',
                "attachmentId": '0',
                "attachmentId": '0',
                'fileName': '',
                "created_at": new Date().toISOString().slice(0, 19).replace('T', ' ')
			}; 

            _this.allMessages.push(msg);
            w.messages.push(msg);
            socket.emit('send-message', msg);
            w.body = "";
            
            scrollToBottom();

            return axios.post(sendApi, {
                clientId: msg.clientId,
                body: msg.body,          
                senderId: msg.senderId,
                isWhisper: msg.isWhisper,
                isAgent: msg.isAgent,
                attachmentId: '0'
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

		downloadAttachment: function downloadAttachment(id) {
			window.open(`/api/message/download?id=${id}`, '_blank');
		},

        formatBytes: function formatBytes(bytes) {
            if (!(bytes || bytes > 0)) return '0 Bytes';    
                                  
            const k = 1024;
            const i = Math.floor(Math.log(bytes) / Math.log(k));
        
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'][i];
        },


		// ************************ Utility Functions ************************ //


		TimeTrigger: function TimeTrigger() {
			var _this = this;
			setInterval(function() { 
                 inp = $("#chat-input").val(); 
                _this.chatbox = inp;
                _this.currentTime = new Date().toLocaleTimeString();
			}, 200);

            setInterval(function() { 
                var allowUpload = document.getElementById("allow-client-upload")?.checked ?? false;
                var count = _this.allowedClientUpload.length;
                let newCount = _this.allowedClientUpload.length;

                if (_this.selectedClientId != 0) {
                    var ind = _this.allowedClientUpload.indexOf(_this.selectedClientId);

                    if (allowUpload && ind == -1) {
                        _this.allowedClientUpload.push(_this.selectedClientId);
                    }
                    else if (!allowUpload && ind >=0 ) {
                        _this.allowedClientUpload.splice(ind, 1);
                    }

                    newCount = _this.allowedClientUpload.length;
                    if (newCount != count) {
                        socket.emit('allow-upload', { willAllow: allowUpload, clientId: _this.selectedClientId});
                    }
               }
           }, 2000);
		},

        // 
	}
});


// *********** Helper Methods *********** //


function showLoader(willShow = true) {
    let loader = document.getElementById("loader");
    if (loader){
        loader.style.display = willShow ? 'block' : 'none';
    }
}

function alertTitle(){
    var c = 1;
    var i = setInterval(function(){
        document.title =  c % 2 == 0 ? "New Message!" : "Reach App";
        c ++;
    } ,1000);
    setTimeout(function( ) { 
        clearInterval(i); 
        document.title = "Reach App";
    }, 10000);
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

        var multiWindow = $(".chat-history");
        if (multiWindow){
            $(".chat-history").stop().animate({
                scrollTop: $(".chat-history")[0]?.scrollHeight
            }, 1000);
        }
    }, 200);
}

function validateIP(str) {
    return /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(str);
}  

function validateDomain(str) {
    return /\S+\.\S+/.test(str);
  }

function checkNotificationCompatibility() {
    if (typeof Notification === 'undefined') {
        console.log("Notification is not supported by this browser");
        return false;
    }
    return true;
}

function requestNotificationPermission() {
    if (checkNotificationCompatibility()) {
        Notification.requestPermission(function(permission){
            console.log('notification permission: '+permission);
        })
    }
}


// request permission for notification
requestNotificationPermission();