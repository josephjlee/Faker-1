<?php
namespace Faker\Tests\Engine\Common\Builder;

use Faker\Components\Engine\Common\Type\ConstantNumber;
use Faker\Components\Engine\Common\Builder\ConstantNumberTypeDefinition;
use Faker\Tests\Base\AbstractProject;

class ConstantNumberDefinitionTest extends AbstractProject
{
    
    public function testTypeExists()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');    
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $database  = $this->getMockBuilder('Doctrine\DBAL\Connection')->disableOriginalConstructor()->getMock();
            
        $type = new ConstantNumberTypeDefinition();
        $type->eventDispatcher($event);
        $type->database($database);
        $type->locale($locale);
        $type->utilities($utilities);
        $type->generator($generator);
        
        
        $this->assertInstanceOf('\Faker\Components\Engine\Common\Builder\AbstractDefinition',$type);
    }
    
    
    public function testCreateType()
    {
         $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');    
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $database  = $this->getMockBuilder('Doctrine\DBAL\Connection')->disableOriginalConstructor()->getMock();
        
        
            
        $type = new ConstantNumberTypeDefinition();
        $type->eventDispatcher($event);
        $type->database($database);
        $type->locale($locale);
        $type->utilities($utilities);
        $type->generator($generator);
        
        $typeNode = $type->value('500.00')->cast('integer')->getNode();
        $interalType = $typeNode->getType();
        
        $this->assertInstanceOf('Faker\Components\Engine\Common\Type\ConstantNumber',$interalType);
        $this->assertInstanceOf('\Faker\Components\Engine\Common\Composite\TypeNode',$typeNode);
        
    }
    
    
    public function testTypePropertiesSet()
    {
         $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();
                          
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');    
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $database  = $this->getMockBuilder('Doctrine\DBAL\Connection')->disableOriginalConstructor()->getMock();
        
        $type = new ConstantNumberTypeDefinition();
        $type->eventDispatcher($event);
        $type->database($database);
        $type->locale($locale);
        $type->utilities($utilities);
        $type->generator($generator);
        
        $typeNode = $type->value('500.00')->cast('integer')->getNode();
        $interalType = $typeNode->getType();
        
        $this->assertEquals('500.00',$interalType->getOption('value'));
        $this->assertEquals('integer',$interalType->getOption('type'));

        $this->assertEquals($generator,$interalType->getGenerator());
        $this->assertEquals($locale,$interalType->getLocale());
        $this->assertEquals($utilities,$interalType->getUtilities());
        
    }
    
}
/*End of file */
