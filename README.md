# Jibannet Call Center API

## System Requirements 
- Requires PHP 7.1 or newer.
- Composer (not required)
        It's recommended that you use [Composer](https://getcomposer.org/) to install.

## Deploy source to Server
For deployment on a productive server, there are some important settings and security releated things to consider.
1. First download the Project zip archive from github
2. Extract from the archive and copy the Slim directory to your public_html directory or wherever you need to install. 
3. You can use composer to generate an optimized build of your application. All dev-dependencies are removed and the Composer autoloader is optimized for performance. In case the server does not have "Composer" installed, you can run this command on the Client before compressing it.
```        
    $ composer install --no-dev --optimize-autoloader
```
4. In your browser navigate to the URL https://www.example.com/{root-api}, where example.com represents your domain name. You should see ‘Slim a microframework for PHP’ in the browser.
    

## Setting Connect Database
First thing first lets open **src/settings.php** file and configure database connection details to the settings array as shown below.
```bash
<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
 
        // Renderer settings
        ....
        ....    
 
        // Monolog settings
        ....
        ....
 
        // Database connection settings
        "db" => [
            "host" => "localhost",
            "dbname" => "call-center",
            "user" => "root",
            "pass" => ""
        ],
    ],
];
```

