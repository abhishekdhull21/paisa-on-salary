<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Digital E-KYC</title>
    <style>
        body {font-family: Arial, Helvetica, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4;}
        table {width: 100%; max-width: 800px; margin: 0 auto; border-collapse: collapse;}
        .container {background: #ffffff; padding: 20px; border: 1px solid #ddd; border-radius: 8px;}
        .header {background: #8180e0; color: white; text-align: center; padding: 20px 0;}
        .header img {max-width: 180px;}
        .button {background: #8180e0; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px; text-align: center; display: inline-block; margin-top: 20px;}
        .footer {background: #8180e0; color: #fff; text-align: center; padding: 10px; font-size: 14px;}
        .footer a {color: #fff; text-decoration: none; margin: 0 10px;}
        .section-title {font-size: 20px; color: #8180e0; margin-bottom: 10px;}
        .section-text {font-size: 14px; line-height: 1.6; margin-bottom: 15px;}
        .section-text strong {color: #0c0ac2b1;}
    </style>
</head>
<body>

    <table>
        <tr>
            <td class="container">
                <div class="header">
                    <table width="100%">
                        <tr>
                            <td><a href="YOUR_WEBSITE_URL" target="_blank"><img src="<?= WEBSITE_URL ?>public/images/final_logo.png" alt="logo"></a></td>
                            <td><strong style="font-size: 24px;">DIGITAL E-KYC</strong></td>
                        </tr>
                    </table>
                </div>

                <p class="section-text"><strong>Dear <?php echo $customer_name; ?>,</strong></p>
                <p class="section-text">We thank you for showing interest in <?php echo WEBSITE; ?>. Your loan application has been assigned for credit approval.</p>
                <p class="section-text">In order to process your loan application further, please complete the e-KYC via DigiLocker using your Aadhaar.</p>
                <p class="section-text">Click the button below to proceed with the Digital E-KYC. You will be redirected to the DigiLocker portal, where you need to follow the steps provided in the <strong>"How it Works"</strong> section.</p>

                <a href="<?php echo $digital_ekyc_url; ?>" class="button">Digital E-KYC</a>

                <p class="section-text">If you're unable to click the button above, please copy and paste this URL into your browser: <a href="<?php echo $digital_ekyc_url; ?>"><?php echo $digital_ekyc_url; ?></a></p>

                <br />
                <br />
                
                <div class="section-title">How it Works</div>

                <table width="100%">
                    <tr>
                        <td class="section-text">
                            <strong>First Step</strong><br>
                            Enter your 12-digit Aadhaar number and press next.
                        </td>
                    </tr>
                    <tr>
                        <td class="section-text">
                            <strong>Second Step</strong><br>
                            Enter the OTP received on your registered mobile number with Aadhaar and press continue.
                        </td>
                    </tr>
                    <tr>
                        <td class="section-text">
                            <strong>Third Step</strong><br>
                            Press "Allow" to grant access to your DigiLocker account for document verification.
                        </td>
                    </tr>
                    <tr>
                        <td class="section-text">
                            <strong>Thank You</strong><br>
                            Your approval for DigiLocker account access for E-KYC has been successfully submitted.
                        </td>
                    </tr>
                </table>
                <br />
                <br />
                <br />
                <div class="footer">
                    <p>Follow us on social media:</p>
                    <a href="YOUR_LINKEDIN_URL" target="_blank"><img src="<?= WEBSITE_URL ?>public/images/linkedin.png" alt="LinkedIn" width="32" height="32"></a>
                    <a href="YOUR_INSTAGRAM_URL" target="_blank"><img src="<?= WEBSITE_URL ?>public/images/instagram.png" alt="Instagram" width="32" height="32"></a>
                    <a href="YOUR_FACEBOOK_URL" target="_blank"><img src="<?= WEBSITE_URL ?>public/images/facebook.png" alt="Facebook" width="32" height="32"></a>
                    <a href="YOUR_TWITTER_URL" target="_blank"><img src="<?= WEBSITE_URL ?>public/images/twitter.png" alt="Twitter" width="32" height="32"></a>
                    <a href="YOUR_YOUTUBE_URL" target="_blank"><img src="<?= WEBSITE_URL ?>public/images/youtube.png" alt="YouTube" width="32" height="32"></a>
                    <br><br>
                    <p>Contact Us:</p>
                    <p><img src="<?= WEBSITE_URL ?>public/images/phone.jpg" alt="phone-icon" width="16" height="16"><a href="tel:YOUR_PHONE_NUMBER" style="color: #fff;"><?php echo REGISTED_MOBILE; ?></a> | <a href="mailto:YOUR_EMAIL" style="color: #fff;"><?php echo INFO_EMAIL; ?></a></p>
                    <p><a href="YOUR_WEBSITE_URL" style="color: #fff;"><?php echo WEBSITE; ?></a></p>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
