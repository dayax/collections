<?php

namespace dayax\collections\tests;

use dayax\collections\Queue;
use dayax\core\test\TestCase;

class QueueTest extends TestCase
{

	public function setUp() {
	}

	public function tearDown() {
	}

	public function testConstruct() {
		$queue = new Queue();
		self::assertEquals(array(), $queue->toArray());
		$queue = new Queue(array(1, 2, 3));
		self::assertEquals(array(1, 2, 3), $queue->toArray());
	}

	public function testToArray() {
		$queue = new Queue(array(1, 2, 3));
		self::assertEquals(array(1, 2, 3), $queue->toArray());
	}

	public function testCopyFrom() {
		$queue = new Queue(array(1, 2, 3));
		$data = array(4, 5, 6);
		$queue->copyFrom($data);
		self::assertEquals(array(4, 5, 6), $queue->toArray());
	}
	
	public function testCanNotCopyFromNonTraversableTypes() {
		$queue = new Queue();
		$data = new \stdClass();
		try {
			$queue->copyFrom($data);
		} catch(\dayax\collections\InvalidDataTypeException $e) {
			return;
		}
		self::fail('An expected TInvalidDataTypeException was not raised');
	}
	
	public function testClear() {
		$queue = new Queue(array(1, 2, 3));
		$queue->clear();
		self::assertEquals(array(), $queue->toArray());
	}

	public function testContains() {
		$queue = new Queue(array(1, 2, 3));
		self::assertEquals(true, $queue->contains(2));
		self::assertEquals(false, $queue->contains(4));
	}

	public function testPeek() {
		$queue = new Queue(array(1,2,3));
		self::assertEquals(1, $queue->peek());
	}
	
	public function testCanNotPeekAnEmptyQueue() {
		$queue = new Queue();
		try {
			$item = $queue->peek();
		} catch(\dayax\collections\InvalidOperationException $e) {
			return;
		}
		self::fail('An expected TInvalidOperationException was not raised');
	}

	public function testDequeue() {
		$queue = new Queue(array(1, 2, 3));
		$first = $queue->dequeue();
		self::assertEquals(1, $first);
		self::assertEquals(array(2, 3), $queue->toArray());
	}
	
	public function testCanNotDequeueAnEmptyQueue() {
		$queue = new Queue();
		try {
			$item = $queue->dequeue();
		}catch(\dayax\collections\InvalidOperationException $e) {
			return;
		}
		self::fail('An expected TInvalidOperationException was not raised');
	}

	public function testEnqueue() {
		$queue = new Queue();
		$queue->enqueue(1);
		self::assertEquals(array(1), $queue->toArray());
	}

 	public function testGetIterator() {
		$queue = new Queue(array(1, 2));
		self::assertInstanceOf('ArrayIterator', $queue->getIterator());
		$n = 0;
		$found = 0;
		foreach($queue as $index => $item) {
			foreach($queue as $a => $b); // test of iterator
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
    	$queue = new Queue();
		self::assertEquals(0, $queue->getCount());
		$queue = new Queue(array(1, 2, 3));
		self::assertEquals(3, $queue->getCount());
	}
	
	public function testCountable() {
		$queue = new Queue();
		self::assertEquals(0, count($queue));
		$queue = new Queue(array(1, 2, 3));
		self::assertEquals(3, count($queue));
	}

}

?>
