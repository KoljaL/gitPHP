<?php


$resp = [
    'user' => array(
        'test' => array(
            'login_password' => 'test'
        ),
    ),
    'session_time' => 300,
    'CustomCommandList' => array(
        'git config --get remote.origin.url',' git remote show origin'
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

        'custom_command' => array(
            'command' =>'/',
            'title' => 'Custom Command',  
            'button' => 'Custom Command',
            'tooltip' => 'send what you want',
            'infolink' => '',
            'text' => '',
            'formfield' => array(
                'datalist' => 'datalist_customcommand',
                'placeholder' => 'Choose or type'
            )
        ),

        'commit_and_push' => array(
            'command' => 'git add . && git commit -m "new commit" && git push origin input_rewrite ',
            'title' => 'Add, commit & push in onecommand',  
            'button' => 'add & push',
            'tooltip' => 'git add .',
            'infolink' => '',
            'text' => 'Mention the commit message and the origin branch name (main)'
        ),

        'add' => array(
            'command' => 'git add .',
            'title' => 'Add all files to git index',  
            'button' => 'add',
            'tooltip' => 'git add .',
            'infolink' => 'https://git-scm.com/docs/git-add',
            'text' => 'This command updates the index using the current content found in the working tree, to prepare the content staged for the next commit.'
        ),

        'init' => array(
            'command' => 'git init',
            'title' => 'Create an empty Git repository',  
            'button' => 'init',
            'tooltip' => 'git init',
            'infolink' => 'https://git-scm.com/docs/git-init',
            'text' => 'This command creates an empty Git repository, or reinitialize an existing one, - basically a .git directory with subdirectories for objects, refs/heads, refs/tags, and template files. An initial branch without any commits will be created.'
        ),

        // 'new_branch' => array(
        //     'command' => 'git checkout -b BRANCHNAME',
        //     'title' => 'Create a new branch in your local repository',  
        //     'button' => 'new branch',
        //     'tooltip' => 'git init',
        //     'infolink' => 'https://git-scm.com/docs/git-branch',
        //     'text' => 'This command createsa new branch in your local repository. Note that this will create the new branch, but it will not switch the working tree to it; use "git switch <newbranch>" to switch to the new branch.'
        // ),

        'commit' => array(
            'command' => 'git commit -m "new commit" ',
            'title' => 'Commit all changes to the local repository',  
            'button' => 'commit',
            'tooltip' => 'git commit -m "new commit"',
            'infolink' => 'https://git-scm.com/docs/git-commit',
            'text' => 'Create a new commit containing the current contents of the index and the given log message describing the changes. ',
            'formfield' => array(
                'textfield' => 'textfield_commit',
                'placeholder' => '',
                'value' => 'new commit',

            ),
        ),

        'branch' => array(
            'command' => 'git branch -M main ',
            'title' => 'Create a new branch',  
            'button' => 'branch',
            'tooltip' => 'git branch -M main',
            'infolink' => 'https://git-scm.com/docs/git-branch',
            'text' => 'This command creates a new branch named \'main\' which points to the current HEAD. ',
            'formfield' => array(
                'textfield' => 'textfield_new_branch',
                'placeholder' => '',
                'value' => 'main',
            ),
        ),

        'create_repo' => array(
            'command' => 'gh repo create REPONAME -y -d "description" --private ',
            'title' => 'Create a new GitHub repository',  
            'button' => 'new repo',
            'tooltip' => 'gh repo create REPONAME -y -d "description" --private',
            'infolink' => 'https://cli.github.com/manual/gh_repo_create',
            'text' => 'When the current directory is a local git repository, the new repository will be added as the "origin" git remote. Otherwise, the command will prompt to clone the new repository into a sub-directory.',
            'formfield' => array(
                'textfield' => 'textfield_repo_description',
                'placeholder' => '',
                'value' => 'description',
            ),
        ),

        'push' => array(
            'command' => 'git push origin main ',
            'title' => 'Push the local repository to the the remote main branch',  
            'button' => 'push',
            'tooltip' => 'git push origin main',
            'infolink' => 'https://git-scm.com/docs/git-push',
            'text' => 'Updates remote refs using local refs, while sending objects necessary to complete the given refs. '
        ),

        'remote_origin_url' => array(
            'command' => 'git config --get remote.origin.url ',
            'title' => 'Show remote origin URL',  
            'button' => 'origin URL',
            'tooltip' => 'git config --get remote.origin.url',
            'infolink' => '',
            'text' => 'Show origin URL'
        ),
        
        'remote_show_origin' => array(
            'command' => 'git remote show origin ',
            'title' => 'Show remote origin ata',  
            'button' => 'origin data',
            'tooltip' => 'git remote show origin',
            'infolink' => '',
            'text' => 'Show remote origin data'
        ),

        'last_commit' => array(
            'command' => 'git log --stat -1',
            'title' => 'Last commit',  
            'button' => 'last commit',
            'tooltip' => 'git log --stat -1',
            'infolink' => '',
            'text' => 'Last commit'
        ),

        'all_commits' => array(
            'command' => 'git log --pretty=format:"%h - %an, %ar : %s"',
            'title' => 'All commits',  
            'button' => 'all commits',
            'tooltip' => 'git log --pretty=format:"%h - %an, %ar : %s"',
            'infolink' => '',
            'text' => 'All commits'
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

// search for a repo: https://api.github.com/repos/<user>/<repo>

// create new repo: 


// git init
// git add .
// git commit -m "tt"
// git branch -M main
// gh repo create AHA -y -d "ne beschreibung" --private
// git push -u origin main


// delete a repo
//   # one time:
// $ gh alias set delete 'api -X DELETE repos/$1'
// $ gh auth refresh -h github.com -s delete_repo
// # usage (WARNING: no confirmation!)
// gh delete user/myrepo

// or this
//git push origin --delete main
// https://stackoverflow.com/questions/2003505/how-do-i-delete-a-git-branch-locally-and-remotely