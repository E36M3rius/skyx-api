<?php

namespace App\Database;

    class MariaDbDatabase implements DatabaseInterface
{
  /**
   * Static instance of self
   * @var object $instance
   */
  private static $instance;

  /**
   * @var string $host
   */
  private $host = "127.0.0.1";

  /**
   * @var string $port
   */
  private $port = 3306;

  /**
   * @var string $user
   */
  private $user = "rwuser";

  /**
   * @var string $password
   */
  private $password = "$%_solmePassword_strong";

  /**
   * @var string $databaseName
   */
  private $databaseName = "skyx";

  /**
   * @var resource $connection
   */
  private $connection;

  /**
   * @var string $sqlQuery
   */
  private $sqlQuery;

  /**
   * Constructor, setting __construct to private to enforce Singleton.
   * Prepares the connection
   */
  private function __construct()
  {
    $this->connect();
  }

  /**
   * Setting __clone to private to enforce Singleton
   */
  private function __clone()
  {
  }

  /**
   * Retrieves an instance of self. It creates one if it doesn't exist
   *
   * @return instance self
   */
  public static function getInstance()
  {
    // check for self instance
    if (!self::$instance instanceof self) {
      // create and set instance
      self::$instance = new self();
    }

    // return instance
    return self::$instance;
  }

  /**
   * Creates a mysql connection
   *
   * @return instance self
   */
  private function connect()
  {
    if ($resource = mysqli_connect(
      $this->host,
      $this->user,
      $this->password,
      $this->databaseName
      )
    ) { // connection ok
      $this->connection = $resource;
    } else { // connection failed
      throw new \Exception("Cannot connect to database. Error: ".mysqli_connect_error());
    }

    return $this;
  }

  /**
   * Escapes strings, use against SQL injection
   *
   * @param  string $string
   * @return string escaped string
   */
  public function escapeString($string)
  {
    return mysqli_real_escape_string($this->connection, $string);
  }

  /**
   * Setter for sqlQuery
   *
   * @param string $sqlQuery
   * @return instance self
   */
  public function setSqlQuery($sqlQuery)
  {
    $this->sqlQuery = $sqlQuery;
    return $this;
  }

  /**
   * Method used for executing any query
   *
   * @return result of mysql query
   */
  private function executeQuery()
  {
    return mysqli_query($this->connection, $this->sqlQuery);
  }

  /**
   * Method used for running select queries. Using generators
   *
   * @return generator for results
   */
  public function get()
  {
    if (!$this->sqlQuery) { // making sure that sqlQuery exists
      return false;
    }

    $dbResults =  $this->executeQuery(); // query

    if (!$dbResults) {
      return false;
    }

    while ($row = mysqli_fetch_assoc($dbResults)) { // fetch assoc
      yield $row; // generator
    }
  }

  /**
   * Method used for running insert queries
   *
   * @return last insert id or false
   */
  public function add()
  {
    if (!$this->sqlQuery) { // making sure that sqlQuery exists
      return false;
    }

    $dbResults = $this->executeQuery(); // query

    return $dbResults ? mysqli_insert_id($this->connection) : false; // last insert id or false
  }

  /**
   * Method used for running delete queries
   *
   * @return affected rows
   */
  public function delete()
  {
    if (!$this->sqlQuery) {
      return false;
    }

    $this->executeQuery(); // query

    return mysqli_affected_rows($this->connection); // affected rows
  }

  /**
   * Method used for running insert queries. Not yet implemented
   */
  public function update()
  {
    if (!$this->sqlQuery) {
      return false;
    }
  }

}
