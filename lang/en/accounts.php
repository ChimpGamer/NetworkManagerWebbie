<?php

return [
    'account-groups' => [
        'title' => 'Account Groups',
        'buttons' => [
            'add' => 'Add Group',
        ],
        'modal' => [
            'add' => [
                'title' => 'Add Group',
                'name-label' => 'Name',
                'submit-button' => 'Add',
            ],
            'edit' => [
                'title' => 'Edit Group',
                'name-label' => 'Name',
                'permissions-label' => 'Permissions',
                'submit-button' => 'Update',
            ],
            'delete' => [
                'title' => 'Delete Group Confirm',
                'text' => 'Are you sure you want to delete group :groupName ?',
                'submit-button' => 'Yes, Delete',
            ],
            'close' => 'Close',
        ],
    ],
    'accounts' => [
        'title' => 'Accounts',
        'buttons' => [
            'add' => 'Add Account',
        ],
        'modal' => [
            'add' => [
                'title' => 'Add Account',
                'username-label' => 'Username',
                'password-label' => 'Password',
                'confirm-password-label' => 'Confirm Password',
                'group-label' => 'Group',
                'submit-button' => 'Add',
            ],
            'edit' => [
                'title' => 'Edit Account',
                'username-label' => 'Username',
                'group-label' => 'Group',
                'submit-button' => 'Update',
            ],
            'delete' => [
                'title' => 'Delete Account Confirm',
                'text' => 'Are you sure you want to delete user :userName ?',
                'submit-button' => 'Yes, Delete',
            ],
            'close' => 'Close',
        ],
    ],
];
