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
 * Map class
 *
 * Map implements a collection that takes key-value pairs.
 *
 * You can access, add or remove an item with a key by using
 * {@link itemAt}, {@link add}, and {@link remove}.
 * To get the number of the items in the map, use {@link getCount}.
 * Map can also be used like a regular array as follows,
 * <code>
 * $map[$key]=$value; // add a key-value pair
 * unset($map[$key]); // remove the value with the specified key
 * if(isset($map[$key])) // if the map contains the key
 * foreach($map as $key=>$value) // traverse the items in the map
 * $n=count($map);  // returns the number of items in the map
 * </code>
 *
 * @author      Anthonius Munthi <toni.munthi@gmail.com>
 * @package     dayax.collections
 * @since       1.0
 */
class Map extends Component implements \IteratorAggregate,\ArrayAccess,\Countable
{
    /**
     * @var array internal data storage
     */
    private $d=array();
    /**
     * @var boolean whether this list is read-only
     */
    private $r=false;

    private $aliases = array();

    private $aliasCaseSensitive = false;

    /**
     * Constructor.
     * Initializes the list with an array or an iterable object.
     * @param array|Iterator the intial data. Default is null, meaning no initialization.
     * @param boolean whether the list is read-only
     * @throws InvalidDataTypeException If data is not null and neither an array nor an iterator.
     */
    public function __construct($data=null,$readOnly=false)
    {
        if ($data!==null) {
            $this->copyFrom($data);
        }
        $this->setReadOnly($readOnly);
    }

    /**
     * @return boolean whether this map is read-only or not. Defaults to false.
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
        //FIXME: ensure boolean
        //$this->r=\dx::ensureBoolean($value);
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
     * Returns the number of items in the map.
     * This method is required by Countable interface.
     * @return integer number of items in the map.
     */
    public function count()
    {
        return $this->getCount();
    }

    /**
     * @return integer the number of items in the map
     */
    public function getCount()
    {
        return count($this->d);
    }

    /**
     * @return array the key list
     */
    public function getKeys()
    {
        return array_keys($this->d);
    }

    /**
     * Returns the item with the specified key.
     * This method is exactly the same as {@link offsetGet}.
     * @param mixed the key
     * @return mixed the element at the offset, null if no element is found at the offset
     */
    public function itemAt($key)
    {
        if (isset($this->aliases[$key])) {
            $key = $this->aliases[$key];
        }

        return isset($this->d[$key]) ? $this->d[$key] : null;
    }

    /**
     * Adds an item into the map.
     * Note, if the specified key already exists, the old value will be overwritten.
     * @param mixed key
     * @param mixed value
     * @throws InvalidOperationException if the map is read-only
     */
    public function add($key,$value)
    {
        if (!$this->r) {
            $key = isset($this->aliases[$key]) ? $this->aliases[$key]:$key;
            $this->d[$key] = $value;
        } else {
            throw new InvalidOperationException('collections.map.readonly', get_class($this));
        }
    }

    /**
     * Removes an item from the map by its key.
     * @param mixed the key of the item to be removed
     * @return mixed                     the removed value, null if no such key exists.
     * @throws InvalidOperationException if the map is read-only
     */
    public function remove($key)
    {
        if (!$this->r) {
            if (isset($this->d[$key]) || array_key_exists($key,$this->d)) {
                $value=$this->d[$key];
                unset($this->d[$key]);

                return $value;
            } else

                return null;
        } else
            throw new InvalidOperationException('map_readonly',get_class($this));
    }

    /**
     * Removes all items in the map.
     */
    public function clear()
    {
        foreach(array_keys($this->d) as $key)
            $this->remove($key);
    }

    /**
     * @param mixed the key
     * @return boolean whether the map contains an item with the specified key
     */
    public function contains($key)
    {
        return isset($this->d[$key]) || array_key_exists($key,$this->d) || isset($this->aliases[$key]);
    }

    /**
     * @return array the list of items in array
     */
    public function toArray()
    {
        return $this->d;
    }

    /**
     * Copies iterable data into the map.
     * Note, existing data in the map will be cleared first.
     * @param mixed the data to be copied from, must be an array or object implementing Traversable
     * @throws InvalidDataTypeException If data is neither an array nor an iterator.
     */
    public function copyFrom($data)
    {
        if (is_array($data) || $data instanceof \Traversable) {
            if ($this->getCount()>0) {
                $this->clear();
            }
            foreach ($data as $key=>$value) {
                $this->add($key,$value);
            }
        } elseif ($data!==null) {
            throw new InvalidDataTypeException('collections.map.data_not_iterable');
        }
    }

    /**
     * Merges iterable data into the map.
     * Existing data in the map will be kept and overwritten if the keys are the same.
     * @param mixed the data to be merged with, must be an array or object implementing Traversable
     * @throws InvalidDataTypeException If data is neither an array nor an iterator.
     */
    public function mergeWith($data)
    {
        if (is_array($data) || $data instanceof Traversable) {
            foreach($data as $key=>$value)
                $this->add($key,$value);
        } else if($data!==null)
            throw new InvalidDataTypeException('map_data_not_iterable');
    }

    /**
     * Returns whether there is an element at the specified offset.
     * This method is required by the interface ArrayAccess.
     * @param mixed the offset to check on
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return $this->contains($offset);
    }

    /**
     * Returns the element at the specified offset.
     * This method is required by the interface ArrayAccess.
     * @param integer the offset to retrieve element.
     * @return mixed the element at the offset, null if no element is found at the offset
     */
    public function offsetGet($offset)
    {
        return $this->itemAt($offset);
    }

    /**
     * Sets the element at the specified offset.
     * This method is required by the interface ArrayAccess.
     * @param integer the offset to set element
     * @param mixed the element value
     */
    public function offsetSet($offset,$item)
    {
        $this->add($offset,$item);
    }

    /**
     * Unsets the element at the specified offset.
     * This method is required by the interface ArrayAccess.
     * @param mixed the offset to unset element
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    /**
     * Add an alias for content
     * @param string $originalKey An original key to be aliased
     * @param string $alias       Alias for original key
     */
    public function addAlias($originalKey,$alias)
    {
        if (!in_array($alias,$this->aliases)) {
            $this->aliases[$alias] = $originalKey;
        }
        if (!$this->aliasCaseSensitive) {
            $alias = strtolower($alias);
            $this->aliases[$alias] = $originalKey;
        }
    }

    public function setAliasCaseSensitive($value)
    {
        $this->aliasCaseSensitive = $value;
    }
}
