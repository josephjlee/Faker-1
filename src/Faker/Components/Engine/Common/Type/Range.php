<?php
namespace Faker\Components\Engine\Common\Type;

use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Utilities;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

/**
 * Number Ranges Type
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.4
 *
 */
class Range extends Type
{

    /**
      *  @var number the last generated value 
      */
    protected $last_value;
    
    /**
      *  @var boolean is this first iteration 
      */
    protected $first_iteration = true;
    
    /**
      *  @inheritdoc  
      */
    public function generate($rows, &$values = array(),$last = array())
    {
        $min         = $this->getOption('min');
        $max         = $this->getOption('max');
        $step        = $this->getOption('step');
        $random      = $this->getOption('random');
        $round       = $this->getOption('round');
        $window_step = $this->getOption('windowStep') + 0;
        
        if($step === false && $random === true) {
            $this->last_value = $this->getGenerator()->generate($min,$max);
            
            if($round > 0) {
                $this->last_value = \round($this->last_value,$round,\PHP_ROUND_HALF_UP);            
            }
            
        } else {
        
            # on first generate call set last value to min
            if($this->last_value === null) {
                $this->last_value = $min;
            }
            else {
               $this->last_value = $this->last_value + $step;
            }
        
            if($round > 0) {
                $this->last_value = \round($this->last_value,$round,\PHP_ROUND_HALF_UP);            
            }
    
            if($this->last_value > $max) {
                $this->last_value      = $min;
                $this->first_iteration = false;
            }
        
        }
        
        # in first iteration reduce window to 0 remove the effect. 
        if($this->first_iteration === true) {
            $window_step = 0;
        }
        
        return ($this->last_value + $window_step);    
    }

    
    //  -------------------------------------------------------------------------

    /**
     * Generates the configuration tree builder.
     *
     */
    public function getConfigTreeExtension(NodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->scalarNode('min')
                    ->isRequired()
                    ->info('Starting Number')
                    ->example('A numeric number like 1 or 1.67 or 0.87')
                    ->validate()
                        ->ifTrue(function($v){
                            return !is_numeric($v);
                        })
                        ->then(function($v){
                            throw new EngineException('Range::min Numeric is required');
                        })
                    ->end()
                ->end()
                ->scalarNode('max')
                    ->isRequired()
                    ->example('A numeric number like 1 or 1.67 or 0.87')
                    ->info('The maxium to use in range')
                    ->validate()
                        ->ifTrue(function($v){
                            return !is_numeric($v);
                        })
                        ->then(function($v){
                            throw new EngineException('Range::max Numeric is required');
                        })
                    ->end()
                ->end()
                ->scalarNode('step')
                    ->defaultValue(false)
                    ->example('1 , 1.5 , 0.6')
                    ->info('Stepping value applied on every increment, not supplied will use random')
                    ->validate()
                        ->ifTrue(function($v){
                            return !(is_numeric($v) || $v === false);
                        })
                        ->then(function($v){
                            throw new EngineException('Range::Step option should be numeric or bool(false) to use random step');
                        })
                    ->end()
                ->end()
                ->scalarNode('windowStep')
                    ->info('Value to add to the base after the iteration has finished (reached max)')                
                    ->example(1)
                    ->defaultValue(0)
                    ->validate()
                        ->ifTrue(function($x){
                            return !is_numeric($x);
                        })
                        ->then(function($x){
                            throw new EngineException('Range:: windowStep must be an number');
                        })
                    ->end()
                ->end()
                ->booleanNode('random')
                    ->defaultFalse()
                    ->example('false|true')
                    ->info('Enable random step value on every loop, step param must be set to false')
                ->end()
                ->scalarNode('round')
                    ->defaultValue(0)
                    ->example('an integer')
                    ->info('number of places to round too')
                     ->validate()
                        ->ifTrue(function($v){
                            return !(is_integer($v) && $v > 0);
                        })
                        ->then(function($v){
                            throw new EngineException('Range::Round option should be a positive integer >= 0');
                        })
                    ->end()
                ->end()
                
            ->end();
            
        return $rootNode;
    }
    
    
    //  -------------------------------------------------------------------------
    
    public function validate()
    {
        parent::validate();
        
        $random      = $this->getOption('random');
        $window_step = $this->getOption('windowStep') + 0;
        
        if($window_step > 0 && $random === true) {
            throw new EngineException('Range:: Cannot use windowStep and RandomStep at same time');
        }
        
        # set the iteration state.
        $this->first_iteration = true;
        
        return true;
    }
    
    //  -------------------------------------------------------------------------

}

/* End of class */