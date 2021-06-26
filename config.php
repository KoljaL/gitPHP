<?php


$resp = [
    'user' => array(
        'test' => array(
            'login_password' => 'test'
        ),
    ),
    'CustomCommandList' => array(
        'git config --get remote.origin.url',' git remote show origin'
    ),
    'presets' => array(
        'one' => array('add', 'commit', 'push'),
        'two' => array('remote_origin_url', 'remote_show_origin', 'last_commit', 'all_commits'),
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

        'add' => array(
            'command' => 'git add .',
            'title' => 'Add all files to git index',  
            'button' => 'add',
            'tooltip' => 'git add .',
            'infolink' => 'https://git-scm.com/docs/git-add',
            'text' => 'This command updates the index using the current content found in the working tree, to prepare the content staged for the next commit.'
        ),

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

