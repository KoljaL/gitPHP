function runhighlightInputField() {
    let highlightingStrings = ["REPONAME", "private", "public", "description", "main", "new commit"];
    let highlightingClass = 'highlightA'
    highlightInputField('HL_input', highlightingStrings, highlightingClass);
}





function sendCommands(command) {
    if (document.getElementById('RepoURL').value == '') {
        // alert('Please select a repository');
        eventFire(RepoURL, 'click');
        return;
    }

    // console.log(command.Command)


    loadingDots("rightFS", true);

    var params = new Object();
    // parameter start is used for the first acction afte choosing the repoURL from drop down menu
    if ('start' == command) {
        params.Command = "git config --get remote.origin.url ";
        params.State = "start";
    } else {
        params.State = "Command";
        params.Command = command.Command.value;

    }

    params.RepoURL = document.getElementById('RepoURL').value;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", 'index.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify(params));
    xhr.onload = function() {
        var data = this.responseText;
        var content = document.getElementById('ConsoleOutput').innerHTML;
        document.getElementById('ConsoleOutput').innerHTML = content + data;
        // scroll to bottom
        document.getElementById('ConsoleOutput').scroll({
            top: 10000000,
            behavior: 'smooth'
        });
        // send last hidden debug array 
        var debug_div = document.getElementsByClassName('deb_resp');
        var last_debug_div = debug_div[debug_div.length - 1].innerHTML;
        document.getElementById('LastCommand').innerHTML = last_debug_div;
        // stop playing loadingDots
        loadingDots("rightFS", false);
        hide_overlay();
    };

}

// sendCommand("<form><input name=Command value='git config --get remote.origin.url '></form>");

//
// hide GH_link overlay
//
function hide_overlay() {
    var overlay_div = document.getElementsByClassName('overlay');
    // console.log(overlay_div);
    for (let i = 0; i < overlay_div.length; i++) {
        // console.log(overlay_div[i]);
        overlay_div[i].addEventListener("click", function() {
            overlay_div[i].style.display = "none";
        });
    }
}


