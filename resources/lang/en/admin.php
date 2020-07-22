<?php

return [
    'admin-user' => [
        'title' => 'Users',

        'actions' => [
            'index' => 'Users',
            'create' => 'New User',
            'edit' => 'Edit :name',
            'edit_profile' => 'Edit Profile',
            'edit_password' => 'Edit Password',
        ],

        'columns' => [
            'id' => 'ID',
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'email' => 'Email',
            'password' => 'Password',
            'password_repeat' => 'Password Confirmation',
            'activated' => 'Activated',
            'forbidden' => 'Forbidden',
            'language' => 'Language',
                
            //Belongs to many relations
            'roles' => 'Roles',
                
        ],
    ],

    'page' => [
        'title' => 'Pages',

        'actions' => [
            'index' => 'Pages',
            'create' => 'New Page',
            'edit' => 'Edit :name',
            'will_be_published' => 'Page will be published at',
        ],

        'columns' => [
            'id' => 'ID',
            'title' => 'Title',
            'url' => 'Url',
            'content' => 'Content',
            'seo_description' => 'Seo description',
            'seo_keywords' => 'Seo keywords',
            'is_published' => 'Is published',
            'published_at' => 'Published at',
            'cover_image' => 'Cover image',
            
        ],
    ],

    'page' => [
        'title' => 'Pages',

        'actions' => [
            'index' => 'Pages',
            'create' => 'New Page',
            'edit' => 'Edit :name',
            'will_be_published' => 'Page will be published at',
        ],

        'columns' => [
            'id' => 'ID',
            'title' => 'Title',
            'url' => 'Url',
            'content' => 'Content',
            'seo_description' => 'Seo description',
            'seo_keywords' => 'Seo keywords',
            'published_at' => 'Published at',
            'cover_image' => 'Cover image',
            
        ],
    ],

    // Do not delete me :) I'm used for auto-generation
];