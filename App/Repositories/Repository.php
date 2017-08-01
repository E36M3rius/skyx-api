<?php

namespace App\Repositories;

use \App\Database;

class Repository
{

  /**
   * @var resource $database
   */
  private $database;

  /**
   * @var string $tableName
   */
  private $tableName;

  /**
   * @var array $fieldNames
   */
  private $fieldNames = array();

  /**
   * @var array $where
   */
  private $where = array();

  /**
   * Store the database instance as well as the table name
   * @param instance $database
   * @param string $tableName
   */
  public function __construct($database, $tableName)
  {
    // Make sure that we have a valid database instance
    $databaseImplements = class_implements($database);

    if (!array_key_exists("App\Database\DatabaseInterface", $databaseImplements)) {
      throw new \Exception("Invalid database instance.");
    }

    $this->database = $database;
    $this->tableName = $tableName;
  }

  /**
   * Main method used for select queries. It is highly customizable
   *
   * @param array $where
   * @param array $order
   * @param array $join
   * @param array $fields
   * @return array $results
   */
  public function get(
    array $where = array(),
    array $order = array(),
    array $join = array(),
    array $fields = array()
  )
  {
    // select custom fields vs all (*)
    if (!empty($fields)) {
      $fields = implode(',', $fields);
    } else {
      $fields = "*";
    }

    // prepare select query
    $sqlQuery = "SELECT $fields FROM $this->tableName";

    if (!empty($join)) {
      foreach($join as $key => $value) {
        $sqlQuery .= " " . $this->buildJoinConditions(array($key => $value));
      }
    }

    // check if we want to add where conditions
    if (!empty($where)) {
      $sqlQuery .= " " . $this->buildWhereConditions($where);
    }

    // check if we want to add sorting conditions
    if (!empty($order)) {
      $sqlQuery .= " " . $this->buildOrderConditions($order);
    }

    return $this->database->setSqlQuery($sqlQuery)->get();
  }

  /**
   * Method used to run insert queries
   *
   * @param array $data (consist of fields => values)
   * @return last insert id
   */
  public function add(array $data)
  {
    // get fields
    $fields = implode(',', array_keys($data));
    // process values
    $values = $this->buildValues(array_values($data), true);

    // prepare insert query
    $sqlQuery = "INSERT INTO $this->tableName ($fields) VALUES (". implode(',', $values) .")";

    return $this->database->setSqlQuery($sqlQuery)->add();
  }

  /**
   * Method used for running update queries. Not implemented at the moment
   */
  public function update()
  {
  }

  /**
   * Method used for running delete queries
   *
   * @param  array  $where
   * @return affected rows
   */
  public function delete(array $where)
  {
    // prepare delete query
    $sqlQuery = "DELETE FROM {$this->tableName}";

    // check if we want to add where conditions
    if (!empty($where)) {
      $sqlQuery .= " " . $this->buildWhereConditions($where);
    }

    return $this->database->setSqlQuery($sqlQuery)->delete();
  }

  /**
   * This method goes over an array of conditions and initiates
   * string escaping on the values. It can return an array with the
   * values or just a string to plug into the sql query
   *
   * @param  array   $whereConditions
   * @param  boolean $returnString
   * @see $this->buildValues()
   * @return string|array string where|$whereConditions
   */
  private function buildWhereConditions(array $whereConditions, $returnString = true)
  {
    $whereConditions = $this->buildValues($whereConditions);

    // determine if we want to return string or array
    if ($returnString) {
      return "WHERE " . implode(" AND ", $whereConditions);
    } else {
      return $whereConditions;
    }
  }

  /**
   * Method used for processing values used by an sql query. It takes care of
   * escaping strings for security purposes as well
   *
   * @param  array   $values
   * @param  boolean $onlyValues
   * @return array $tmpArray
   */
  private function buildValues(array $values, $onlyValues = false)
  {
    // store instance reference into the variable for use in the callback
    // function belllow
    $database = $this->database;
    // go over the array and escape for string, cast for int
    $values = array_map(function($item) use ($database) {
      if (is_numeric($item)) {
        return (int)$item; // int
      } else {
        return "'".$database->escapeString($item)."'"; // string
      }
    }, $values);

    $tmpArray = array();
    // build array with key => values
    foreach($values as $key => $value) {
      $tmpArray[] = $onlyValues ? $value : $key . "=" . $value;
    }

    return $tmpArray;
  }

  /**
   * Method used for building sorting in sql query
   *
   * @param  array  $order
   * @return string order
   */
  private function buildOrderConditions(array $order)
  {
    return 'ORDER BY ' . key($order) . " " . current($order);
  }

  /**
   * Method used for buuilding the join conditions in sql query
   *
   * @param  array  $join
   * @return string join
   */
  private function buildJoinConditions(array $join)
  {
    list($joinType, $joinTable) = explode(":", key($join));
    $joinValue = current($join);

    return "$joinType JOIN $joinTable ON $joinValue";
  }

}
