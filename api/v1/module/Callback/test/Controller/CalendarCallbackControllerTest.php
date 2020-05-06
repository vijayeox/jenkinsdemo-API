<?php
namespace Callback;

use Callback\Controller\CalendarCallbackController;
use Mockery;
use Oxzion\Test\ControllerTest;
use PHPUnit\DbUnit\DataSet\YamlDataSet;

class CalendarCallbackControllerTest extends ControllerTest
{
    public function setUp(): void
    {
        $this->loadConfig();
        $ics_file = dirname(__FILE__) . "/../files/invite.ics";
        $_FILES = array(
            'attachment' => array(
                'tmp_name' => $ics_file,
                'name' => 'invite.ics',
                'type' => 'text/calendar',
                'size' => 1395,
                'error' => 0,
            ),
        );
        parent::setUp();
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/../Dataset/Email.yml");
        return $dataset;
    }

    private function getMockEmailServiceForCalendarService()
    {
        $calendarService = $this->getApplicationServiceLocator()->get(Service\CalendarService::class);
        $mockEmailService = Mockery::mock('Oxzion\Service\EmailService');
        $calendarService->setEmailService($mockEmailService);
        return $mockEmailService;
    }

    private function getMockEmailClientForCalendarService()
    {
        $calendarService = $this->getApplicationServiceLocator()->get(Service\CalendarService::class);
        $mockEmailClient = Mockery::mock('Oxzion\Email\EmailClient');
        $calendarService->setEmailClient($mockEmailClient);
        return $mockEmailClient;
    }
    
    public function testSendMail()
    {
        $data = ['to' => 'bharatg@myvamla.com', 'from' => 'bharatg@myvamla.com', 'subject' => 'test case for email', 'body' => 'test body for email'];
        $headers = array(
            'to' => $data['to'],
            'from' => $data['from'],
            'subject' => $data['subject'],
        );
        $body = $data['body'];
        $mockemailService = $this->getMockEmailServiceForCalendarService();
        $smtpConfig = array('host' => 'box3053.bluehost.com',
            'password' => 'password',
            'port' => '465',
            'username' => 'bharatg@myvamla.com',
            'secure' => 'ssl');
        $smtpDetails = $mockemailService->expects('getEmailAccountsByEmailId')->with($data['from'], true)->once()->andReturn($smtpConfig);

        $mockemailClient = $this->getMockEmailClientForCalendarService();
        $mockemailClient->expects('buildAndSendMessage')->withAnyArgs()->once()->andReturn(null);
        $this->dispatch('/callback/calendar/sendmail', 'POST', $data);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('calendarsendmailcallback');
    }

    public function testSendMailwithoutAttachment()
    {
        $data = ['to' => 'bharatg@myvamla.com', 'from' => 'bharatg@myvamla.com', 'subject' => 'test case for email', 'body' => 'test body for email'];
        $headers = array(
            'to' => $data['to'],
            'from' => $data['from'],
            'subject' => $data['subject'],
        );
        $body = $data['body'];

        $mockemailService = $this->getMockEmailServiceForCalendarService();
        $smtpConfig = array('host' => 'box3053.bluehost.com',
            'username' => 'bharatg@myvamla.com',
            'password' => 'password',
            'port' => '465',
            'secure' => 'ssl');
        $smtpDetails = $mockemailService->expects('getEmailAccountsByEmailId')->with($data['from'], true)->once()->andReturn($smtpConfig);
        $mockemailClient = $this->getMockEmailClientForCalendarService();
        $mockemailClient->expects('buildAndSendMessage')->withAnyArgs()->once()->andReturn(null);
        $this->dispatch('/callback/calendar/sendmail', 'POST', $data);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('calendarsendmailcallback');
    }

    public function testSendMailFail()
    {
        $data = ['from' => 'bharatg@myvamla.com', 'subject' => 'test case for email', 'body' => 'test body for email'];
        $headers = array(
            'from' => $data['from'],
            'subject' => $data['subject'],
        );
        $body = $data['body'];

        $mockemailService = $this->getMockEmailServiceForCalendarService();
        $smtpConfig = array('host' => 'box3053.bluehost.com',
            'password' => 'password',
            'port' => '465',
            'secure' => 'ssl');
        $smtpDetails = $mockemailService->expects('getEmailAccountsByEmailId')->with($data['from'], true)->once()->andReturn($smtpConfig);

        $mockemailClient = Mockery::mock('Oxzion\Email\EmailClient');
        $mockemailClient->expects('buildAndSendMessage')->with($data['body'], array(), $headers, $smtpDetails)->once()->andReturn(null);
        $this->dispatch('/callback/calendar/sendmail', 'POST', $data);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->assertEquals($content['message'], 'Mail Send Failed');
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('calendarsendmailcallback');
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('Callback');
        $this->assertControllerName(CalendarCallbackController::class); // as specified in router's controller name alias
        $this->assertControllerClass('CalendarCallbackController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }
}
