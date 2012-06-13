<?php
namespace Faker\Components\Faker\Composite;

use Faker\Components\Faker\Exception as FakerException,
    Faker\Components\Faker\Formatter\FormatEvents,
    Faker\Components\Faker\Formatter\GenerateEvent,
    Faker\Components\Faker\CacheInterface,
    Faker\Components\Faker\GeneratorCache,
    Symfony\Component\EventDispatcher\EventDispatcherInterface,
    Symfony\Component\Config\Definition\Builder\TreeBuilder,
    Doctrine\DBAL\Types\Type as ColumnType;


class Column extends BaseComposite implements CacheInterface
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
      *  @var Doctrine\DBAL\Types\Type the mapper to convert php types into database representations
      */
    protected $column_type;
    
    /**
      *  @var Symfony\Component\EventDispatcher\EventDispatcherInterface
      */
    protected $event;
    
    /**
      *  @var GeneratorCache the cache which old all values
      */
    protected $cache;
    
    /**
      *  @var boolean true to use cache class 
      */
    protected $use_cache = false;
    
    /**
      *  Class construtor
      *
      *  @access public
      *  @return void
      *  @param string $id the schema name
      *  @param CompositeInterface $parent 
      */
    public function __construct($id, CompositeInterface $parent, EventDispatcherInterface $event, ColumnType $column,$options = array())
    {
        $this->id = $id;
        $this->setParent($parent);
        $this->event = $event;
        $this->column_type = $column;
        $this->options = $options;
        $this->use_cache = false;
    }
    
    /**
      *  @inheritdoc 
      */
    public function generate($rows,$values = array())
    {
        # dispatch the start event
        
        $this->event->dispatch(
                        FormatEvents::onColumnStart,
                        new GenerateEvent($this,$values,$this->getId())
        );
        
        # send the generate command to the type
        $value = array();
        
        foreach($this->child_types as $type) {
                         
            # if we have many types we concatinate
            $value[] =$type->generate($rows,$values);
        
            # dispatch the generate event
            $this->event->dispatch(
                FormatEvents::onColumnGenerate,
                new GenerateEvent($this,array( $this->getId() => $value ),$this->getId())
            );
        }
        
        # assign the value to the struct, check if only one value
        # if one value we want to keep the type the same
        
        if(count($value) > 1) {
            $values[$this->getId()] = implode('',$value); # join as a string 
        } else {
            $values[$this->getId()] = $value[0]; 
        }
        
        # test if the value needs to be cached
        if($this->use_cache === true) {
            $this->cache->add($values[$this->getId()]);
        }
        
        # dispatch the stop event
        $this->event->dispatch(
                FormatEvents::onColumnEnd,
                new GenerateEvent($this,$values,$this->getId())
        );
        
        # return values so they can be grouped in table parent
        return $values;
    }
    
    //  -------------------------------------------------------------------------
    
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
      *  Get Event Dispatcher
      *
      *  @return Symfony\Component\EventDispatcher\EventDispatcherInterface 
      */ 
    public function getEventDispatcher()
    {
        return $this->event;
    }
    
    
    /**
      *  Convert the column into xml representation
      *
      *  @return string
      *  @access public
      */
    public function toXml()
    {
        $str = '<column name="'.$this->getId().'" type="'.$this->getColumnType()->getName().'">'.PHP_EOL;
    
        foreach($this->getChildren() as $child) {
            $str .= $child->toXml();
        }
    
        $str .= '</column>'. PHP_EOL;
      
        return $str;
        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  Return the doctrine column type
      *
      * @return Doctrine\DBAL\Types\Type
      */
    public function getColumnType()
    {
        return $this->column_type;        
    }
    
    //  -------------------------------------------------------------------------

    public function validate()
    {
        # validate internal config
        $this->options = $this->merge($this->options);
        
        # ask children to validate themselves
        
        foreach($this->getChildren() as $child) {
        
          $child->validate(); 
        }
        
        # check that children have been added
        
        if(count($this->getChildren()) === 0) {
          throw new FakerException('Column must have at least 1 Type');
        }
        
        
        # test if a cache has been set
        if($this->use_cache === true && !$this->cache instanceof GeneratorCache ) {
            throw new FakerException('Column has been told to use cache but none set');
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
        $rootNode    = $treeBuilder->root('config');

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
                            throw new \Faker\Components\Faker\Exception('Column::Locale not in valid list');
                        })
                    ->end()
                ->end()
            ->end();
            
        return $treeBuilder;
    }
    
    //  ------------------------------------------------------------------------- 
    # CacheInterface
    
    /**
      *  @inheritdoc
      */
    public function setGeneratorCache(GeneratorCache $cache)
    {
        $this->cache = $cache;
        return $this;
    }
    
    
    /**
      *  @inheritdoc
      */
    public function getGeneratorCache()
    {
        return $this->cache;
    }
    
    
    /**
      *  @inheritdoc
      */
    public function setUseCache($bool)
    {
        $this->use_cache = $bool;
        
        return $this;
    }
    
    
    /**
      *  @inheritdoc
      */
    public function getUseCache()
    {
        return $this->use_cache;
    }
    
    //------------------------------------------------------------------
    
}
/* End of File */