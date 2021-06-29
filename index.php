<?php

// input rewrite

ini_set( 'display_errors', 1 );
ini_set( 'display_startup_errors', 1 );
error_reporting( E_ALL );

//
// include password & configfile
//
require_once __DIR__.'/pw.php';
require_once __DIR__.'/config.php';

// echo is_dir('../AHA/.git');
// exit;
//
// include phpseclib.com
//
require_once __DIR__.'/vendor/autoload.php';
use phpseclib3\Net\SSH2;

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
// get favivon
//
$icon = get_icon();

//
// sesssion management (login)
//
session( $resp );

//
//
// ASCNC load another preset
if ( isset( $_GET['presetlist'] ) ) {
    makeItemsForCommands( $resp, $preset = $_GET['presetlist'] );
    exit;
}

// Command => git config --get remote.origin.url

// RepoURL => gitPHP

// abs_path => /www/htdocs/w01c010a/dev.rasal.de

if (isset($_GET)){

    $RepoURL = key($_GET);
    $Command = $_GET[$RepoURL];
    $Command = $resp['commands'][$Command]['command'];
    
    $get_array = array(
        'Command' => $Command,
        'RepoURL' => $RepoURL,
        'abs_path' => dirname(dirname(__FILE__)),
    );
        // print_r($get_array);
        execPHP( $get_array );
}


//
// ASYNC if POST data in JSON are available
//
ASreadPOSTandExecuteCommand( $resp );

/**
 *
 *
 * FUNCTIONS
 *
 *
 */

//
// if POST data in JSON are available
//
function ASreadPOSTandExecuteCommand( &$resp ) {
    if ( !empty( json_decode( file_get_contents( 'php://input' ), true ) ) ) {
        $post_array = json_decode( file_get_contents( 'php://input' ), true );
        $post_array['abs_path'] = $resp['abs_path'];
        execPHP( $post_array );
        exit;
    }
}

//
// execute SSH commands & echo console output & write CommandHistory
//
function execPHP( $post_array ) {
    global $resp;    // make SSH connection
    global $host, $user, $password; // just fur development
    $ssh = new SSH2( $host );
    if ( !$ssh->login( $user, $password ) ) {
        throw new \Exception( 'Login failed' );
    }

    // send hidden data for debugging
    echo "<div class=deb_resp style='display:none;'>";
    print_resp( $post_array );
    echo '</div>';

    // execute SSH
    $output = $ssh->exec( 'cd '.$post_array['abs_path'].'/'.$post_array['RepoURL'].' && '.$post_array['Command'] );
    // $output = 'cd '.$post_array['abs_path'].'/'.$post_array['RepoURL'].' && '.$post_array['Command'];

    // search for error strings, in cade add error class
    foreach ( $resp['ConsoleError'] as $value ) {
        if ( str_contains( $output, $value ) ) {
            $error = 'error';
            break; // one error is enough to leave the foreach loop
        } else {
            $error = '';
        }
    }

    // git add . && git commit -m "rewrite the item input" && git push origin input_rewrite 
    $gh_link_identi = 'To github.com:';
    $gh_link_tail = '.git';
    $optputs_parts = explode("\n", $output);

    // search for USER/REPONAME and extract it
    foreach ($optputs_parts as $key => $value) {
        if ( str_contains($value , $gh_link_identi) ) {
            $gh_link = $value;
            $gh_link = str_replace($gh_link_identi,'',$gh_link);
            $gh_link = str_replace($gh_link_tail,'',$gh_link);
            break;
        } else {
            $gh_link = '';
        }
    }

    // prepare var for heredoc
    $output_command = $post_array['Command'];
    // console output
    echo <<<HTML
        <div class=response>
            <span>$post_array[RepoURL]:</span>
            $output_command\n
            <pre class="$error">$output</pre>
        </div>
        HTML;

        // GH_link overlay
        if(!empty($gh_link)){
            echo <<< HTML
            <div class=overlay>
            <div class=GH_link_text>
                <h2>push successful</h2>
                visit remote repo: <a href="https://github.com/$gh_link" id=GH_link target="_blank">$gh_link</a>
            </div>
        </div>
        HTML;
    }


    // write command in logfile
    CommandHistory( $post_array );
}

