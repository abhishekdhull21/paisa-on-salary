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


defined('CSS_VERSION') OR define('CSS_VERSION', 1); // highest automatically-assigned error code

defined('ALL_FROM_EMAIL') OR define('ALL_FROM_EMAIL', 'info@surya.com');

defined('BCC_SANCTION_EMAIL') OR define('BCC_SANCTION_EMAIL', 'tech.team@surya.com');
defined('BCC_DISBURSAL_EMAIL') OR define('BCC_DISBURSAL_EMAIL', '');
defined('BCC_DISBURSAL_WAIVE_EMAIL') OR define('BCC_DISBURSAL_WAIVE_EMAIL', 'tech.team@surya.com');

defined('CC_SANCTION_EMAIL') OR define('CC_SANCTION_EMAIL', '');
defined('CC_DISBURSAL_EMAIL') OR define('CC_DISBURSAL_EMAIL', '');
defined('CC_DISBURSAL_WAIVE_EMAIL') OR define('CC_DISBURSAL_WAIVE_EMAIL', 'tech.team@surya.com');

defined('TO_KYC_DOCS_ZIP_DOWNLOAD_EMAIL') OR define('TO_KYC_DOCS_ZIP_DOWNLOAD_EMAIL', 'tech.team@surya.com');

define("COMPONENT_PATH", "/home/devmunitechuat/public_html/common_component/");
define("UPLOAD_PATH", "/home/devmunitechuat/public_html/upload/");

define("LOANS_KYC_DOCS", "/kycdocs/loans/");

define("FEEDBACK_WEB_PATH", "https://uat-website.devmunitech.com/customer-feedback/");

// ********** LMS DEFINED VARIABLE *****

define("LMS_URL", "https://devmunitech.com/");
define("WEBSITE_URL", "https://uat-website.devmunitech.com/");
define("WEBSITE", "uat-website.devmunitech.com");
define("WEBSITE_UTM_SOURCE", WEBSITE_URL . "apply-now?utm_source=");

define("LMS_COMPANY_LOGO", LMS_URL . "public/front/img/company_logo.png");
define("LMS_BRAND_LOGO", LMS_URL . "public/front/img/brand_logo.jpg");

define("COMPANY_NAME", "DEVMUNI LEASING & FINANCE LIMITED");
define("RBI_LICENCE_NUMBER", "B_14.02719");
define("BRAND_NAME", "Bharat Loan");
define('CONTACT_PERSON','ALOK NAUTIYAL');
define("REGISTED_ADDRESS", "1689/121, Shanti Nagar, Tri Nagar, New Delhi, North West Delhi, Delhi, 110035");
define("REGISTED_MOBILE", "+91-8282824-644");

define("TECH_EMAIL", "tech.team@surya.com");
define("INFO_EMAIL", "tech.team@surya.com");
define("CARE_EMAIL", "tech.team@surya.com");
define("RECOVERY_EMAIL", "tech.team@surya.com");
define("COLLECTION_EMAIL", "tech.team@surya.com");
define("CTO_EMAIL", "shubham@surya.com");

// ********** TEMPLETE DEFINED VARIABLE *****

define("EMAIL_BRAND_LOGO", WEBSITE_URL . "public/emailimages/disbursal_template_logo.jpg");
define("DISBURSAL_LETTER_BANNER", WEBSITE_URL . "public/emailimages/disbursal_banner.jpg");

define("SANCTION_LETTER_HEADER", WEBSITE_URL . "public/emailimages/sanction_letterhead.jpg");
define("SANCTION_LETTER_FOOTER", WEBSITE_URL . "public/emailimages/sanction_letterfooter.png");

define("GENERATE_SANCTION_LETTER_HEADER", LMS_URL . "public/emailimages/sanction-letterhead.jpg");
define("GENERATE_SANCTION_LETTER_FOOTER", LMS_URL . "public/emailimages/sanction-letterfooter.png");

define("EKYC_BRAND_LOGO", WEBSITE_URL . "public/emailimages/Digilocker_eKyc/images/ekyc_brand_logo.gif");
define("EKYC_HEADER_BACK", WEBSITE_URL . "public/emailimages/Digilocker_eKyc/images/header_back.jpg");
define("EKYC_LINES", WEBSITE_URL . "public/emailimages/Digilocker_eKyc/images/line.png");
define("EKYC_IMAGES_1", WEBSITE_URL . "public/emailimages/Digilocker_eKyc/images/1st.jpg");
define("EKYC_IMAGES_1_SHOW", WEBSITE_URL . "public/emailimages/Digilocker_eKyc/images/image1.png");
define("EKYC_IMAGES_2", WEBSITE_URL . "public/emailimages/Digilocker_eKyc/images/2nd.jpg");
define("EKYC_IMAGES_2_SHOW", WEBSITE_URL . "public/emailimages/Digilocker_eKyc/images/image2.png");
define("EKYC_IMAGES_3", WEBSITE_URL . "public/emailimages/Digilocker_eKyc/images/image3.png");
define("EKYC_IMAGES_3_SHOW", WEBSITE_URL . "public/emailimages/Digilocker_eKyc/images/3rd.jpg");
define("EKYC_IMAGES_4", WEBSITE_URL . "public/emailimages/Digilocker_eKyc/images/4th.jpg");
define("EKYC_IMAGES_4_SHOW", WEBSITE_URL . "public/emailimages/Digilocker_eKyc/images/4th.jpg");

