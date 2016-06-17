<?php
namespace Ubigeo2Tests;

use PHPUnit_Framework_TestCase;
use BaseTest\ServiceManagerGrabber;
use Mockery as m;

use Ubigeo2\Service\Ubigeo2Service;

class Ubigeo2ServiceTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $sm = new ServiceManagerGrabber();
        $this->serviceManager = $sm->getServiceManager();
    }
    
    
    public function tearDown()
    {
        m::Close();
    }
    
    
    public function setupDoctrineMocks()
    {
        $queryMock = m::mock()
                     ->shouldReceive('getResult')->once()
                     ->andReturn(true)
                     ->getMock();
        
        $qbMock = m::mock('Doctrine\ORM\QueryBuilder')
                  ->shouldDeferMissing()
                  ->shouldReceive('getQuery')->once()
                  ->andReturn($queryMock)
                  ->getMock();
        
        $entityManagerMock = m::mock('Doctrine\ORM\EntityManager')
                             ->shouldReceive('createQueryBuilder')->once()
                             ->andReturn($qbMock)
                             ->getMock();
        
        $this->entityManagerMock = $entityManagerMock;
        $this->qbMock            = $qbMock;
    }
    
    
    public function testGetDepartmentsList()
    {
        $this->setupDoctrineMocks();
        $qb = $this->qbMock;
        
        $m = new Ubigeo2Service($this->serviceManager);
        $m->setEntityManager($this->entityManagerMock);
        $m->getDepartmentsList();
        
        $dql = "SELECT d.id, d.name FROM Ubigeo2\Entity\Department d ORDER BY d.name ASC";
        $this->assertEquals($dql, $qb->getDql());
    }
    
    
    public function testGetProvincesList()
    {
        $this->setupDoctrineMocks();
        $qb = $this->qbMock;
        
        $m = new Ubigeo2Service($this->serviceManager);
        $m->setEntityManager($this->entityManagerMock);
        $m->getProvincesList(78);
        
        $dql = "SELECT p.id, p.name FROM Ubigeo2\Entity\Province p WHERE p.department = :departmentID ORDER BY p.name ASC";
        $this->assertEquals($dql, $qb->getDql());
        $this->assertEquals(78, $qb->getParameter('departmentID')->getValue());
    }
    
    
    public function testGetDistrictsList()
    {
        $this->setupDoctrineMocks();
        $qb = $this->qbMock;
        
        $m = new Ubigeo2Service($this->serviceManager);
        $m->setEntityManager($this->entityManagerMock);
        $m->getDistrictsList(78, 323);
        
        $dql = "SELECT d.id, d.name FROM Ubigeo2\Entity\District d WHERE d.department = :departmentID
                         AND d.province= :provinceID
                     ORDER BY d.name ASC";
        $this->assertEquals($dql, $qb->getDql());
        $this->assertEquals(78, $qb->getParameter('departmentID')->getValue());
        $this->assertEquals(323, $qb->getParameter('provinceID')->getValue());
    }
    
    
    public function testGetDepartmentOptions()
    {
        $in = [
            [
                'id'   => '123',
                'name' => 'Bongo',
            ],
            [
                'id'   => '456',
                'name' => 'Suomi',
            ],
        ];
        
        $out = [
            '123' => 'Bongo',
            '456' => 'Suomi',
        ];
        
        $umMock = m::mock('Ubigeo2\Service\Ubigeo2Service')
                  ->shouldDeferMissing()
                  ->shouldReceive('getDepartmentsList')->once()
                  ->andReturn($in)
                  ->getMock();
        
        $result = $umMock->getDepartmentOptions();
        $this->assertEquals($out, $result);
    }
    
    
    public function testGetProvinceOptions()
    {
        $in = [
            [
                'id'   => '123',
                'name' => 'Bongo',
            ],
            [
                'id'   => '456',
                'name' => 'Suomi',
            ],
        ];
        
        $out = [
            '123' => 'Bongo',
            '456' => 'Suomi',
        ];
        
        $umMock = m::mock('Ubigeo2\Service\Ubigeo2Service')
                  ->shouldDeferMissing()
                  ->shouldReceive('getProvincesList')->once()
                  ->with(78)
                  ->andReturn($in)
                  ->getMock();
        
        $result = $umMock->getProvinceOptions(78);
        $this->assertEquals($out, $result);
    }
    
    
    public function testGetDistrictOptions()
    {
        $in = [
            [
                'id'   => '123',
                'name' => 'Bongo',
            ],
            [
                'id'   => '456',
                'name' => 'Suomi',
            ],
        ];
        
        $out = [
            '123' => 'Bongo',
            '456' => 'Suomi',
        ];
        
        $umMock = m::mock('Ubigeo2\Service\Ubigeo2Service')
                  ->shouldDeferMissing()
                  ->shouldReceive('getDistrictsList')->once()
                  ->with(78, 323)
                  ->andReturn($in)
                  ->getMock();
        
        $result = $umMock->getDistrictOptions(78, 323);
        $this->assertEquals($out, $result);
    }
}

