<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

<div id="payday_data_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Data Response</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="payday_model_body"></div>
            </div>
        </div>
    </div>
</div>


<div id="achievement_popup"></div>


<footer>

    <div class="container">

        <div class="row">

            <div class="col-md-5">

                <div class="footer-content">

                    <h2>ABOUT</h2>

                    <p><?= COMPANY_NAME ?> is a NBFC registered with RBI having its registered office at <?= REGISTED_ADDRESS ?>. The company uses proprietary loan softwares for its various loan offerings to individual customers in a completely fintech environment.</p>

                </div>

            </div>

            <div class="col-md-4 footer-soci">

                <div class="footer-content">

                </div>

            </div>

            <div class="col-md-3">

                <div class="footer-support">

                    <h2>ADMIN</h2>

                    <p><i class="fa fa-envelope"></i>&nbsp;<a href="mailto:<?= TECH_EMAIL ?>"><?= TECH_EMAIL ?></a></p>

                    <!--<p><i class="fa fa-phone"></i>&nbsp;<a href="tel:+918936962573">+91 89369 62573</a></p>-->

                </div>

            </div>

        </div>

    </div>

</footer>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="<?= base_url('public/front'); ?>/dist/jquery.expander.min.js"></script>

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<script src="<?= base_url(); ?>public/js/jquery-1.11.2.min.js"></script>
<!-- <script src="<?= base_url(); ?>public/js/jquery-3.5.1.min.js"></script> -->

<!-- <script src="<?= base_url(); ?>public/js/jquery-migrate-1.2.1.min.js"></script> -->

<script src="<?= base_url(); ?>public/js/jRespond.min.js"></script>

<script src="<?= base_url(); ?>public/js/nav-accordion.js"></script>

<script src="<?= base_url(); ?>public/js/hoverintent.js"></script>

<script src="<?= base_url(); ?>public/js/waves.js"></script>

<script src="<?= base_url(); ?>public/js/switchery.js"></script>

<script src="<?= base_url(); ?>public/js/jquery.loadmask.js"></script>

<script src="<?= base_url(); ?>public/js/icheck.js"></script>

<script src="<?= base_url(); ?>public/js/bootstrap-filestyle.js"></script>

<script src="<?= base_url(); ?>public/js/bootbox.js"></script>


<script src="<?= base_url(); ?>public/js/colorpicker.js"></script>

<script src="<?= base_url(); ?>public/js/bootstrap-datepicker.js"></script>

<script src="<?= base_url(); ?>public/js/sweetalert.js"></script>

<script src="<?= base_url(); ?>public/js/moment.js"></script>

<script src="<?= base_url(); ?>public/js/summernote.min.js"></script>

<script src="<?= base_url(); ?>public/js/calendar/fullcalendar.js"></script>



<script src="<?= base_url(); ?>public/js/smart-resize.js"></script>

<script src="<?= base_url(); ?>public/js/layout.init.js"></script>

<script src="<?= base_url(); ?>public/js/matmix.init.js"></script>

<!-- <script src="<?= base_url(); ?>public/js/retina.min.js"></script> -->







<!--<script src="<?= base_url(); ?>public/front/js/dataTable/dataTable_1.10.25.min.js"></script>

<script src="<?= base_url(); ?>public/front/js/dataTable/dataTable.semantic.min.js"></script>



<script src="<?= base_url(); ?>public/js/jquery.dataTables.js"></script>

<script src="<?= base_url(); ?>public/js/dataTables.tableTools.js"></script>

<script src="<?= base_url(); ?>public/js/dataTables.bootstrap.js"></script>-->

<script src="<?= base_url(); ?>public/js/bootstrap.min.js"></script>

<!--<script src="<?= base_url(); ?>public/js/dataTables.responsive.js"></script>-->

<!--<script src="<?= base_url(); ?>public/js/stacktable.js"></script>-->

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script> -->

<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.7/summernote.js"></script> -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

<script src="<?= base_url(); ?>public/front/js/datepicker.min.js"></script>

<script src="<?= base_url(); ?>public/front/js/jquery.multiselect.js"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet" text="text/css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.js"></script> 

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="https://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css"></script>
<script src="https://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>