define("LOAN_REPAY_LINK", WEBSITE_URL . "repay-loan");
define("AUTHORISED_SIGNATORY", WEBSITE_URL . "public/front/images/Authorised-Signatory.jpeg");

define("PRE_APPROVED_LINES", WEBSITE_URL . "public/emailimages/final-email-template/images/back-line.png");
define("PRE_APPROVED_BANNER", WEBSITE_URL . "public/emailimages/final-email-template/images/email-surya.gif");
define("PRE_APPROVED_LINE_COLOR", WEBSITE_URL . "public/emailimages/final-email-template/images/line-color.png");
define("PRE_APPROVED_PHONE_ICON", WEBSITE_URL . "public/emailimages/final-email-template/images/phone-icon.png");
define("PRE_APPROVED_WEB_ICON", WEBSITE_URL . "public/emailimages/final-email-template/images/web-icon.png");
define("PRE_APPROVED_EMAIL_ICON", WEBSITE_URL . "public/emailimages/final-email-template/images/emil-icon.png");
define("PRE_APPROVED_ARROW_LEFT", WEBSITE_URL . "public/emailimages/final-email-template/images/arrow-left.png");
define("PRE_APPROVED_ARROW_RIGHT", WEBSITE_URL . "public/emailimages/final-email-template/images/arrow-right.png");

define("FEEDBACK_HEADER", WEBSITE_URL . "public/emailimages/feedback/images/header2.jpg");
define("FEEDBACK_LINE", WEBSITE_URL . "public/emailimages/feedback/images/line.png");
define("FEEDBACK_PHONE_ICON", WEBSITE_URL . "public/emailimages/feedback/images/phone-icon.png");
define("FEEDBACK_WEB_ICON", WEBSITE_URL . "public/emailimages/feedback/images/web-icon.png");
define("FEEDBACK_EMAIL_ICON", WEBSITE_URL . "public/emailimages/feedback/images/email-icon.png");

define("COLLECTION_BRAND_LOGO", WEBSITE_URL . "public/emailimages/collection/image/lw-logo.png");
define("COLLECTION_EXE_BANNER", WEBSITE_URL . "public/emailimages/collection/image/Collection-Executive-banner.jpg");
define("COLLECTION_LINE", WEBSITE_URL . "public/emailimages/collection/image/line.png");
define("COLLECTION_INR_ICON", WEBSITE_URL . "public/emailimages/collection/image/inr-icon.png");
define("COLLECTION_ROAD_BANNER", WEBSITE_URL . "public/emailimages/collection/image/CRM.jpg");
define("COLLECTION_PHONE_ICON", WEBSITE_URL . "public/emailimages/collection/image/phone-icon.png");
define("COLLECTION_EMAIL_ICON", WEBSITE_URL . "public/emailimages/collection/image/emil-icon.png");
define("COLLECTION_WEB_ICON", WEBSITE_URL . "public/emailimages/collection/image/web-icon.png");

// *********SOCIAL MEDIA LINK ********

define("APPLE_STORE_LINK", "https://apps.apple.com/in/app/bharat-loan/id1671613689");
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

define("PHONE_ICON", WEBSITE_URL . "public/emailimages/Digilocker_eKyc/images/phone-icon.png");
define("WEB_ICON", WEBSITE_URL . "public/emailimages/Digilocker_eKyc/images/web_icon.png");
define("EMAIL_ICON", WEBSITE_URL . "public/emailimages/Digilocker_eKyc/images/emil_icon.png");

// ******* CRON JOBS ********

define("BIRTHDAY_LINE", WEBSITE_URL . "public/emailimages/birthday/line.png");
define("BIRTHDAY_BIRTHDAY_PIC", WEBSITE_URL . "public/emailimages/birthday/email-design.jpg");

define("FESTIVAL_BANNER", WEBSITE_URL . "public/emailimages/festiv/image/offer.jpg");
define("FESTIVAL_CLOSE_BANNER", WEBSITE_URL . "public/emailimages/new-cust/image/b.jpg");
define("FESTIVAL_OFFICIAL_NUMBER", WEBSITE_URL . "public/emailimages/festiv/image/phone-icon.png");
define("FESTIVAL_LINE", WEBSITE_URL . "public/emailimages/festiv/image/line.png");

