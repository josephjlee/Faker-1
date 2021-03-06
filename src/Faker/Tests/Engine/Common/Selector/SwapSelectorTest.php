<?php
namespace Faker\Tests\Engine\Common\Selector;

use Faker\Components\Engine\Common\Selector\SwapSelector;
use Faker\Components\Engine\Common\PositionManager;
use Faker\Tests\Base\AbstractProject;

class SwapSelectorTest extends AbstractProject
{
    
    public function testNewObject()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');
        
        
        $type = new SwapSelector();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Type\\TypeInterface',$type);
        
        
    }
    
    
    
    public function testGenerate()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');
        
        
        $type = new SwapSelector();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->registerSwap(new PositionManager(3));
        $type->registerSwap(new PositionManager(3));
        
        $type->validate();
        
        $values = array(); 
        $this->assertEquals(1,$type->generate(0,$values));
        $this->assertEquals(1,$type->generate(0,$values));
        $this->assertEquals(1,$type->generate(0,$values));
        $this->assertEquals(2,$type->generate(0,$values));
        $this->assertEquals(2,$type->generate(0,$values));
        $this->assertEquals(2,$type->generate(0,$values));
        
        # loops?
        $this->assertEquals(1,$type->generate(0,$values));
        $this->assertEquals(1,$type->generate(0,$values));
        $this->assertEquals(1,$type->generate(0,$values));
        $this->assertEquals(2,$type->generate(0,$values));
        $this->assertEquals(2,$type->generate(0,$values));
        $this->assertEquals(2,$type->generate(0,$values));
        
        
    }
    
   
    
}
/* End of File */