<script src="<?= base_url(); ?>public/front/js/ace-responsive-menu.js"></script>

<script src="<?= base_url(); ?>public/front/js/price-counter/jquery.countup.js"></script>





<script>
    var csrf_token = $("input[name=csrf_token]").val();
    document.querySelectorAll('#roi, #processing_fee_percent').forEach(input => {
        input.addEventListener('change', e => {
            let el = e.target;
            el.value = Math.min(Math.max(el.value, el.min), el.max);
        });
    });

    $(document).ready(function () {

        $(".togglebtn").click(function () {

            $(".shome").toggle("fast");

        });

    });
</script>
<script type="text/javascript">
    loader();

    function loader() {

        $(window).on('load', function () {

            $("#cover").fadeOut(1750);

        });

    }

    $(document).ready(function () {

        $(window).on('load', function () {

            $("#cover").fadeOut(1750);

        });

        $("#respMenu").aceResponsiveMenu({

            resizeWidth: '768', // Set the same in Media query

            animationSpeed: 'fast', //slow, medium, fast

            accoridonExpAll: false //Expands all the accordion menu on click

        });



        $('.counter').each(function () {

            $(this).prop('Counter', 0).animate({

                Counter: $(this).text()

            }, {

                duration: 3500,

                easing: 'swing',

                step: function (now) {

                    $(this).text(Math.ceil(now));

                }

            });

        });

    });
</script>

<!--counter end-->

<script type="text/javascript">
    var fullDate = new Date();

    var currentMonth = ((fullDate.getMonth().length + 1) === 1) ? (fullDate.getMonth() + 1) : '0' + (fullDate.getMonth() + 1);

    var fullDate = fullDate.getFullYear() + "-" + currentMonth + "-" + fullDate.getDate();

    // var valid_age =  fullDate.getFullYear() - 20 + "-" + currentMonth + "-" + fullDate.getDate();

    // var valid_start_age =  fullDate.getFullYear() - 60 + "-" + currentMonth + "-" + fullDate.getDate();

    // console.log("currentMonth : " +currentMonth);



    $(document).ready(function () {

        $("#dob, DOB, #dateOfJoining, #date_of_recived, #dob, #employedSince,#p_dob").keypress(function myfunction(event) {

            var regex = new RegExp("^[0-9?=.*!@#$%^&*]+$");

            var key = String.fromCharCode(event.charCode ? event.which : event.charCode);

            if (!regex.test(key)) {

                event.preventDefault();

                return false;

            }

            return false;

        });

        $("#dob, #dateOfJoining, #date_of_recived,#p_dob").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            // startView: 2,
            endDate: new Date()
        });

        $("#employedSince, #residenceSince").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            startView: 2,
//            viewMode: "months",
//            minViewMode: "months",
            endDate: new Date()
        });
        var currentM = '';
        $('#salary_credit1').change(function () {
            currentM = $(this).val();
            console.log(currentM);
        });

        $("#DOB,#dob,#p_dob").datepicker({

            format: 'dd-mm-yyyy',

            todayHighlight: true,

            autoclose: true,

            // startView: 2,

            startDate: '-60y',

            endDate: '-19y'

        });


        $("#holiday_date").datepicker({

            format: 'dd-mm-yyyy',

            todayHighlight: true,

            autoclose: true,

            // startView: 2,

            startDate: '0d',
//
//            endDate: '-19y'

        });



        $("#cc_statementDate").datepicker({

            format: 'dd-mm-yyyy',

            todayHighlight: true,

            autoclose: true,

            startDate: '-30d',

            endDate: new Date()

        });



        $("#cc_paymentDueDate, #repayment_date").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            startDate: new Date(),
            endDate: '+90d'
        });
        $("#next_pay_date").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            startDate: new Date(),
            endDate: '+60d'
        });



        $("#disbursal_date").datepicker({

            format: 'dd-mm-yyyy',

            todayHighlight: true,

            autoclose: true,

            startDate: '-5d',

            endDate: '+2d'

        });

    });



    $("#to_date, .SearchForExport").prop('disabled', true);

    $("#from_date").datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true,
        // startView: 2,
