function sendCommands(command) {
    if (document.getElementById('RepoURL').value == '') {
        // alert('Please select a repository');
        eventFire(RepoURL, 'click');
        return;
    }
    loadingDots("console", true);





    //
    // array for input field IDs
    //
    var InputIdValues = ['custom_command_inputID', 'commit_inputID', 'RepoURL'];

    var params = new Object();
    params.GitCommand = command;

    InputIdValues.forEach(InputId => {
        if (document.getElementById(InputId) !== null) {
            params[InputId] = document.getElementById(InputId).value;
        } else {
            params[InputId] = ' ';
        }
    });
    // console.log(params);
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
        // add new options to existing list
        var content = document.getElementById('RepoURLs').innerHTML;
        document.getElementById('RepoURLs').innerHTML = content + data;
        // hide the more... button
        document.querySelector('#ChooseRepoURL button').style.display = 'none';
        // click on input to initalize the new items
        eventFire(RepoURL, 'click');
    };
};




// change the preset with dropdown menu
var options = document.querySelectorAll("#Presets option");
options.forEach(option => {
    option.addEventListener("click", function(event) {
        var PresetValue = this.value;
        // console.log(PresetValue);
        var xhr = new XMLHttpRequest();
        xhr.open("GET", 'index.php?presetlist=' + PresetValue, true);
        xhr.send(null);
        xhr.onload = function() {
            var data = this.responseText;
            document.getElementById('items').innerHTML = data;
        };
    });
});


