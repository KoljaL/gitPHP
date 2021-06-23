function sendCommands(values) {
    console.log(values);


    var params = new Object();
    params.CustomCommand = document.getElementById('CustomCommand').value;
    params.CommitMessage = document.getElementById('CommitMessage').innerText;
    params.RepoURL = document.getElementById('RepoURL').innerText;
    params.GitCommand = values;

    // console.log(document.getElementById('RepoURL'));

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
        document.getElementById('console_output').scroll({
            top: 1000,
            behavior: 'smooth'
        });



        var debug_div = document.getElementsByClassName('deb_resp');
        var last_debug_div = debug_div[debug_div.length - 1].innerHTML;
        document.getElementById('debug').innerHTML = last_debug_div;






    };
}



document.querySelector('[contenteditable]').addEventListener('paste', function(event) {
    console.log(event)

    event.preventDefault();
    document.execCommand('inserttext', false, event.clipboardData.getData('text/plain'));
});

document.querySelector('[contenteditable]').addEventListener('keypress', function(event) {
    if (event.which === 13) {
        console.log(event)
        event.preventDefault();
    }
});

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