//        startDate: '-180d',
        endDate: new Date()
    });

    // startDate: '-30d',

    $("#from_date").change(function () {
        var from_date = $(this).val();
        $("#to_date").prop('disabled', false);
        $("#to_date").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            // startView: 2,
            startDate: from_date,
            endDate: '+31d'
        });
    });
</script>



<script>
    $(document).ready(function () {

        $("#mobile, #alternateMobileNo, #refrence1mobile, #enterAltMobileOTP").keypress(function (e) {

            $('#errormobile').html('');

            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {

                $('#errormobile').html('Number Only!').show().css({
                    'color': 'red'
                }).fadeOut('slow');

                return false;

            }

            if ($(this).val().length >= 10) {

                $('#errormobile').html('Verified Mobile!').show().css({
                    'color': 'green'
                });

                return false;

            }

            if ($(this).val().length < 9)

            {

                $('#errormobile').html('Mobile 10 digit required!').show().css({
                    'color': 'red'
                });

            } else {

                $('#errormobile').html('Verified Mobile!').show().css({
                    'color': 'green'
                });



            }

        });



        $("#pincode, #pincode1, #pincode2, #pincode3, #yourPincode").keypress(function (e) {

            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {

                return false;

            }

            if ($(this).val().length >= 6) {

                return false;

            }

            if ($(this).val().length < 5)

            {

                $('#errorpincode').html('Pincode 6 digit required!').show().css({
                    'color': 'red'
                });

            } else {

                $('#errorpincode').html('Verified Pincode!').show().css({
                    'color': 'green'
                });



            }

        });



        // number only



        $("#higherDPDLast3month, #loan_recomended, #processing_fee, #cc_outstanding, #cc_limit").keypress(function (e) {

            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {

                return false;

            }

        });

        $("#roi, #loan_applied, #loan_tenure, #obligations").keypress(function (e) {
            var val = $(this).val();
            var regex = /^(\+|-)?(\d*\.?\d*)$/;
            if (regex.test(val + String.fromCharCode(e.charCode))) {
                return true;
            }
            return false;
        });

        $("#bankA_C_No, #confBankA_C_No").keypress(function (e) {
            var val = $(this).val();
            var regex = /^(\+|-)?(\d*\.?\d*)$/;
            if (regex.test(val + String.fromCharCode(e.charCode))) {
                return true;
            }

            var regex_alpha = /^[A-Za-z ]+$/;
            if (regex_alpha.test(val + String.fromCharCode(e.charCode))) {
                $(this).val("");
                return false;
            }
        });




        // alpha only



        $('input[type=text], select').keyup(function () {
            $(this).val($(this).val().toUpperCase());
        });

        $('textarea').focusout(function () {
            $(this).val($(this).val().toUpperCase());
        });



        $("#first_name, #middle_name, #sur_name, #customer_name, #special_approval, #bankHolder_name, #refrence1").keypress(function (event) {
            var inputValue = event.which;
            if (!(inputValue >= 65 && inputValue <= 122) && (inputValue != 32 && inputValue != 0)) {
                event.preventDefault();
            }
        });
    });

    function IsEmail(email) {
        var regex = /([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})/;
        let valid_email = $(email).val();
        if (valid_email.match(regex)) {
            return true;
        } else {
            $(email).val("").focus();
            return false;
        }
    }

    function IsOfficialEmail(email) {
        let valid_email = $(email).val();
        var re = /.+@(loanwalle)\.com$/;
        var validEmail = re.test(valid_email);
        if (validEmail == true) {
            $("#emailErr").html("");
            return true;
        } else {
            $(email).val("");
            $("#emailErr").html("Acceptable domain name '@loanwalle.com'").css('color', 'red');
            return false;
        }

    }



    function validatePanNumber(pan) {
        let pannumber = $(pan).val();
        var regex = /[a-zA-z]{5}\d{4}[a-zA-Z]{1}/;
        if (pannumber.length == 10) {
            if (pannumber.match(regex)) {
                $(pan).css('border-color', 'lightgray');
            } else {
                $(pan).val("").focus().css('border-color', 'red');
                return false;
            }
        } else {
            $(pan).val("").focus().css('border-color', 'red');
            return false;
        }
    }
</script>

<script src="<?= base_url(); ?>public/js/toast_notification/flash.min.js"></script>

