<?php
namespace Faker\Tests\Config\CLI;

use Faker\Tests\Base\AbstractProject,
    Faker\Tests\Base\AbstractProjectWithDb,
    Faker\Components\Config\EntityInterface,
    Faker\Components\Config\Driver\CLI\Sqlite;

class SqliteTest extends AbstractProject
{
    
    
    public function testMergeNotMemory()
    {
        $parsed = array(
            'type'  => 'pdo_sqlite',
            'username' => 'root',
            'password' => 'vagrant',
            'path' => 'mydb.sqlite',
            'memory' => false
        );
        
        
        
        $entity = $this->getMockBuilder('\Faker\Components\Config\EntityInterface')->getMock();
    
        $entity->expects($this->once())->method('setUser')->with($this->equalTo('root'));
        $entity->expects($this->once())->method('setType')->with($this->equalTo('pdo_sqlite'));
        $entity->expects($this->once())->method('setPassword')->with($this->equalTo('vagrant'));
        $entity->expects($this->once())->method('setPath')->with($this->equalTo('mydb.sqlite'));
        
        $dsn = new Sqlite();
        $dsn->merge($entity,$parsed);
    
    }
    
    public function testMergeMemory()
    {
        $parsed = array(
            'type'  => 'pdo_sqlite',
            'username' => 'root',
            'password' => 'vagrant',
            'memory'   => ':memory'
        );
        
        
        
        $entity = $this->getMockBuilder('\Faker\Components\Config\EntityInterface')->getMock();
    
        $entity->expects($this->once())->method('setUser')->with($this->equalTo('root'));
        $entity->expects($this->once())->method('setType')->with($this->equalTo('pdo_sqlite'));
        $entity->expects($this->once())->method('setPassword')->with($this->equalTo('vagrant'));
        $entity->expects($this->once())->method('setMemory')->with($this->equalTo(':memory'));
        
        $dsn = new Sqlite();
        $dsn->merge($entity,$parsed);
    
    }
    
    public function testMergeOptionalUserAndPassword()
    {
        $parsed = array(
            'type'  => 'pdo_sqlite',
            'username' => false,
            'password' => false,
            'memory'   => ':memory'
        );
        
        
        
        $entity = $this->getMockBuilder('\Faker\Components\Config\EntityInterface')->getMock();
    
        $entity->expects($this->once())->method('setUser')->with($this->equalTo(false));
        $entity->expects($this->once())->method('setType')->with($this->equalTo('pdo_sqlite'));
        $entity->expects($this->once())->method('setPassword')->with($this->equalTo(false));
        $entity->expects($this->once())->method('setMemory')->with($this->equalTo(':memory'));
        
        $dsn = new Sqlite();
        $dsn->merge($entity,$parsed);
    
    }
    
    /**
      *  @expectedException \Faker\Components\Config\InvalidConfigException
      *  @expectedExceptionMessage Invalid configuration for path "database.type": Database is not a valid type
      */
    public function testParseInvalidTypeConfig()
    {
        # unsupported db typ
        $parsed = array(
            'type'  => 'mysql',
            'username' => 'root',
            'password' => 'vagrant',
        );
        
        
        
        $entity = $this->getMockBuilder('\Faker\Components\Config\EntityInterface')->getMock();
        $dsn = new Sqlite();
        $dsn->merge($entity,$parsed);
    }
    
    /**
      *  @expectedException \Faker\Components\Config\InvalidConfigException
      *  @expectedExceptionMessage Neither path or memory are set one option must be chosen
      */
    public function testParseMissingPathAndMemory()
    {
        $parsed = array(
            'type'  => 'pdo_sqlite',
            'username' => 'root',
            'password' => 'vagrant',
        );
        
        
        
        $entity = $this->getMockBuilder('\Faker\Components\Config\EntityInterface')->getMock();
       
       
        $dsn = new Sqlite();
        $dsn->merge($entity,$parsed);
    }
    
}
/* End of File MysqlTest.php */
