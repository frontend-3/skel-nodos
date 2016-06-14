<?php
namespace Base2Test;

use PHPUnit_Framework_TestCase;
use SkelsusTests\ServiceManagerGrabber;
use Mockery as m;

use Base2\QuickMail;

class QuickMailTest extends PHPUnit_Framework_TestCase
{
    private $serviceManager;
    private $config;
    
    public function setUp()
    {
        $this->config = array(
            'quickmail' => array(
                'transport'        => 'smtp',
                'from_address'     => 'jaime.wong@nodosdigital.pe',
                'from_name'        => 'Jaime G. Wong',
                'host'             => 'smtp.gmail.com',
                'username'         => 'jaime.wong@nodosdigital.pe',
                'password'         => '*****',
                'connection_class' => 'login',
                'ssl'              => 'tls',
                'port'             => 587,
            ),
        );
    }
    
    
    public function tearDown()
    {
        m::Close();
    }
    
    
    public function setupServiceManager()
    {
        // Mock ServiceManager
        $sm = m::mock('Zend\ServiceManager\ServiceManager');
        $sm->shouldReceive('get')->with('Config')->andReturn($this->config);
        $this->servicemanager = $sm;
        return $sm;
    }
    
    
    public function testConstruct()
    {
        $sm = $this->setupServicemanager();
        $qm = new QuickMail($sm);
        
        $message = $qm->getMessage();
        $this->assertInstanceOf('Zend\Mail\Message', $message);
        
        $transport = $qm->getTransport();
        $this->assertInstanceOf('Zend\Mail\Transport\TransportInterface', $transport);
    }
    
    
    public function testNoConfig()
    {
        $this->setExpectedException('RuntimeException');
        
        unset($this->config['quickmail']);
        
        $sm = $this->setupServicemanager();
        $qm = new QuickMail($sm);
    }
    
    
    public function testTransportSMTP()
    {
        $sm = $this->setupServicemanager();
        $qm = new QuickMail($sm);
        
        $transport = $qm->getTransport();
        $this->assertInstanceOf('Zend\Mail\Transport\Smtp', $transport);
    }
    
    
    public function testTransportSendmail()
    {
        $this->config['quickmail']['transport'] = 'sendmail';
        $sm = $this->setupServicemanager();
        $qm = new QuickMail($sm);
        
        $transport = $qm->getTransport();
        $this->assertInstanceOf('Zend\Mail\Transport\Sendmail', $transport);
    }
    
    
    public function testTransportInMemory()
    {
        $this->config['quickmail']['transport'] = 'inmemory';
        $sm = $this->setupServicemanager();
        $qm = new QuickMail($sm);
        
        $transport = $qm->getTransport();
        $this->assertInstanceOf('Zend\Mail\Transport\InMemory', $transport);
    }
    
    
    public function testTransportInvalid()
    {
        $this->setExpectedException('Zend\Mail\Transport\Exception\RuntimeException');
        
        $this->config['quickmail']['transport'] = 'nonexistant';
        
        $sm = $this->setupServicemanager();
        $qm = new QuickMail($sm);
    }
    
    
    public function testSetBody()
    {
       $sm = $this->setupServicemanager();
       
       $mimePart = m::mock('Zend\Mime\Part')
                       ->shouldIgnoreMissing()
                       ->shouldReceive('setContent')->once()
                       ->getMock();
       
       $mimeMessage = m::mock('Zend\Mime\Message')
                          ->shouldIgnoreMissing()
                          ->shouldReceive('addPart')
                          ->shouldReceive('setEncoding')
                          ->shouldReceive('setBody')
                          ->getMock();
       
       $qm = new QuickMail($sm, null, $mimePart, $mimeMessage);
       $qm->setBody('blah');
    }
    
    
    public function testSetSubject()
    {
       $sm = $this->setupServicemanager();
       
       $message = m::mock('Zend\Mail\Message')
                      ->shouldIgnoreMissing()
                      ->shouldReceive('setSubject')->once()
                      ->getMock();
       
       $qm = new QuickMail($sm, $message);
       $qm->setSubject('blah');
    }
    
    
    public function testAddTo()
    {
       $sm = $this->setupServicemanager();
       
       $message = m::mock('Zend\Mail\Message')
                      ->shouldIgnoreMissing()
                      ->shouldReceive('addTo')->once()
                      ->getMock();
       
       $qm = new QuickMail($sm, $message);
       $qm->addTo('j@jgwong.org', 'Jaime G. Wong');
    }
    
    
    public function testSend()
    {
       $sm = $this->setupServicemanager();
       
       $transport = m::mock('Zend\Mail\Transport\TransportInterface')
                        ->shouldIgnoreMissing()
                        ->shouldReceive('send')->once()
                        ->getMock();
       
       $qm = new QuickMail($sm, null, null, null, $transport);
       $qm->send();
    }
}