//
// read parent folder & make an option list
//
function ASscanFolderList() {
    if ( isset( $_GET['folderlist'] ) ) {
        $folder = glob( '../*', GLOB_ONLYDIR );
        foreach ( $folder as $key => $value ) {
            if (is_dir('../'.basename($value).'/.git')){
                $rep_exists = 'repo';
            }else{
                $rep_exists = '';
            }
            echo "<option value='".basename( $value )."' class='".$rep_exists."'>".basename( $value )."</option>\n";
        }
        exit;
    }
}

//
// make an option list from preselected_folder[] // was $resp['preselected_folder']
//
function makeOptionList( $array ) {
    // print_r($array);
    $option = '';
    foreach ( $array as $key => $value ) {
        if ( !is_array( $value ) ) {
            // $key = array_flip($key);
            $key = $value;
            // print_r($value);
        }
        $option .= "<option value='".basename( $key )."'>".basename( $key )."</option>\n";
    }
    return $option;
}

//
// write command log to file
//
function CommandHistory( $post_array ) {
    global $ssh, $resp;
    $filecontent = ( file_exists( 'history.log' ) ) ? file_get_contents( 'history.log' ) : '';
    $filecontent = $filecontent."\n".date( 'd.m.Y H:i' )." 'cd ".$post_array['abs_path']."/".$post_array['RepoURL']." && ".$post_array['Command']."'";

    file_put_contents( 'history.log', $filecontent );
}

//
// choose randomly between two differend icons
//
function get_icon() {
    if ( rand( 0, 1 ) ) {
        $icon = <<<HTML
            <link rel="icon" type="image/png" sizes="32x32" href="data:image/png;base64,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" />
        HTML;
    } else {
        $icon = <<<HTML
            <link rel="icon" type="image/png" sizes="32x32" href="data:image/png;base64,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" />
        HTML;
    }
    return $icon;
}

//
// nicely print ann aray for debugging
//
function print_resp( $resp ) {
    echo "<div class=responsedebug>
        <h3 style='color:var(--orange)'>Debug: &#36;resp[]</h3><br>
        <pre>";
    pprint( $resp );
    echo '</pre>
    </div>';
}

//
// smallest prettyprint i ever done :-)
//
function pprint( $array ) {
    $array_str = print_r( $array, true );
    $lines     = explode( "\n", $array_str );
    foreach ( $lines as $line ) {
        $line = str_replace( ['Array', '(', ')'], '', $line );
        $line = str_replace( '[', '<span style="color: var(--orange)">', $line );
        $line = str_replace( ']', '</span>', $line );
        if ( trim( $line ) != '' ) {
            $indentation = ( strlen( $line ) - strlen( ltrim( $line ) ) ) / 4;
            $space       = '';
            for ( $i = 1; $i < $indentation / 2; $i++ ) {
                $space .= '    ';
            }
            echo "<pre style='margin:0'>".$space.trim( $line ).'</pre>';
        }
    }
}
 

/**
 *
 *
 *                  HTML
 *
 *
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charSet="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Git via PHP</title>
    <meta name="description" content="Git over PHP" />
    <script src="https://cdn.jsdelivr.net/gh/tunguskha/debugCSS@latest/src/debugcss.min.js"></script>
    <?=$icon ?>
    <!--
    <link rel="preconnect" href="https://fonts.rasal.de" crossorigin />
    <link rel="preload" as="style" href="https://fonts.rasal.de/?font=LibreBaskerville,Sentinel,OldStandardTT,NewComputerModern,NewComputerModern,JetBrainsMono" />
    <link rel="stylesheet" href="https://fonts.rasal.de/?font=LibreBaskerville,Sentinel,OldStandardTT,NewComputerModern,NewComputerModern,JetBrainsMono" media="print" onload="this.media='all'" />
    -->
    <link rel="stylesheet" href="style.css">
    <style type="text/css">
    /* < ?php echo file_get_contents('style.css')?> */
    </style>
