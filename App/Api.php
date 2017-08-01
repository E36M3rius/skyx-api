<?php

namespace App;

class Api
{

  /**
   * @var array $allowedKeys
   */
  private $allowedKeys = array(
    'skyx-client' => 'tkiy538lbkqnzcyjflxcikgxz'
  );

  /**
   * Does nothing, added for visibility.
   */
  public function __construct()
  {
  }

  /**
   * Checks if client key is valid
   *
   * @param  string  $clientKey
   * @throws \Exception if ClientKey not allowed
   * @return boolean true
   */
  public function isClientAllowed($clientKey)
  {
    if (!in_array($clientKey, $this->allowedKeys)) {
      throw new \Exception('Client key is invalid.');
    }

    return true;
  }

}
