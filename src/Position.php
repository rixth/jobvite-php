<?php

/**
 * This class represents a job opening. You can access its data fields using
 * object or array notation.
 *
 * @package jobvite-php
 * @author Thomas Rix
 */
class Jobvite_Position implements ArrayAccess {
  protected $data;
  
  public function __construct($data) {
    $this->data = $data;
    $this->data['date'] = strtotime($this->data['date']);
  }

  public function __get($name) {
    if (array_key_exists($name, $this->data)) {
      return $this->data[$name];
    }
    throw new Jobvite_Exception("Field '$name' is not a valid property of a position.");
  }
  
  public function __set($name, $value)
  {
    throw new Jobvite_Exception("You cannot set properties on positions.");
  }
  
  public function offsetExists($offset) {
    return array_key_exists($offset, $this->data);
  }
  
  public function offsetGet($offset) {
    return $this->$offset;
  }
  
  public function offsetSet($offset, $value) {
    $this->$offset = $value;
  }
  
  public function offsetUnset($offset) {}
}
