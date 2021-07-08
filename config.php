<?php
$resp = [
    'user' => array(
        'username' => array(
            'login_password' => 'md5_hashed_string'
        ),
    ),
    'session_time' => 300,
    'SSH' =>  array(
        'password' => '****',
        'user'     => 'ssh-w00',
        'host'     => 'ssh.server.com',
        'port'     => '22',
    ),
    'presets' => array(
        'startpage' => 'start', 
        'create new repo' => array('init', 'add', 'branch', 'commit', 'create_repo', 'push'),
        'commit and push' => array('commit_and_push', 'add', 'commit', 'push'),
        'info and stats' => array('remote_origin_url', 'remote_show_origin', 'last_commit', 'all_commits'),
        'all' => 'all',
    ),
    // to avoit WARNINGs every entry has to have every key set
    'commands' => array(

        'commit_and_push' => array(
            'command' => 'git add . && git commit -m "new commit" && git push origin main ',
            'title' => 'Add, commit & push in onecommand',  
            'button' => 'commit&push',
            'text' => 'Mind the commit message and the origin branch name (main)'
        ),

        'add' => array(
            'command' => 'git add .',
            'title' => 'Add all files to git index',  
            'button' => 'add',
            'infolink' => 'https://git-scm.com/docs/git-add',
            'text' => 'This command updates the index using the current content found in the working tree, to prepare the content staged for the next commit.'
        ),

        'init' => array(
            'command' => 'git init',
            'title' => 'Create an empty Git repository',  
            'button' => 'init',
            'infolink' => 'https://git-scm.com/docs/git-init',
            'text' => 'This command creates an empty Git repository, or reinitialize an existing one, - basically a .git directory with subdirectories for objects, refs/heads, refs/tags, and template files. An initial branch without any commits will be created.'
        ),

        'commit' => array(
            'command' => 'git commit -m "new commit" ',
            'title' => 'Commit all changes to the local repository',  
            'button' => 'commit',
            'infolink' => 'https://git-scm.com/docs/git-commit',
            'text' => 'Create a new commit containing the current contents of the index and the given log message describing the changes. ',
        ),

        'branch' => array(
            'command' => 'git branch -M main ',
            'title' => 'Create a new branch',  
            'button' => 'branch',
            'infolink' => 'https://git-scm.com/docs/git-branch',
            'text' => 'This command creates a new branch named \'main\' which points to the current HEAD. ',
        ),

        'create_repo' => array(
            'command' => 'gh repo create REPONAME -y -d "description" --private ',
            'title' => 'Create a new GitHub repository',  
            'button' => 'new repo',
            'infolink' => 'https://cli.github.com/manual/gh_repo_create',
            'text' => 'When the current directory is a local git repository, the new repository will be added as the "origin" git remote. Otherwise, the command will prompt to clone the new repository into a sub-directory.',
        ),

        'push' => array(
            'command' => 'git push origin main ',
            'title' => 'Push the local repository to the the remote main branch',  
            'button' => 'push',
            'infolink' => 'https://git-scm.com/docs/git-push',
            'text' => 'Updates remote refs using local refs, while sending objects necessary to complete the given refs. '
        ),

        'remote_origin_url' => array(
            'command' => 'git config --get remote.origin.url ',
            'title' => 'Show remote origin URL',  
            'button' => 'origin URL',
            'infolink' => '',
            'text' => 'Show origin URL'
        ),
        
        'remote_show_origin' => array(
            'command' => 'git remote show origin ',
            'title' => 'Show remote origin ata',  
            'button' => 'origin data',
            'infolink' => '',
            'text' => 'Show remote origin data'
        ),

        'last_commit' => array(
            'command' => 'git log --stat -1',
            'title' => 'Last commit',  
            'button' => 'last commit',
            'infolink' => '',
            'text' => 'Last commit'
        ),

        'all_commits' => array(
            'command' => 'git log --pretty=format:"%h - %an, %ar : %s" --reverse',
            'title' => 'Show all commits',  
            'button' => 'all commits',
            'infolink' => '',
            'text' => 'Show all commits'
        ),

    ),
    'abs_path' => dirname(dirname(__FILE__)),
    'preselected_folder' => array(
        'gitPHP', 'KnowledgeBase'
    ),
    'ConsoleError' => array(
        'fatal','not a git repository','command not found','syntax error'
    ),    

];
