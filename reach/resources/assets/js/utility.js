module.exports = {
    
    alertTitle: function alertTitle(msg){

        if (this.checkNotificationCompatibility() && Notification.permission === 'granted') {
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
    },
    
    handleError: function handleError(e) {
        console.log(e);
        divLoader = document.getElementById("preloader");
        var nodes = divLoader.children;
        divLoader.style.display = 'none';
    
        for (var i = 0; i < nodes.length; i++) {
            nodes[i].style.display = 'none';
        }
    },
    
    scrollToBottom: function scrollToBottom() {
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
    },
    
    validateIP: function validateIP(str) {
        return /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(str);
    },
    
    validateDomain: function validateDomain(str) {
        return /\S+\.\S+/.test(str);
    },
    
    checkNotificationCompatibility: function checkNotificationCompatibility() {
        if (typeof Notification === 'undefined') {
            console.log("Notification is not supported by this browser");
            return false;
        }
        return true;
    },
    
    requestNotificationPermission: function requestNotificationPermission() {
        if (this.checkNotificationCompatibility()) {
            Notification.requestPermission(function(permission){
                console.log('notification permission: '+permission);
            })
        }
    },
    
    validateExtension: function validateExtension(fileName) {
        var exts = [".jpg", ".jpeg", ".bmp", "txt", "rar", "mp4", "mp3", "rar",
        ".gif", ".png", "doc", "docx", "xls", "xlsx", "js", "zip", "pdf", "ppt", "pptx",];
        return (new RegExp('(' + exts.join('|').replace(/\./g, '\\.') + ')$')).test(fileName.toLowerCase());
    },
    
    
    getChartConfig: function getChartConfig(reportList) {
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
  };



