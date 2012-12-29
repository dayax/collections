<?php


namespace dayax\collections\tests;

use dayax\core\test\TestCase;

use dayax\collections\AttributeCollection;

class AttributeCollectionTest extends TestCase
{

	public function setUp() {
	}

	public function tearDown() {
	}

	public function testCanGetProperty() {
		$collection = new AttributeCollection();
		$collection->Property = 'value';
		self::assertEquals('value', $collection->Property);
		self::assertEquals(true, $collection->canGetProperty('Property'));
	}
	
	public function testCanNotGetUndefinedProperty() {
		$collection = new AttributeCollection(array(), true);
		self::assertEquals(false, $collection->canGetProperty('Property'));
		try {
			$value = $collection->Property;
		} catch(\dayax\core\InvalidOperationException $e) {
			return;
		}
		self::fail('An expected TInvalidOperationException was not raised');
	}

	public function testCanSetProperty() {
		$collection = new AttributeCollection();
		$collection->Property = 'value';
		self::assertEquals('value', $collection->itemAt('Property'));
		self::assertEquals(true, $collection->canSetProperty('Property'));
	}
	
	public function testCanNotSetPropertyIfReadOnly() {
		$collection = new AttributeCollection(array(), true);
		try {
			$collection->Property = 'value';
		} catch(\dayax\collections\InvalidOperationException $e) {
			return;
		}
		self::fail('An expected TInvalidOperationException was not raised');
	}
	
	public function testGetCaseSensitive() {
		$collection = new AttributeCollection();
		$collection->setCaseSensitive(false);
		self::assertEquals(false, $collection->getCaseSensitive());
		$collection->setCaseSensitive(true);
		self::assertEquals(true, $collection->getCaseSensitive());
	}
	
	public function testSetCaseSensitive() {
		$collection = new AttributeCollection();
		$collection->Property = 'value';
		$collection->setCaseSensitive(false);
		self::assertEquals('value', $collection->itemAt('property'));
	}
	
	public function testItemAt() {
		$collection = new AttributeCollection();
		$collection->Property = 'value';
		self::assertEquals('value', $collection->itemAt('Property'));
	}
	
	public function testAdd() {
		$collection = new AttributeCollection();
		$collection->add('Property', 'value');
		self::assertEquals('value', $collection->itemAt('Property'));
	}
	
	public function testRemove() {
		$collection = new AttributeCollection();
		$collection->add('Property', 'value');
		$collection->remove('Property');
		self::assertEquals(0, count($collection));
	}
	
	public function testContains() {
		$collection = new AttributeCollection();
		self::assertEquals(false, $collection->contains('Property'));
		$collection->Property = 'value';
		self::assertEquals(true, $collection->contains('Property'));
	}
	
	public function testHasProperty() {
		$collection = new AttributeCollection();
		self::assertEquals(false, $collection->hasProperty('Property'));
		$collection->Property = 'value';
		self::assertEquals(true, $collection->hasProperty('Property'));
	}

}

?>
