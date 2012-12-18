<?php

namespace dayax\collections\tests;

use dayax\core\test\TestCase;
use dayax\collections\ListCollection;

class ListItem
{
    public $data='data';
}

/**
 * @package System.Collections
 */
class ListCollectionTest extends TestCase
{

    protected $list;
    protected $item1, $item2, $item3, $item4;

    public function setUp()
    {
        $this->list=new ListCollection;
        $this->item1=new ListItem;
        $this->item2=new ListItem;
        $this->item3=new ListItem;
        $this->item4=new ListItem;
        $this->list->add($this->item1);
        $this->list->add($this->item2);
    }

    public function tearDown()
    {
        $this->list=null;
        $this->item1=null;
        $this->item2=null;
        $this->item3=null;
        $this->item4=null;
    }

    public function testConstruct()
    {
        $a=array(1,2,3);
        $list=new ListCollection($a);
        $this->assertEquals(3,$list->getCount());
        $list2=new ListCollection($this->list);
        $this->assertEquals(2,$list2->getCount());
    }

    public function testGetReadOnly()
    {
        $list = new ListCollection(null, true);
        self::assertEquals(true, $list->getReadOnly(), 'List is not read-only');
        $list = new ListCollection(null, false);
        self::assertEquals(false, $list->getReadOnly(), 'List is read-only');
    }

    public function testGetCount()
    {
        $this->assertEquals(2,$this->list->getCount());
        $this->assertEquals(2,$this->list->Count);
    }

    public function testItemAt()
    {
        $this->assertTrue($this->list->itemAt(0) === $this->item1);
        $this->assertTrue($this->list->itemAt(1) === $this->item2);
    }

    public function testAdd()
    {
        $this->assertEquals(2,$this->list->add(null));
        $this->assertEquals(3,$this->list->add($this->item3));
        $this->assertEquals(4,$this->list->getCount());
        $this->assertEquals(3,$this->list->indexOf($this->item3));
    }

    /**
     * @expectedException dayax\collections\InvalidOperationException
     */
    public function testCanNotAddWhenReadOnly()
    {
        $list = new ListCollection(array(), true);
        $list->add(1);
    }

    public function testInsertAt()
    {
        $this->assertNull($this->list->insertAt(0,$this->item3));
        $this->assertEquals(3,$this->list->getCount());
        $this->assertEquals(2,$this->list->indexOf($this->item2));
        $this->assertEquals(0,$this->list->indexOf($this->item3));
        $this->assertEquals(1,$this->list->indexOf($this->item1));
    }

    /**
     * @expectedException dayax\collections\InvalidDataValueException
     */
    public function testInsertAtException()
    {
        $this->list->insertAt(4,$this->item3);
    }

    /**
     * @expectedException   dayax\collections\InvalidOperationException
     * @dataProvider        getCanNotInsertAtWhenReadOnly
     */
    public function testCanNotInsertAtWhenReadOnly($index,$item)
    {
        $list = new ListCollection(array(), true);
        $list->insertAt($index,$item);
        self::fail('An expected TInvalidOperationException was not raised');
    }

    public function getCanNotInsertAtWhenReadOnly()
    {
        /*
        $list = new ListCollection(array(), true);

        try {
            $list->insertAt(1, 2);
            self::fail('An expected TInvalidOperationException was not raised');
        } catch (TInvalidOperationException $e) {
        }
        try {
            $list->insertAt(0, 2);
            self::fail('An expected TInvalidOperationException was not raised');
        } catch (TInvalidOperationException $e) {
        }*/

        return array(
            array(1,2),
            array(0,2),
        );
    }

    public function testInsertBefore()
    {
        $this->assertEquals(2,$this->list->getCount());
        $this->assertEquals(0,$this->list->insertBefore($this->item1,$this->item3));
        $this->assertEquals(3,$this->list->getCount());
        $this->assertEquals(0,$this->list->indexOf($this->item3));
        $this->assertEquals(1,$this->list->indexOf($this->item1));
        $this->assertEquals(2,$this->list->indexOf($this->item2));
    }

    /**
     * @expectedException dayax\collections\InvalidDataValueException
     */
    public function testInsertBeforeException()
    {
        $this->list->insertBefore($this->item4,$this->item3);
    }

    /**
     * @dataProvider        getCanNotInsertBeforeWhenReadOnly
     * @expectedException   dayax\collections\InvalidOperationException
     */
    public function testCanNotInsertBeforeWhenReadOnly($baseitem,$item)
    {
        $list = new ListCollection(array(5), true);
        $list->insertBefore($baseitem, $item);
    }

    public function getCanNotInsertBeforeWhenReadOnly()
    {
        return array(
          array(5,6),
          array(8,6),
        );
    }

    public function testInsertAfter()
    {
        $this->assertEquals(2,$this->list->getCount());
        $this->assertEquals(2,$this->list->insertAfter($this->item2,$this->item3));
        $this->assertEquals(3,$this->list->getCount());
        $this->assertEquals(0,$this->list->indexOf($this->item1));
        $this->assertEquals(1,$this->list->indexOf($this->item2));
        $this->assertEquals(2,$this->list->indexOf($this->item3));
    }

    /**
     * @expectedException dayax\collections\InvalidDataValueException
     */
    public function testInsertAfterException()
    {
        $this->list->insertAfter($this->item4,$this->item3);
    }

