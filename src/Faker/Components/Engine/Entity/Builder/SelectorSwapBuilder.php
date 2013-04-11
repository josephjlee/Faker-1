<?php
namespace Faker\Components\Engine\Entity\Builder;

use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Builder\TypeDefinitionInterface;
use Faker\Components\Engine\Common\Composite\SelectorNode;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Selector\SwapSelector;
use Faker\Components\Engine\Common\PositionManager;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Faker\Components\Engine\Common\Utilities;
use Faker\Locale\LocaleInterface;
use PHPStats\Generator\GeneratorInterface;
use Doctrine\DBAL\Connection;

/**
  *  Allows the Swap Selector to be created and populated with types
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class SelectorSwapBuilder extends NodeCollection implements TypeDefinitionInterface 
{
    
    /**
      *  @var array[PositionManager] 
      */
    protected $positionManagers = array();
    
    
    //------------------------------------------------------------------
    #TypeDefinitionInterface
    
    public function locale(LocaleInterface $locale)
    {
        $this->locale = $locale;
        
        return $this;
    }
    
    
    public function generator(GeneratorInterface $gen)
    {
        $this->generator = $gen;
        
        return $this;
    }
    
    
    public function utilities(Utilities $util)
    {
        $this->utilities = $utilities;
        
        return $this;
    }
    
    
    public function eventDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->eventDispatcher = $dispatcher;
        
        return $this;
    }
    
    
    public function database(Connection $conn)
    {
        $this->database = $conn;
        
        return $this;
    }
    
    /**
      * Sets an attribute on the node.
      *
      * @param string $key
      * @param mixed $value
      *
      * @return AbstractDefinition
      */
    public function attribute($key, $value)
    {
        $this->attributes[$key] = $value;

        return $this;
    }
    
    
    //------------------------------------------------------------------
    
    /**
      *  Allows the description of the selector
      *
      *  @access public
      *  @return \Faker\Components\Engine\Entity\Builder\NodeBuilder
      */
    public function swapAt($number)
    {
        # create new node builder
        $nodeBuilder = new TypeBuilder('randomSelectorBuilder',$this->eventDispatcher,$this->repo,$this->utilities,$this->generator,$this->locale,$this->database);
        
        $this->positionManagers[] = new PositionManager($number);
        
        # bind this definition as the parent of nodebuilder
        $nodeBuilder->setParent($this);
        
        # return node builder to continue chain
        return $nodeBuilder;
    }
    
    
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
        $type = new SwapSelector();
        $type->setGenerator($this->generator);
        $type->setUtilities($this->utilities);
        $type->setLocale($this->locale);
             
        foreach($this->attributes as $attribute => $value) {
            $type->setOption($attribute,$value);
        }
        
        # register position managers 
        foreach($this->positionManagers as $mgr) {
            $type->registerSwap($mgr);
        }
        
        # return the composite generator selectorNode
        return new SelectorNode('selectorNode',$this->eventDispatcher,$type); 
    }
    
    
    /**
      * Returns the parent node.
      *
      * @return ParentNodeInterface The builder of the parent node
      */
    public function end()
    {
        # construct the node from this definition.
        $node     = $this->getNode();
        $parent   = $this->getParent();
        
        # append compositeNode to the parent builder.
        $parent->append($node);
        
        # return the parent to continue chain.
        return $parent;
    }
    
  
}
/* End of file */