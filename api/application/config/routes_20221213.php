<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*

  | -------------------------------------------------------------------------

  | URI ROUTING

  | -------------------------------------------------------------------------

  | This file lets you re-map URI requests to specific controller functions.

  |

  | Typically there is a one-to-one relationship between a URL string

  | and its corresponding controller class/method. The segments in a

  | URL normally follow this pattern:

  |

  |	example.com/class/method/id/

  |

  | In some instances, however, you may want to remap this relationship

  | so that a different class/function is called than the one

  | corresponding to the URL.

  |

  | Please see the user guide for complete details:

  |

  |	https://codeigniter.com/user_guide/general/routing.html

  |

  | -------------------------------------------------------------------------

  | RESERVED ROUTES

  | -------------------------------------------------------------------------

  |

  | There are three reserved routes:

  |

  |	$route['default_controller'] = 'welcome';

  |

  | This route indicates which controller class should be loaded if the

  | URI contains no data. In the above example, the "welcome" class

  | would be loaded.

  |

  |	$route['404_override'] = 'errors/page_missing';

  |

  | This route will tell the Router which controller/method to use if those

  | provided in the URL cannot be matched to a valid route.

  |

  |	$route['translate_uri_dashes'] = FALSE;

  |

  | This is not exactly a route, but allows you to automatically route

  | controller and method names that contain dashes. '-' isn't a valid

  | class or method name character, so it requires translation.

  | When you set this option to TRUE, it will replace ALL dashes in the

  | controller and method URI segments.

  |

  | Examples:	my-controller/index	-> my_controller/index

  |		my-controller/my-method	-> my_controller/my_method

 */


$route['UserVerificationNew']['post'] = 'Api/LMSSanctionApp/LoginController/qdeAppSaveRegistration';
$route['OTPVerifyNew']['post'] = 'Api/LMSSanctionApp/LoginController/qdeAppOtpVerify';
$route['resendOtp']['post'] = 'Api/LMSSanctionApp/LoginController/qdeAppResendOTP';
$route['SaveCustomer']['post'] = 'Api/LMSSanctionApp/LoginController/qdeAppSaveCustomer';
$route['SaveLeadApp']['post'] = 'Api/LMSSanctionApp/LoginController/qdeAppApplyLoan';
$route['userresidencedetails']['post'] = 'Api/LMSSanctionApp/LoginController/qdeAppCustomerResidenceDetails';
$route['saveOfficeAddress']['post'] = 'Api/LMSSanctionApp/LoginController/qdeAppSaveOfficeDetails';
$route['saverefrencedetails']['post'] = 'Api/LMSSanctionApp/LoginController/qdeAppSaveReferenceDetails';
$route['savebankdetils']['post'] = 'Api/LMSSanctionApp/LoginController/qdeAppSaveBankDetails';
$route['uploadDocuments']['post'] = 'Api/LMSSanctionApp/LoginController/qdeAppUploadDocuments';
$route['thankyou']['post'] = 'Api/LMSSanctionApp/LoginController/thankyou';

$route['getCityName']['post'] = 'Api/LMSSanctionApp/LoginController/getCityName';
$route['refrenceDetails']['post'] = 'Api/LMSSanctionApp/LoginController/getReferenceMasterList';
$route['getallifsc']['post'] = 'Api/LMSSanctionApp/LoginController/getIFSCMasterList';
$route['getAllRecordsFromMobile']['post'] = 'Api/LMSSanctionApp/LoginController/qdeAppAllRecordsFromMobile';
$route['masterAPI']['post'] = 'Api/LMSSanctionApp/MasterController/masterAPI';
$route['getState']['post'] = 'Api/LMSSanctionApp/LoginController/GetState';
$route['getCity']['post'] = 'Api/LMSSanctionApp/LoginController/GetCity';
$route['getUserType']['post'] = 'Api/LMSSanctionApp/LoginController/getUserType';
$route['residenceType']['post'] = 'Api/LMSSanctionApp/LoginController/residenceType';
$route['getPupposeOfLoan']['post'] = 'Api/LMSSanctionApp/LoginController/getPupposeOfLoan';
$route['getbankdetails']['post'] = 'Api/LMSSanctionApp/LoginController/getbankdetails';

