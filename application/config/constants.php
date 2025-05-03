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
  |       https://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
  |       (This link also contains other GNU-specific conventions)
  |    BSD sysexits.h:
  |       https://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
  |    Bash scripting:
  |       https://tldp.org/LDP/abs/html/exitcodes.html
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


defined('CSS_VERSION') OR define('CSS_VERSION', 1.1); // highest automatically-assigned error code

defined('ALL_FROM_EMAIL') OR define('ALL_FROM_EMAIL', getenv("MAIN_EMAIL"));

defined('BCC_SANCTION_EMAIL') OR define('BCC_SANCTION_EMAIL', getenv("MAIN_EMAIL"));
defined('BCC_DISBURSAL_EMAIL') OR define('BCC_DISBURSAL_EMAIL', '');
defined('BCC_NOC_EMAIL') OR define('BCC_NOC_EMAIL', getenv("MAIN_EMAIL"));
defined('BCC_DISBURSAL_WAIVE_EMAIL') OR define('BCC_DISBURSAL_WAIVE_EMAIL', getenv("MAIN_EMAIL"));

defined('CC_SANCTION_EMAIL') OR define('CC_SANCTION_EMAIL', '');
defined('CC_DISBURSAL_EMAIL') OR define('CC_DISBURSAL_EMAIL', '');
defined('CC_DISBURSAL_WAIVE_EMAIL') OR define('CC_DISBURSAL_WAIVE_EMAIL', getenv("MAIN_EMAIL"));

defined('TO_KYC_DOCS_ZIP_DOWNLOAD_EMAIL') OR define('TO_KYC_DOCS_ZIP_DOWNLOAD_EMAIL', getenv("MAIN_EMAIL"));

define("COMPONENT_PATH", getenv("WWW_PATH")."common_component/");
define("UPLOAD_PATH", getenv("WWW_PATH")."upload/");
define("UPLOAD_LEGAL_PATH", getenv("WWW_PATH")."upload/legal/");
define("UPLOAD_SETTLEMENT_PATH", getenv("WWW_PATH")."upload/settlement/");
define("TEMP_UPLOAD_PATH", getenv("WWW_PATH")."upload/");
define("UPLOAD_TEMP_PATH", getenv("WWW_PATH")."temp_upload/");
define("UPLOAD_DISBURSAL_PATH", getenv("WWW_PATH")."upload/disburse_letter/");


//define("LOANS_KYC_DOCS", "/kycdocs/loans/");

define("FEEDBACK_WEB_PATH", getenv('WEBSITE_URL')."customer-feedback/");

// ********** API URL DEFINE *****

defined('SERVER_API_URL') OR define('SERVER_API_URL', getenv("SERVER_API_URL")); //SERVER API URL

// ********** LMS DEFINED VARIABLE *****

define("LMS_URL", getenv("WEBSITE_URL") ?? "http://localhost/pos/");
define("WEBSITE_URL", getenv("WEBSITE_URL") ?? "http://localhost/pos/");
define("WEBSITE", getenv("WEBSITE"));
define("WEBSITE_UTM_SOURCE", WEBSITE_URL . "apply-now?utm_source=");

define("LMS_COMPANY_LOGO", LMS_URL . "public/front/img/company_logo.jpg");
define("LMS_KASAR_LETTERHEAD", LMS_URL . "public/images/kasar_letter_head_bg.jpg");
define("LMS_COMPANY_MIS_LOGO", LMS_URL . "public/front/img/company_logo.jpg");
define("LMS_BRAND_LOGO", LMS_URL . "public/front/img/brand_logo.jpg");
define("BANK_STATEMENT_UPLOAD", getenv("WWW_PATH")."application/helpers/integration/");
define("COMPANY_NAME",getenv("COMPANY_NAME"));
define("RBI_LICENCE_NUMBER", "B-14.01843");
define('CONTACT_PERSON', 'AJAY Sheokand');
define("REGISTED_ADDRESS", "G -51, Krishna Apra Business Square, Netaji Subhash Place, New Delhi - 110034");
define("REGISTED_MOBILE", "+91-8800002890");
define("BRAND_NAME",getenv("BRAND_NAME"));

define("TECH_EMAIL", getenv("TECH_EMAIL"));
define("INFO_EMAIL", getenv("MAIN_EMAIL"));
define("CARE_EMAIL", getenv("MAIN_EMAIL"));
define("RECOVERY_EMAIL", getenv("MAIN_EMAIL"));
define("COLLECTION_EMAIL", getenv("MAIN_EMAIL"));
define("CTO_EMAIL", getenv("MAIN_EMAIL"));

// ********** TEMPLETE DEFINED VARIABLE *****

