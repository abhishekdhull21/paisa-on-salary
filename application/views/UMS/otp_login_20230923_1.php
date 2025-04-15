
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>OTP Verification</title>
        <link rel="icon" href="<?= base_url('public/front'); ?>/images/fav.png" type="image/*" />
        <link rel="stylesheet preload" href="<?= base_url('public/front'); ?>/css/bootstrap.min.css">
        <link rel="stylesheet preload" href="<?= base_url('public/front'); ?>/css/bootstrap.css">
        <link rel="stylesheet preload" href="<?= base_url('public/front'); ?>/css/font-awesome.min.css">
        <link rel="stylesheet preload" href="<?= base_url('public/front'); ?>/css/style.css">
        <script src="<?= base_url('public/front'); ?>/js/jquery.3.5.1.min.js"></script>
        <script>
            $(document).ready(function(){
              $("a.close").click(function(){
                $("#myDIV").hide();
              });

            });
        </script>
        <style>
            body {
        
                background-image: url('<?= base_url('public/front'); ?>/../images/login_background_img.jpg');
                background-position: center center;
                background-repeat: no-repeat;
                background-attachment: fixed;
                background-size: cover;
                background-color: #464646;
            }
            .verify_reset{
                display: flex;
            }
            .verify_reset #userSigin{
                width: 50% !important;
                margin-left: 0 !important;
            }
            .verify_reset #resendOtp{
                width: 50% !important;
                margin-left: 0 !important;
            }
            .form {
                background: #fff;
                border: 1px solid #fff;
                padding: 20px;
                margin: 18%;
                box-shadow: 0px 0px 5px gray;
                border-radius: 0px 50px;
            }

            input[type="text"],
            input[type="password"] {
                height: 45px;
                border-top: 0;
                border-left: 0;
                border-right: 0;
                border-radius: 0;
                text-align: center;
            }

            button[id="userSigin"] {
                width: 41%;
                margin-left: 9%;
                height: 45px;
                border-top: 0;
                border-left: 0;
                border-right: 0;
                border-radius: 0;
                text-align: center;
                background-color: #0d7ec0;
                color: #fff;
                float:left;
                margin-right: 10px;
              
            }
            a#resendOtp{
                width: 35%;
                padding-top: 10px;
                height: 45px;
                border-top: 0;
                border-left: 0;
                border-right: 0;
                border-radius: 0;
                text-align: center;
                background-color: #0d7ec0;
                color: #fff;
                padding-top: 12px;
                margin-left: 285px;
                display: block;
                text-decoration: none;
                
              }
            a#submitOtp {
              width: 35%;
              padding-top: 10px;
              height: 45px;
              border-top: 0;
              border-left: 0;
              border-right: 0;
              border-radius: 0;
              text-align: center;
              background-color: #0d7ec0;
              color: #fff;
              padding-top: 12px;
              float:left;
              margin-left: 80px;
              display: block;
              text-decoration: none;
            }
            button[id="userSigin"]:hover {
                background-color: #005d86;
                color: #fff;
            }

            h1 {
                color: #0d7ec0;
                font-size: 20px;
            }

            p {
                margin-bottom: 40px;
            }

            @media all and (max-width: 320px),
            (max-width: 375px),
            (max-width: 384px),
            (max-width: 414px),
            (max-device-width: 450px),
            (max-device-width: 480px),
            (max-device-width: 540px),
            (max-device-width: 590px),
            (max-device-width: 620px),
            (max-device-width: 680px) {
                .form {
                    background: #fff;
                    border: 1px solid #fff;
                    padding: 20px;
                    margin: 0%;
                    box-shadow: 0px 0px 5px gray;
                    border-radius: 0px 50px;
                    margin-top: 30%;
                }
            }
        </style>
    </head>

    <body>
        <?php
        $csrf = array(
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash()
        );
        ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="form">

                        <form method="post" action="<?= base_url('otpLogin'); ?>" id="formData" autocomplete="off">
                            
                            <input type="hidden" name="otp_flag" value="1" />
                            <p class="text-center">
                                <img class="img-rounded" src="<?= LMS_BRAND_LOGO ?>" alt="brand-logo">
                            </p>
                            <p class="text-center mb-4">
                                <!--<div class="titleSignin text-center"></div>-->
                            </p>
                            <?php if ($this->session->flashdata('msg') != '') { ?>
                                <p style="text-align:center" id="myDIV" class="alert alert-success alert-dismissible">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong><?= $this->session->flashdata('msg'); ?></strong>
                                </p>
                                <?php
                            }
                            if ($this->session->flashdata('err') != '') {
                                ?>
                                <p style="text-align:center"  id="myDIV" class="alert alert-danger alert-dismissible">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>'<?= $this->session->flashdata('err'); ?></strong>
                                </p>
                            <?php } ?>
                            
                            <div class="form-group">
                              <input type="text" name="otp" class="form-control" placeholder="OTP" title="OTP" maxlength="4" onkeypress="if (isNaN(String.fromCharCode(event.keyCode)))
                                                                            return false;" required autocomplete="off">
                            </div>
                           

                            <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />
                            
                            <input type="hidden" name="user_id" value="<?= $user_id; ?>" />
                           
                            <!-- </div> -->

                            <div class="form-group verify_reset">
                               <?php if($_SESSION["coutOtpResend"] <=3) { ?>
                                <button type="text" class="form-control" id="userSigin" title="Verify OTP">Submit</button>                      
                                <a href="<?= base_url('otpResend'); ?>" class="form-control" id="resendOtp" title="Resend OTP">RESEND OTP</a>
                               <?php }else {  ?>   
                                <button type="text" class="form-control" id="userSigin" title="Verify OTP">Submit</button>                     
                                <a href="<?= base_url(); ?>" class="form-control" id="resendOtp" style="pointer-events: none;" title="Resend OTP">RESEND OTP</a> 
                               <?php } ?>
                           
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>
