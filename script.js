function sendCommands(values) {
    if (document.getElementById('RepoURL').value == '') {
        // alert('Please select a repository');
        eventFire(RepoURL, 'click');
        return;
    }
    loadingDots("console", true);
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
        var content = document.getElementById('console_output').innerHTML;
        document.getElementById('console_output').innerHTML = content + data;
        // scroll to bottom
        document.getElementById('console_output').scroll({
            top: 10000000,
            behavior: 'smooth'
        });
        // send last hidden debug array 
        var debug_div = document.getElementsByClassName('deb_resp');
        var last_debug_div = debug_div[debug_div.length - 1].innerHTML;
        document.getElementById('debug').innerHTML = last_debug_div;
        loadingDots("console", false);
    };
}



function FolderList() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", 'index.php?folderlist', true);
    xhr.send(null);
    xhr.onload = function() {
        var data = this.responseText;
        // add new options zo existing list
        var content = document.getElementById('RepoURLs').innerHTML;
        document.getElementById('RepoURLs').innerHTML = content + data;
        // hide the more... button
        document.querySelector('#ChooseRepoURL button').style.display = 'none';
        // click on input to initalize the new items
        eventFire(RepoURL, 'click');
    };
};






function DDDL() {
    // DATALIST
    // https: //dev.to/siddev/customise-datalist-45p0
    const DropDownDataLists = document.querySelectorAll("input.DropDownDataList");
    // console.log(DropDownDataLists);
    for (let i = 0; i < DropDownDataLists.length; i++) {
        DropDownDataLists[i].addEventListener("click", function() {
            var dddlInput = this;
            var dddlInputName = dddlInput.name;
            var dddlDatalist = document.getElementById(dddlInputName);
            dddlDatalist.style.display = "block";
            // console.log(' ');
            // console.log(' ');
            // console.log(dddlInputName)
            // console.log(dddlDatalist)
            // onFocus not needed, because of eventlistener()click?
            // dddlInput.onfocus = function() {
            // console.log("dddlInputonfocus");
            // };
            // console.log(' ');
            // console.log(dddlInput)
            // console.log(dddlInputName)
            // console.log(dddlDatalist)
            // console.log(dddlDatalist.options)

            // make option.value to input.value & hide option list
            for (let option of dddlDatalist.options) {
                option.onclick = function() {
                    dddlInput.value = option.value;
                    dddlDatalist.style.display = "none";
                    // console.log("option.onclick");
                    // console.log(option);
                    // console.log(' ');
                };
            }

            // hide datalist when mouse is leaving the list
            dddlDatalist.addEventListener('mouseleave', e => {
                dddlDatalist.style.display = "none";
            });

            dddlInput.oninput = function() {
                currentFocus = -1;
                var text = dddlInput.value.toUpperCase();
                // console.log("text");
                // console.log(text);
                // console.log(' ');
                for (let option of dddlDatalist.options) {
                    if (option.value.toUpperCase().indexOf(text) > -1) {
                        option.style.display = "block";
                    } else {
                        option.style.display = "none";
                    }
                }
            };
            var currentFocus = -1;
            dddlInput.onkeydown = function(e) {
                if (e.keyCode == 40) {
                    currentFocus++;
                    addActive(dddlDatalist.options);
                } else if (e.keyCode == 38) {
                    currentFocus--;
                    addActive(dddlDatalist.options);
                } else if (e.keyCode == 13) {
                    e.preventDefault();
                    if (currentFocus > -1) {
                        /*and simulate a click on the "active" item:*/
                        if (dddlDatalist.options) dddlDatalist.options[currentFocus].click();
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

        }); // addEventListener("click")
    } // for DropDownDataLists
    // DATALIST
}
DDDL();




// loadingDots("console", true);

function loadingDots(divID, switchOn) {
    // switch off
    if (switchOn == false) {
        // var spinningFrame = document.getElementById(divID);
        // spinningFrame.removeChild(document.getElementById("divLoadingFrame"));
        // spinningFrame.removeChild(document.getElementById("styleLoadingWindow"));
        // return;
    }
    // do nothing 
    if (document.getElementById("divLoadingFrame") != null) {
        return;
    }
    // load dots
    var spinningFrame = document.getElementById(divID);
    var style = document.createElement("style");
    style.id = "styleLoadingWindow";
    style.innerHTML = `
        // .loading-frame {position: relative;left: 0;right: 0;top: -50vh;z-index: 4;}
        .loading-frame {position: absolute;right: 0;top: 0;width: 100%;height: 100%;z-index: 4;}
        // .loading-frame {position: absolute;right: 50px;top: 50px;width: max-content;height: max-content;z-index: 4;}
        .loading-track {height: 50px;display: inline-block;position: absolute;top: calc(50% - 50px);left: 50%;}
        .loading-dot {height: 5px;width: 5px;border-radius: 100%;opacity: 0;}
        fieldset#console{background-color: rgba(0, 0, 0, 0.2);z-index:3;}
        .color0{background: #be5046;}
        .color1{background: #e06c75;}
        .color2{background: #d19a66;}
        .color3{background: #e6c07b;}
        .color4{background: #98c379;}
        .color5{background: #56b6c2;}
        .color6{background: #61aeee;}
        .color7{background: #c678dd;} 
        .loading-dot-animated {animation-name: loading-dot-animated;animation-direction: alternate;animation-duration: .75s;animation-iteration-count: infinite;animation-timing-function: ease-in-out;}
        @keyframes loading-dot-animated {from {opacity: 0;}to {opacity: 1;}}
    `
    spinningFrame.appendChild(style);
    var frame = document.createElement("div");
    frame.id = "divLoadingFrame";
    frame.classList.add("loading-frame");
    for (var i = 0; i < 10; i++) {
        var track = document.createElement("div");
        track.classList.add("loading-track");
        var dot = document.createElement("div");
        dot.classList.add("loading-dot");
        var j = Math.floor(Math.random() * 8);
        dot.classList.add("color" + j);
        track.style.transform = "rotate(" + String(i * 36) + "deg)";
        track.appendChild(dot);
        frame.appendChild(track);
    }
    spinningFrame.appendChild(frame);
    var wait = 0;
    var dots = document.getElementsByClassName("loading-dot");
    for (var i = 0; i < dots.length; i++) {
        window.setTimeout(function(dot) {
            dot.classList.add("loading-dot-animated");
        }, wait, dots[i]);
        wait += 150;
    }
};




// creates an event on an element eg 'click'
function eventFire(el, etype) {
    if (el.fireEvent) {
        el.fireEvent('on' + etype);
    } else {
        var evObj = document.createEvent('Events');
        evObj.initEvent(etype, true, false);
        el.dispatchEvent(evObj);
    }
}



var debugcheck = document.getElementById("debugcheck");
debugcheck.addEventListener("click", function(event) {
    // console.log(debugcheck.checked)
    if (debugcheck.checked) {
        document.getElementById("debug").style.display = 'block';
    } else {
        document.getElementById("debug").style.display = 'none';

    }

});





// // DATALIST
// // https: //dev.to/siddev/customise-datalist-45p0
// RepoURL.onfocus = function() {
//     RepoURLs.style.display = "block";
//     console.log("RepoURL");
//     console.log(RepoURL);
//     console.log(' ');
// };

// for (let option of RepoURLs.options) {
//     option.onclick = function() {
//         RepoURL.value = option.value;
//         RepoURLs.style.display = "none";
//         console.log("RepoURL");
//         console.log(RepoURL);
//         console.log(' ');
//     };
// }

// RepoURL.oninput = function() {
//     currentFocus = -1;
//     var text = RepoURL.value.toUpperCase();
//     for (let option of RepoURLs.options) {
//         if (option.value.toUpperCase().indexOf(text) > -1) {
//             option.style.display = "block";
//         } else {
//             option.style.display = "none";
//         }
//     }
// };
// var currentFocus = -1;
// RepoURL.onkeydown = function(e) {
//     if (e.keyCode == 40) {
//         currentFocus++;
//         addActive(RepoURLs.options);
//     } else if (e.keyCode == 38) {
//         currentFocus--;
//         addActive(RepoURLs.options);
//     } else if (e.keyCode == 13) {
//         e.preventDefault();
//         if (currentFocus > -1) {
//             /*and simulate a click on the "active" item:*/
//             if (RepoURLs.options) RepoURLs.options[currentFocus].click();
//         }
//     }
// };

// function addActive(x) {
//     if (!x) return false;
//     removeActive(x);
//     if (currentFocus >= x.length) currentFocus = 0;
//     if (currentFocus < 0) currentFocus = x.length - 1;
//     x[currentFocus].classList.add("active");
//     console.log("active");
//     console.log(x[currentFocus]);
//     console.log(' ');
// }

// function removeActive(x) {
//     for (var i = 0; i < x.length; i++) {
//         x[i].classList.remove("active");
//     }
// }
// // DATALIST







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