</head>
<body>
    <!-- DEBUG OUTPUT -->
    <div id="debug">
        <?=print_resp( $resp ) ?>
    </div>
    <!-- CONTENT -->
    <div id="content">
        <fieldset id=form>
            <legend>Git Actions</legend>
            <!-- HEADER -->
            <div id=header class=item>
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
                <!-- LOGOUT -->
                <div id="logout">
                    <form action='' method='post'>
                        <input type='hidden' name='destroy'>
                        <input id=logout_submit type='submit' value='Log Out' style="display:none">
                        <label id=logoutButton for=logout_submit class=button>Log&nbsp;Out</label>
                    </form>
                </div>
            </div><!-- HEADER -->
            <!-- COMMAND ITEMS -->
            <div id="items">
                <?php makeItemsForCommands( $resp ); ?>
            </div>
        </fieldset>
        <div id=resize_gap></div>
        <!-- CONSOLE -->
        <fieldset id=console>
            <legend>Console</legend>
            <div id=console_header>
                <label id=history_label>command history</label>
                <label id=debug_label>config file</label>
            </div>
            <div id="console_output"></div>
            <div id="debug_output" style="display:none"></div>
            <div id="history_output" style="display:none"></div>
        </fieldset>
    </div>

    <script>
    // < ?php echo file_get_contents('script.js')?>
    // debugCSS({
    //     selector: "#console div",
    //     not: ".logo"
    // });
    </script>
    <script src="script.js"></script>
</body>
</html>

<?php

//
// read the history.log file & make a nice output
//
function getHistoryLog() {
    if ( isset( $_GET['history'] ) ) {

        $abs_path = dirname(dirname(__FILE__));
        $file  = file_get_contents( 'history.log' );
        $lines = explode( "\n", $file );
        foreach ( $lines as $key => $line ) {
            // split line
            $part    = explode( "'", $line );
            $date    = $part[0];
            $command = str_replace( $date, '', $line );
            // get reponame
            $repo_part = explode(" ", $command);
            $repo = basename($repo_part[1]);
            // remove "cd /www..."
            $part_command = explode('&&', $command);
            $command_short = str_replace( $part_command[0], '', $command );
            $command_short = substr($command_short,2);

            echo '<span class=date>'.$date.'</span><span class=repo>'.$repo.'</span><span title="'.htmlspecialchars($command).'" class=command>'.$command_short.'</span><br>';
        }
        exit;
    }
}

/**
 *
 * makes for every command in config file an item on frontend
 *
 */
function makeItemsForCommands( $resp, $preset = 'start' ) {
    if ( 'start' == $preset ) {
        $html = <<< HTML
            <div class="item startpage">
                <h3>bla bla info text</h3>
                <div class="text">
                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                </div>
            </div>
            <div class="item startpage">
                <h3>Presets</h3>
                <div class="text">
                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                </div>
            </div>
            <div class="item startpage">
                <h3>Repositotries</h3>
                <div class="text">
                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                </div>
            </div>
        HTML;
    } elseif ( 'all' == $preset ) {
        $commandlist = $resp['commands'];
    } else {
        $presets  = $resp['presets'][$preset];
        $commands = $resp['commands'];
        // flip values to keys
        $presets = array_flip( $presets );
        // make blacklist  - remove presets from commands
        $blacklist = array_diff_key( $commands, $presets );
        // make whitelist - remove blacklist from commands
        $commandlist = array_diff_key( $commands, $blacklist );
        // sort commandlist in preset order
        $commandlist = array_merge($presets, $commandlist);
        // print_r($commandlist);
        // exit;
    }

    // create item for every command
    if ( 'start' !== $preset ) {
        $html = '';
        $no = 0;
        foreach ( $commandlist as $c_name => $c_value ) {
            $title   = $c_value['title'];
            $link    = ( !empty( $c_value['infolink'] ) ) ? '<a href="$c_value[infolink]" target="_blanc"></a>' : '';
            $button  = htmlspecialchars( $c_value['button'] );
            $command  = htmlspecialchars( $c_value['command'] );
            $no++;

            //
            // make item for every command
            //
            $html .= <<<HTML
            <div class=item>
                <h3>$title</h3>
                <div class=text>
                    $c_value[text]
                    $link
                </div>
                <form action="javascript:;" onsubmit="sendCommands(this)" id="$no">
                    <input type="text" id="Command" value="$command" name="Command">
                    <button type="submit" form="$no" value="Submit">$button</button> 
                </form>
            </div>
            HTML;
        }
    }
    echo $html;
}

/**
 *
 * session management
 *
 */