/* * ******************************************************************************* */

/* * **********************Android App V1 URLs*************************************** */
$route['checkAppVersionARD']['post'] = 'Api/ANDROIDAPP/AndroidController/qdeAppVersionCheck';
$route['getOTPARD']['post'] = 'Api/ANDROIDAPP/AndroidController/qdeAppSaveRegistration';
$route['getOTPVerifyARD']['post'] = 'Api/ANDROIDAPP/AndroidController/qdeAppOtpVerify';
$route['getResendOtpARD']['post'] = 'Api/ANDROIDAPP/AndroidController/qdeAppResendOTP';
$route['savePersonalDetailsARD']['post'] = 'Api/ANDROIDAPP/AndroidController/qdeAppSaveCustomer';
$route['applyLoanQuoteARD']['post'] = 'Api/ANDROIDAPP/AndroidController/qdeAppApplyLoan';
$route['saveResidenceAddressARD']['post'] = 'Api/ANDROIDAPP/AndroidController/qdeAppSaveResidenceAddresss';
$route['saveOfficeAddressARD']['post'] = 'Api/ANDROIDAPP/AndroidController/qdeAppSaveOfficeDetails';
$route['saveReferenceDetailsARD']['post'] = 'Api/ANDROIDAPP/AndroidController/qdeAppSaveReferenceDetails';
$route['saveBankDetailsARD']['post'] = 'Api/ANDROIDAPP/AndroidController/qdeAppSaveBankDetails';
$route['uploadDocumentsARD']['post'] = 'Api/ANDROIDAPP/AndroidController/qdeAppUploadDocuments';
$route['thankyouARD']['post'] = 'Api/ANDROIDAPP/AndroidController/qdeAppThankYou';

$route['getCityNameARD']['post'] = 'Api/ANDROIDAPP/AndroidController/qdeAppGetCityName';
$route['getReferenceMasterARD']['post'] = 'Api/ANDROIDAPP/AndroidController/getReferenceMasterList';
$route['getSearchIFSCCodeARD']['post'] = 'Api/ANDROIDAPP/AndroidController/qdeAppGetIFSCMasterList';
$route['getCustomerDetailsARD']['post'] = 'Api/ANDROIDAPP/AndroidController/qdeAppGetLeadDetails';
$route['getMasterDataARD']['post'] = 'Api/ANDROIDAPP/MasterController/masterAPI';
$route['getStateARD']['post'] = 'Api/ANDROIDAPP/AndroidController/GetState';
$route['getCityARD']['post'] = 'Api/ANDROIDAPP/AndroidController/GetCity';
$route['getAllCityARD']['post'] = 'Api/ANDROIDAPP/AndroidController/getAllCity';
$route['getUserTypeARD']['post'] = 'Api/ANDROIDAPP/AndroidController/getUserType';
$route['residenceTypeARD']['post'] = 'Api/ANDROIDAPP/AndroidController/residenceType';
$route['getPupposeOfLoanARD']['post'] = 'Api/ANDROIDAPP/AndroidController/getPupposeOfLoan';
$route['getBankDetailsARD']['post'] = 'Api/ANDROIDAPP/AndroidController/qdeAppGetBankDetails';

/* * **********************Android App V2 URLs*************************************** */

