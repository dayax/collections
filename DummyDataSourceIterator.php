<?php

namespace dayax\collections;

/**
 * DummyDataSourceIterator class
 *
 * DummyDataSourceIterator implements Iterator interface.
 *
 * DummyDataSourceIterator is used by {@link TDummyDataSource}.
 * It allows TDummyDataSource to return a new iterator
 * for traversing its dummy items.
 *
 * @author Anthonius Munthi <me@itstoni.com>
 */
class DummyDataSourceIterator implements \Iterator
{
    private $_index;
    private $_count;

    /**
     * Constructor.
     * @param integer number of (virtual) items in the data source.
     */
    public function __construct($count)
    {
        $this->_count=$count;
        $this->_index=0;
    }

    /**
     * Rewinds internal array pointer.
     * This method is required by the interface Iterator.
     */
    public function rewind()
    {
        $this->_index=0;
    }

    /**
     * Returns the key of the current array item.
     * This method is required by the interface Iterator.
     * @return integer the key of the current array item
     */
    public function key()
    {
        return $this->_index;
    }

    /**
     * Returns the current array item.
     * This method is required by the interface Iterator.
     * @return mixed the current array item
     */
    public function current()
    {
        return null;
    }

    /**
     * Moves the internal pointer to the next array item.
     * This method is required by the interface Iterator.
     */
    public function next()
    {
        $this->_index++;
    }

    /**
     * Returns whether there is an item at current position.
     * This method is required by the interface Iterator.
     * @return boolean
     */
    public function valid()
    {
        return $this->_index<$this->_count;
    }
}