<?php

namespace dayax\collections;

use dayax\core\Component;

/**
 * DummyDataSource class
 *
 * TDummyDataSource implements a dummy data collection with a specified number
 * of dummy data items. The number of virtual items can be set via
 * {@link setCount Count} property. You can traverse it using <b>foreach</b>
 * PHP statement like the following,
 * <code>
 * foreach($dummyDataSource as $dataItem)
 * </code>
 *
 * @author Anthonius Munthi <me@itstoni.com>
 */
class DummyDataSource extends Component implements \IteratorAggregate, \Countable
{
	private $_count;

	/**
	 * Constructor.
	 * @param integer number of (virtual) items in the data source.
	 */
	public function __construct($count)
	{
		$this->_count=$count;
	}

	/**
	 * @return integer number of (virtual) items in the data source.
	 */
	public function getCount()
	{
		return $this->_count;
	}

	/**
	 * @return Iterator iterator
	 */
	public function getIterator()
	{
		return new DummyDataSourceIterator($this->_count);
	}

	/**
	 * Returns the number of (virtual) items in the data source.
	 * This method is required by Countable interface.
	 * @return integer number of (virtual) items in the data source.
	 */
	public function count()
	{
		return $this->getCount();
	}
}