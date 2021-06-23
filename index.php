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
    echo '<div class=response><span>'.$resp['RepoURL'].':</span> &nbsp;&nbsp;'.$resp[$gitCommand] .'<br><pre>';
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
    // print_resp(json_decode(file_get_contents('php://input'), true));

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

if (rand(0, 1)) { 
    $icon = <<<HTML
        <link rel="icon" type="image/png" sizes="32x32" href="data:image/png;base64,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" />
    HTML;
}else{    
    $icon = <<<HTML
        <link rel="icon" type="image/png" sizes="32x32" href="data:image/png;base64,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" />
    HTML;
 }

 


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
    <?= $icon ?>
    <link rel="stylesheet" href="style.css">
    <style type="text/css">
    </style>

</head>
<body>
    <div id="debug">
        <?= print_resp($resp)?>
    </div>

    <div id="debugcheckDIV" style="position: absolute;right:20px;top:20px;">
        <label for="debugcheck" style="font-size: x-large;">&#128027;</label>
        <input type="checkbox" name="debugcheck" id="debugcheck" style="display:none">
    </div>
    <div id="content">


        <fieldset id=form>
            <legend>Git Actions</legend>
            <div id="items">

                <!-- ChooseRepoURL -->
                <div id=ChooseRepoURL class=item>
                    <div class=text>
                        <h3>Repo folder</h3>
                        <input type="text" list="RepoURLs" placeholder="test" data-name="relative path to local repository" id=RepoURL class=ff_input onmouseover="focus();old = value;" onmousedown="value = '';" onmouseup="value = old;">
                        <datalist id="RepoURLs">
                            <option value="KnowledgeBase">
                            <option value="gitPHP">
                        </datalist>
                    </div>
                </div>

                <!-- remote.origin.url -->
                <div class=item>
                    <div class=text>
                        <h3>Show the remote origin URL</h3>
                    </div>
                    <button onclick="sendCommands('remote.origin.url');" data-tooltip="<?= $resp['remote.origin.url'] ?>">origin.url</button>
                </div>
                <!-- remote show origin  -->
                <div class=item>
                    <h3>Show info ablut the remote origin</h3>
                    <div class=text>Augment the output of all queried config options with the origin type (file, standard input, blob, command line) and the actual origin (config file path, ref, or blob id if applicable).</div>
                    <button onclick="sendCommands('remote show origin');" data-tooltip="<?= $resp['remote show origin'] ?>">show&nbsp;origin</button>
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
                        <input type=text data-name="Commit Message" id=CommitMessage class=ff_input value="new commit">

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