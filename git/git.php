<?php
header('Content-Type: application/json');

$local = '../.git/logs/refs/heads';
$remote = '../.git/logs/refs/remotes/origin';

if(is_dir($remote)){
    $remotecommit = array_diff(scandir($remote), array('..', '.'));
    $remote_data = array_values($remotecommit);

}
if(is_dir($local)){
    $localcommit = array_diff(scandir($local), array('..', '.'));
    $local_data = array_values($localcommit);
}

$allArr = array(
    "local"=>!empty($local_data)?$local_data:[],
    "remote"=>!empty($remote_data)?$remote_data:[]
);
echo json_encode($allArr);
?>