define("EMAIL_BRAND_LOGO", LMS_URL . "public/front/images/company_logo.jpeg");
define("DISBURSAL_LETTER_BANNER", LMS_URL. "public/emailimages/disbursal_banner.png");

define("SANCTION_LETTER_HEADER", LMS_URL . "public/emailimages/header.png");
define("SANCTION_LETTER_FOOTER", WEBSITE_URL . "public/salaryontime.in/images/kasar_letter_footer.jpg");

define("GENERATE_SANCTION_LETTER_HEADER", LMS_URL . getenv("WWW_PATH")."public/emailimages/header.png");
define("GENERATE_SANCTION_LETTER_FOOTER", LMS_URL . "public/salaryontime.in/emailimages/sanction-letterfooter.png");

define("EKYC_BRAND_LOGO", WEBSITE_URL . "public/salaryontime.in/emailimages/Digilocker_eKyc/images/ekyc_brand_logo.gif");
define("EKYC_HEADER_BACK", WEBSITE_URL . "public/salaryontime.in/emailimages/Digilocker_eKyc/images/header_back.jpg");
define("EKYC_LINES", WEBSITE_URL . "public/salaryontime.in/emailimages/Digilocker_eKyc/images/line.png");
define("EKYC_IMAGES_1", WEBSITE_URL . "public/salaryontime.in/emailimages/Digilocker_eKyc/images/1st.jpg");
define("EKYC_IMAGES_1_SHOW", WEBSITE_URL . "public/salaryontime.in/emailimages/Digilocker_eKyc/images/image1.png");
define("EKYC_IMAGES_2", WEBSITE_URL . "public/salaryontime.in/emailimages/Digilocker_eKyc/images/2nd.jpg");
define("EKYC_IMAGES_2_SHOW", WEBSITE_URL . "public/salaryontime.in/emailimages/Digilocker_eKyc/images/image2.png");
define("EKYC_IMAGES_3", WEBSITE_URL . "public/salaryontime.in/emailimages/Digilocker_eKyc/images/image3.png");
define("EKYC_IMAGES_3_SHOW", WEBSITE_URL . "public/salaryontime.in/emailimages/Digilocker_eKyc/images/3rd.jpg");
define("EKYC_IMAGES_4", WEBSITE_URL . "public/salaryontime.in/emailimages/Digilocker_eKyc/images/4th.jpg");
define("EKYC_IMAGES_4_SHOW", WEBSITE_URL . "public/salaryontime.in//Digilocker_eKyc/images/4th.jpg");

define("LOAN_REPAY_LINK", WEBSITE_URL . "repay-loan");
define("REPAYMENT_REPAY_LINK", WEBSITE_URL . "repay-loan-details");
define("AUTHORISED_SIGNATORY", WEBSITE_URL . "public/salaryontime.in/front/images/Authorised-Signatory.jpeg");

define("PRE_APPROVED_LINES", WEBSITE_URL . "public/salaryontime.in/emailimages/final-email-template/images/back-line.png");
define("PRE_APPROVED_BANNER", WEBSITE_URL . "public/salaryontime.in/emailimages/final-email-template/images/email-salaryontime.gif");
define("PRE_APPROVED_LINE_COLOR", WEBSITE_URL . "public/salaryontime.in/emailimages/final-email-template/images/line-color.png");
define("PRE_APPROVED_PHONE_ICON", WEBSITE_URL . "public/salaryontime.in/emailimages/final-email-template/images/phone-icon.png");
define("PRE_APPROVED_WEB_ICON", WEBSITE_URL . "public/salaryontime.in/emailimages/final-email-template/images/web-icon.png");
define("PRE_APPROVED_EMAIL_ICON", WEBSITE_URL . "public/salaryontime.in/emailimages/final-email-template/images/emil-icon.png");
define("PRE_APPROVED_ARROW_LEFT", WEBSITE_URL . "public/salaryontime.in/emailimages/final-email-template/images/arrow-left.png");
define("PRE_APPROVED_ARROW_RIGHT", WEBSITE_URL . "public/salaryontime.in/emailimages/final-email-template/images/arrow-right.png");

define("FEEDBACK_HEADER", WEBSITE_URL . "public/salaryontime.in/emailimages/feedback/images/header2.jpg");
define("FEEDBACK_LINE", WEBSITE_URL . "public/salaryontime.in/emailimages/feedback/images/line.png");
define("FEEDBACK_PHONE_ICON", WEBSITE_URL . "public/salaryontime.in/emailimages/feedback/images/phone-icon.png");
define("FEEDBACK_WEB_ICON", WEBSITE_URL . "public/salaryontime.in/emailimages/feedback/images/web-icon.png");
define("FEEDBACK_EMAIL_ICON", WEBSITE_URL . "public/salaryontime.in/emailimages/feedback/images/email-icon.png");