define("MARKETING_BANNER2", WEBSITE_URL . "public/emailimages/personal-jan/images/loanwalle-email-marketing-2.jpg");
define("MARKETING_BANNER3", WEBSITE_URL . "public/emailimages/personal-jan/images/loanwalle-email-marketing-3.png");
define("MARKETING_BACK", WEBSITE_URL . "public/emailimages/personal-jan/images/loanwalle-email-marketing-back.JPG");
define("MARKETING_BANNER_RUPEE_ICON", WEBSITE_URL . "public/emailimages/personal-jan/images/icon.png");
define("MARKETING_BANNER_APPLY_BUTTON", WEBSITE_URL . "public/emailimages/personal-jan/images/button.PNG");
define("MARKETING_BANNER_APP_STORE_ICON", WEBSITE_URL . "public/emailimages/personal-jan/images/app_store.PNG");
define("MARKETING_BANNER_FB_ICON", WEBSITE_URL . "public/emailimages/personal-jan/images/fb.png");
define("MARKETING_BANNER_INSTAGRAM_ICON", WEBSITE_URL . "public/emailimages/personal-jan/images/instagram.png");
define("MARKETING_BANNER_TWITTER_ICON", WEBSITE_URL . "public/emailimages/personal-jan/images/twitter.png");
define("MARKETING_BANNER_LINKEDIN_ICON", WEBSITE_URL . "public/emailimages/personal-jan/images/linkedin.png");
define("MARKETING_BANNER_YOUTUBE_ICON", WEBSITE_URL . "public/emailimages/personal-jan/images/youtube.png");
define("MARKETING_BANNER_PLAY_STORE_ICON", WEBSITE_URL . "public/emailimages/personal-jan/images/play_store.PNG");
define("MARKETING_LINE", WEBSITE_URL . "public/emailimages/personal-jan/images/loanwalle-email-line.png");
define("MARKETING_FOOTER_LINE", WEBSITE_URL . "public/emailimages/personal-jan/images/loanwalle-email-hr.png");
define("MARKETING_APPLY_NOW", WEBSITE_URL . "public/emailimages/personal-jan/images/loanwalle-email-marketing-tc-apply.png");

define("OUTSTANDING_DEBIT", WEBSITE_URL . "public/emailimages/Outstanding/images/top-back.jpg");
define("OUTSTANDING_LINE", WEBSITE_URL . "public/emailimages/Outstanding/images/line.png");
define("OUTSTANDING_BACKGROUND", WEBSITE_URL . "public/emailimages/Outstanding/images/background.jpg");
define("OUTSTANDING_FOOTER_LINK", WEBSITE_URL . "public/emailimages/Outstanding/images/back2-new.jpg");

define("REMINDER_LINE", WEBSITE_URL . "public/emailimages/reminder-payment/image/line.png");
define("REMINDER_SOCIAL_LINK", WEBSITE_URL . "public/emailimages/reminder-payment/image/social-line.png");
define("REMINDER_FOOTER", WEBSITE_URL . "public/emailimages/reminder-payment/image/back2-new.jpg");
define("REMINDER_HAND_SHAKE", WEBSITE_URL . "public/emailimages/28-feb/image/header-image2.jpg");
define("REMINDER_5_DAY", WEBSITE_URL . "public/emailimages/reminder-payment/image/5-day-left.png");
define("REMINDER_4_DAY", WEBSITE_URL . "public/emailimages/reminder-payment/image/4-day-left.png");
define("REMINDER_3_DAY", WEBSITE_URL . "public/emailimages/reminder-payment/image/3-day-left.png");
define("REMINDER_2_DAY", WEBSITE_URL . "public/emailimages/reminder-payment/image/2-day-left.png");
define("REMINDER_1_DAY", WEBSITE_URL . "public/emailimages/reminder-payment/image/1-day-left.png");
define("REMINDER_ON_DAY", WEBSITE_URL . "public/emailimages/reminder-payment/image/due-date.png");
//define("REMINDER_PAYMENT_REMINDER", WEBSITE_URL . "public/emailimages/reminder-payment/image/back1.jpg");
define("REMINDER_PAYMENT_REMINDER", WEBSITE_URL . "public/emailimages/reminder-payment/image/back1-new.jpg");
define("BRAND_TRANSPARENT_LOGO", WEBSITE_URL . "public/emailimages/reminder-payment/image/brand_logo.png");
define("REMINDER_PAYMENT_TODAY", WEBSITE_URL . "public/emailimages/reminder-payment/image/background-due-date.jpg");
