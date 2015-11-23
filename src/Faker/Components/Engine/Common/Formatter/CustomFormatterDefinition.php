<?php
namespace Faker\Components\Engine\Common\Formatter;

use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\FormatterNode;


/**
  *  Definition for the Custom Formatters added by the user
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class CustomFormatterDefinition extends AbstractDefinition
{
    
    protected $className;
    
    /**
    * Instantiate and configure the node according to this definition
    *
    * @return Faker\Components\Engine\Common\Composite\FormatterNode The node instance
    *
    * @throws InvalidDefinitionException When the definition is invalid
    */
    public function getNode()
    {
        # assign mysql as a default platform
        if($this->dbalPlatform === null) {
            $this->dbalPlatform = 'mysql';
        }
        
        $platform  = $this->platformFactory->create($this->dbalPlatform);
        $formatter = $this->formatterFactory->create($this->className,$platform,$this->attributes);
        
        # return a CompositeInterface Node
        return new FormatterNode('formatterNode'.rand(100,1000),$this->eventDispatcher,$formatter);
    }
    
    /**
      *  FQN of the Custom Formatter
      *
      *  @param string the class name
      *  @return DefaultTypeDefinition
      *  @access protected
      */    
    public function typeName($class)
    {
        $this->className = $class;
        
        return $this;
    }
    
    /**
     * @see self::end()
     */ 
    public function endCustomWriter()
    {
        return $this->end();
    }
}
/* End of File */