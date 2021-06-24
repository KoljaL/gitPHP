<?php


$resp['login_name'] = 'test';
$resp['login_password'] = 'test';

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

$resp['ConsoleError'] = array('fatal: not a git repository','command not found','syntax error');