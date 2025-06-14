<?php
function renderEmailTemplate(string $title, string $bodyHtml): string {
    $config = require __DIR__ . '/config.php';
    $logoUrl       = $config['logo_url'];
    $supportEmail  = $config['support_email'];
    $supportPhone  = $config['support_phone'];

    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>{$title}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
    }

    .email-container {
      max-width: 600px;
      width: 100%;
      margin: 0 auto;
      background-color: #ffffff;
      border: 1px solid #dddddd;
    }

    .header {
      background: #222222;
      padding: 20px;
      text-align: center;
    }

    .header img {
      max-height: 60px;
      height: auto;
    }

    .content {
      padding: 25px 20px;
      color: #333333;
      font-size: 15px;
      line-height: 1.6;
    }

    .footer {
      background: #222222;
      padding: 15px;
      text-align: center;
      color: #aaaaaa;
      font-size: 0.9em;
    }

    .footer a {
      color: #4ea1f3;
      text-decoration: none;
    }

    @media only screen and (max-width: 480px) {
      .email-container {
        width: 100% !important;
        border: none;
        margin: 0;
      }

      .content {
        padding: 20px 15px;
        font-size: 14px;
      }

      .header img {
        width: 100px;
      }

      .footer {
        font-size: 0.8em;
      }
    }
  </style>
</head>
<body>
  <div class="email-container">
    <div class="header">
      <img src="cid:logo_cid" alt="Logo"/>
    </div>
    <div class="content">
      {$bodyHtml}
    </div>
    <div class="footer">
      Need help? Email <a href="mailto:{$supportEmail}">{$supportEmail}</a> or call {$supportPhone}
    </div>
  </div>
</body>
</html>
HTML;
}