//  debugger;
//  console.trace('trace var');


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

            // make option.value to input.value & hide option list
            for (let option of dddlDatalist.options) {
                option.onclick = function() {
                    // console.log(dddlInput);
                    dddlInput.value = option.value;
                    dddlDatalist.style.display = "none";
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



//
// loadingDots("console", true);
//
function loadingDots(divID, switchOn) {
    // switch off
    if (switchOn == false) {
        var spinningFrame = document.getElementById(divID);
        spinningFrame.removeChild(document.getElementById("divLoadingFrame"));
        spinningFrame.removeChild(document.getElementById("styleLoadingWindow"));
        return;
    }
    // do nothing 
    if (document.getElementById("divLoadingFrame") != null) {
        return;
    }
    // load dots   background-color: rgba(0, 0, 0, 0.2);
    var spinningFrame = document.getElementById(divID);
    var style = document.createElement("style");
    style.id = "styleLoadingWindow";
    style.innerHTML = `
        // .loading-frame {position: relative;left: 0;right: 0;top: -50vh;z-index: 4;}
        .loading-frame {position: absolute;right: 0;top: 0;width: 100%;height: 100%;z-index: 4;}
        // .loading-frame {position: absolute;right: 50px;top: 50px;width: max-content;height: max-content;z-index: 4;}
        .loading-track {height: 50px;display: inline-block;position: absolute;top: calc(50% - 50px);left: 50%;}
        .loading-dot {height: 5px;width: 5px;border-radius: 100%;opacity: 0;}
        #console_output{z-index:3;filter: brightness(55%);}
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


//
// enlarge the page
//
var large = document.querySelector("#form legend");
var div_content = document.getElementById("content");
large.addEventListener("click", function(event) {
    // console.log(div_content.offsetWidth);
    // console.log(div_content.clientWidth);
    if (div_content.clientWidth == 1200) {
        div_content.style.maxWidth = "95%";
    } else {
        div_content.style.maxWidth = '1200px';
    }
});






//
// DEBUG WINDOW
//
var debug_label = document.getElementById("debug_label");
var debug_output = document.getElementById("debug_output");
var hidden_debug = document.querySelector("#debug .responsedebug");
debug_label.addEventListener("click", function(event) {
    if (debug_output.innerHTML == '') {
        // console.log(hidden_debug)
        debug_output.style.display = 'block';
        debug_output.innerHTML = hidden_debug.innerHTML;
    } else {
        debug_output.style.display = 'none';
        debug_output.innerHTML = '';
    }
});




//
// show command history
//
var history_label = document.getElementById("history_label");
var history_output = document.getElementById("history_output");
history_label.addEventListener("click", function(event) {
    if (history_output.innerHTML == '') {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", 'index.php?history', true);
        xhr.send(null);
        xhr.onload = function() {
            var data = this.responseText;
            // console.log(data);
        history_output.style.display = 'block';

            history_output.innerHTML = data;
        };

    } else {
        history_output.style.display = 'none';

        history_output.innerHTML = '';
    }
});





//
// resize both container horizontally 
//
const resizer = document.getElementById("resize_gap");
const leftSide = resizer.previousElementSibling;
const rightSide = resizer.nextElementSibling;
// let x = 0;
// let y = 0;
// let leftWidth = 0;
let leftWidth = x = y = 0;
const mouseDownHandler = function(e) {
    x = e.clientX;
    y = e.clientY;
    leftWidth = leftSide.getBoundingClientRect().width;
    document.addEventListener("mousemove", mouseMoveHandler);
    document.addEventListener("mouseup", mouseUpHandler);
};
const mouseMoveHandler = function(e) {
    // How far the mouse has been moved
    const dx = e.clientX - x;
    const dy = e.clientY - y;
    const newLeftWidth =
        ((leftWidth + dx) * 100) / resizer.parentNode.getBoundingClientRect().width;
    leftSide.style.width = `${newLeftWidth}%`;
    resizer.style.cursor = "col-resize";
    document.body.style.cursor = "col-resize";
    leftSide.style.userSelect = "none";
    leftSide.style.pointerEvents = "none";
    rightSide.style.userSelect = "none";
    rightSide.style.pointerEvents = "none";
};
const mouseUpHandler = function() {
    resizer.style.removeProperty("cursor");
    document.body.style.removeProperty("cursor");
    leftSide.style.removeProperty("user-select");
    leftSide.style.removeProperty("pointer-events");
    rightSide.style.removeProperty("user-select");
    rightSide.style.removeProperty("pointer-events");
    document.removeEventListener("mousemove", mouseMoveHandler);
    document.removeEventListener("mouseup", mouseUpHandler);
};
resizer.addEventListener("mousedown", mouseDownHandler);
//
// resize both container horizontally 
//





























//
// DEBUG WINDOW
//
// var debugcheck = document.querySelector("#console legend");
// var debugwindow = document.getElementById("debug");
// var debugconsole = document.getElementById("console_output");
// debugconsole.addEventListener("contextmenu", function(event) {
//     toggleDebug();
// });
// debugcheck.addEventListener("click", function(event) {
//     toggleDebug();
// });
// debugwindow.addEventListener("dblclick", function(event) {
//     toggleDebug();
// });
// function toggleDebug() {
//     if (debugwindow.style.display == 'none') {
//         debugwindow.style.display = 'block';
//     } else {
//         debugwindow.style.display = 'none';
//     }
// }
















// const left_side = document.getElementById("form");
// const right_side = document.getElementById("console");
// const resize_gap = document.getElementById("resize_gap");

// let m_pos;
// function resize(e){
//     console.log('resize');

//   const dx = m_pos - e.x;
//   m_pos = e.x;
//     console.log(dx);



//   left_side.style.width = (parseInt(getComputedStyle(left_side, '').width) - dx) + "px";
//   right_side.style.width = (parseInt(getComputedStyle(right_side, '').width) + dx) + "px";
// }

// resize_gap.addEventListener("mousedown", function(e){
//     console.log('mousedown');
//     console.log(e.offsetX);

//     m_pos = e.x;

//     document.addEventListener("mousemove", resize, false);
// }, false);

// document.addEventListener("mouseup", function(){
//     document.removeEventListener("mousemove", resize, false);
// }, false);




// var params = new Object();
// params.custom_command_inputID = document.getElementById('custom_command_inputID').value;
// params.commit_inputID = document.getElementById('commit_inputID').value;
// params.RepoURL = document.getElementById('RepoURL').value;
// params.GitCommand = values;



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