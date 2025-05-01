<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($subject) ?></title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
      color: #333;
      margin: 0;
      padding: 0;
    }
    .container {
      width: 100%;
      max-width: 650px;
      margin: 0 auto;
      background-color: #ffffff;
      border: 1px solid #dee2e6;
      padding: 20px;
    }
    .header {
      background-color: rgb(95,99,104);
      color: white;
      padding: 10px 20px;
      font-size: 18px;
      font-weight: bold;
    }
    .row {
      border-bottom: 1px solid #e9ecef;
      padding: 10px 0;
    }
    .row:last-child {
      border-bottom: none;
    }
    .label {
      font-weight: bold;
      width: 200px;
      display: inline-block;
    }
    .footer {
      font-size: 12px;
      color: #6c757d;
      padding-top: 20px;
      border-top: 1px solid #dee2e6;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">Password Change Notification</div>
    <p>Dear <?= htmlspecialchars($name) ?>,</p>
    <p>Your password has been changed. Below are the details of the activity:</p>

    <div class="row"><span class="label">URL:</span> <?= base_url() ?></div>
    <div class="row"><span class="label">Login Email:</span> <?= htmlspecialchars($email) ?></div>
    <div class="row"><span class="label">One-Time Password (OTP):</span> <?= htmlspecialchars($otp) ?></div>
    <div class="row"><span class="label">IP Address:</span> <?= htmlspecialchars($ip) ?></div>
    <div class="row"><span class="label">Platform:</span> <?= $platform ?></div>
    <div class="row"><span class="label">Browser:</span> <?= $browser ?></div>
    <div class="row"><span class="label">User Agent:</span> <?= $user_agent ?></div>
    <div class="row"><span class="label">Change Time:</span> <?= $change_time ?></div>

    <div class="footer">
      If this change wasn't made by you, please contact IT support immediately at
      <a href="mailto:<?= TECH_EMAIL ?>"><?= TECH_EMAIL ?></a>.<br><br>
      Thank you,<br>
      <?= BRAND_NAME ?> Support Team
    </div>
  </div>
</body>
</html>
