<?php

namespace AlbuquerqueLucas\UserTaskManager\Exceptions;

use Exception;

class AuthException extends Exception
{
  protected $messageType;
  protected $expiration;
  protected $redirect;
  public function __construct($message, $messageType)
  {
    parent::__construct($message);
    $this->messageType = $messageType;
  }

  public function getMessageType()
  {
    return $this->messageType;
  }
}