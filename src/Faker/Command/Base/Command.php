<?php
namespace Faker\Command\Base;

use Symfony\Component\Console\Command\Command as BaseCommand,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Input\InputOption,
    Faker\Project,
    Faker\Exception as FakerException;

class Command extends BaseCommand
{

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->addOption('--path','-p',     InputOption::VALUE_OPTIONAL,'the project folder path',false);
        
        # mysql://root:vagrant@tcp(localhost:3306)/sakila
        # http://pear.php.net/manual/en/package.database.db.intro-dsn.php
        $this->addOption('--dsn', '',   InputOption::VALUE_OPTIONAL,'DSN to connect to db',false);
    }


    /**
     * Initializes the command just after the input has been validated.
     *
     * This is mainly useful when a lot of commands extends one main command
     * where some things need to be initialized based on the input arguments and options.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
       $project = $this->getApplication()->getProject();

       if (true === $input->hasParameterOption(array('--path', '-p'))) {
            #switch path to the argument
            $project->getPath()->parse((string)$input->getOption('path'));
       
            # change the extension directories
            $project['loader']->setExtensionNamespace(
               'Faker\\Extension' , $project->getPath()->get()
            );
       }

        #try and detect if path exits
        $path = $project->getPath()->get();
        if($path === false) {
            throw new \RuntimeException('Project Folder does not exist');
        }

        # path exists does it have a project
        if(Project::detect((string)$path) === false && $this->getName() !== 'faker:init') {
            throw new \RuntimeException('Project Folder does not contain the correct folder heirarchy');
        } 
        
        # load the extension bootstrap the path has been verifed to contain an extension folder
        if($this->getName() !== 'faker:init') {
          $project->getPath()->loadExtensionBootstrap();    
        }

        if (true === $input->hasParameterOption(array('--schema'))) {
            #switch path to the argument
            $project['schema_name'] = $input->getOption('schema');;
        }

        # Test for DSN
        if (true === $input->hasParameterOption(array('--dsn'))) {
            $project['dsn_command'] = $input->getOption('dsn');
        }
        
    }
    
}
/* End of File */
