<?php
namespace Faker\Components\Config;

use Faker\Io\IoInterface;

/**
  *  Class Writer
  */
class Writer
{

     /**
      * The extension of the config files
      *
      * @var string
      */
    const EXTENSION   = '.php';


     //----------------------------------------------------------------

    /**
      * Writes a config array to a file
      *
      * @param array $config a key value store
      * @param string $alias a name for the file
      * @param boolean $overrite setting true will overwrite a file
      * @return boolean true on sucessful write false otherwise
      */
    public function write($config,$alias,$overrite = FALSE)
    {

        #setup new config entity
        $entity = new Entity();
        $entity->merge($config);

        #write to file
        $file = <<<EOF
<?php
        /* Database Config file */

        return array(
            'db_type' => '%s' ,
            'db_schema' => '%s' ,
            'db_user' => '%s' ,
            'db_password' => '%s',
            'db_host' => '%s' ,
            'db_port' => %s 
        );

        /* End of Config File */

EOF;

        $file = sprintf($file,$entity->getType(),
                            $entity->getSchema(),
                            $entity->getUser(),
                            $entity->getPassword(),
                            $entity->getHost(),
                            $entity->getPort()
        );


        #assign file ext to alias
        if(strpos($alias,'.')  === FALSE) {
            $alias .= self::EXTENSION;
        }


        # Write file to the config folder
       return $this->getIo()->write($alias,null,$file,$overrite);

   }

  //------------------------------------------------------------------

   /**
    * Class Constructor
    *
    *  @param Faker\Io\IoInterface $Io
    */
    public function __construct(IoInterface $Io)
    {
        $this->setIo($Io);
    }



    //--------------------------------------------------------------------
    /**
     * Input Output controller
     *
     *  @var IoInterface
    */
    protected $io;

   /**
    * Fetches the Io Class
    *
    * @return IoInterface
    */
    public function getIo()
    {
        return $this->io;
    }

    /**
    * Sets the IO class
    *
    *  @param IoInterface $io
    */
    public function setIo(IoInterface $io)
    {
        $this->io = $io;

        return $this;
    }


    //---------------------------------------------------------------------

}
/* End of File */
