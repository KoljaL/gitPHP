<?php
ini_set( 'display_errors', 1 );
ini_set( 'display_startup_errors', 1 );
error_reporting( E_ALL );

// include passwordfile
require_once __DIR__.'/pw.php';

// https://github.com/spatie/ssh

// include phpseclib
// https://phpseclib.com
require_once __DIR__.'/vendor/autoload.php';
use phpseclib3\Net\SSH2;
$ssh = new SSH2( $host );
if ( !$ssh->login( $user, $password ) ) {
    throw new \Exception( 'Login failed' );
}
 

 
// git config --get remote.origin.url 
// git remote show origin

// // generate new SSH key add it to ~/.ssh and show it
// $cd = 'cd ~ &&';
// execPHP( 'ssh-keygen -b 4096 -t rsa -C "xxx@xxx.de" -f ~/.ssh/id_rsa <<<y >/dev/null -N "phrase"' ,$cd);
// execPHP( 'eval "$(ssh-agent -s && ssh-add ~/.ssh/id_rsa)"' ,$cd);
// execPHP( 'cat ~/.ssh/id_rsa.pub' ,$cd);


function execPHP( $gitCommand ) {
    global $ssh,$resp;
    // echo '<div class=response>/'.$resp['RepoURL'].'~$ '.$resp[$gitCommand] .'<br><pre>';
    echo '<div class=response>'.$resp['cd'].$resp[$gitCommand] .'<br><pre>';
    echo $ssh->exec( $resp['cd'].$resp[$gitCommand] );
    echo '</pre></div>';
    CommandHistory($gitCommand);
}



$resp = [
    'custom' => '/',
    'add' => 'git add .',
    'commit' => 'git commit -m "new commit" ',
    'push' => 'git push origin main ',
    'remote.origin.url' => 'git config --get remote.origin.url ',
    'remote show origin' => 'git remote show origin ',
    'last commit' => 'git log --stat -1',
    'all commits' => 'git log --pretty=format:"%h - %an, %ar : %s"',
    'abs_path' => dirname(dirname(__FILE__))
];
// git show -p origin/main
// print_resp($resp);
if( !empty( file_get_contents('php://input') ) ) {
    print_resp(json_decode(file_get_contents('php://input'), true));

    $resp = array_merge($resp,json_decode(file_get_contents('php://input'), true));
    $resp['cd'] = "cd ".$resp['abs_path']."/".$resp['RepoURL']." && ";
 

    
    echo "<div class=deb_resp style='display:none'>";
    print_resp($resp);
    echo "</div>";


    switch ($resp['GitCommand']) {

    case 'custom':
        $resp['custom'] = $resp['CustomCommand'];

        execPHP(  'custom' );
        break;

    case 'add':
        execPHP(  'add' );
    break;

    case 'commit':
        $resp['commit'] = str_replace('new commit',$resp['CommitMessage'] , $resp['commit']);
        execPHP(  'commit' );
        break;

    case 'push':
        execPHP(  'push' );
        break;

    case 'remote.origin.url':
        execPHP(  'remote.origin.url' );
        break;

    case 'remote show origin':
        execPHP(  'remote show origin' );
        break;
     
    default:
        echo 'no command found';
    }
exit;
}


function CommandHistory($gitCommand){
    global $ssh,$resp;
$filecontent = (file_exists('history.log'))? file_get_contents('history.log'): '';
$filecontent = $filecontent."\n".date("d.m.Y H:i")." '".$resp['cd'].$resp[$gitCommand]."'";
 file_put_contents('history.log',$filecontent);
}
 

// // git commit and push
// $cd = 'cd /www/htdocs/w01c010a/dev.rasal.de/KnowledgeBase &&';
// execPHP( 'git add .',$cd );
// execPHP( 'git commit -m "PHP Test1"' ,$cd);
// // execPHP( 'git remote remove origin',$cd );
// // execPHP( 'git remote add origin git@github.com:KoljaL/KnowledgeBase.git',$cd );
// execPHP( 'git push origin main',$cd );

// echo $ssh->exec( 'cd www && ls' );
// echo $ssh->exec( $cd.$command );

// echo $ssh->read( 'username@username:~$' );
// $ssh->write( "ls -la\n" ); // note the "\n"
// echo $ssh->read( 'username@username:~$' );
// execPHP( $command, $cd );



