<?php
namespace Faker\Components\Engine\XML\Builder;

use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\XML\Composite\SelectorNode;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Selector\PickSelector;
use Faker\Components\Engine\Common\Builder\SelectorWeightBuilder as BaseBuilder;
use Faker\Components\Engine\Common\Builder\TypeDefinitionInterface;


/**
  *  Allows the Weighted Alternate Selector to be created and populated with types
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class SelectorWeightBuilder extends BaseBuilder implements TypeDefinitionInterface 
{
    
    
    //------------------------------------------------------------------
    # Node Collection
    
    /**
      *  Fetch the node managed by this definition
      *
      *  @access public
      *  @return Faker\Components\Engine\Common\Composite\CompositeInterface the new node
      */
    public function getNode()
    {
        # construct the selector type
        $type = new PickSelector();
        $type->setGenerator($this->generator);
        $type->setUtilities($this->utilities);
        $type->setLocale($this->locale);
        
        
        foreach($this->attributes as $attribute => $value) {
            $type->setOption($attribute,$value);
        }
        
        # return the composite generator selectorNode
        return new SelectorNode('selectorNode',$this->eventDispatcher,$type); 
    }
    
  
}
/* End of file */
