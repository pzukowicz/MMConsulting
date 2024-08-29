<?php

declare(strict_types=1);

/**
 * Request handles data from HTTP requests and sessions.
 */
class Request {
  /**
   * 
   * Data from the $_GET superglobal.
   * 
   * @var array
   */
  private array $get;

  /**
   * Data from the $_POST superglobal.
   * 
   * @var array
   */
  private array $post;

  /**
   * Data from the $_REQUEST superglobal.
   * 
   * @var array
   */
  private array $request;

  /**
   * Data from the $_SESSION superglobal.
   * 
   * @var array
   */
  private array $session;

  /**
   * Initialize the request with superglobals.
   */
  public function __construct() {
    $this->get = $_GET;
    $this->post = $_POST;
    $this->request = $_REQUEST;
    $this->session = $_SESSION ?? [];
  }

  /**
   * Get data from $_GET.
   *
   * @param string $key 
   *   The key to retrieve.
   * @param mixed $default
   *   Default value if the key doesn't exist.
   * 
   * @return mixed 
   *   Returns sanitized value or default.
   */
  public function getGet(string $key, $default = null) {
    return $this->sanitize($this->get[$key] ?? $default);
  }

  /**
   * Get data from $_POST.
   *
   * @param string $key 
   *   The key to retrieve.
   * @param mixed $default
   *   Default value if the key doesn't exist.
   * 
   * @return mixed 
   *   Returns sanitized value or default.
   */
  public function getPost(string $key, $default = null) {
    return $this->sanitize($this->post[$key] ?? $default);
  }

  /**
   * Get data from $_REQUEST.
   *
   * @param string $key 
   *   The key to retrieve.
   * @param mixed $default
   *   Default value if the key doesn't exist.
   * 
   * @return mixed 
   *   Returns sanitized value or default.
   */
  public function getRequest(string $key, $default = null) {
    return $this->sanitize($this->request[$key] ?? $default);
  }

  /**
   * Get data from $_SESSION.
   *
   * @param string $key 
   *   The key to retrieve.
   * @param mixed $default 
   *   Default value if the key doesn't exist.
   * 
   * @return mixed 
   *   Returns session value or default.
   */
  public function getSession(string $key, $default = null) {
    return $this->session[$key] ?? $default;
  }

  /**
   * Helper function to sanitize input data.
   *
   * @param mixed $data 
   *   The data to sanitize.
   * 
   * @return mixed 
   *   Returns sanitized value or default.
   */
  private function sanitize($data) {
    if (is_string($data)) {
      return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
    return $data;
  }

  /**
   * Set data in $_SESSION.
   *
   * @param string $key 
   *   The key to set.
   * @param mixed $value 
   *   The value to set.
   * 
   * @return void
   */
  public function setSession(string $key, $value): void {
    $_SESSION[$key] = $value;
    $this->session[$key] = $value;
  }
  
}
