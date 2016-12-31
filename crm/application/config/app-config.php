<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
|
| URL to your CodeIgniter root. Typically this will be your base URL,
| WITH a trailing slash:
|
|   http://example.com/
|
| If this is not set then CodeIgniter will try guess the protocol, domain
| and path to your installation. However, you should always configure this
| explicitly and never rely on auto-guessing, especially in production
| environments.
|
*/

define('APP_BASE_URL','http://localhost/perfex_crm/crm/');

/*
|--------------------------------------------------------------------------
| Encryption Key
| IMPORTANT: Dont change this EVER
|--------------------------------------------------------------------------
|
| If you use the Encryption class, you must set an encryption key.
| See the user guide for more info.
|
| http://codeigniter.com/user_guide/libraries/encryption.html
|
| Auto updated added on install
*/

define('APP_ENC_KEY','996757f426acf0440cdc58e9cc4ef7be');

/* Database credentials - Auto added on install */

/* The hostname of your database server. */
define('APP_DB_HOSTNAME','192.185.16.192');
/* The username used to connect to the database */
define('APP_DB_USERNAME','rafaq5_auri_perf');
/* The password used to connect to the database */
define('APP_DB_PASSWORD','wujtRLWFyd4qezhc');
/* The name of the database you want to connect to */
define('APP_DB_NAME','rafaq5_auri_perfex_crm');

/* Session Handler */

define('SESS_DRIVER','database');
define('SESS_SAVE_PATH','tblsessions');