<?php

/*
 * This file is part of the dayax project.
 *
 * (c) Anthonius Munthi <toni.dayax@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dayax\collections;
use dayax\core\Component;

/**
 * ListCollection class
 *
 * ListCollection implements an integer-indexed collection class.
 *
 * You can access, append, insert, remove an item by using
 * {@link itemAt}, {@link add}, {@link insertAt}, {@link remove}, and {@link removeAt}.
 * To get the number of the items in the list, use {@link getCount}.
 * ListCollection can also be used like a regular array as follows,
 * <code>
 * $list[]=$item;  // append at the end
 * $list[$index]=$item; // $index must be between 0 and $list->Count
 * unset($list[$index]); // remove the item at $index
 * if(isset($list[$index])) // if the list has an item at $index
 * foreach($list as $index=>$item) // traverse each item in the list
 * $n=count($list); // returns the number of items in the list
 * </code>
 *
 * To extend ListCollection by doing additional operations with each addition or removal
 * operation, override {@link insertAt()}, and {@link removeAt()}.
 *
 * @author      Anthonius Munthi <toni.munthi@gmail.com>
 * @package     dayax\collections
 * @since       1.0
 */
class ListCollection extends Component implements \IteratorAggregate,\ArrayAccess,\Countable
{
    /**
     * internal data storage
     * @var array
     */
    private $d=array();

    /**
     * number of items
     * @var integer
     */
    private $c=0;

    /**
     * @var boolean whether this list is read-only
     */
    private $r=false;

    /**
     * Constructor.
     * Initializes the list with an array or an iterable object.
     * @param array|Iterator the initial data. Default is null, meaning no initialization.
     * @param boolean whether the list is read-only
     * @throws InvalidDataTypeException If data is not null and neither an array nor an iterator.
     */
    public function __construct($data=null,$readOnly=false)
    {
        if($data!==null)
            $this->copyFrom($data);
        $this->setReadOnly($readOnly);
    }

    /**
     * @return boolean whether this list is read-only or not. Defaults to false.
     */
    public function getReadOnly()
    {
        return $this->r;
    }

    /**
     * @param boolean whether this list is read-only or not
     */
    protected function setReadOnly($value)
    {
        $this->r = $value;
    }

    /**
     * Returns an iterator for traversing the items in the list.
     * This method is required by the interface IteratorAggregate.
     * @return Iterator an iterator for traversing the items in the list.
     */
    public function getIterator()
    {
        return new \ArrayIterator( $this->d );
    }

    /**
     * Returns the number of items in the list.
     * This method is required by Countable interface.
     * @return integer number of items in the list.
     */
    public function count()
    {
        return $this->getCount();
    }

    /**
     * @return integer the number of items in the list
     */
    public function getCount()
    {
        return $this->c;
    }

    /**
     * Returns the item at the specified offset.
     * This method is exactly the same as {@link offsetGet}.
     * @param integer the index of the item
     * @return mixed                      the item at the index
     * @throws TInvalidDataValueException if the index is out of the range
     */
    public function itemAt($index)
    {
        if ($index>=0 && $index<$this->c) {
            return $this->d[$index];
        } else {
            throw new InvalidDataValueException('list_index_invalid',$index);
        }
    }

    /**
     * Appends an item at the end of the list.
     * @param mixed new item
     * @return integer                   the zero-based index at which the item is added
     * @throws InvalidOperationException if the list is read-only
     */
    public function add($item)
    {
        $this->insertAt($this->c,$item);

        return $this->c-1;
    }

    /**
     * Inserts an item at the specified position.
     * Original item at the position and the next items
     * will be moved one step towards the end.
     * @param integer the specified position.
     * @param mixed new item
     * @throws InvalidDataValueException If the index specified exceeds the bound
     * @throws InvalidOperationException if the list is read-only
     */
    public function insertAt($index,$item)
    {
            if (!$this->r) {
                if ($index===$this->c) {
                    $this->d[$this->c++]=$item;
                } elseif ($index>=0 && $index<$this->c) {
                    array_splice($this->d,$index,0,array($item));
                    $this->c++;
                } else {
                    throw new InvalidDataValueException('list_index_invalid',$index);
                }
            } else {
                throw new InvalidOperationException('list_readonly',get_class($this));
            }
    }

    /**
     * Removes an item from the list.
     * The list will first search for the item.
     * The first item found will be removed from the list.
     * @param mixed the item to be removed.
     * @return integer                   the index at which the item is being removed
     * @throws InvalidDataValueException If the item does not exist
     * @throws InvalidOperationException if the list is read-only
     */
    public function remove($item)
    {
        if (!$this->r) {
            if (($index=$this->indexOf($item))>=0) {
                $this->removeAt($index);

                return $index;
            } else {
                throw new InvalidDataValueException('list_item_inexistent');
            }
        } else {
            throw new InvalidOperationException('list_readonly',get_class($this));
        }
    }

