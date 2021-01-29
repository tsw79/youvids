<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/2/2019
 * Time: 02:23
 */
namespace phpchassis\mailer;

use Mailgun\Mailgun;

/**
 * Class MailgunMailer
 * @package phpchassis-ddd\mailer
 */
class MailgunMailer implements MailerInterface {

    private $mailgun;
    private $domain;

    /**
     * Constructor
     */
    public function __construct() {
        $this->mailgun = new Mailgun();
        $this->domain = "somedamain";
    }

    /**
     * Sends a message
     * @param MessageInterface $message
     * @return mixed
     */
    public function send(MessageInterface $message) {

        $this->mailgun->sendMessage($this->domain, [
            "from"      =>  "",
            "to"        =>  "",
            "subject"   =>  "",
            "text"      =>  ""
        ]);
    }
}


// Send email using mailgun API without any libraries
// https://www.spidersoft.com.au/2016/send-email-using-mailgun-api-without-any-libraries/
/*
<?php
function send_simple_message() {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  curl_setopt($ch, CURLOPT_USERPWD, 'api:key-example');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
  curl_setopt($ch, CURLOPT_URL,
              'https://api.mailgun.net/v2/samples.mailgun.org/messages');
  curl_setopt($ch, CURLOPT_POSTFIELDS,
                array('from' => 'Dwight Schrute <dwight@example.com>',
                      'to' => 'Michael Scott <michael@example.com>',
                      'subject' => 'The Printer Caught Fire',
                      'text' => 'We have a problem.'));
  $result = curl_exec($ch);
  curl_close($ch);
  return $result;
}
echo send_simple_message();

*/