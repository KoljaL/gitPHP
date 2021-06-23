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

// DATALIST
// https: //dev.to/siddev/customise-datalist-45p0
RepoURL.onfocus = function() {
    RepoURLs.style.display = "block";
    console.log("RepoURL");
    console.log(RepoURL);
    console.log(' ');
};

for (let option of RepoURLs.options) {
    option.onclick = function() {
        RepoURL.value = option.value;
        RepoURLs.style.display = "none";
        console.log("RepoURL");
        console.log(RepoURL);
        console.log(' ');
    };
}

RepoURL.oninput = function() {
    currentFocus = -1;
    var text = RepoURL.value.toUpperCase();
    for (let option of RepoURLs.options) {
        if (option.value.toUpperCase().indexOf(text) > -1) {
            option.style.display = "block";
        } else {
            option.style.display = "none";
        }
    }
};
var currentFocus = -1;
RepoURL.onkeydown = function(e) {
    if (e.keyCode == 40) {
        currentFocus++;
        addActive(RepoURLs.options);
    } else if (e.keyCode == 38) {
        currentFocus--;
        addActive(RepoURLs.options);
    } else if (e.keyCode == 13) {
        e.preventDefault();
        if (currentFocus > -1) {
            /*and simulate a click on the "active" item:*/
            if (RepoURLs.options) RepoURLs.options[currentFocus].click();
        }
    }
};

function addActive(x) {
    if (!x) return false;
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = x.length - 1;
    x[currentFocus].classList.add("active");
}

function removeActive(x) {
    for (var i = 0; i < x.length; i++) {
        x[i].classList.remove("active");
    }
}
// DATALIST




var debugcheck = document.getElementById("debugcheck");
debugcheck.addEventListener("click", function(event) {
    // console.log(debugcheck.checked)
    if (debugcheck.checked) {
        document.getElementById("debug").style.display = 'block';
    } else {
        document.getElementById("debug").style.display = 'none';

    }

});





// document.getElementById('RepoURL').setAttribute('placeholder', 'choose Repository');
















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