function session( &$resp ) {
    session_start();
    // print_r($_SESSION);
    // check userdata and create session
    $session_timeout = 60 * $resp['session_time'];
    // seconds * minutes
    if ( !isset( $_SESSION['last_visit'] ) ) {
        $_SESSION['last_visit'] = time();
    }
    // check login values & create session
    if ( isset( $_POST['name'] ) && isset( $_POST['password'] )
        && $_POST['password'] === $resp['user'][$_POST['name']]['login_password'] ) {
        $_SESSION['id'] = rand();
    }
    // destroy session
    if ( isset( $_POST['destroy'] ) || ( time() - $_SESSION['last_visit'] ) > $session_timeout ) {
        $_SESSION = [];
        session_destroy();
    }
    // reset session time & show logout button
    if ( isset( $_SESSION['id'] ) ) {
        $_SESSION['last_visit'] = time();
        $html                   = <<<HTML
            <form action='' method='post'>
            <input type='hidden' name='destroy'>
            <input id=logout type='submit' value='Log Out' style="display:none">
            <label id=logoutButton for=logout class=button>Log Out</label>
            </form>
        HTML;
        return $html;
    }
    // show login form
    if ( !isset( $_SESSION['id'] ) ) {
        $html = <<<HTML
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charSet="utf-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1" />
                <title>Git via PHP</title>
                <meta name="description" content="Git over PHP" />
                <link rel="icon" type="image/png" sizes="32x32" href="data:image/png;base64,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" />
                <link rel="stylesheet" href="style.css">
                <style type="text/css">
                </style>
            </head>
            <body id=body>
                <div id="content">
                    <fieldset id=login>
                        <legend>Log In</legend>
                        <form id=Form action='' method='post'>
                            <label>Name</label>
                            <input class=login type='text' size='15' name='name'>
                            <br><br>
                            <label>Password</label>
                            <input class=login type='password' size='15' name='password'>
                            <br><br>
                            <input id=login type='submit' value='Log In' style="display:none">
                            <button id=loginButton for=login class=button>Log In</button>
                        </form>
                    </fieldset>
                </div>
            </body>
            </html>
            HTML;
        return exit( $html );
    }
}

?>
<!--




    // foreach ($resp as $key=>$value):
    //     if(is_array($value)){
    //         foreach ($value as $k => $v) {
    //             echo "<b>".$key."</b> &#8594; <b>".$k."</b> &#8594; ".$v."1<br>";
    //         }
    //         continue;
    //     }else{
    //             echo "<b>".$key."</b> &#8594; ".$value."2<br>"; //"&nbsp;&nbsp;&nbsp;";
    //     }
    // endforeach;







// if ( !empty( $_POST ) ) {
//     echo "<br>POST<br>";
//     print_r( $_POST );
//     echo "<br>";
//     exit;
// }elseif( !empty( $_GET ) ) {
//     echo "<br>GET<br>";
//     print_r( $_GET );
//     echo "<br>";
//     exit;
// }elseif( !empty( file_get_contents('php://input') ) ) {
//     echo "<div class=response >";
//     // print_r( file_get_contents('php://input') );
//     print_r(json_decode(file_get_contents('php://input'), true));
//     echo "</div>";
//     exit;
// }




https: //stackoverflow.com/a/43235320/8110291
From ssh-keygen man page:
-N new_passphrase provides the new passphrase.
-q silence ssh-keygen.
-f filename specifies the filename of the key file.
ssh-keygen -b 4096 -t rsa -C "xxx@xxx.de" -f ~/.ssh/id_rsa <<<y >/dev/null 2>&1 -q -N ""


// // generate new SSH key add it to ~/.ssh and show it
// execPHP( 'ssh-keygen -b 4096 -t rsa -C "xxx@xxx.de" -f ~/.ssh/id_rsa <<<y >/dev/null -N "phrase"' );
// execPHP( 'eval "$(ssh-agent -s && ssh-add ~/.ssh/id_rsa)"' );
// execPHP( 'cat ~/.ssh/id_rsa.pub' );


// show current working dir
// execPHP( 'pwd' );




// in der shell als php funktioniert es
php -r "exec('git push origin main');




// like a terminal
// use phpseclib3\File\ANSI;
// $ansi = new ANSI();
// $ssh->write( "clear && ls\n" );
// $ssh->setTimeout( 5 );
// $ansi->appendString( $ssh->read() );
// echo $ansi->getScreen(); // outputs HTML


// simple exec with output
// function execPHP( $command, $cd = '' ) {
//     exec( $cd.$command.'  2>&1', $output, $result );
//     $return = '<b>'.$command.'</b> '.$result.'<br>';
//     foreach ( $output as $key => $value ) {
//         $return .= $value.'<br>';
//     }
//     echo $return.'<br>';
// }
// echo exec( 'whoami' );
-->