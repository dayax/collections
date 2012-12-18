<?php

namespace dayax\collections\tests;

use dayax\core\test\TestCase;
use dayax\collections\Map;
use dayax\collections\ListCollection;

class MapItemTest
{
  public $data='data';
}

/**
 * @package System.Collections
 */
class MapTest extends TestCase
{
  protected $map;
  protected $item1,$item2,$item3;

  public function setUp()
  {
    $this->map=new Map;
    $this->item1=new MapItemTest;
    $this->item2=new MapItemTest;
    $this->item3=new MapItemTest;
    $this->map->add('key1',$this->item1);
    $this->map->add('key2',$this->item2);
  }

  public function tearDown()
  {
    $this->map=null;
    $this->item1=null;
    $this->item2=null;
    $this->item3=null;
  }

  public function testConstruct()
  {
    $a=array(1,2,'key3'=>3);
    $map=new Map($a);
    $this->assertEquals(3,$map->getCount());
    $map2=new Map($this->map);
    $this->assertEquals(2,$map2->getCount());
  }

    public function testGetReadOnly()
    {
        $map = new Map(null, true);
        self::assertEquals(true, $map->getReadOnly(), 'List is not read-only');
        $map = new ListCollection(null, false);
        self::assertEquals(false, $map->getReadOnly(), 'List is read-only');
    }

  public function testGetCount()
  {
    $this->assertEquals(2,$this->map->getCount());
  }

  public function testGetKeys()
  {
    $keys=$this->map->getKeys();
    $this->assertTrue(count($keys)===2 && $keys[0]==='key1' && $keys[1]==='key2');
  }

    public function testAdd()
    {
        $this->map->add('key3',$this->item3);
        $this->assertTrue($this->map->getCount()==3 && $this->map->contains('key3'));
    }

    /**
     * @expectedException dayax\collections\InvalidOperationException
     */
    public function testCanNotAddWhenReadOnly()
    {
        $map = new Map(array(), true);
        $map->add('key', 'value');
    }

    public function testRemove()
    {
        $this->map->remove('key1');
        $this->assertTrue($this->map->getCount()==1 && !$this->map->contains('key1'));
        $this->assertTrue($this->map->remove('unknown key')===null);
    }

    /**
     * @expectedException dayax\collections\InvalidOperationException
     */
    public function testCanNotRemoveWhenReadOnly()
    {
        $map = new Map(array('key' => 'value'), true);
        $map->remove('key');
    }

    public function testClear()
    {
        $this->map->clear();
        $this->assertTrue($this->map->getCount()==0 && !$this->map->contains('key1') && !$this->map->contains('key2'));
    }

    public function testContains()
    {
        $this->assertTrue($this->map->contains('key1'));
        $this->assertTrue($this->map->contains('key2'));
        $this->assertFalse($this->map->contains('key3'));
    }

    /**
     * @expectedException dayax\collections\InvalidDataTypeException
     */
    public function testCopyFrom()
    {
        $array=array('key3'=>$this->item3,'key4'=>$this->item1);
        $this->map->copyFrom($array);
        $this->assertTrue($this->map->getCount()==2 && $this->map['key3']===$this->item3 && $this->map['key4']===$this->item1);
        $this->map->copyFrom($this);
        $this->fail('no exception raised when copying a non-traversable object');
    }

    /**
     * @expectedException dayax\collections\InvalidDataTypeException
     */
    public function testMergeWith()
    {
        $array=array('key2'=>$this->item1,'key3'=>$this->item3);
        $this->map->mergeWith($array);
        $this->assertTrue($this->map->getCount()==3 && $this->map['key2']===$this->item1 && $this->map['key3']===$this->item3);
        $this->map->mergeWith($this);
    }

    public function testArrayRead()
    {
        $this->assertTrue($this->map['key1']===$this->item1);
        $this->assertTrue($this->map['key2']===$this->item2);
        $this->assertEquals(null,$this->map['key3']);
    }

    public function testArrayWrite()
    {
        $this->map['key3']=$this->item3;
        $this->assertTrue($this->map['key3']===$this->item3 && $this->map->getCount()===3);
        $this->map['key1']=$this->item3;
        $this->assertTrue($this->map['key1']===$this->item3 && $this->map->getCount()===3);
        unset($this->map['key2']);
        $this->assertTrue($this->map->getCount()===2 && !$this->map->contains('key2'));
        try {
            unset($this->map['unknown key']);

        } catch (Exception $e) {
            $this->fail('exception raised when unsetting element with unknown key');
        }
    }

    public function testArrayForeach()
    {
        $n=0;
        $found=0;
        foreach ($this->map as $index=>$item) {
            $n++;
            if($index==='key1' && $item===$this->item1)
                $found++;
            if($index==='key2' && $item===$this->item2)
                $found++;
        }
        $this->assertTrue($n==2 && $found==2);
    }

    public function testArrayMisc()
    {
        $this->assertEquals($this->map->Count,count($this->map));
        $this->assertTrue(isset($this->map['key1']));
        $this->assertFalse(isset($this->map['unknown key']));
    }

    public function testToArray()
    {
        $map = new Map(array('key' => 'value'));
        self::assertEquals(array('key' => 'value'), $map->toArray());
    }

    public function testAliases()
    {
        $m = new Map(array(
            'foo'=>'foo',
            'bar'=>'bar',
            'hello'=>'hello',
            'world'=>'world',
        ));

        $m->addAlias('foo', 'foo1');
        $m->addAlias('bar', 'bar1');
        $this->assertTrue($m->contains('foo1'),'array should contain alias key');
        $this->assertEquals('foo', $m['foo1'],'test get from alias');
        $this->assertEquals('bar', $m['bar1'],'test get from alias');

        $m['foo1'] = "foo bar";
        $this->assertEquals('foo bar', $m['foo'],'can set with aliases');

        $m->setAliasCaseSensitive(true);
        $m->addAlias('hello', "FOO");
        $this->assertEquals('hello', $m['FOO']);
    }
}