    /**
     * @dataProvider        getCanNotInsertAfterWhenReadOnly
     * @expectedException   dayax\collections\InvalidOperationException
     */
    public function testCanNotInsertAfterWhenReadOnly($baseitem,$item)
    {
        $list = new ListCollection(array(5), true);
        $list->insertAfter($baseitem, $item);
    }

    public function getCanNotInsertAfterWhenReadOnly()
    {
        return array(
            array(5,6),
            array(8,6),
        );
    }

    /**
     * @expectedException dayax\collections\InvalidDataValueException
     */
    public function testRemove()
    {
        $this->assertEquals(0,$this->list->remove($this->item1));
        $this->assertEquals(1,$this->list->getCount());
        $this->assertEquals(-1,$this->list->indexOf($this->item1));
        $this->assertEquals(0,$this->list->indexOf($this->item2));
        $this->list->remove($this->item1);
    }

    /**
     * @expectedException   dayax\collections\InvalidOperationException
     * @dataProvider        getCanNotRemoveWhenReadOnly
     */
    public function testCanNotRemoveWhenReadOnly($item)
    {
        $list = new ListCollection(array(1, 2, 3), true);
        $list->remove($item);
    }

    public function getCanNotRemoveWhenReadOnly()
    {
        return array(
            array(2),
            array(10),
        );
    }

    /**
     * @expectedException dayax\collections\InvalidDataValueException
     */
    public function testRemoveAt()
    {
        $this->list->add($this->item3);
        $this->assertEquals($this->item2, $this->list->removeAt(1));
        $this->assertEquals(-1,$this->list->indexOf($this->item2));
        $this->assertEquals(1,$this->list->indexOf($this->item3));
        $this->assertEquals(0,$this->list->indexOf($this->item1));

        $this->list->removeAt(2);//throw exception
    }

    /**
     * @dataProvider getCanNotRemoveAtWhenReadOnly
     * @expectedException dayax\collections\InvalidOperationException
     */
    public function testCanNotRemoveAtWhenReadOnly($index)
    {
        $list = new ListCollection(array(1, 2, 3), true);
        $list->removeAt($index);
    }

    public function getCanNotRemoveAtWhenReadOnly()
    {
        return array(
            array(2),
            array(10),
        );
    }

    public function testClear()
    {
        $this->list->clear();
        $this->assertEquals(0,$this->list->getCount());
        $this->assertEquals(-1,$this->list->indexOf($this->item1));
        $this->assertEquals(-1,$this->list->indexOf($this->item2));
    }

    /**
     * @expectedException dayax\collections\InvalidOperationException
     */
    public function testCanNotClearWhenReadOnly()
    {
        $list = new ListCollection(array(1, 2, 3), true);
        $list->clear();
    }

    public function testContains()
    {
        $this->assertTrue($this->list->contains($this->item1));
        $this->assertTrue($this->list->contains($this->item2));
        $this->assertFalse($this->list->contains($this->item3));
    }

    public function testIndexOf()
    {
        $this->assertEquals(0,$this->list->indexOf($this->item1));
        $this->assertEquals(1,$this->list->indexOf($this->item2));
        $this->assertEquals(-1,$this->list->indexOf($this->item3));
    }

    /**
     * @expectedException dayax\collections\InvalidDataTypeException
     */
    public function testCopyFrom()
    {
        $array=array($this->item3,$this->item1);
        $this->list->copyFrom($array);
        $this->assertTrue(count($array)==2 && $this->list[0]===$this->item3 && $this->list[1]===$this->item1);

        $this->list->copyFrom($this);//throws exception
    }

    /**
     * @expectedException dayax\collections\InvalidDataTypeException
     */
    public function testMergeWith()
    {
        $array=array($this->item3,$this->item1);
        $this->list->mergeWith($array);
        $this->assertTrue($this->list->getCount()==4 && $this->list[0]===$this->item1 && $this->list[3]===$this->item1);
        $this->list->mergeWith($this);//throws exception
    }

    public function testToArray()
    {
        $array=$this->list->toArray();
        $this->assertTrue(count($array)==2 && $array[0]===$this->item1 && $array[1]===$this->item2);
    }

    /**
     * @expectedException dayax\collections\InvalidDataValueException
     */
    public function testArrayRead()
    {
        $this->assertTrue($this->list[0]===$this->item1);
        $this->assertTrue($this->list[1]===$this->item2);
        $a=$this->list[2];//throws exception
    }

    public function testGetIterator()
    {
        $n=0;
        $found=0;
        foreach ($this->list as $index=>$item) {
            foreach($this->list as $a=>$b);	// test of iterator
                $n++;
            if($index===0 && $item===$this->item1)
                $found++;
            if($index===1 && $item===$this->item2)
                $found++;
        }
        $this->assertTrue($n==2 && $found==2);
    }

    public function testArrayMisc()
    {
        $this->assertEquals($this->list->Count,count($this->list));
        $this->assertTrue(isset($this->list[1]));
        $this->assertFalse(isset($this->list[2]));
    }

    public function testOffsetSetAdd()
    {
        $list = new ListCollection(array(1, 2, 3));
        $list->offsetSet(null, 4);
        self::assertEquals(array(1, 2, 3, 4), $list->toArray());
    }

    public function testOffsetSetReplace()
    {
        $list = new ListCollection(array(1, 2, 3));
        $list->offsetSet(1, 4);
        self::assertEquals(array(1, 4, 3), $list->toArray());
    }

    public function testOffsetUnset()
    {
        $list = new ListCollection(array(1, 2, 3));
        $list->offsetUnset(1);
        self::assertEquals(array(1, 3), $list->toArray());
    }
}
