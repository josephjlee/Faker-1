#!/usr/bin/env php
<?php
namespace Faker;

use Faker\Project,
    Faker\Path,
    Faker\Command\Application,
    Faker\Command\GenerateCommand,
    Faker\Command\AnalyseCommand,
    Faker\Command\ConfigureCommand,
    Faker\Command\InitProjectCommand;

if(strpos('@PHP-BIN@', '@PHP-BIN@') === 0) {
    // stand-alone version is running
  require __DIR__ . DIRECTORY_SEPARATOR .'..' . DIRECTORY_SEPARATOR . 'src'. DIRECTORY_SEPARATOR .'Faker'. DIRECTORY_SEPARATOR .'Bootstrap.php';
}
else {
   require '@PEAR-DIR@'. DIRECTORY_SEPARATOR .'Faker'. DIRECTORY_SEPARATOR .'Bootstrap.php';
}

//---------------------------------------------------------------------
// Set Pear Directories
//
//--------------------------------------------------------------------

if(strpos('@PHP-BIN@', '@PHP-BIN@') === 0) {
    //not a pear install run normally
  $project['data_path'] = new Path(__DIR__ . DIRECTORY_SEPARATOR .'..'. DIRECTORY_SEPARATOR .'data');
}
else {
   $project['data_path'] = new Path('@PEAR-DATA@');
}

//---------------------------------------------------------------------
// Inject Faker Install Ccommands
//
//--------------------------------------------------------------------

$project->getConsole()->add(new GenerateCommand('generate'));
$project->getConsole()->add(new AnalyseCommand('analyse'));
$project->getConsole()->add(new ConfigureCommand('configure'));
$project->getConsole()->add(new InitProjectCommand('init'));


//--------------------------------------------------------------------
// Run the App
//--------------------------------------------------------------------

$project->getConsole()->run();


/* End of Class */
