<?php

/*
 * This file is part of the dayax package.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dayax\collections;

use dayax\core\Component;

/**
 * Queue class
 *
 * Queue implements a queue.
 *
 * The typical queue operations are implemented, which include
 * {@link enqueue()}, {@link dequeue()} and {@link peek()}. In addition,
 * {@link contains()} can be used to check if an item is contained
 * in the queue. To obtain the number of the items in the queue,
 * check the {@link getCount Count} property.
 *
 * Items in the queue may be traversed using foreach as follows,
 * <code>
 * foreach($queue as $item) ...
 * </code>
 *
 * @author Anthonius Munthi <me@itstoni.com>
 */
class Queue extends Component implements \IteratorAggregate,\Countable
{
	/**
	 * internal data storage
	 * @var array
	 */
	private $_d=array();
	/**
	 * number of items
	 * @var integer
	 */
	private $_c=0;

	/**
	 * Constructor.
	 * Initializes the queue with an array or an iterable object.
	 * @param array|Iterator the intial data. Default is null, meaning no initialization.
	 * @throws InvalidDataTypeException If data is not null and neither an array nor an iterator.
	 */
	public function __construct($data=null)
	{
		if($data!==null){
			$this->copyFrom($data);
		}
	}

	/**
	 * @return array the list of items in queue
	 */
	public function toArray()
	{
		return $this->_d;
	}

	/**
	 * Copies iterable data into the queue.
	 * Note, existing data in the list will be cleared first.
	 * @param mixed the data to be copied from, must be an array or object implementing Traversable
	 * @throws InvalidDataTypeException If data is neither an array nor a Traversable.
	 */
	public function copyFrom($data)
	{
		if(is_array($data) || ($data instanceof Traversable))
		{
			$this->clear();
			foreach($data as $item)
			{
				$this->_d[]=$item;
				++$this->_c;
			}
		}
		else if($data!==null){
			throw new InvalidDataTypeException('queue_data_not_iterable');
		}
	}

	/**
	 * Removes all items in the queue.
	 */
	public function clear()
	{
		$this->_c=0;
		$this->_d=array();
	}

	/**
	 * @param mixed the item
	 * @return boolean whether the queue contains the item
	 */
	public function contains($item)
	{
		return array_search($item,$this->_d,true)!==false;
	}

	/**
	 * Returns the first item at the front of the queue.
	 * Unlike {@link dequeue()}, this method does not remove the item from the queue.
	 * @return mixed item at the top of the queue
	 * @throws InvalidOperationException if the queue is empty
	 */
	public function peek()
	{
		if($this->_c===0){
			throw new InvalidOperationException('queue_empty');
		}else{
			return $this->_d[0];
		}
	}

	/**
	 * Removes and returns the object at the beginning of the queue.
	 * @return mixed the item at the beginning of the queue
	 * @throws InvalidOperationException if the queue is empty
	 */
	public function dequeue()
	{
		if($this->_c===0)
			throw new InvalidOperationException('queue_empty');
		else
		{
			--$this->_c;
			return array_shift($this->_d);
		}
	}

	/**
	 * Adds an object to the end of the queue.
	 * @param mixed the item to be appended into the queue
	 */
	public function enqueue($item)
	{
		++$this->_c;
		$this->_d[] = $item;
	}

	/**
	 * Returns an iterator for traversing the items in the queue.
	 * This method is required by the interface IteratorAggregate.
	 * @return Iterator an iterator for traversing the items in the queue.
	 */
	public function getIterator()
	{
		return new \ArrayIterator( $this->_d );
	}

	/**
	 * @return integer the number of items in the queue
	 */
	public function getCount()
	{
		return $this->_c;
	}

	/**
	 * Returns the number of items in the queue.
	 * This method is required by Countable interface.
	 * @return integer number of items in the queue.
	 */
	public function count()
	{
		return $this->getCount();
	}
}