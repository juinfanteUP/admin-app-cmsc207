/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue').default;


// ***************** Update these Properties ***************** //

var socketioUrl = "";
var socket = "";

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
            img_src: 'assets/images/widget-icon.png',
            hasSchedule: false,
            starttime: '',
            endtime: '',
            script: '',
            banListEnabled: 'false',
            whiteListEnabled: 'false',
            scheduleEnabled: 'false'
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
        socketServerUrl: "", 


        // Client Ban
        clientBanList: [],
        searchClientBan: '',
        currentClientBanPage: 1,
        totalClientBanPage: 0, 
        totalClientBanRecord: 0,     
        skipCountClientBan: 10,

        // Allow components
        whiteList: [],
        whiteSelectionList: [
            {id: 'domain', labels:'Domain'}, {id: 'ipaddress', labels:'IP Address'}, 
            {id: 'country', labels:'Country'}, {id: 'city', labels:'City'}, 
        ],
        whiteInput: '',
        selectedWhiteKey: 'domain',

        // Schedule
        schedule: [],

        // Message Inputs
		chatbox: '',
		file: { name: '' },
		isSubmitting: false,
        messages: [],
        allMessages: [],
        unseenMessages: {},
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
        currentTime: '',
        searchMessage: '',
        currentHistoryPage: 1,
        totalHistoryPage: 0, 
        totalHistoryRecord: 0,     
        skipCountHistory: 10,
	},

	mounted: function mounted() {
        const params = new Proxy(new URLSearchParams(window.location.search), {
            get: (searchParams, prop) => searchParams.get(prop),
        });
        var api = `/api/widget/settings`;
        var _this = this;

        axios.get(api).then(function(response) {   
            socketioUrl = response.data.socket;
            socket = io(socketioUrl);

            _this.getProfile();	
            _this.getMessages();
            _this.TimeTrigger();
            _this.registerSocketServer();  
            
            if (params.id) {
                _this.getClients(params.id);
            }
            else {
                _this.setWidgetSettings(response.data);
                _this.getClients();               
                _this.getReports();
                _this.getClientBanList();   
            }
        })["catch"](function(error) {
            handleError(error);
        });
	},

	computed: {
        unseenMessagesCount: function unseenMessagesCount() {
            return this.unseenMessages;
        },

        resultMessageHistory: function resultMessageHistory() {
            var _this = this;
            let messages = this.allMessages;     

			if (this.searchMessage) {
                _this.currentHistoryPage= 1;
				messages = this.allMessages.filter((i)=>{
					return _this.searchMessage.toLowerCase().split(' ').every(function(v) {
						return i.body.toLowerCase().includes(v) || i.clientId.toLowerCase().includes(v);
					});
				});
			}

            _this.totalHistoryPage = Math.ceil(messages.length / _this.skipCountHistory);
            _this.totalHistoryPage = _this.totalHistoryPage <= 0 ? 1 : _this.totalHistoryPage;
            _this.totalHistoryRecord = messages.length;

            return  _.take(_.drop(messages, _this.skipCountHistory * ( _this.currentHistoryPage -1 )), _this.skipCountHistory);
        },


        resultClientBanList: function resultClientBanList() {
            var _this = this;
            let banList = this.clientBanList;     

			if (this.searchClientBan) {
                _this.currentClientBanPage= 1;
				banList = this.clientBanList.filter((i)=>{
					return _this.searchClientBan.toLowerCase().split(' ').every(function(v) {
						return i.clientId.toLowerCase().includes(v) || i.domain.toLowerCase().includes(v);
					});
				});
			}

            _this.totalClientBanPage = Math.ceil(banList.length / _this.skipCountClientBan);
            _this.totalClientBanPage = _this.totalClientBanPage <= 0 ? 1 : _this.totalClientBanPage;
            _this.totalClientBanRecord = banList.length;

            return  _.take(_.drop(banList, _this.skipCountClientBan * ( _this.currentClientBanPage -1 )), _this.skipCountClientBan);
        },


		resultClientSearch: function resultClientSearch() {
			var _this = this;

			if (this.searchClient) {
				return this.clients.filter((i)=>{
					return _this.searchClient.toLowerCase().split(' ').every(function(v) {
						return i.clientId?.toLowerCase().includes(v) || i.label?.toLowerCase().includes(v) || i.source?.toLowerCase().includes(v);
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
                _this.onlineClientIds.push({ clientId: clientId, willRemove: false });
                _this.reports.clientCount++;
                _this.getClients();
            });


            // Message from server
            socket.on('message', (msg) => {
                _this.reports.messageVolumeCount++;
                msg.created_at = new Date().toISOString().slice(0, 19).replace('T', ' ');
                msg.isSeen = false;
                
                _this.allMessages.push(msg);

                if (msg.clientId === _this.selectedClientId) {
                    _this.messages.push(msg);
                } else {
                    var ctr = 0;

                    if (isNaN(_this.unseenMessages.unseenCount)) {
                        _this.unseenMessages.unseenCount = 0;
                    }
                    _this.unseenMessages.unseenCount += 1;
                    _this.unseenMessages = {
                        "clientId": msg.clientId,
                        "unseenCount": _this.unseenMessages.unseenCount
                    };
                }

                let windowIndex = _.findIndex(_this.multiWindowList, (w) => { return w.clientId == msg.clientId });
                if(windowIndex>=0){
                    _this.multiWindowList[windowIndex].messages.push(msg);
                }

                let isMute = false;
                let clientIndex = _.findIndex(_this.clients, (c) => { return c.clientId == msg.clientId });
                if(clientIndex>=0){
                    _this.clients[clientIndex].missedCount++;
                    isMute =  _this.clients[clientIndex].isMute;
                }

                _this.$forceUpdate();
                scrollToBottom();

                $("#typing-client").text("");
                $("#istyping").text("");
            
                if (!isMute) {
                    let body = msg.attachmentId == "0" ? msg.body : "Attachment has been uploaded";
                    alertTitle(body);
                }        
            });

            socket.on('listen-client-type', (msg) => {   
                if (_this.selectedClientId == msg.clientId) {  
                    $("#istyping").text("Client is typing this: ");
                    $("#typing-client").text(msg.body);
                }

                if (!msg.isAgent) {
                    _this.addClientAsOnline(msg.clientId);
                }     
            });
		},

        addClientAsOnline: function (cid) {
            if (!this.isClientOnline(cid)) {
                this.onlineClientIds.push({ clientId: cid, willRemove: false });
                return
            }

            var ind = _.findIndex(this.onlineClientIds, (c)=> { return c.clientId == cid })
            this.onlineClientIds[ind].willRemove = false;
        },

        isClientOnline: function (cid) {
            return _.find(this.onlineClientIds, (c)=> { return c.clientId == cid }) != null;
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
                var canvas = document.getElementById("reportCanvas");
                if(canvas != null){
                    var ctx = document.getElementById("reportCanvas").getContext("2d");
                    window.myLine = new Chart(ctx, getChartConfig(_this.reports.historyList));
                }
			})["catch"](function(error) {
				handleError(error);
			});
		},


        // ************************ Client Helper ************************ //


		getClients: function getClients(defaultId=null) {		
            var api = `/api/client/list`;
            var _this = this;
            _this.clients = [];

			axios.get(api).then(function(response) {        
                $.getJSON( "/assets/js/flag.json", ( flags ) => { 
                    response.data.forEach(c => {
                        c.missedCount = 0;
                        c.flag  = _.find(flags, (f)=> { return f.keywords.indexOf(c.country.toLowerCase()) >= 0 })?.emoji ?? "🚩";
                        _this.selectClient(c);
                        _this.clients.push(c);
                    });
    
                    if (defaultId == null) {
                        _this.reports.clientCount = _this.clients.length;
                        if(_this.clients.length>0){
                           _this.selectClient(_this.clients[0]);
                        }
                    }
                    else {
                        var ind = _.findIndex(_this.clients, (c)=> { return c.clientId == defaultId});
                        if(ind >= 0){
                            _this.selectClient(_this.clients[ind]);
                        }  
                        else {
                            alert('Client does not exist. Window will now close.');
                            window.close();
                        }
                    }       
    
                    _this.$forceUpdate();
                });             
			})["catch"](function(error) {
				handleError(error);
			});
		},

        selectClient: function selectClient(client) {
            this.selectedClientId = client.clientId;   
            this.messages = [];
            var _this = this;
            this.unseenMessages.unseenCount = 0;

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
                source: client?.source,
				domain: client?.domain,
                country: client?.country,
                clientId: client?.clientId,
				city: client?.city,
                flag: client?.flag,
                createddtm: client?.createddtm,
                label: client?.label,
                notes: client?.notes
			};
            
			$('#view-client-modal').modal('show');
		},

        openChatWindow: function openChatWindow(client) {
            var url = `/chat?id=${client.clientId}`;
            window.open(url, "_blank");
        },

        updateClient: function updateClient() {
            var api = `/api/client/update`
            var _this = this;

            if(confirm('Are you sure you want to update this client?')) {
                axios.put(api, _this.viewClient).then(function() {

                    let ind = _.findIndex(_this.clients, (c) => { return c.clientId == _this.viewClient.clientId });   
                    if (ind>=0) {
                        _this.clients[ind].label = _this.viewClient.label;
                        _this.clients[ind].notes = _this.viewClient.notes;
                    }

                    alert('Client has been updated successfully!');
				})["catch"](function(error) {
					handleError(error);
				});
            }
        },

        controlClientMute: function controlClientMute (client) {
            var api = `/api/client/update`
            client.isMute = !client.isMute;

            axios.put(api, client).then(function() { })["catch"](function(error) {
                handleError(error);
            });
        },

        endClientSession: function endClientSession(client){
            var api = `/api/client/endSession`;
            var _this = this;
            
            if(confirm('Are you sure you want to end the session for this client?')) {
                socket.emit('end-session', client.clientId);

                let ind = _.findIndex(_this.clients, (c) => { return c.clientId == client.clientId });    
                let windowInd = _.findIndex(_this.multiWindowList, (c) => { return c.clientId == client.clientId });
                if(windowInd>=0) _this.multiWindowList.splice(windowInd, 1);
                if (ind>=0) _this.clients.splice(ind, 1);

                axios.put(api, { clientId: client.clientId }).then(function() {
                    _this.selectedClientId = 0;
                    _this.messages = [];
                    _this.allMessages = _.filter(_this.allMessages, function (c) { return c.clientiD != client.clientId; });
                    _this.$forceUpdate();
				})["catch"](function(error) {
					handleError(error);
				});
            }
        },


        // ************************ Client Ban Helper ************************ //


        getClientBanList: function getClientBanList () {
            var api = `/api/client/ban`;
            var _this = this;
            _this.clientBanList = [];

            axios.get(api).then(function(response) {
                response.data.forEach(c => {
                    c.created_at = new Date(c.created_at).toISOString().slice(0, 19).replace('T', ' ');
                    _this.clientBanList.push(c);
                });
            })["catch"](function(error) {
                handleError(error);
            });
        },

        banClient: function banClient(client) {
            var api = `/api/client/ban`
            var _this = this;

            if(confirm(`Are you sure you want to ban this client? The client's IP address, domain and country will be banned for future usage of the widget app.`)) {              
                axios.post(api, client).then(function() {
                    socket.emit('end-session', client.clientId);

                    let ind = _.findIndex(_this.clients, (c) => { return c.clientId == client.clientId });    
                    let windowInd = _.findIndex(_this.multiWindowList, (c) => { return c.clientId == client.clientId });
                    if(windowInd>=0) _this.multiWindowList.splice(windowInd, 1);
                    if (ind>=0) _this.clients.splice(ind, 1);

                    _this.selectedClientId = 0;
                    _this.messages = [];
                    _this.allMessages = _.filter(_this.allMessages, function (c) { return c.clientiD != client.clientId; });
                    _this.$forceUpdate();

                    _this.getClientBanList();
                    
                    alert('Client has been banned successfully!');        
				})["catch"](function(error) {
					handleError(error);
				});
            }
        },

        removeClientBan: function removeClientBan(clientId) {
            var api = `/api/client/ban`
            var _this = this;

            if(confirm('Are you sure you want to remove this client from the ban list?')) {
                axios.put(api, { clientId: clientId }).then(function() {

                    _this.getClientBanList();
                    alert('Client has been removed from the ban list.');        
				})["catch"](function(error) {
					handleError(error);
				});
            }
        },


		// ************************ Widget Helper ************************ //


        setWidgetSettings: function setWidgetSettings(result) {
            let _this = this;

            _this.socketServerUrl = result.socket;
            _this.widget = result.widget;
            _this.widget.script = result.script;
            _this.widget.domainBanList?.forEach(ban => _this.banList.push({ type: 'domain', value: ban })) ?? [];
            _this.widget.ipBanList?.forEach(ban => _this.banList.push({ type: 'ipaddress', value: ban })) ?? [];
            _this.widget.countryBanList?.forEach(ban => _this.banList.push({ type: 'country', value: ban })) ?? [];
            _this.widget.cityBanList?.forEach(ban => _this.banList.push({ type: 'city', value: ban })) ?? [];
            _this.widget.domainWhiteList?.forEach(white => _this.whiteList.push({ type: 'domain', value: white })) ?? [];
            _this.widget.ipWhiteList?.forEach(white => _this.whiteList.push({ type: 'ipaddress', value: white })) ?? [];
            _this.widget.countryWhiteList?.forEach(white => _this.whiteList.push({ type: 'country', value: white })) ?? [];
            _this.widget.cityWhiteList?.forEach(white => _this.whiteList.push({ type: 'city', value: white })) ?? [];
            _this.widget.schedule?.forEach(sched => _this.schedule.push({ value: sched })) ?? [];
            $('#color-picker').val(_this.widget.color); 

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
                    countryWhiteList: [],
                    banListEnabled: _this.widget.banListEnabled,
                    whiteListEnabled: _this.widget.whiteListEnabled,
                    scheduleEnabled: _this.widget.scheduleEnabled,
                    schedule: _this.widget.schedule
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
                      
               if (!validateExtension(_this.file?.name))
               {
                   alert("File extension is invalid.");
                   return;
               }

                 // Handle message with attachment
                let formData = new FormData();
                formData.append('file', _this.file);
                formData.append('document', JSON.stringify(msg));

                _this.isSubmitting = true;
                _this.$forceUpdate();

                return axios.post(sendApi, formData, {headers: { 'Content-Type': 'multipart/form-data'} })
                .then(function(response) {
                    let newMsg = response.data;
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


        addClientToMultiWindow: function addClientToMultiWindow (client) {
            let index = _.findIndex(this.multiWindowList, (w) => { return w.clientId == client.clientId });

            if (index >= 0) {
                return;
            }

            let msgs = _.filter(this.allMessages, (m) => { return m.clientId == client.clientId });

            let entity = {
                windowId: `mw-${client.clientId}`,
                label: `${client.flag}  ${client.label ? client.label : client.clientId}`,
                clientId: client.clientId,
                body: '',
                messages: msgs
            }
      
            socket.emit('join-room', {
                "room": client.clientId,
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

			setInterval(() => { 
                 inp = $("#chat-input").val(); 
                _this.chatbox = inp;
                _this.currentTime = new Date().toLocaleTimeString();
			}, 200);

            setInterval(() => { 
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

           setInterval(() => {
               let cidToDelete = [];

                _this.onlineClientIds.forEach(c => {
                    if (!c.willRemove) {
                        c.willRemove = true;
                    }
                    else {
                        cidToDelete.push(c.clientId);
                    }
                });  
                
                cidToDelete.forEach(id => {
                    let ind = _.findIndex(_this.onlineClientIds, (c)=> { return c.clientId == id });
                    if(ind >= 0) {
                        _this.onlineClientIds.splice(ind, 1);
                    }
                });
           }, 10000);
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

function alertTitle(msg){

    if (checkNotificationCompatibility() && Notification.permission === 'granted') {
        console.log('incoming message, creating notification')
        notify = new Notification("REACH", {
            icon: 'assets/images/brand/reach-64.png',
            body: msg
        });
    }

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

function validateExtension(fileName) {
    var exts = [".jpg", ".jpeg", ".bmp", "txt", "rar", "mp4", "mp3", "rar",
    ".gif", ".png", "doc", "docx", "xls", "xlsx", "js", "zip", "pdf", "ppt", "pptx",];
    return (new RegExp('(' + exts.join('|').replace(/\./g, '\\.') + ')$')).test(fileName);
}


function getChartConfig(reportList) {
    let dateList = [0];
    let messageCountList = [0];
    let clientCountList = [0];
    
    reportList.forEach(c => {
        dateList.push(c.date);
        messageCountList.push(c.messageVolumeCount);
        clientCountList.push(c.clientCount);
    });

    return {
        type: 'line',
        data: {
            labels: dateList,
            datasets: [{
                label: "Client Engagement Count",
                backgroundColor: "#4eac6d99",
                borderColor: "#4eac6d",
                data: clientCountList,
                fill: true,
            }, {
                label: "Message Volume",
                fill: false,
                backgroundColor: "#aaa",
                borderColor: "#aaa",
                data: messageCountList,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            tooltips: {
                mode: 'index',
                intersect: false,
            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                    }
                }]
            }
        }
    };
}


// request permission for notification
requestNotificationPermission();