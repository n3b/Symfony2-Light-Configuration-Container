<?php

namespace n3b\Bundle\Util;

class ConfigContainer implements \ArrayAccess
{
    private $elements;

    public function get($key)
    {
        $keys = explode('.', $key);

        if(isset($this->elements[$keys[0]])) {
            if(count($keys) > 1 && $this->elements[$keys[0]] instanceof ConfigContainer)
                    return $this->elements[$keys[0]]->get(\str_replace($keys[0] . '.',
                            '', $key));

            return $this->elements[$keys[0]];
        }

        return null;
    }

    public function offsetExists($offset)
    {
        return isset($this->elements[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->get[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if(!isset($offset)) {
            return $this->add($value);
        }
        
        return $this->elements[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        if (isset($this->elements[$offset])) {
            $removed = $this->elements[$offset];
            unset($this->elements[$offset]);
            
            return $removed;
        }

        return null;
    }
    
    public function add($value)
    {
        $this->elements[] = $value;
        
        return true;
    }
    
    public function toArray()
    {
        return \array_map( function($a) {return $a instanceof ConfigContainer ? $a->toArray() : $a; }, $this->elements);
    }
}