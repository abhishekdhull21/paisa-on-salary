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
  |
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


define("COLLEX_DOC_URL", 'https://devmunitech.com/direct-document-file/'); //production
define("LMS_URL", "https://devmunitech.com/");
define("WEBSITE_URL", "https://uat-website.devmunitech.com/");
define("WEBSITE", "uat-website.devmunitech.com");

define("LMS_COMPANY_LOGO", LMS_URL . "/public/front/img/company_logo.png");
define("LMS_BRAND_LOGO", LMS_URL . "/public/front/img/brand_logo.jpg");

define("COMPANY_NAME", "DEVMUNI LEASING & FINANCE LIMITED");
define("BRAND_NAME", "Bharat Loan");
define("REGISTED_ADDRESS", "1689/121, Shanti Nagar, Tri Nagar, New Delhi, North West Delhi, Delhi, 110035");
define("REGISTED_MOBILE", "+91-8282824-644");

define("TECH_EMAIL", "tech.team@surya.com");
define("INFO_EMAIL", "tech.team@surya.com");
define("CARE_EMAIL", "tech.team@surya.com");
define("RECOVERY_EMAIL", "tech.team@surya.com");

// *****  TEMPLETE VARIABLES *******

define("COLLECTION_BRAND_LOGO", WEBSITE_URL . "public/emailimages/collection/image/lw-logo.png");
define("COLLECTION_LINE", WEBSITE_URL . "public/emailimages/collection/image/line.png");
define("COLLECTION_INR_ICON", WEBSITE_URL . "public/emailimages/collection/image/inr-icon.png");
define("COLLECTION_PHONE_ICON", WEBSITE_URL . "public/emailimages/collection/image/phone-icon.png");
define("COLLECTION_EMAIL_ICON", WEBSITE_URL . "public/emailimages/collection/image/emil-icon.png");
define("COLLECTION_WEB_ICON", WEBSITE_URL . "public/emailimages/collection/image/web-icon.png");
define("COLLECTION_FIELD_BANNER", WEBSITE_URL . "public/emailimages/collection/image/Field-Executive-icon.png");
define("COLLECTION_FIELD_ROAD", WEBSITE_URL . "public/emailimages/collection/image/Field-Executive.jpg");

// *********SOCIAL MEDIA LINK ********
define("APPLE_STORE_LINK", "#");
define("ANDROID_STORE_LINK", "https://play.google.com/store/apps/details?id=com.bhartloan.personalloan");
define("LINKEDIN_LINK", "https://www.linkedin.com/company/surya");
define("INSTAGRAM_LINK", "https://www.instagram.com/surya_india");
define("FACEBOOK_LINK", "https://www.facebook.com/surya-105632195732824");
define("YOUTUBE_LINK", "https://www.youtube.com/channel/UCUwrJB1IMvDiMctHHRKDLxw");
define("TWITTER_LINK", "https://twitter.com/suryas");

// ******* SOCIAL MEDIA ICONS ***********

define("APPLE_STORE_ICON", WEBSITE_URL . "public/emailimages/Digilocker_eKyc/images/app_store_icon.png");
define("ANDROID_STORE_ICON", WEBSITE_URL . "public/emailimages/Digilocker_eKyc/images/google_play_icon.png");
define("LINKEDIN_ICON", WEBSITE_URL . "public/emailimages/Digilocker_eKyc/images/linked_in_icon.png");
define("INSTAGRAM_ICON", WEBSITE_URL . "public/emailimages/Digilocker_eKyc/images/instagram_icon.png");
define("FACEBOOK_ICON", WEBSITE_URL . "public/emailimages/Digilocker_eKyc/images/facebook_icon.png");
define("YOUTUBE_ICON", WEBSITE_URL . "public/emailimages/Digilocker_eKyc/images/youtube_icon.png");
define("TWITTER_ICON", WEBSITE_URL . "public/emailimages/Digilocker_eKyc/images/twitter_icon.png");
