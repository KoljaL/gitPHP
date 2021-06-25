<?php


$resp = [
    'user' => array(
        'test' => array(
            'login_password' => 'test')),
    'commands' => array(

        'custom' => array(
            'command' =>'/'),
        'add' => array(
            'command' => 'git add .'),
        'commit' => array(
            'command' => 'git commit -m "new commit" '),
        'push' => array(
            'command' => 'git push origin main '),
        'remote.origin.url' => array(
            'command' => 'git config --get remote.origin.url '),
        'remote show origin' => array(
            'command' => 'git remote show origin '),
        'last commit' => array(
            'command' => 'git log --stat -1'),
        'all commits' => array(
            'command' => 'git log --pretty=format:"%h - %an, %ar : %s"'),
    ),
        'abs_path' => dirname(dirname(__FILE__)),
    'preselected_folder' => array('gitPHP', 'KnowledgeBase'),
    'ConsoleError' => array(
        'fatal','not a git repository','command not found','syntax error')
];

// $resp['ConsoleError'] = array('fatal','not a git repository','command not found','syntax error');