<script type="text/javascript">
    $("#salary_credit1_date, #salary_credit2_date, #salary_credit3_date, #sfd, #sed").datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        changeMonth: true,
        autoclose: true,
        // minDate: m,
        // startView: 2,
        // viewMode: "months", 
        // minViewMode: "months",
        // startMonth : m,
        // endMonth : m
    });


    function SalaryCredit(month) {
        var m = $(month).val();
        console.log(m);

        // $("#salary_credit1_date, #salary_credit2_date, #salary_credit3_date").datepicker({
        //     format: 'dd-mm',
        //     todayHighlight: true,
        //     changeMonth: true,
        //     autoclose: true,
        //     minDate: m,
        //     // startView: 2,
        //     // viewMode: "months", 
        //     // minViewMode: "months",
        //     // startMonth : m,
        //     // endMonth : m
        // });
    }

    function catchSuccess(success) {

        $('<audio id="chatAudio"><source src="<?= base_url() ?>public/ringtone/success.mp3" type="audio/ogg"><source src="<?= base_url() ?>public/ringtone/success.mp3" type="audio/mpeg"></audio>').appendTo('body');

        // $('#chatAudio')[0].play();

        flash(success, {
            'bgColor': '#2d6f36'
        });

    }

    function catchError(error) {

        $('<audio id="chatAudio"><source src="<?= base_url() ?>public/ringtone/success.mp3" type="audio/ogg"><source src="<?= base_url() ?>public/ringtone/success.mp3" type="audio/mpeg"></audio>').appendTo('body');

        // $('#chatAudio')[0].play();

        flash(error, {
            'bgColor': '#C0392B'
        });

    }

function catchNotification(notify) {

    $('<audio id="chatAudio"><source src="<?= base_url() ?>public/ringtone/success.mp3" type="audio/ogg"><source src="<?= base_url() ?>public/ringtone/success.mp3" type="audio/mpeg"></audio>').appendTo('body');

    // $('#chatAudio')[0].play();

    flash(notify, {
        'bgColor': '#d4ac1a'
    })
}

function tenure(t) {
    var val = $(t).val();
    if (val <= 0 || val > 90) {
        $(t).val('');
        $(t).attr('placeholder', 'Tenure should be between 1 to 90 days');
    }
}

function monthlyIncome(t) {
    var val = $(t).val();
    if (val.length < 5) {
        $(t).val('');
        $(t).attr('placeholder', 'Monthly Salary should be 10000 minimum');
    }
}

function showCity(state_id){
    $.ajax({
        url: '<?= base_url("getCity/") ?>' + state_id,
        type: 'POST',
        dataType: "json",
        data: {csrf_token},
        success: function (response) {
            //console.log(response);
            $("#city_id").html('<option value="">Select City</option>');
            $.each(response.city, function (index, myarr) {
                var s = "";
                if(city_id == myarr.m_city_id) {
                    s = "Selected";
                }
                $("#city_id").append('<option value="' + myarr.m_city_id + '" ' + s + '>' + myarr.m_city_name + '</option>');
            });
        }
    });
}

function showPincode(city_id){
  $.ajax({
        url: '<?= base_url("getPincode/") ?>' + city_id,
        type: 'POST',
        dataType: "json",
        data: {csrf_token},
        success: function (response) {
            //console.log(response);
            $(".residence_pincode_cls").html('<option value="">Select Pincode</option>');
            $.each(response.pincode, function (index, myarr) {
                $(".residence_pincode_cls").append('<option value="' + myarr.m_pincode_value + '">' + myarr.m_pincode_value + '</option>');
            });
        }
    });
}

function emailEabledAndDisabled() {
  if($('#check_email_id').prop("checked") == true){
    $("#email_id").removeAttr("disabled");
  } else {
    $("#email_id").attr("disabled", true);
  }
}

function alternateEmailEabledAndDisabled() {
  if($('#check_alternate_email_id').prop("checked") == true){
    $("#alternate_email_id").removeAttr("disabled");
  } else {
    $("#alternate_email_id").attr("disabled", true);
  }
}

