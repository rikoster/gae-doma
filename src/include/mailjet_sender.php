<?php
  // This module added by rikoster on 2020-12-13
  // The standard php mailing needs to be replaced with MailJet in Google App
  // Engine
  require_once(dirname(__FILE__) ."/../vendor/autoload.php");
  use \Mailjet\Resources;

  class MailjetSender
  {
    public static function send($fromName, $toEmail, $subject, $body)
    {
      $mj = new \Mailjet\Client(MJ_APIKEY_PUBLIC, MJ_APIKEY_PRIVATE, true,['version' => 'v3.1']);
      $body = [
        'Messages' => [
          [
            'From' => [
              'Email' => ADMIN_EMAIL,
              'Name' => $fromName
            ],
            'To' => [
              [
                'Email' => $toEmail
              ]
            ],
            'Subject' => $subject,
            'TextPart' => $body
          ]
        ]
      ];
      $response = $mj->post(Resources::$Email, ['body' => $body]);
      return $response->success();
    }
  }
?>
