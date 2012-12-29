<?php

namespace dayax\collections\tests;


use dayax\collections\Stack;
use dayax\core\test\TestCase;

class StackTest extends TestCase
{

	public function setUp() {
	}

	public function tearDown() {
	}

	public function testConstruct() {
		$stack = new Stack();
		self::assertEquals(array(), $stack->toArray());
		$stack = new Stack(array(1, 2, 3));
		self::assertEquals(array(1, 2, 3), $stack->toArray());
	}

	public function testToArray() {
		$stack = new Stack(array(1, 2, 3));
		self::assertEquals(array(1, 2, 3), $stack->toArray());
	}

	public function testCopyFrom() {
		$stack = new Stack(array(1, 2, 3));
		$data = array(4, 5, 6);
		$stack->copyFrom($data);
		self::assertEquals(array(4, 5, 6), $stack->toArray());
	}
	
	public function testCanNotCopyFromNonTraversableTypes() {
		$stack = new Stack();
		$data = new \stdClass();
		try {
			$stack->copyFrom($data);
		} catch(\dayax\collections\InvalidDataTypeException $e) {
			return;
		}
		self::fail('An expected TInvalidDataTypeException was not raised');
	}
	
	public function testClear() {
		$stack = new Stack(array(1, 2, 3));
		$stack->clear();
		self::assertEquals(array(), $stack->toArray());
	}

	public function testContains() {
		$stack = new Stack(array(1, 2, 3));
		self::assertEquals(true, $stack->contains(2));
		self::assertEquals(false, $stack->contains(4));
	}

	public function testPeek() {
		$stack = new Stack(array(1));
		self::assertEquals(1, $stack->peek());
	}
	
	public function testCanNotPeekAnEmptyStack() {
		$stack = new Stack();
		try {
			$item = $stack->peek();
		} catch(\dayax\collections\InvalidOperationException $e) {
			return;
		}
		self::fail('An expected TInvalidOperationException was not raised');
	}

	public function testPop() {
		$stack = new Stack(array(1, 2, 3));
		$last = $stack->pop();
		self::assertEquals(3, $last);
		self::assertEquals(array(1, 2), $stack->toArray());
	}
	
	public function testCanNotPopAnEmptyStack() {
		$stack = new Stack();
		try {
			$item = $stack->pop();
		} catch(\dayax\collections\InvalidOperationException $e) {
			return;
		}
		self::fail('An expected TInvalidOperationException was not raised');
	}

	public function testPush() {
		$stack = new Stack();
		$stack->push(1);
		self::assertEquals(array(1), $stack->toArray());
	}

 	public function testGetIterator() {
		$stack = new Stack(array(1, 2));
		self::assertInstanceOf('ArrayIterator', $stack->getIterator());
		$n = 0;
		$found = 0;
		foreach($stack as $index => $item) {
			foreach($stack as $a => $b); // test of iterator
			$n++;
			if($index === 0 && $item === 1) {
				$found++;
			}
			if($index === 1 && $item === 2) {
				$found++;	
			}
		}
		self::assertTrue($n == 2 && $found == 2);
	}

	public function testGetCount() {
		$stack = new Stack();
		self::assertEquals(0, $stack->getCount());
		$stack = new Stack(array(1, 2, 3));
		self::assertEquals(3, $stack->getCount());
	}
	
	public function testCount() {
		$stack = new Stack();
		self::assertEquals(0, count($stack));
		$stack = new Stack(array(1, 2, 3));
		self::assertEquals(3, count($stack));
	}

}

?>