function mobileEabledAndDisabled() {
  if($('#check_mobile_id').prop("checked") == true){
    $("#mobile_id").removeAttr("disabled");
  } else {
    $("#mobile_id").attr("disabled", true);
  }
}

function alternateMobileEabledAndDisabled() {
  if($('#check_alternate_mobile_id').prop("checked") == true){
    $("#alternate_mobile_id").removeAttr("disabled");
  } else {
    $("#alternate_mobile_id").attr("disabled", true);
  }
}

$(function () {
    $('.update_personal_details').click(function () {
        var check_email               = $('#check_email_id').prop("checked");
        var check_alternate_email     = $('#check_alternate_email_id').prop("checked");
        var check_mobile              = $('#check_mobile_id').prop("checked");
        var check_alternate_mobile    = $('#check_alternate_mobile_id').prop("checked");
        var lead_id                   = $('#lead_id').val();
        var email                     = $('#email_id').val();
        var alternate_email           = $('#alternate_email_id').val();
        var mobile                    = $('#mobile_id').val();
        var alternate_mobile          = $('#alternate_mobile_id').val();
        var loan_amount               = $('#loan_amount_id').val();
        var pancard                   = $('#pancard_id').val();
        var gender                    = $('#gender_id').val();
        var dob                       = $('#dob_id').val();
        var religion_id               = $('#customer_religion_id').val();
        var marital_status_id         = $('#customer_marital_status_id').val();
        var spouse_name               = $('#customer_spouse_name_id').val();
        var spouse_occupation_id      = $('#customer_spouse_occupation_id').val();        
        var current_house             = $('#current_house_id').val();
        var current_locality          = $('#current_locality_id').val();
        var current_landmark          = $('#current_landmark_id').val();
        var current_state             = $('#current_state_id').val();
        var current_city              = $('#city_id').val();
        var residence_pincode         = $('#residence_pincode_id').val();        
        var lead_followup_remark      = $('#lead_followup_remark').val();
        if(lead_id == "") {
            catchError("Lead ID cannot be empty");
            return false;
        }
        if(email == "") {
            catchError("Personal email cannot be empty");
            return false;
        }
        if(mobile == "") {
            catchError("Personal mobile cannot be empty");
            return false;
        }
        if(loan_amount == "") {
            catchError("loan amount cannot be empty");
            return false;
        }
        if(pancard == "") {
            catchError("Pancard number cannot be empty");
            return false;
        }
        if(gender == "") {
            catchError("Gender cannot be empty");
            return false;
        }
        if(dob == "") {
            catchError("DOB cannot be empty");
            return false;
        }
        if(religion_id == "") {
            catchError("Religion cannot be empty");
            return false;
        }
        if(marital_status_id == "") {
            catchError("Marital status cannot be empty");
            return false;
        }        
        if(current_house == "") {
            catchError("Current house cannot be empty");
            return false;
        }
        if(current_locality == "") {
            catchError("Current locality cannot be empty");
            return false;
        }
        if(current_state == "") {
            catchError("State cannot be empty");
            return false;
        }
        if(current_city == "") {
            catchError("City cannot be empty");
            return false;
        }
        if(residence_pincode == "") {
            catchError("Pincode cannot be empty");
            return false;
        }
        $.ajax({
            url: '<?= base_url("savePersonalDetails") ?>',
            type: 'POST',
            dataType: "json",
            data: {check_email:check_email,check_alternate_email:check_alternate_email,check_mobile:check_mobile,check_alternate_mobile:check_alternate_mobile,lead_id:lead_id,email:email,alternate_email:alternate_email,mobile:mobile,alternate_mobile:alternate_mobile,loan_amount:loan_amount,pancard:pancard,gender:gender,dob:dob,religion_id:religion_id,marital_status_id:marital_status_id,spouse_name:spouse_name,spouse_occupation_id:spouse_occupation_id,current_house:current_house,current_locality:current_locality,current_landmark:current_landmark,current_state:current_state,current_city:current_city,residence_pincode:residence_pincode,lead_followup_remark:lead_followup_remark,csrf_token},
            beforeSend: function () {
              $('.update_personal_details').html('<span class="spinner-border spinner-border-sm" role="status"></span>Processing...').addClass('disabled');
            },
            success: function (data) {
                if(data.err) {
                    catchError(data.err);
                } else {
                  $('.update_personal_details').html('Update Personal Details').removeClass('disabled');
                  catchSuccess(data.msg);
                  //console.log(data.msg);
                  //window.location.reload();
                }
            },
        });
    });

    $('.update_employment_details').click(function () {
        var lead_id                   = $('#lead_id').val();
        var employer_name             = $('#employer_name_id').val();
        var emp_email                 = $('#emp_email_id').val();
        var emp_house                 = $('#emp_house_id').val();
        var emp_street                = $('#emp_street_id').val();
        var emp_landmark              = $('#emp_landmark_id').val();
        var state                     = $('#state_id').val();
        var city                      = $('#city_id').val();
        var emp_pincode               = $('#emp_pincode_id').val();
        var emp_residence_since       = $('#emp_residence_since_id').val();
        var emp_designation           = $('#emp_designation_id').val();
        var emp_department            = $('#emp_department_id').val();
        var emp_employer_type         = $('#emp_employer_type_id').val();
        var salary_mode               = $('#salary_mode_id').val();
        var lead_followup_remark      = $('#lead_followup_remark').val();
        if(lead_id == "") {
            catchError("Lead ID cannot be empty");
            return false;
        } 
        if(employer_name == "") {
            catchError("Name cannot be empty");
            return false;
        }       
        $.ajax({
            url: '<?= base_url("saveEmploymentDetails") ?>',
            type: 'POST',
            dataType: "json",
            data: {employer_name:employer_name,emp_email:emp_email,emp_house:emp_house,emp_street:emp_street,emp_street:emp_street,emp_landmark:emp_landmark,state:state,city:city,emp_pincode:emp_pincode,emp_residence_since:emp_residence_since,emp_designation:emp_designation,emp_department:emp_department,emp_employer_type:emp_employer_type,salary_mode:salary_mode,lead_id:lead_id,lead_followup_remark:lead_followup_remark,csrf_token},
            beforeSend: function () {
              $('.update_employment_details').html('<span class="spinner-border spinner-border-sm" role="status"></span>Processing...').addClass('disabled');
            },
            success: function (data) {
                if(data.err) {
                    catchError(data.err);
                } else {
                  $('.update_employment_details').html('Update Employment Details').removeClass('disabled');
                  catchSuccess(data.msg);
                }
            },
        });
    });

    $('.update_reference_details').click(function () {
        var lcr_id                    = $('#lcr_id').val();
        var lead_id                   = $('#lead_id').val();
        var lcr_name                  = $('#lcr_name_id').val();
        var lcr_mobile                = $('#lcr_mobile_id').val();
        var lcr_relationType          = $('#lcr_relationType_id').val();
        var lead_followup_remark      = $('#lead_followup_remark').val();
        if(lead_id == "") {
            catchError("Lead ID cannot be empty");
            return false;
        } 
        if(lcr_name == "") {
            catchError("Name cannot be empty");
            return false;
        }
        if(lcr_mobile == "") {
            catchError("Mobile cannot be empty");
            return false;
        } 
        if(lcr_relationType == "") {
            catchError("Relation type cannot be empty");
            return false;
        }       
        $.ajax({
            url: '<?= base_url("saveReferenceDetails") ?>',
            type: 'POST',
            dataType: "json",
            data: {lcr_id:lcr_id,lead_id:lead_id,lcr_name:lcr_name,lcr_mobile:lcr_mobile,lcr_relationType:lcr_relationType,lead_followup_remark:lead_followup_remark,csrf_token},
            beforeSend: function () {
              $('.update_reference_details').html('<span class="spinner-border spinner-border-sm" role="status"></span>Processing...').addClass('disabled');
            },
            success: function (data) {
                if(data.err) {
                    catchError(data.err);
                } else {
                  $('.update_reference_details').html('Update Reference Details').removeClass('disabled');
                  catchSuccess(data.msg);
                }
            },
        });
    });

    $('.update_docs_details').click(function () {
        var lead_id                   = $('#lead_id').val();
        var pancard                   = $('#pancard_id').val();
        var lead_followup_remark      = $('#lead_followup_remark').val();
        if(lead_id == "") {
            catchError("Lead ID cannot be empty");
            return false;
        } 
        if(pancard == "") {
            catchError("Pan number cannot be empty");
            return false;
        }              
        $.ajax({
            url: '<?= base_url("updateDocsDetails") ?>',
            type: 'POST',
            dataType: "json",
            data: {lead_id:lead_id,pancard:pancard,lead_followup_remark:lead_followup_remark,csrf_token},
            beforeSend: function () {
              $('.update_docs_details').html('<span class="spinner-border spinner-border-sm" role="status"></span>Processing...').addClass('disabled');
            },
            success: function (data) {
                if(data.err) {
                    catchError(data.err);
                } else {
                  $('.update_docs_details').html('Update Docs Details').removeClass('disabled');
                  catchSuccess(data.msg);
                }
            },
        });
    });

    $('.update_cam_details').click(function () {
        var lead_id                   = $('#lead_id').val();
        var salary_credit1_date       = $('#salary_credit1_date_id').val();
        var salary_credit1_amount     = $('#salary_credit1_amount_id').val();
        var salary_credit2_date       = $('#salary_credit2_date_id').val();
        var salary_credit2_amount     = $('#salary_credit2_amount_id').val();
        var salary_credit3_date       = $('#salary_credit3_date_id').val();
        var salary_credit3_amount     = $('#salary_credit3_amount_id').val();
        var next_pay_date             = $('#next_pay_date_id').val();
        var median_salary             = $('#median_salary_id').val();
        var remark                    = $('#cam_remark_id').val();
        var lead_followup_remark      = $('#lead_followup_remark').val();
        if(lead_id == "") {
            catchError("Lead ID cannot be empty");
            return false;
        } 
        if(salary_credit1_date == "") {
            catchError("Salary date cannot be empty");
            return false;
        } 
        if(salary_credit1_amount == "") {
            catchError("Salary amount cannot be empty");
            return false;
        } 
        if(next_pay_date == "") {
            catchError("Next pay date cannot be empty");
            return false;
        } 
        if(median_salary == "") {
            catchError("Avg. salary cannot be empty");
            return false;
        }            
        $.ajax({
            url: '<?= base_url("updateCAMDetails") ?>',
            type: 'POST',
            dataType: "json",
            data: {lead_id:lead_id,salary_credit1_date:salary_credit1_date,salary_credit1_amount:salary_credit1_amount,salary_credit2_date:salary_credit2_date,salary_credit2_amount:salary_credit2_amount,salary_credit3_date:salary_credit3_date,salary_credit3_amount:salary_credit3_amount,next_pay_date:next_pay_date,median_salary:median_salary,remark:remark,lead_followup_remark:lead_followup_remark,csrf_token},
            beforeSend: function () {
              $('.update_cam_details').html('<span class="spinner-border spinner-border-sm" role="status"></span>Processing...').addClass('disabled');
            },
            success: function (data) {
                if(data.err) {
                    catchError(data.err);
                } else {
                  $('.update_cam_details').html('Update CAM Details').removeClass('disabled');
                  catchSuccess(data.msg);
                }
            },
        });
    });
    
    $('.update_bank_details').click(function () {
        var lead_id                   = $('#lead_id').val();
        var bank_name                 = $('#bank_name_id').val();
        var ifsc_code                 = $('#ifsc_code_id').val();
        var account_status            = $('#account_status_id').val();
        var beneficiary_name          = $('#beneficiary_name_id').val();
        var account                   = $('#account_id').val();
        var account_type              = $('#account_type_id').val();
        var branch                    = $('#branch_id').val();        
        var lead_followup_remark      = $('#lead_followup_remark').val();
        if(lead_id == "") {
            catchError("Lead ID cannot be empty");
            return false;
        } 
        if(bank_name == "") {
            catchError("Bank name cannot be empty");
            return false;
        }
        if(ifsc_code == "") {
            catchError("IFSC Code cannot be empty");
            return false;
        }
        if(beneficiary_name == "") {
            catchError("Beneficiary name cannot be empty");
            return false;
        }
        if(account == "") {
            catchError("Account cannot be empty");
            return false;
        }
        if(account_type == "") {
            catchError("Account Type cannot be empty");
            return false;
        }
        if(branch == "") {
            catchError("Branch cannot be empty");
            return false;
        }
        $.ajax({
            url: '<?= base_url("updateBankDetails") ?>',
            type: 'POST',
            dataType: "json",
            data: {lead_id:lead_id,bank_name:bank_name,ifsc_code:ifsc_code,account_status:account_status,beneficiary_name:beneficiary_name,account:account,account_type:account_type,branch:branch,lead_followup_remark:lead_followup_remark,csrf_token},
            beforeSend: function () {
              $('.update_bank_details').html('<span class="spinner-border spinner-border-sm" role="status"></span>Processing...').addClass('disabled');
            },
            success: function (data) {
                if(data.err) {
                    catchError(data.err);
                } else {
                  $('.update_bank_details').html('Update Docs Details').removeClass('disabled');
                  catchSuccess(data.msg);
                }
            },
        });
    });
});

