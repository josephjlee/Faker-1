<?php
namespace Faker\Components\Faker\Composite;

use Faker\Components\Faker\Exception as FakerException,
    Faker\Components\Faker\Formatter\FormatEvents,
    Faker\Components\Faker\Formatter\GenerateEvent,
    Symfony\Component\EventDispatcher\EventDispatcherInterface,
    Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Schema extends BaseComposite 
{
    
    /**
      *  @var CompositeInterface 
      */
    protected $parent_type;
    
    /**
      *  @var CompositeInterface[] 
      */
    protected $child_types = array();
    
    /**
      *  @var string the id of the component 
      */
    protected $id;
    
    /**
      *  @var EventDispatcherInterface 
      */
    protected $event;
    
    /**
      *  @var FormatterInterface[] the assigned writters  
      */
    protected $writers = array();
    
    /**
      *  Class construtor
      *
      *  @access public
      *  @return void
      *  @param string $id the schema name
      *  @param CompositeInterface $parent (Optional for this object)
      */
    public function __construct($id , CompositeInterface $parent = null, EventDispatcherInterface $event,$options = array())
    {
        $this->id = $id;
        $this->event = $event;
        $this->options = $options;    
            
        if($parent !== null) {
            $this->setParent($parent);
        }
    }
    
    /**
      *  @inheritdoc 
      */
    public function generate($rows,$values = array())
    {
          # dispatch the start event
       
          $this->event->dispatch(
               FormatEvents::onSchemaStart,
               new GenerateEvent($this,array(),$this->getId())
          );
        
          # send generate command to children
       
          foreach($this->child_types as $type) {
               $type->generate($rows,$values);
          }
       
          # dispatch the stop event
     
          $this->event->dispatch(
               FormatEvents::onSchemaEnd,
               new GenerateEvent($this,array(),$this->getId())
          );
    }
    
     /**
       *  Return the writters
       *
       *  @return FormaterInterface[] 
       */    
    public function getWriters()
    {
          return $this->writers;
    }
    
    /**
      *   
      */
    public function setWriters(array $writters)
    {
        $this->writers = $writters;
    }
    
    
    /**
      *  @inheritdoc 
      */
    public function getId()
    {
        return $this->id;
    }
    
    
    /**
      * @inheritdoc
      */
    public function getParent()
    {
        return $this->parent_type;
    }

    /**
      * @inheritdoc  
      */
    public function setParent(CompositeInterface $parent)
    {
        $this->parent_type = $parent;
    }
    
    
    /**
      *  @inheritdoc
      */
    public function getChildren()
    {
        return $this->child_types;
    }
    
    
    /**
      *  @inheritdoc
      */
    public function addChild(CompositeInterface $child)
    {
        return array_push($this->child_types,$child);
    }
    
    /**
      *  @inheritdoc
      */
    public function getEventDispatcher()
    {
          return $this->event;
    }
    
    /**
      *  @inheritdoc
      */
    public function toXml()
    {
          # schema declaration
          
          $str  = '<?xml version="1.0"?>' .PHP_EOL;
          
          $str .= '<schema name="'.$this->getId().'">' . PHP_EOL;
     
          # generate xml def for each writter
          
          foreach($this->getWriters() as $writer ) {
               $str .= $writer->toXml();
          }
          
          foreach($this->child_types as $child) {
               $str .= $child->toXml();     
          }
     
          $str .= '</schema>' . PHP_EOL;
      
          return $str;
    }
    
     
    //  -------------------------------------------------------------------------
     
     public function validate()
     {
        # ask children to validate themselves
        
        foreach($this->getChildren() as $child) {
        
          $child->validate(); 
        }
        
        # check that children have been added
        
        if(count($this->getChildren()) === 0) {
          throw new FakerException('Schema must have at least 1 table');
        }
        
        # check if a writter been set
        if(count($this->writers) === 0) {
          throw new FakerException('Writter not found must have atleast on writter');
        }

        return true;          
     }

    //  -------------------------------------------------------------------------
    # Option Interface
    
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('config');

        $rootNode
            ->children()
                ->scalarNode('locale')
                    ->treatNullLike('en')
                    ->defaultValue('en')
                    ->setInfo('The Default Local for this schema')
                    ->validate()
                        ->ifTrue(function($v){
                            return !is_string($v);
                        })
                        ->then(function($v){
                            throw new \Faker\Components\Faker\Exception('Schema::Locale not in valid list');
                        })
                    ->end()
                ->end()
            ->end();
            
        return $treeBuilder;
    }
    
    //  -------------------------------------------------------------------------
}
/* End of File */