$route['checkAppVersionARD2']['post'] = 'Api/ANDROIDAPP/Android2Controller/qdeAppVersionCheck';
$route['getOTPARD2']['post'] = 'Api/ANDROIDAPP/Android2Controller/qdeAppSaveRegistration';
$route['getOTPVerifyARD2']['post'] = 'Api/ANDROIDAPP/Android2Controller/qdeAppOtpVerify';
$route['getResendOtpARD2']['post'] = 'Api/ANDROIDAPP/Android2Controller/qdeAppResendOTP';
$route['savePersonalDetailsARD2']['post'] = 'Api/ANDROIDAPP/Android2Controller/qdeAppSaveCustomer';
$route['applyLoanQuoteARD2']['post'] = 'Api/ANDROIDAPP/Android2Controller/qdeAppApplyLoan';
$route['saveResidenceAddressARD2']['post'] = 'Api/ANDROIDAPP/Android2Controller/qdeAppSaveResidenceAddresss';
$route['saveOfficeAddressARD2']['post'] = 'Api/ANDROIDAPP/Android2Controller/qdeAppSaveOfficeDetails';
$route['saveReferenceDetailsARD2']['post'] = 'Api/ANDROIDAPP/Android2Controller/qdeAppSaveReferenceDetails';
$route['saveBankDetailsARD2']['post'] = 'Api/ANDROIDAPP/Android2Controller/qdeAppSaveBankDetails';
$route['uploadDocumentsARD2']['post'] = 'Api/ANDROIDAPP/Android2Controller/qdeAppUploadDocuments';
$route['requiredUploadedDocsARD2']['post'] = 'Api/ANDROIDAPP/Android2Controller/qdeAppRequiredUploadedDocs';
$route['thankyouARD2']['post'] = 'Api/ANDROIDAPP/Android2Controller/qdeAppThankYou';

$route['getCityNameARD2']['post'] = 'Api/ANDROIDAPP/Android2Controller/qdeAppGetCityName';
$route['getReferenceMasterARD2']['post'] = 'Api/ANDROIDAPP/Android2Controller/getReferenceMasterList';
$route['getSearchIFSCCodeARD2']['post'] = 'Api/ANDROIDAPP/Android2Controller/qdeAppGetIFSCMasterList';
$route['getCustomerDetailsARD2']['post'] = 'Api/ANDROIDAPP/Android2Controller/qdeAppGetLeadDetails';
$route['getMasterDataARD2']['post'] = 'Api/ANDROIDAPP/MasterController/masterAPI';
$route['getStateARD2']['post'] = 'Api/ANDROIDAPP/Android2Controller/GetState';
$route['getCityARD2']['post'] = 'Api/ANDROIDAPP/Android2Controller/GetCity';
$route['getAllCityARD2']['post'] = 'Api/ANDROIDAPP/Android2Controller/getAllCity';
$route['getUserTypeARD2']['post'] = 'Api/ANDROIDAPP/Android2Controller/getUserType';
$route['residenceTypeARD2']['post'] = 'Api/ANDROIDAPP/Android2Controller/residenceType';
$route['getPupposeOfLoanARD2']['post'] = 'Api/ANDROIDAPP/Android2Controller/getPupposeOfLoan';
$route['getBankDetailsARD2']['post'] = 'Api/ANDROIDAPP/Android2Controller/qdeAppGetBankDetails';

/* * ******************************************************************************* */

/* * **********************Android App V3 URLs*************************************** */