define("COLLECTION_BRAND_LOGO", WEBSITE_URL . "public/salaryontime.in/emailimages/collection/image/lw-logo.png");
define("COLLECTION_EXE_BANNER", WEBSITE_URL . "public/salaryontime.in/emailimages/collection/image/Collection-Executive-banner.jpg");
define("COLLECTION_LINE", WEBSITE_URL . "public/salaryontime.in/emailimages/collection/image/line.png");
define("COLLECTION_INR_ICON", WEBSITE_URL . "public/salaryontime.in/emailimages/collection/image/inr-icon.png");
define("COLLECTION_ROAD_BANNER", WEBSITE_URL . "public/salaryontime.in/emailimages/collection/image/CRM.jpg");
define("COLLECTION_PHONE_ICON", WEBSITE_URL . "public/salaryontime.in/emailimages/collection/image/phone-icon.png");
define("COLLECTION_EMAIL_ICON", WEBSITE_URL . "public/salaryontime.in/emailimages/collection/image/emil-icon.png");
define("COLLECTION_WEB_ICON", WEBSITE_URL . "public/salaryontime.in/emailimages/collection/image/web-icon.png");

// *********SOCIAL MEDIA LINK ********

define("APPLE_STORE_LINK", "#");
define("ANDROID_STORE_LINK", "#");
define("LINKEDIN_LINK", "https://www.linkedin.com/company/KS");
define("INSTAGRAM_LINK", "https://www.instagram.com/KS");
define("FACEBOOK_LINK", "https://www.facebook.com/KS");
define("YOUTUBE_LINK", "https://www.youtube.com/channel/KS");
define("TWITTER_LINK", "https://twitter.com/KS");

// ******* SOCIAL MEDIA ICONS ***********

define("APPLE_STORE_ICON", WEBSITE_URL . "public/salaryontime.in/emailimages/Digilocker_eKyc/images/app_store_icon.png");
define("ANDROID_STORE_ICON", WEBSITE_URL . "public/salaryontime.in/emailimages/Digilocker_eKyc/images/google_play_icon.png");
define("LINKEDIN_ICON", WEBSITE_URL . "public/salaryontime.in/emailimages/Digilocker_eKyc/images/linked_in_icon.png");
define("INSTAGRAM_ICON", WEBSITE_URL . "public/salaryontime.in/emailimages/Digilocker_eKyc/images/instagram_icon.png");
define("FACEBOOK_ICON", WEBSITE_URL . "public/salaryontime.in/emailimages/Digilocker_eKyc/images/facebook_icon.png");
define("YOUTUBE_ICON", WEBSITE_URL . "public/salaryontime.in/emailimages/Digilocker_eKyc/images/youtube_icon.png");
define("TWITTER_ICON", WEBSITE_URL . "public/salaryontime.in/emailimages/Digilocker_eKyc/images/twitter_icon.png");

define("PHONE_ICON", WEBSITE_URL . "public/salaryontime.in/emailimages/Digilocker_eKyc/images/phone-icon.png");
define("WEB_ICON", WEBSITE_URL . "public/salaryontime.in/emailimages/Digilocker_eKyc/images/web_icon.png");
define("EMAIL_ICON", WEBSITE_URL . "public/salaryontime.in/emailimages/Digilocker_eKyc/images/emil_icon.png");

// ******* CRON JOBS ********

define("BIRTHDAY_LINE", WEBSITE_URL . "public/salaryontime.in/emailimages/birthday/line.png");
define("BIRTHDAY_BIRTHDAY_PIC", WEBSITE_URL . "public/salaryontime.in/emailimages/birthday/email-design.jpg");

define("FESTIVAL_BANNER", WEBSITE_URL . "public/salaryontime.in/emailimages/festiv/image/offer.jpg");
define("FESTIVAL_CLOSE_BANNER", WEBSITE_URL . "public/salaryontime.in/emailimages/new-cust/image/b.jpg");
define("FESTIVAL_OFFICIAL_NUMBER", WEBSITE_URL . "public/salaryontime.in/emailimages/festiv/image/phone-icon.png");
define("FESTIVAL_LINE", WEBSITE_URL . "public/salaryontime.in/emailimages/festiv/image/line.png");

define("BLOG", WEBSITE_URL . "public/blog/");


define("BY_PASS_OCR_KYC", TRUE);
define('ENCRYPTION_METHOD', 'AES-256-CBC');
define('SECRET_KEY', getenv("SECRET_KEY")); // replace with a secure key
define('SECRET_IV', getenv('SECRET_IV'));