function docsDelete(docs_id,id){
  if(docs_id == "") {
    catchError("DOCS ID cannot be empty");
    return false;
  } 
  $.ajax({
        url: '<?= base_url("docsDelete") ?>',
        type: 'POST',
        dataType: "json",
        data: {docs_id:docs_id,csrf_token},
        success: function (data) {
            if(data.err) {
                catchError(data.err);
            } else {
                $('#'+id).hide();
              catchSuccess(data.msg);
            }
        },
    });
}

$(function () {
        $('.salaryDate1').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            startDate: '-1m',
            endDate: '-0m'
        });
});

$(function () {
    $('.salaryDate2').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        startDate: '-2m',
        endDate: '-1m'
    });
});

$(function () {
    $('.salaryDate3').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        startDate: '-3m',
        endDate: '-2m'
    });
});

$(function () {
    $('.nextSalaryDate').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        startDate: '+0d'
    });
});

$(function () {
    $('.residing_since_date, .employed_since_current_date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true,
        startDate: '-15y',
        endDate: '-2y'
    });
});

$(function () {
    $('.dob_class').datepicker({
        format: 'dd-mm-yyyy',
        defaultDate: "01-01-1990",
        todayHighlight: true,
        autoclose: true,
        //startView: 2,
        startDate: '-55y',
        endDate: '-21y'
    });
});