$route['checkAppVersionARD3']['post'] = 'Api/ANDROIDAPP/Android3Controller/qdeAppVersionCheck';
$route['getOTPARD3']['post'] = 'Api/ANDROIDAPP/Android3Controller/qdeAppSaveRegistration';
$route['getOTPVerifyARD3']['post'] = 'Api/ANDROIDAPP/Android3Controller/qdeAppOtpVerify';
$route['getResendOtpARD3']['post'] = 'Api/ANDROIDAPP/Android3Controller/qdeAppResendOTP';
$route['savePersonalDetailsARD3']['post'] = 'Api/ANDROIDAPP/Android3Controller/qdeAppSaveCustomer';
$route['applyLoanQuoteARD3']['post'] = 'Api/ANDROIDAPP/Android3Controller/qdeAppApplyLoan';
$route['saveResidenceAddressARD3']['post'] = 'Api/ANDROIDAPP/Android3Controller/qdeAppSaveResidenceAddresss';
$route['saveOfficeAddressARD3']['post'] = 'Api/ANDROIDAPP/Android3Controller/qdeAppSaveOfficeDetails';
$route['saveReferenceDetailsARD3']['post'] = 'Api/ANDROIDAPP/Android3Controller/qdeAppSaveReferenceDetails';
$route['saveBankDetailsARD3']['post'] = 'Api/ANDROIDAPP/Android3Controller/qdeAppSaveBankDetails';
$route['uploadDocumentsARD3']['post'] = 'Api/ANDROIDAPP/Android3Controller/qdeAppUploadDocuments';
$route['requiredUploadedDocsARD3']['post'] = 'Api/ANDROIDAPP/Android3Controller/qdeAppRequiredUploadedDocs';
$route['thankyouARD3']['post'] = 'Api/ANDROIDAPP/Android3Controller/qdeAppThankYou';

$route['getCityNameARD3']['post'] = 'Api/ANDROIDAPP/Android3Controller/qdeAppGetCityName';
$route['getReferenceMasterARD3']['post'] = 'Api/ANDROIDAPP/Android3Controller/getReferenceMasterList';
$route['getSearchIFSCCodeARD3']['post'] = 'Api/ANDROIDAPP/Android3Controller/qdeAppGetIFSCMasterList';
$route['getCustomerDetailsARD3']['post'] = 'Api/ANDROIDAPP/Android3Controller/qdeAppGetLeadDetails';
$route['getMasterDataARD3']['post'] = 'Api/ANDROIDAPP/MasterController/masterAPI';
$route['getStateARD3']['post'] = 'Api/ANDROIDAPP/Android3Controller/GetState';
$route['getCityARD3']['post'] = 'Api/ANDROIDAPP/Android3Controller/GetCity';
$route['getAllCityARD3']['post'] = 'Api/ANDROIDAPP/Android3Controller/getAllCity';
$route['getUserTypeARD3']['post'] = 'Api/ANDROIDAPP/Android3Controller/getUserType';
$route['residenceTypeARD3']['post'] = 'Api/ANDROIDAPP/Android3Controller/residenceType';
$route['getPupposeOfLoanARD3']['post'] = 'Api/ANDROIDAPP/Android3Controller/getPupposeOfLoan';
$route['getBankDetailsARD3']['post'] = 'Api/ANDROIDAPP/Android3Controller/qdeAppGetBankDetails';

/* * ******************************************************************************* */

/* * **************************IOS LOANWALLE APP URLS Version 1********************************* */
//Version Check
$route['checkAppVersionIOS']['post'] = 'Api/IOSAPP/IOSQDEController/qdeAppVersionCheck';
//master
$route['getCityNameIOS']['post'] = 'Api/IOSAPP/IOSQDEController/qdeAppGetCityName';
$route['getSearchIFSCCodeIOS']['post'] = 'Api/IOSAPP/IOSQDEController/qdeAppGetIFSCMasterList';
$route['getBankDetailsIOS']['post'] = 'Api/IOSAPP/IOSQDEController/qdeAppGetBankDetails';
$route['getMasterDataIOS']['post'] = 'Api/IOSAPP/IOSMasterController/qdeAppMasterAPI';
//app actions
$route['getCustomerDetailsIOS']['post'] = 'Api/IOSAPP/IOSQDEController/qdeAppGetLeadDetails';
$route['getOTPIOS']['post'] = 'Api/IOSAPP/IOSQDEController/qdeAppSaveRegistration';
$route['getResendOtpIOS']['post'] = 'Api/IOSAPP/IOSQDEController/qdeAppResendOTP';
$route['getOTPVerifyIOS']['post'] = 'Api/IOSAPP/IOSQDEController/qdeAppOtpVerify';
$route['savePersonalDetailsIOS']['post'] = 'Api/IOSAPP/IOSQDEController/qdeAppSaveCustomer';
$route['applyLoanQuoteIOS']['post'] = 'Api/IOSAPP/IOSQDEController/qdeAppApplyLoan';
$route['saveResidenceAddressIOS']['post'] = 'Api/IOSAPP/IOSQDEController/qdeAppSaveResidenceAddresss';
$route['saveOfficeAddressIOS']['post'] = 'Api/IOSAPP/IOSQDEController/qdeAppSaveOfficeDetails';
$route['saveBankDetailsIOS']['post'] = 'Api/IOSAPP/IOSQDEController/qdeAppSaveBankDetails';
$route['saveReferenceDetailsIOS']['post'] = 'Api/IOSAPP/IOSQDEController/qdeAppSaveReferenceDetails';
$route['uploadDocumentsIOS']['post'] = 'Api/IOSAPP/IOSQDEController/qdeAppUploadDocuments';
$route['thankYouIOS']['post'] = 'Api/IOSAPP/IOSQDEController/qdeAppThankYou';

