<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Aadhaar eKYC Verification</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .card {
      border-radius: 1rem;
      box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
    }
    .spinner-border-sm {
      width: 1rem;
      height: 1rem;
    }
    #statusAlert {
      margin-bottom: 1rem;
    }
  </style>
  <script>
    var csrf_token_name = '<?= $this->security->get_csrf_token_name(); ?>';
    var csrf_token = '<?= $this->security->get_csrf_hash(); ?>';
    let otpGenerationResponse;
</script>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center min-vh-100">
  <div class="card p-4 w-100 d-none" id="ekycVerificationSection" style="max-width: 420px;">
    <h4 class="text-center mb-1">Aadhaar eKYC Verification</h4>
    <p class="text-center text-muted small mb-3">Please verify your Aadhaar to proceed with eKYC for secured access.</p>

    <!-- Alert for messages -->
    <div id="statusAlert" class="alert d-none" role="alert"></div>

    <form id="uidaiForm" novalidate>
      <!-- Aadhaar Input -->
      <div class="mb-3">
        <label for="aadharNumber" class="form-label">Aadhaar Number</label>
        <input type="number" class="form-control" id="aadharNumber" value="123456789012" maxlength="12" pattern="\d{12}" required placeholder="Enter 12-digit Aadhaar">
      </div>

      <!-- Send OTP -->
      <button type="button" class="btn btn-primary w-100 mb-3" id="sendOtpBtn">
        <span id="sendOtpText">Send OTP</span>
        <span id="sendOtpLoader" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
      </button>

      <!-- OTP Section -->
      <div id="otpSection" class="d-none">
        <div class="mb-3">
          <label for="otpInput" class="form-label">Enter OTP</label>
          <input type="text" class="form-control" id="otpInput" maxlength="6" pattern="\d{6}" required placeholder="6-digit OTP">
        </div>

        <button type="button" class="btn btn-success w-100 mb-2" id="verifyOtpBtn">
          <span id="verifyOtpText">Verify OTP</span>
          <span id="verifyOtpLoader" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
        </button>
      </div>
    </form>

    <div class="text-center mt-3">
      <small class="text-muted">Your Aadhaar details are used only for eKYC and will not be stored.</small>
    </div>
  </div>
  <!-- Success Section (Initially Hidden) -->
<div id="ekycSuccessSection" class="text-center d-none mt-4">
  <div class="mb-3">
    <div class="spinner-grow text-success" role="status">
      <span class="visually-hidden">Success...</span>
    </div>
  </div>
  <h5 class="text-success">eKYC Verification Completed</h5>
  <p class="text-muted">Your Aadhaar has been successfully verified. You may now close this window or continue.</p>
  <!-- <button class="btn btn-outline-secondary mt-2" onclick="window.close();">Close Window</button> -->
</div>

</div>

<!-- jQuery + Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
  const showAlert = (type, message) => {
    const alertBox = $('#statusAlert');
    alertBox.removeClass('d-none alert-success alert-danger alert-warning')
            .addClass(`alert alert-${type}`)
            .text(message);
  };

  $(document).ready(function () {
  // Get the URL parameters
  const urlParams = new URLSearchParams(window.location.search);

  // Check if the 'verification' parameter exists and equals '1'
  if (urlParams.get('verification') === '1') {
    // Show the success section
    $('#ekycSuccessSection').removeClass('d-none');
    $('#ekycVerificationSection').addClass('d-none');
  }else{
    $('#ekycSuccessSection').addClass('d-none');
    $('#ekycVerificationSection').removeClass('d-none');
  }
});

  $('#sendOtpBtn').click(function () {
    const aadhar = $('#aadharNumber').val().trim();

    if (!/^\d{12}$/.test(aadhar)) {
        showAlert('warning', 'Please enter a valid 12-digit Aadhaar number.');
        return;
    }

    $('#sendOtpBtn').prop('disabled', true);
    $('#sendOtpLoader').removeClass('d-none');
    $('#sendOtpText').text('Sending...');

    $.ajax({
        url: '<?= base_url("aadhaar-veri-request-genrate-otp") ?>',
        type: 'POST',
        data: {
            aadhaar: aadhar,
            processId:"<?= $processId ?>",
            csrf_token
        },
        dataType: 'json',
        beforeSend: function () {
            $("#cover").show();
        },
        success: function (response) {
            if (response.errSession) {
                window.location.href = '<?= base_url() ?>';
            } else if (response.success) {
                $('#aadharNumber').prop('readonly', true);
                $('#aadharNumber').attr('disabled', true); 
                $('#sendOtpBtn').hide();

                $('#otpSection').removeClass('d-none');
                showAlert('success', response.message);

                otpGenerationResponse = response?.data?.model || response?.data;
            } else {
                showAlert('danger', response.err || response.message || response.error || 'Unexpected error');
            }
        },
        complete: function () {
            $('#sendOtpBtn').prop('disabled', false);
            $('#sendOtpLoader').addClass('d-none');
            $('#sendOtpText').text('Send OTP');
            $("#cover").fadeOut(1750);
        }
    });
});

$('#verifyOtpBtn').click(function () {
  const otp = $('#otpInput').val().trim();

  if (!/^\d{6}$/.test(otp)) {
    showAlert('warning', 'Please enter a valid 6-digit OTP.');
    return;
  }

  console.log("control on line: 136",otpGenerationResponse);
  
  // ✅ Validate that otpGenerationResponse is available and has required fields
  if (
    !otpGenerationResponse ||
    !otpGenerationResponse.codeVerifier ||
    !otpGenerationResponse.fwdp ||
    !otpGenerationResponse.transactionId
  ) {
    showAlert('danger', 'OTP session is missing or expired. Please resend OTP.');
    return;
  }

  $('#verifyOtpBtn').prop('disabled', true);
  $('#verifyOtpLoader').removeClass('d-none');
  $('#verifyOtpText').text('Verifying...');

  $.post('<?= base_url('aadhaar-veri-request-verify-otp') ?>', {
    csrf_token,
    otp,
    processId: "<?= $processId ?>",
    codeVerifier: otpGenerationResponse.codeVerifier,
    fwdp: otpGenerationResponse.fwdp,
    transactionId: otpGenerationResponse.transactionId,
  }, function (response) {
    setTimeout(() => {
      $('#verifyOtpBtn').prop('disabled', false);
      $('#verifyOtpLoader').addClass('d-none');
      $('#verifyOtpText').text('Verify OTP');

      if (response.success) {
        showAlert('success', 'Aadhaar successfully verified. eKYC complete.');
        $('#ekycSuccessSection').removeClass('d-none');
        $('#ekycVerificationSection').addClass('d-none');


        // Add verification=1 to URL
        const url = new URL(window.location);
        url.searchParams.set('verification', '1');
        window.history.replaceState({}, '', url);
      } else {
        showAlert('danger', response.message || 'OTP verification failed. Please try again.');
      }
    }, 1000);
  }).fail(() => {
    $('#verifyOtpBtn').prop('disabled', false);
    $('#verifyOtpLoader').addClass('d-none');
    $('#verifyOtpText').text('Verify OTP');
    showAlert('danger', 'OTP verification failed due to network/server error.');
  });
});

</script>
</body>
</html>
