<?php
/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new \Phalcon\Config([
    'database' => [
        'adapter'     => 'Mysql',
        'host'        => 'nc-ind-api-db.mysql.database.azure.com',
        'username'    => 'phpmyadmin@nc-ind-api-db',
        'password'    => 'N!dara@9876',
        'dbname'      => 'nidara_private_school',
        'charset'     => 'utf8',
    ],
    'application' => [
        'appDir'         => APP_PATH . '/',
        'controllersDir' => APP_PATH . '/controllers/',
        'modelsDir'      => APP_PATH . '/models/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'pluginsDir'     => APP_PATH . '/plugins/',
        'libraryDir'     => APP_PATH . '/library/',
	'helperDir'     =>  APP_PATH . '/helper/',
        'cacheDir'       => BASE_PATH . '/cache/',

        // This allows the baseUri to be understand project paths that are not in the root directory
        // of the webpspace.  This will break if the public/index.php entry point is moved or
        // possibly if the web server rewrite rules are changed. This can also be set to a static path.
        'baseUri'        => preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER["PHP_SELF"]),
    ],


    //Azure AD 

    'tenantId' => 'a812087f-d0d0-4a13-b6a3-d92930d56005',
	'clientId' => '6a00ce30-d34b-4d06-ad9e-59cfaa93ee7f',
    'clientSecret' => 'X3s5ahEV-PW9GO_s__KObXARzmm7Glnf1z',    

//    'Audience'=>'http://services.expedux.in',
//    'Issuer'=>'http://login.expedux.in',
    'Audience'=>'http://nctestapi.nidarachildren.com',
    'Issuer'=>'http://nctestapi.nidarachildren.com',
    'appurl'=>'https://apiqa.expedux.in/nidara-test-v1/',
    'weburl'=>'http://school.nidarachildren.com',
    'baseurl'=>'http://nctestapi.nidarachildren.com',
    'wpurl'=>'http://blog.nidarachildren.com/',
    'working_key' => 'B10B0D5CE031F76E7368B1C119808F44',
    'wpapi_key' => 'NIDARA02@cHilD',
    'colorurl'=>'https://apischool.nidarachildren.in',

]);
