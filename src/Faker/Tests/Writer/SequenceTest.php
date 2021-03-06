<?php
namespace Faker\Tests\Writer;

use Faker\Project,
    Faker\Io\Io,
    Faker\Components\Writer\Writer,
    Faker\Components\Writer\Io as TemplateIO,
    Faker\Components\Writer\Cache,
    Faker\Components\Writer\Limit,
    Faker\Components\Writer\Sequence,
    Faker\Tests\Base\AbstractProject;

class SequenceTest extends AbstractProject
{
      //  -------------------------------------------------------------------------
      # File Sequence Tests
      
      /**
        *  @group Writer 
        */
      public function testNewSequence()
      {
            $seq = new Sequence('schema','table','suffix','sql');
            $this->assertInstanceOf('\Faker\Components\Writer\Sequence',$seq);
      }

      /**
        *  @group Writer 
        */
      public function testSequenceReturnsValidFilename()
      {
            $seq = new Sequence('schema','table','suffix','sql');
            $seq->add();
            
            $this->assertSame($seq->get(),'schema_table_suffix_1.sql');

      }

      /**
        *  @group Writer 
        */
      public function testSequenceCustomFormat()
      {
            $seq = new Sequence('schema','table','','sql','{prefix}_{body}_{seq}.{ext}');
            $seq->add();
            
            $this->assertSame($seq->get(),'schema_table_1.sql');

      }
      
      /**
        *  @group Writer 
        */
      public function testSequenceAdd()
      {
            $seq = new Sequence('schema','table','','sql','{prefix}_{body}_{seq}.{ext}');
            $seq->add();
            
            $this->assertSame($seq->get(),'schema_table_1.sql');

            $seq->add();
            $this->assertSame($seq->get(),'schema_table_2.sql');

            $seq->add();
            $this->assertSame($seq->get(),'schema_table_3.sql');

            $this->assertSame(count($seq),3);
      }
      
      /**
        *  @group Writer 
        */
      public function testSequenceClear()
      {
            $seq = new Sequence('schema','table','','sql','{prefix}_{body}_{seq}.{ext}');
            $seq->add();
            $seq->add();
            $seq->add();
            $this->assertSame(count($seq),3);

            $seq->clear();

            $this->assertSame(count($seq),0);
            
            $seq->add();
            $this->assertSame($seq->get(),'schema_table_1.sql');

      }

    
}