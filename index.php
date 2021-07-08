<?php
ini_set( 'display_errors', 1 );
ini_set( 'display_startup_errors', 1 );
error_reporting( E_ALL );

//
// include configfile
//
require_once __DIR__.'/config.php';


//
// include file with SSH login, not included in git repo
// just to keep my password secret
//
if(is_file(__DIR__.'/pw.php')){
    $pw = require_once __DIR__.'/pw.php';
    $resp = array_merge($resp,$pw);
}


//
// include phpseclib.com
//
// require_once __DIR__.'/vendor/autoload.php';// was for phpseclib2 installed with composer
// use phpseclib3\Net\SSH2;// was for phpseclib2 installed with composer
set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib');
include('Net/SSH2.php');


//
// include functions
//
require_once __DIR__.'/function.php';


//======================================================================
//
//                               FUNCTIONS
//
//======================================================================


//
// sesssion management (login)
//
session( $resp );


//
// ASCNC load another preset
//
if ( isset( $_GET['presetlist'] ) ) {
    makeItemsForCommands( $resp, $preset = $_GET['presetlist'] );
    exit;
}


//
// read parameter for repo & command from URL & execute
//
commandOverGet($resp);


//
// ASYNC if POST data in JSON are available
//
ASreadPOSTandExecuteCommand( $resp );


//
// ASYNC read the history.log file & make a nice output
//
getHistoryLog();


//
// ASYNC function to scall parent folder
// and add folders as list of options to dropdown menu
//
ASscanFolderList();


//
// choose favivon
//
$icon = get_icon();


//======================================================================
//
//                               HTML
//
//======================================================================
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charSet="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Git via PHP</title>
    <meta name="description" content="Git over PHP" />
    <?=$icon ?>
    <link rel="stylesheet" href="style.css">
    <style type="text/css">
    </style>
</head>
<body>
    <!-- DEBUG OUTPUT -->
    <div id="debug" style="display:none"></div>
    <!-- CONTENT -->
    <div id="Content">
        <fieldset id=leftFS>
            <legend>Git Actions</legend>
            <!-- HEADER -->
            <div id=HeaderLeft class=item>
                <!-- LOGOUT -->
                <div id="Logout">
                    <form action='' method='post'>
                        <input id=LogoutSubmit type='submit' name='destroy' style="display:none">
                        <label id=LogoutLogo title="Log Out" for=LogoutSubmit>&nbsp;</label>
                    </form>
                </div>

                <!-- PRESETS -->
                <div id=SelectPreset>
                    <input autocomplete="off" class=DropDownDataList role="combobox" list="" id="Preset" name="Presets" placeholder="Select Preset">
                    <datalist id="Presets" class="DDDL_small" role="listbox">
                        <?php echo makeOptionList( $resp['presets'] ); ?>
                    </datalist>
                </div>
                <!-- ChooseRepoURL -->
                <div id=ChooseRepoURL>
                    <input autocomplete="off" class=DropDownDataList role="combobox" list="" id="RepoURL" name="RepoURLs" placeholder="Select Repository">
                    <datalist id="RepoURLs" role="listbox">
                        <?php echo makeOptionList( $resp['preselected_folder'] ); ?>
                        <button onclick="FolderList();">scan folder</button>
                    </datalist>
                </div>

            </div><!-- HEADER -->
            <!-- COMMAND ITEMS -->
            <div id="Items">
                <?php makeItemsForCommands( $resp ); ?>
            </div>
        </fieldset>
        <div id=ResizeGap></div>
        <!-- CONSOLE -->
        <fieldset id=rightFS>
            <legend>Console</legend>
            <div id=HeaderRight class=item>
                <label for=ConsoleOutput id=ConsoleLabel title="console output"></label>
                <label for=HistoryOutput id=HistoryLabel title="command history"></label>
                <label for=DebugOutput id=DebugLabel title="config file"></label>
            </div>
            <div id="ConsoleOutput"><span>Select a repository to connect to the server</span></div>
            <div id="DebugOutput" style="display:none">
                <div id=LastCommand></div>
                <div class=responsedebug>
                    <h3 style='color:var(--bluegreen)'>&#36;config[]</h3><br>
                    <pre>
                        <?= pprint( $resp ); ?>
                    </pre>
                </div>
            </div>
            <div id="HistoryOutput" style="display:none"></div>
        </fieldset>
    </div>

    <script src="script.js"></script>
</body>
</html>