/* * *********************************************************************************************** */

/* * **************************IOS LOANWALLE APP URLS Version 2****on 2022-10-13***************************** */
//Version Check
$route['checkAppVersionIOS2']['post'] = 'Api/IOSAPP/IOSQDE2Controller/qdeAppVersionCheck';
//master
$route['getCityNameIOS2']['post'] = 'Api/IOSAPP/IOSQDE2Controller/qdeAppGetCityName';
$route['getSearchIFSCCodeIOS2']['post'] = 'Api/IOSAPP/IOSQDE2Controller/qdeAppGetIFSCMasterList';
$route['getBankDetailsIOS2']['post'] = 'Api/IOSAPP/IOSQDE2Controller/qdeAppGetBankDetails';
$route['getMasterDataIOS2']['post'] = 'Api/IOSAPP/IOSMasterController/qdeAppMasterAPI';
//app actions
$route['getCustomerDetailsIOS2']['post'] = 'Api/IOSAPP/IOSQDE2Controller/qdeAppGetLeadDetails';
$route['getOTPIOS2']['post'] = 'Api/IOSAPP/IOSQDE2Controller/qdeAppSaveRegistration';
$route['getResendOtpIOS2']['post'] = 'Api/IOSAPP/IOSQDE2Controller/qdeAppResendOTP';
$route['getOTPVerifyIOS2']['post'] = 'Api/IOSAPP/IOSQDE2Controller/qdeAppOtpVerify';
$route['savePersonalDetailsIOS2']['post'] = 'Api/IOSAPP/IOSQDE2Controller/qdeAppSaveCustomer';
$route['applyLoanQuoteIOS2']['post'] = 'Api/IOSAPP/IOSQDE2Controller/qdeAppApplyLoan';
$route['saveResidenceAddressIOS2']['post'] = 'Api/IOSAPP/IOSQDE2Controller/qdeAppSaveResidenceAddresss';
$route['saveOfficeAddressIOS2']['post'] = 'Api/IOSAPP/IOSQDE2Controller/qdeAppSaveOfficeDetails';
$route['saveBankDetailsIOS2']['post'] = 'Api/IOSAPP/IOSQDE2Controller/qdeAppSaveBankDetails';
$route['saveReferenceDetailsIOS2']['post'] = 'Api/IOSAPP/IOSQDE2Controller/qdeAppSaveReferenceDetails';
$route['requiredUploadedDocsIOS2']['post'] = 'Api/IOSAPP/IOSQDE2Controller/qdeAppRequiredUploadedDocs';
$route['uploadDocumentsIOS2']['post'] = 'Api/IOSAPP/IOSQDE2Controller/qdeAppUploadDocuments';
$route['thankYouIOS2']['post'] = 'Api/IOSAPP/IOSQDE2Controller/qdeAppThankYou';

//**************************************  Collex APP API **************************************//