    /**
     * Removes an item at the specified position.
     * @param integer the index of the item to be removed.
     * @return mixed                     the removed item.
     * @throws InvalidDataValueException If the index specified exceeds the bound
     * @throws InvalidOperationException if the list is read-only
     */
    public function removeAt($index)
    {
        if (!$this->r) {
            if ($index>=0 && $index<$this->c) {
                $this->c--;
                if($index===$this->c)

                    return array_pop($this->d);
                else {
                    $item=$this->d[$index];
                    array_splice($this->d,$index,1);

                    return $item;
                }
            } else
                throw new InvalidDataValueException('list_index_invalid',$index);
        } else
            throw new InvalidOperationException('list_readonly',get_class($this));
    }

    /**
     * Removes all items in the list.
     * @throws InvalidOperationException if the list is read-only
     */
    public function clear()
    {
        for($i=$this->c-1;$i>=0;--$i)
            $this->removeAt($i);
    }

    /**
     * @param mixed the item
     * @return boolean whether the list contains the item
     */
    public function contains($item)
    {
        return $this->indexOf($item)>=0;
    }

    /**
     * @param mixed the item
     * @return integer the index of the item in the list (0 based), -1 if not found.
     */
    public function indexOf($item)
    {
        if (($index=array_search($item,$this->d,true))===false) {
            return -1;
        } else {
            return $index;
        }
    }

    /**
     * Finds the base item.  If found, the item is inserted before it.
     * @param mixed the base item which will be pushed back by the second parameter
     * @param mixed the item
     * @return int                       the index where the item is inserted
     * @throws InvalidDataValueException if the base item is not within this list
     * @throws InvalidOperationException if the list is read-only
     * @since 3.2a
     */
    public function insertBefore($baseitem, $item)
    {
        if (!$this->r) {
            if (($index = $this->indexOf($baseitem)) == -1) {
                throw new InvalidDataValueException('collections.list.item_inexistent');
            }
            $this->insertAt($index, $item);

            return $index;
        } else
            throw new InvalidOperationException('list_readonly',get_class($this));
    }

    /**
     * Finds the base item.  If found, the item is inserted after it.
     * @param mixed the base item which comes before the second parameter when added to the list
     * @param mixed the item
     * @return int                       the index where the item is inserted
     * @throws InvalidDataValueException if the base item is not within this list
     * @throws InvalidOperationException if the list is read-only
     * @since 3.2a
     */
    public function insertAfter($baseitem, $item)
    {
        if (!$this->r) {
            if(($index = $this->indexOf($baseitem)) == -1)
                throw new InvalidDataValueException('list_item_inexistent');

            $this->insertAt($index + 1, $item);

            return $index + 1;
        } else
            throw new InvalidOperationException('list_readonly',get_class($this));
    }

    /**
     * @return array the list of items in array
     */
    public function toArray()
    {
        return $this->d;
    }

    /**
     * Copies iterable data into the list.
     * Note, existing data in the list will be cleared first.
     * @param mixed the data to be copied from, must be an array or object implementing Traversable
     * @throws InvalidDataTypeException If data is neither an array nor a Traversable.
     */
    public function copyFrom($data)
    {
        if (is_array($data) || ($data instanceof \Traversable)) {
            if($this->c>0)
                $this->clear();
            foreach($data as $item)
                $this->add($item);
        } elseif ($data!==null) {
            throw new InvalidDataTypeException('list_data_not_iterable');
        }
    }

    /**
     * Merges iterable data into the map.
     * New data will be appended to the end of the existing data.
     * @param mixed the data to be merged with, must be an array or object implementing Traversable
     * @throws InvalidDataTypeException If data is neither an array nor an iterator.
     */
    public function mergeWith($data)
    {
        if (is_array($data) || ($data instanceof Traversable)) {
            foreach($data as $item)
                $this->add($item);
        } else if($data!==null)
            throw new InvalidDataTypeException('list_data_not_iterable');
    }

    /**
     * Returns whether there is an item at the specified offset.
     * This method is required by the interface ArrayAccess.
     * @param integer the offset to check on
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return ($offset>=0 && $offset<$this->c);
    }

    /**
     * Returns the item at the specified offset.
     * This method is required by the interface ArrayAccess.
     * @param integer the offset to retrieve item.
     * @return mixed                     the item at the offset
     * @throws InvalidDataValueException if the offset is invalid
     */
    public function offsetGet($offset)
    {
        return $this->itemAt($offset);
    }

    /**
     * Sets the item at the specified offset.
     * This method is required by the interface ArrayAccess.
     * @param integer the offset to set item
     * @param mixed the item value
     */
    public function offsetSet($offset,$item)
    {
        if($offset===null || $offset===$this->c)
            $this->insertAt($this->c,$item);
        else {
            $this->removeAt($offset);
            $this->insertAt($offset,$item);
        }
    }

    /**
     * Unsets the item at the specified offset.
     * This method is required by the interface ArrayAccess.
     * @param integer the offset to unset item
     */
    public function offsetUnset($offset)
    {
        $this->removeAt($offset);
    }
}
