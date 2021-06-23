function sendCommands(values) {
    // console.log(values);
    // console.log(document.getElementById('RepoURL').value);


    var params = new Object();
    params.CustomCommand = document.getElementById('CustomCommand').value;
    params.CommitMessage = document.getElementById('CommitMessage').value;
    params.RepoURL = document.getElementById('RepoURL').value;
    params.GitCommand = values;

    // console.log(document.getElementById('CommitMessage'));

    var xhr = new XMLHttpRequest();
    xhr.open("POST", 'index.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify(params));
    xhr.onload = function() {
        var data = this.responseText;
        // console.log(' ');
        // console.log(' JSON responseText');
        // console.log(data);
        var content = document.getElementById('console_output').innerHTML;
        document.getElementById('console_output').innerHTML = content + data;

        // jump to bottom
        // var objDiv = document.getElementById("console_output");
        // objDiv.scrollTop = objDiv.scrollHeight;

        // scroll to bottom
        document.getElementById('console_output').scroll({
            top: 10000000,
            behavior: 'smooth'
        });



        var debug_div = document.getElementsByClassName('deb_resp');
        var last_debug_div = debug_div[debug_div.length - 1].innerHTML;
        document.getElementById('debug').innerHTML = last_debug_div;






    };
}


// (function(win, doc) {
//     if (doc.querySelectorAll) {
//         var inputs = doc.querySelectorAll('input[list]'),
//             total = inputs.length;
//         for (var i = 0; i < total; i++) {
//             var input = inputs[i],
//                 id = input.getAttribute('list'),
//                 list = doc.getElementById(id),
//                 options = list.getElementsByTagName('option'),
//                 amount = options.length,
//                 rand = Math.floor(Math.random() * amount),
//                 option = options[rand],
//                 value = option.getAttribute('value');
//             input.setAttribute('placeholder', value);
//         }
//     }
// })(this, this.document);




var debugcheck = document.getElementById("debugcheck");
debugcheck.addEventListener("click", function(event) {
    // console.log(debugcheck.checked)
    if (debugcheck.checked) {
        document.getElementById("debug").style.display = 'block';
    } else {
        document.getElementById("debug").style.display = 'none';

    }

});





document.getElementById('RepoURL').setAttribute('placeholder', 'choose Repository');
















// var RepoURL = document.getElementById('RepoURL');
// if (!RepoURL.value) {
//     console.log(RepoURL);
//     document.getElementById('RepoURL').style.border = "red";
// }





// document.querySelector('[contenteditable]').addEventListener('paste', function(event) {
//     console.log(event)
//     event.preventDefault();
//     document.execCommand('inserttext', false, event.clipboardData.getData('text/plain'));
// });

// document.querySelector('[contenteditable]').addEventListener('keypress', function(event) {
//     if (event.which === 13) {
//         console.log(event)
//         event.preventDefault();
//     }
// });






// var debug_div = document.querySelectorAll('deb_resp');

// for (let i = 0; i < debug_div.length; i++) {
//     console.log(debug_div[i]);
// }



// var buttons = document.querySelectorAll("button");
// for (button of buttons) {
//     button.addEventListener("click", function(event) {
//         console.log(button)

//     });
// }