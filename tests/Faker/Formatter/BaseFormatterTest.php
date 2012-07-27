<?php
namespace Faker\Tests\Faker\Formatter;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Faker\Tests\Base\AbstractProject,
    Faker\Components\Faker\Formatter\BaseFormatter;

class MockFormatter extends BaseFormatter
{
    
    public function getName()
    {
        return 'mockFormatter';
    }
    
    public function toXml()
    {
        return '';
    }
    
    public function getConfigExtension(ArrayNodeDefinition $rootNode)
    {
        return $rootNode;
    }
    
    public function getOuputFileFormat()
    {
        return '{prefix}_{body}_{suffix}_{seq}.{ext}';
    }
    
};    
    
    
class BaseFormatterTest extends AbstractProject
{
    public function testOptionProperties()
    {
        $base = new MockFormatter();
        
        $mock = new \stdClass();
        $base->setOption('option1',1);
        $base->setOption('option2','anoption');
        $base->setOption('option3',$mock);
        $this->assertEquals(1,$base->getOption('option1'));
        $this->assertEquals('anoption',$base->getOption('option2'));
        $this->assertEquals($mock,$base->getOption('option3'));
        
    }
    
    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Option at option100 does not exist
      */
    public function testMissingOptionException()
    {
        $base = new MockFormatter();
        $base->getOption('option100');
        
    }
    
    public function testColumnMapProperties()
    {
        $map = array('v1' => 1);
        $base = new MockFormatter();
        
        $base->setColumnMap($map);
        $this->assertEquals($map,$base->getColumnMap());
    }
    
    public function testWriterProperties()
    {
        $writer = $this->getMock('\Faker\Components\Writer\WriterInterface');
        $base = new MockFormatter();
        $base->setWriter($writer);
        $this->assertEquals($writer,$base->getWriter());
        
    }
    
    
    public function testEventDispatcherProperty()
    {
        $event = $this->getMock('\Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $base = new MockFormatter();
        $base->setEventDispatcher($event);
        $this->assertEquals($event,$base->getEventDispatcher());
    }
    
    
    public function testMergeMissingDefaults()
    {
        $base = new MockFormatter();
        $base->merge();
        $this->assertEquals(false,$base->getOption('splitOnTable'));
        $this->assertEquals('{prefix}_{body}_{suffix}_{seq}.{ext}',$base->getOption('outFileFormat'));
            
    }
    
    public function testMergeMissingValues()
    {
        $base = new MockFormatter();
        $base->setOption('splitOnTable',null);
        $base->merge();
        $this->assertFalse($base->getOption('splitOnTable'));
    }
    
    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage  Invalid type for path "config.splitOnTable". Expected boolean, but got string
      */
    public function testMergeBadValues()
    {
        $base = new MockFormatter();
        $base->setOption('splitOnTable','true');
        $base->merge();
        
    }
    
    
    public function testMergeGoodValues()
    {
        $base = new MockFormatter();
        $base->setOption('splitOnTable',true);
        $base->setOption('outFileFormat','{suffix}_{seq}.{ext}');
        $base->merge();
        $this->assertTrue($base->getOption('splitOnTable'));
        $this->assertEquals('{suffix}_{seq}.{ext}',$base->getOption('outFileFormat'));
    }
    
}
/* End of File */