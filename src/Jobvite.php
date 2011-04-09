<?php
require_once(dirname(__FILE__).'/Position.php');

/**
 * Represents a connection to the XML-based feed of positions from Jobvite.
 *
 * @package jobvite-php
 * @author Thomas Rix
 */
class Jobvite { 
  /**
   * Maps a position's xml fields to its php name
   */
  private static $xmlFieldToObjProp = array(
    'id', 'title', 'category', 'location', 'jobtype' => 'jobType', 'location',
    'date', 'details-url' => 'detailsUrl', 'apply-url' => 'applyUrl', 'description',
    'briefdescription' => 'briefDescription'
  );
  
  protected $positions = array();
  protected $companyId;
  
  /**
   * Create a new instance
   *
   * @param string $companyId The ID of the company at Jobvite
   * @author Thomas Rix
   */
  public function __construct($companyId) {
    if (!$companyId) {
      throw new Jobvite_Exception("You must pass a companyId when you instantiate this object.");
    }
    
    $this->companyId = $companyId;
  }
  
  /**
   * Does the magic. Fetches the XML from Jobvite and parses it in to a number
   * of Jobvite_Position objects.
   *
   * @return void
   */
  public function build() {
    $xml = file_get_contents('http://www.jobvite.com/CompanyJobs/Xml.aspx?c=' . $this->companyId);
    if (!$xml) {
      throw new JobviteException("could not load the xml from jobvite");
    }
      
    $dom = simplexml_load_string($xml);
    if (!$dom) {
      throw new JobviteException("could not parse xml from jobvite");
    }
      
    foreach ($dom->job as $positions) {
      $dataFields = array();
      foreach(self::$xmlFieldToObjProp as $xmlField => $objectField) {
        $dataFields[$objectField] = (string) $positions->{!is_numeric($xmlField) ? $xmlField : $objectField};
      }
      $this->positions[] = new Jobvite_Position($dataFields);
    }
  }
  
  /**
   * Fetch the positions found in the feed.
   *
   * You can also filter positions by passing an array as the first argument.
   * The format is filterField => filterValue. If the filterField on a given
   * position is blank, it is assumed to have 'passed' the filter.
   *
   * @param string $filters 
   * @return array
   */
  public function positions($filters = null) {
    if (is_array($filters)) {
      $filteredPositions = array();
      foreach ($this->positions as $position) {
        $pass = true;
        foreach($filters as $filterField => $filterValue) {
          if ($position->$filterField && $position->$filterField != $filterValue) {
            $pass = false;
            break;
          }
        }
        if ($pass) {
          $filteredPositions[] = $position;
        }
      }
      return $filteredPositions;
    }
    return $this->positions;
  }
  
  /**
   * Fetch an array of fields and the count of jobs they contain.
   *
   * By default, it will return a map of categoryName => no. jobs 
   *
   * @return array
   */
  public function categoryListBy($field = 'category') {
    $categories = array();
    foreach ($this->positions() as $position) {
      $categories[$position->$field] = isset($categories[$position->$field]) ? $categories[$position->$field] + 1 : 1;
    }
    return $categories;
  }
}

class Jobvite_Exception extends Exception {}