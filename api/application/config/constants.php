<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | Display Debug backtrace
  |--------------------------------------------------------------------------
  |
  | If set to TRUE, a backtrace will be displayed along with php errors. If
  | error_reporting is disabled, the backtrace will not display, regardless
  | of this setting
  |
 */
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |UPLOAD_PATH
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
defined('FILE_READ_MODE') OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') OR define('DIR_WRITE_MODE', 0755);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */
defined('FOPEN_READ') OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
  |--------------------------------------------------------------------------
  | Exit Status Codes
  |--------------------------------------------------------------------------
  |
  | Used to indicate the conditions under which the script is exit()ing.
  | While there is no universal standard for error codes, there are some
  | broad conventions.  Three such conventions are mentioned below, for
  | those who wish to make use of them.  The CodeIgniter defaults were
  | chosen for the least overlap with these conventions, while still
  | leaving room for others to be defined in future versions and user
  | applications.
  |
  | The three main conventions used for determining exit status codes
  | are as follows:
  |
  |    Standard C/C++ Library (stdlibc):
  |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
  |       (This link also contains other GNU-specific conventions)
  |    BSD sysexits.h:
  |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
  |    Bash scripting:
  |       http://tldp.org/LDP/abs/html/exitcodes.html
  |
 */
defined('EXIT_SUCCESS') OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
// cibil api key

define("COMPONENT_PATH", getenv("WWW_PATH")."common_component/");
// define("COMP_PATH", COMPONENT_PATH);
define("UPLOAD_PATH", getenv("WWW_PATH")."upload/");

define("COLLEX_DOC_URL", 'http://salaryontime.in/direct-document-file/'); //production
define("LMS_URL", getenv("WEBSITE_URL") ?? "http://localhost/pos/");
define("WEBSITE_URL", getenv("WEBSITE_URL") ?? "http://localhost/pos/");
define("WEBSITE", getenv("WEBSITE"));

// define("UPLOAD_PATH", "C:/xampp/htdocs/pos/upload/");
define("TEMP_DOC_PATH", getenv("WWW_PATH")."temp_upload/");

define("API_DOC_S3_FLAG", true); //true=> Store in S3 bucket , false=> Physical store.

define("LMS_COMPANY_LOGO", LMS_URL . "public/front/img/company_logo.jpg");
define("LMS_BRAND_LOGO", LMS_URL . "public/front/img/brand_logo.jpg");

define("COMPANY_NAME", getenv("COMPANY_NAME"));
define("BRAND_NAME", getenv("BRAND_NAME"));
define("REGISTED_ADDRESS", "NSP");
define("REGISTED_MOBILE", "+91-8282824-633");

define("TECH_EMAIL", getenv("TECH_EMAIL"));
define("INFO_EMAIL", getenv("MAIN_EMAIL"));
define("CARE_EMAIL", getenv("MAIN_EMAIL"));
define("RECOVERY_EMAIL", getenv("MAIN_EMAIL"));
define("COLLECTION_EMAIL", getenv("MAIN_EMAIL"));
define("CTO_EMAIL", getenv("MAIN_EMAIL"));
define('ENCRYPTION_METHOD', getenv('ENCRYPTION_METHOD'));
define('SECRET_KEY', getenv('SECRET_KEY')); // replace with a secure key
define('SECRET_IV', getenv('SECRET_IV'));
// *****  TEMPLETE VARIABLES *******
