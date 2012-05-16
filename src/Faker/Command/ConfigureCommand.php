<?php
namespace Faker\Command;

use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Helper\DialogHelper,
    Faker\Command\Base\Command,
    Faker\Components\Config\Io as ConfigIo,
    Faker\Components\Config\Manager,
    Faker\Io\FileExistException;

class ConfigureCommand extends Command
{


    protected $answers;

    protected $alias = "default";

     /**
     * Interacts with the user.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $dialog = new DialogHelper();
        $answers =  array();

        # Ask for the database type
        $answers['db_type'] =  strtolower($dialog->ask($output,'<question>Which Database does this belong? [mysql|mssql|oracle|posgsql]: </question>','mysql'));

        # apply format of the Doctrine DBAL
        $answers['db_type'] = ($answers['db_type'] !== 'oci8') ? $answers['db_type'] = 'pdo_' . $answers['db_type'] : $answers['db_type'];        
        
        # Ask Database Schema Name
        $answers['db_schema'] =  $dialog->ask($output,'<question>What is the Database schema name? : </question>');

        #Database user Name
        $answers['db_user'] =  $dialog->ask($output,'<question>What is the Database user name? : </question>');

        #Database user Password
        $answers['db_password'] =  $dialog->ask($output,'<question>What is the Database users password? : </question>');

        #Database host
        $answers['db_host'] =  $dialog->ask($output,'<question>What is the Database host name? [localhost] : </question>','localhost');

        #Database port
        $answers['db_port'] =  $dialog->ask($output,'<question>What is the Database port? [3306] :</question> ',3306);

        # Store answers for the execute method
        $this->answers = $answers;

      
        return true;
    }

    //  -------------------------------------------------------------------------
    # Execute




    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $project = $this->getApplication()->getProject();
    
        $manager = $project['config_manager'];

        try {

            #Write config file to the project
            $manager->getWriter()->write($this->answers,$project->getConfigName());

        }
        catch(FileExistException $e) {
            #ask if they want to overrite
           $dialog = new DialogHelper();
           $answer = $dialog->askConfirmation($output,'Config <info>Exists</info> do you want to <info>Overrite?</info> [y|n] :',false);

            if($answer) {
                #Write config file to the project
                $manager->getWriter()->write($this->answers,$project->getConfigName(),true);

            }

        }

        # tell them the file was written
        $output->writeln(sprintf("++ Writing <comment>config file</comment>  %s.php",$this->alias));

    }


    protected function configure()
    {
        $this->setDescription('Will create / overrite the configuration');
        $this->setHelp(<<<EOF
Write a <info>new configuration file</info> to the project folder:

Example

>> configure

Will as you the following questions. 

Type of Database [mysql | oracle  | mssql]?
Database Schema Name?
Database user Password?
Database user Name?
EOF
);



        parent::configure();
    }

}
/* End of File */
