<?php
namespace Faker\Tests\Parser;    

use Faker\Parser\File,
    Faker\Parser\VFile,
    Faker\Parser\FileFactory,
    Faker\Tests\Base\AbstractProject;


class FileTest extends AbstractProject
{
    
    protected $str;

    public function setUp()
    {
          $this->str = <<<EOF
<?xml version="1.0" encoding="ISO-8859-1"?>
    <note>
       <to>Tove</to>
       <from>Jani</from>
       <heading>Reminder</heading>
       <body>Don't forget me this weekend!</body>
   </note>
EOF;

     file_put_contents('text.xml',$this->str);

      parent::setUp();
    }


    public function tearDown()
    {
        parent::tearDown();
        
        if(file_exists('text.xml')) {
            unlink('text.xml');
        }
    }



    public function testFileImplmentsInterface()
    {
        # assert normal file
        
        $file = new File();
        $this->assertInstanceOf('\\Faker\\Parser\\FileInterface',$file);
        
        # assert Vfile
        
        $vfile = new VFile('');
        $this->assertInstanceOf('\\Faker\\Parser\\FileInterface',$vfile);
        
    }
    
    
    public function testOpenGoodFile()
    {
        $file = new File();
        $file->fopen('text.xml');
        
        $this->assertTrue(true);
        
        
    }
    
    /**
      *  @expectedException \Faker\Parser\Exception\CantOpenFile
      */
    public function testOpenBadFile()
    {
        $file = new File();
        $file->fopen('bad_file.xml');
        
    }
    

    public function testVFileOpen()
    {
        $vfile = new VFile($this->str);
        $vfile->fopen('dummy.xml');
        $this->assertTrue(true);
        
        $vfile = new Vfile('string://'.$this->str);
        $this->assertTrue(true);
    
    }
    
    
    public function testFileFactory()
    {
        # virtualFile
        $vfile = FileFactory::create('string://'.$this->str);
        $this->assertInstanceOf('\\Faker\\Parser\\VFile',$vfile);
        
        $file = FileFactory::create('text.xml');
        $this->assertInstanceOf('\\Faker\\Parser\\File',$file);
        
    }
    
    
}
/* End of File */