$route['collexAuth']['post'] = 'Api/CollectionApp/CollectionController/collexAuth';
$route['collexAuthLogout']['post'] = 'Api/CollectionApp/CollectionController/collexAuthLogout';
$route['collexAuthOtpVerification']['post'] = 'Api/CollectionApp/CollectionController/collexAuthOtpVerification';
$route['collexGetTotalCollection']['post'] = 'Api/CollectionApp/CollectionController/collexGetTotalCollection';
$route['collexGetLoanDetails']['post'] = 'Api/CollectionApp/CollectionController/collexGetLoanDetails';
$route['collexGetUserProfile']['post'] = 'Api/CollectionApp/CollectionController/collexGetUserProfile';
$route['collexGetVisitAndManagerDetails']['post'] = 'Api/CollectionApp/CollectionController/collexGetVisitAndManagerDetails';
$route['collexGetRepaymentDetails']['post'] = 'Api/CollectionApp/CollectionController/collexGetRepaymentDetails';
$route['collexFeStartEndVisit']['post'] = 'Api/CollectionApp/CollectionController/collexFeStartEndVisit';
$route['collexUpdateFollowupAndCollection']['post'] = 'Api/CollectionApp/CollectionController/collexUpdateFollowupAndCollection';
$route['collexReturnFromVisit']['post'] = 'Api/CollectionApp/CollectionController/collexReturnFromVisit';
$route['collexGetListPaymentMode']['post'] = 'Api/CollectionApp/CollectionController/collexGetListPaymentMode';
$route['collexGetListMasterStatus']['post'] = 'Api/CollectionApp/CollectionController/collexGetListMasterStatus';
$route['collexAppVersionCheck']['post'] = 'Api/CollectionApp/CollectionController/collexAppVersionCheck';

$route['test_api']['post'] = 'Api/CollectionApp/CollectionController/test_api';

//************************************** API FOR PRODUCTION APP **************************************// 
//$route['userRegistration']['post'] = 'Api/ProdApi/ProdController/userRegistration';
//$route['userVerificationProd']['post'] = 'Api/ProdApi/ProdController/userVerificationProd'; //
//$route['vinSaveTasks']['post'] = 'Api/ProdApi/ProdController/vinSaveTasks';
//
//$route['getStatepro']['post'] = 'Api/ProdApi/ProdController/getStatepro';
//$route['getCitypro']['post'] = 'Api/ProdApi/ProdController/getCitypro';

/* * ************Connector Exposed**************** */
$route['getQdeAppState']['get'] = 'Connector/QdeApi/getState';
$route['getQdeAppCity']['post'] = 'Connector/QdeApi/getCity';
$route['saveQdeApp']['post'] = 'Connector/QdeApi/saveQdeApp';
$route['otpVerifyQdeApp']['post'] = 'Connector/QdeApi/otpVerifyQdeApp';
/* * ****************************************** */

//**************************************  Loanwalle Feedback form API **************************************//

$route['get_customer_details']['post'] = 'Api/FeedbackApi/get_customer_details';
$route['save_customer_feedback']['post'] = 'Api/FeedbackApi/save_customer_feedback';

/* * ******************Chat Bot API URL**************** */

$route['saveQdeAppChatBot']['post'] = 'Api/ChatBot/ChatBotController/qdeAppSaveRegistration';
$route['getOTPVerifyChatBot']['post'] = 'Api/ChatBot/ChatBotController/qdeAppOtpVerify';
$route['getResendOtpChatBot']['post'] = 'Api/ChatBot/ChatBotController/qdeAppResendOTP';
$route['getReLoanChatBot']['post'] = 'Api/ChatBot/ChatBotController/qdeAppReLoanRequest';
$route['getLoanStatusChatBot']['post'] = 'Api/ChatBot/ChatBotController/qdeAppLoanStatus';
$route['getLeadStatusChatBot']['post'] = 'Api/ChatBot/ChatBotController/qdeAppLeadStatus';
$route['getMasterDataChatBot']['post'] = 'Api/ChatBot/ChatBotController/qdeAppMasterAPI';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
