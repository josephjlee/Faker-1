<?php
namespace Faker\Tests\Engine\Original\Type;

use Faker\Components\Engine\Original\Type\BooleanType,
    Faker\Tests\Base\AbstractProject;

class BooleanTest extends AbstractProject
{
    
    public function testTypeExists()
    {
        
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Original\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')
                        ->getMock();

        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
      
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $type = new BooleanType($id,$parent,$event,$utilities,$generator);
        
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Original\\TypeInterface',$type);
    
    }
    
    //--------------------------------------------------------------------------
    
    public function testConfig()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Original\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $type = new BooleanType($id,$parent,$event,$utilities,$generator);
        $type->setOption('value' , true);
        $type->setOption('name','boolean');
        $type->merge();        
    }
    
    //  -------------------------------------------------------------------------
    
    
    public function testGenerate()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Original\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();
                          
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $type = new BooleanType($id,$parent,$event,$utilities,$generator);
        $type->setOption('value',true);
        $type->setOption('name','boolean');
        $type->validate(); 
         
        $this->assertEquals(true,$type->generate(1,array()));
        
        $type = new BooleanType($id,$parent,$event,$utilities,$generator);
        $type->setOption('value',false);
        $type->setOption('name','boolean');
        $type->validate(); 

        $this->assertEquals(false,$type->generate(1,array()));
    
    }
    
}
/*End of file */