const btn = document.getElementById('selectText');

btn.addEventListener('click', function onClick(event) {

    const copied = document.getElementById('widgetScript');
    var range = document.createRange();
    range.selectNode(copied); //changed here
    window.getSelection().removeAllRanges(); 
    window.getSelection().addRange(range); 
    document.execCommand("copy");
    document.getElementById('widgetScript').style.backgroundColor="#06d6a0";
    window.getSelection().removeAllRanges();
    var delayInMilliseconds = 1000; //1 second
    setTimeout(function() {
    //your code to be executed after 1 second
    document.getElementById('widgetScript').style.backgroundColor="#f9f9f9";
    }, delayInMilliseconds);
    //alert("text copied");

});

// function copyData(containerid) {#f9f9f9
//     var range = document.createRange();
//     range.selectNode(containerid); //changed here
//     window.getSelection().removeAllRanges(); 
//     window.getSelection().addRange(range); 
//     document.execCommand("copy");
//     document.body.style.backgroundColor = 'green';
//     window.getSelection().removeAllRanges();
//   }