//
// calls index and gets an option list with folders (for select repository)
//
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
            document.getElementById('Items').innerHTML = data;
        };
        setTimeout(function() {
            runhighlightInputField();
        }, 300);
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
                    // call the first command
                    if ('RepoURL' == DropDownDataLists[i].id) {
                        sendCommands('start');

                    }

                };
            }

            // hide datalist when mouse is leaving the list
            dddlDatalist.addEventListener('mouseleave', e => {
                dddlDatalist.style.display = "none";
            });

            dddlInput.oninput = function() {
                currentFocus = -1;
                var text = dddlInput.value.toUpperCase();
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



//
// creates an event on an element eg 'click'
//
function eventFire(el, etype) {
    console.log(el);
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
var large = document.querySelector("#leftFS legend");
var div_content = document.getElementById("Content");
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
// show and hide the outputs in the right fieldset by clicking the labels
//
var HeaderRight = document.getElementById("HeaderRight");
// console.log(HeaderRight)
// make an array of all his children and add an eventlistener to them
Array.from(HeaderRight.children).forEach((child) => {
    child.addEventListener("click", function(event) {
        // get the DIV_ID of the element, that label was clickt for
        var OutputDIV_block = child.getAttribute("for");
        // same array again to hide all DIVs
        Array.from(HeaderRight.children).forEach((child_none) => {
            // get the DIV_IDs of the elements
            var OutputDIV_none = child_none.getAttribute("for");
            // hide all DIVs
            document.getElementById(OutputDIV_none).style.display = 'none';
        })
        if (OutputDIV_block == 'HistoryOutput') {
            getCommandHistory();
        }
        document.getElementById(OutputDIV_block).style.display = 'block';
    })
})


//
// send request to get the history log
//
function getCommandHistory() {
    var history_output = document.getElementById("HistoryOutput");
    var xhr = new XMLHttpRequest();
    xhr.open("GET", 'index.php?history', true);
    xhr.send(null);
    xhr.onload = function() {
        history_output.innerHTML = this.responseText;
        history_output.scroll({
            top: 10000000,
            behavior: 'smooth'
        });
    };
}









//
// resize both container horizontally 
//
const resizer = document.getElementById("ResizeGap");
const leftSide = resizer.previousElementSibling;
const rightSide = resizer.nextElementSibling;
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



// call the first function
//
// loops hj_framingDIV, finds behind_input & input
// copy input value to behind_input
// highlights 6 sets eventlistener
//
function highlightInputField(IClass, HKeywords, HClass) {
    var inputFields = document.getElementsByTagName('input');
    for (let i = 0; i < inputFields.length; i++) {
        // do only for choosen input fields 
        if (inputFields[i].classList.contains(IClass)) {
            let inputField_new = inputFields[i];
            // make a framing DIV arround the input tag
            var framingDIV = document.createElement('div');
            framingDIV.classList.add("hj_framingDIV");
            inputField_new.parentNode.insertBefore(framingDIV, inputField_new);
            framingDIV.appendChild(inputField_new);
            // add an emty DIV before the input tag, to insert the text 
            var behind_input_new = document.createElement('div');
            behind_input_new.classList.add("behind_input");
            framingDIV.insertBefore(behind_input_new, inputField_new);
        }
    }
    // get all framing DIVs
    var hj_framingDIV = document.getElementsByClassName('hj_framingDIV');
    for (let i = 0; i < hj_framingDIV.length; i++) {
        // get children of framingDIV 
        let behind_input = hj_framingDIV[i].children[0];
        let inputField = hj_framingDIV[i].children[1];
        if (inputField.classList.contains(IClass)) {
            // // do for the first view
            doHighlighting(behind_input, inputField, HKeywords, HClass);
            // set eventlistener for every event on a input field
            inputField.addEventListener("change", event => {
                doHighlighting(behind_input, inputField, HKeywords, HClass);
            });
            inputField.addEventListener("input", event => {
                doHighlighting(behind_input, inputField, HKeywords, HClass);
            });
            inputField.addEventListener("keydown", event => {
                doHighlighting(behind_input, inputField, HKeywords, HClass);
            });
            inputField.addEventListener("scroll", event => {
                doHighlighting(behind_input, inputField, HKeywords, HClass);
            });
        }
    }
    // on every event do the highlighting
    function doHighlighting(behind_input, inputField, HKeywords, HClass) {
        // set content of behind_input like value of inputField
        behind_input.innerHTML = inputField.value;
        // scroll behind_input like inputField 
        requestAnimationFrame(() => behind_input.scrollLeft = inputField.scrollLeft);
        // highlighting process
        caseSensitive = true;
        const flags = caseSensitive ? "gi" : "g";
        HKeywords.sort((a, b) => b.length - a.length);
        Array.from(behind_input.childNodes).forEach((child) => {
            const keywordRegex = RegExp(HKeywords.join("|"), flags);
            if (keywordRegex.test(child.textContent)) {
                const frag = document.createDocumentFragment();
                let lastIdx = 0;
                child.textContent.replace(keywordRegex, (match, idx) => {
                    const part = document.createTextNode(
                        child.textContent.slice(lastIdx, idx)
                    );
                    const highlighted = document.createElement("span");
                    highlighted.textContent = match;
                    highlighted.classList.add(HClass);
                    frag.appendChild(part);
                    frag.appendChild(highlighted);
                    lastIdx = idx + match.length;
                });
                const end = document.createTextNode(child.textContent.slice(lastIdx));
                frag.appendChild(end);
                child.parentNode.replaceChild(frag, child);
            }
        });
    }
}



















//          NOT IN USE really
//          NOT IN USE really
//          NOT IN USE really
//          NOT IN USE really
//          NOT IN USE really
//          NOT IN USE really
//          NOT IN USE really



//
// find all links in a string
//
function detectURLs(message) {
    var urlRegex = /(((https?:\/\/)|(www\.))[^\s]+)/g;
    return message.match(urlRegex)
}

//
// click link,will not open _blank target
function clickLink(link) {
    var cancelled = false;
    if (document.createEvent) {
        var event = document.createEvent("MouseEvents");
        event.initMouseEvent("click", true, true, window,
            0, 0, 0, 0, 0,
            false, false, false, false,
            0, null);
        cancelled = !link.dispatchEvent(event);
    } else if (link.fireEvent) {
        cancelled = !link.fireEvent("onclick");
    }
    if (!cancelled) {
        window.location = link.href;
    }
}












// //
// // DEBUG WINDOW
// // & show command history
// //
// var debug_label = document.getElementById("DebugLabel");
// var debug_output = document.getElementById("DebugOutput");
// // var hidden_debug = document.querySelector("#debug .responsedebug");
// var history_label = document.getElementById("HistoryLabel");
// var history_output = document.getElementById("HistoryOutput");


// debug_label.addEventListener("click", function(event) {
//     if (debug_output.style.display == 'none') {
//         // hide history output & set label fontweight        
//         history_output.style.display = 'none';
//         history_label.style.fontWeight = "400";
//         // show debug output
//         debug_output.style.display = 'block';
//         // debug_output.innerHTML = hidden_debug.innerHTML;
//         debug_label.style.fontWeight = "900";
//     } else {
//         debug_output.style.display = 'none';
//         // debug_output.innerHTML = '';
//         debug_label.style.fontWeight = "400";
//     }
// });
// //
// // show command history
// //
// history_label.addEventListener("click", function(event) {
//     if (history_output.style.display == 'none') {

//         var xhr = new XMLHttpRequest();
//         xhr.open("GET", 'index.php?history', true);
//         xhr.send(null);
//         xhr.onload = function() {
//             var data = this.responseText;
//             // hide debug output
//             debug_output.style.display = 'none';
//             debug_label.style.fontWeight = "400";
//             // show history output & fill with data & scroll down
//             history_output.style.display = 'block';
//             history_output.innerHTML = data;
//             history_output.scroll({
//                 top: 10000000,
//                 behavior: 'smooth'
//             });
//             history_label.style.fontWeight = "900";
//         };
//     } else {
//         history_output.style.display = 'none';
//         history_output.innerHTML = '';
//         history_label.style.fontWeight = "400";
//     }
// });






// // search for a GH_link in the console output
// var gh_link = detectURLs(data);
// // eventFire(document.getElementById('GH_link'), 'click');
// clickLink(document.getElementById('GH_link'));
// // window.open(gh_link[0],"_blank", "width=300, height=300");
// console.log(gh_link[0])





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