function print_resp($resp){
    echo "<div class=responsedebug><pre>";
    foreach ($resp as $key=>$value):
            echo $key." -> ".$value."<br>"; //"&nbsp;&nbsp;&nbsp;";
    endforeach;
    echo "</pre></div>";
}

// escape strings for HTML 
foreach ($resp as $key => $value) {
    $resp[$key] = htmlspecialchars($value);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charSet="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Git over PHP</title>
    <meta name="description" content="Git over PHP" />
    <link rel="icon" type="image/png" sizes="32x32" href="data:image/png;base64,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" />
    <link rel="stylesheet" href="style.css">
    <style type="text/css">
    </style>

</head>
<body>
    <div id="debug">
        <?= print_resp($resp)?>
    </div>
    <div id="content">


        <fieldset id=form>
            <legend>Git Actions</legend>
            <div id="items">

                <div class=item>
                    <div class=text>
                        <h3>Repo folder</h3>
                        <input type="text" list="RepoURLs" data-name="relative path to local repository" id=RepoURL class=ff_input onmouseover="focus();old = value;" onmousedown="value = '';" onmouseup="value = old;">
                        <datalist id="RepoURLs">
                            <option value="KnowledgeBase">
                            <option value="gitPHP">
                        </datalist>
                    </div>
                    <!-- <div data-name="relative path to local repository" id=RepoURL class=ff_input contenteditable>KnowledgeBase</div> -->
                    <br>
                </div>

                <!-- CUSTOM -->
                <div class=item>
                    <div class=text>
                        <h3>Custom Command</h3>
                        <input type=text data-name="Custom Command" id=CustomCommand class=ff_input value="custom command">
                    </div>
                    <button onclick="sendCommands('custom');" data-tooltip="<?= $resp['custom'] ?>">custom</button>
                </div>

                <!-- ADD -->
                <div class=item>
                    <div class=text>
                        <h3>Add all files to git index</h3>
                        This command updates the index using the current content found in the working tree, to prepare the content staged for the next commit.
                        This command updates the index using the current content found in the working tree, to prepare the content staged for the next commit.
                        This command updates the index using the current content found in the working tree, to prepare the content staged for the next commit.
                    </div>
                    <button onclick="sendCommands('add');" data-tooltip="<?= $resp['add'] ?>">add</button>
                </div>


                <!-- COMMIT -->
                <div class=item>
                    <h3>Commit all changes to the local repository</h3>
                    <div class=text>Create a new commit containing the current contents of the index and the given log message describing the changes.
                        <div data-name="Commit Message" id=CommitMessage class=ff_input contenteditable>new commit</div>
                    </div>
                    <button onclick="sendCommands('commit');" data-tooltip="<?= $resp['commit'] ?>">commit</button>
                </div>

                <!-- PUSH -->
                <div class=item>
                    <div class=text>
                        <h3>Push the local repository to the the remote main branch</h3>
                        Updates remote refs using local refs, while sending objects necessary to complete the given refs.
                        Updates remote refs using local refs, while sending objects necessary to complete the given refs.
                        Updates remote refs using local refs, while sending objects necessary to complete the given refs.
                    </div>
                    <button onclick="sendCommands('push');" data-tooltip="<?= $resp['push'] ?>">push</button>
                </div>

                <!-- remote.origin.url -->
                <div class=item>
                    <div class=text>
                        <h3>Show the remote origin URL</h3>
                    </div>
                    <button onclick="sendCommands('remote.origin.url');" data-tooltip="<?= $resp['remote.origin.url'] ?>">remote.origin.url</button>
                </div>
                <!-- remote show origin  -->
                <div class=item>
                    <h3>Show info ablut the remote origin</h3>
                    <div class=text>Augment the output of all queried config options with the origin type (file, standard input, blob, command line) and the actual origin (config file path, ref, or blob id if applicable).</div>
                    <button onclick="sendCommands('remote show origin');" data-tooltip="<?= $resp['remote show origin'] ?>">remote show origin</button>
                </div>



            </div>
        </fieldset>
        <fieldset id=console>
            <legend>Console</legend>
            <div id="console_output">
            </div>
        </fieldset>
    </div>

    <script>

    </script>
    <script src="script.js"></script>
</body>
</html>


<!--








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