function defaultLoginRole(user_id, role_id)
{
    if (confirm("Are you sure you want to change your role."))
    {
        var role_id = $(role_id).val();
        $.ajax({
            url: '<?= base_url("defaultLoginRole/") ?>' + user_id,
            type: 'POST',
            dataType: "json",
            async: false,
            data: {role_id: role_id, csrf_token},
            success: function (response) {
                //checkSessionStatusByRole(user_id,role_id,'<?= $_SESSION['isUserSession']['role_id'] ?>');
                if (response.errSession) {
                    window.location.href = "<?= base_url() ?>";
                } else if (response.msg != undefined) {
                    window.location.href = "<?= base_url('dashboard') ?>";
                } else {
                    alert(response.err);
                    window.location.href = "<?= base_url('logout') ?>";
                }
            }
        });
    }
}
/*
function checkSessionStatusByRole(user_id,new_role_id,previous_role_id){   
  if(previous_role_id!=new_role_id){window.location.href = "<?= base_url() ?>";}
}
*/
</script>
<script>  
  function checkSessionStatus(user_id,role_id) {
        $.ajax({
            url: '<?= base_url("check_session_status/") ?>' + user_id,
            type: 'POST',
            data: {role_id: role_id, csrf_token},
            success: function (response) {
              if(response=='2'){
                  window.location.href = "<?= base_url() ?>";
              }
            }
        });
  }
  checkSessionStatus(<?= $_SESSION['isUserSession']['user_id'] ?>,<?= $_SESSION['isUserSession']['role_id'] ?>);
   $(window).blur(function(){
    checkSessionStatus(<?= $_SESSION['isUserSession']['user_id'] ?>,<?= $_SESSION['isUserSession']['role_id'] ?>);
  });
  $(window).focus(function(){
    checkSessionStatus(<?= $_SESSION['isUserSession']['user_id'] ?>,<?= $_SESSION['isUserSession']['role_id'] ?>);
  });
</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>

</html>
