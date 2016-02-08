<?php
namespace Phpoke;

class Phpoke {

  protected $apiUrl = 'http://pokeapi.co/api/v2/';
  protected $guzzleClient;
  protected $apiRequest = [];
  protected $url;
  protected $pagination;

  function __construct($apiUrl = false) {
    // Allow users to override the API URL
    if($apiUrl) {
      $this->apiUrl = $apiUrl;
    }

    $this->pagination = 20;

    // Setup Guzzle
    $this->guzzleClient = new \GuzzleHttp\Client();
  }

  private function _request() {
    // @TODO: Validate endpoint

    // Make request
    try {
      $res = $this->guzzleClient->request('GET', $this->url, [
        'query' => [
          'limit' => $this->pagination
        ]
      ]);
    } catch (\GuzzleHttp\Exception\RequestException $e) {
      return [
        'type' => 'error',
        'exception' => $e
      ];
    }

    return [
      'type' => 'success',
      'response' => $res->getBody()
    ];
  }

  private function _buildUrl() {
    // Setup blank URL
    $url = '';

    // Add the API URL
    $url .= $this->apiUrl;

    // Have we set an endpoint? Let's add that
    if(isset($this->apiRequest['endpoint'])) {
      $url .= $this->apiRequest['endpoint'];
    }

    // Have we sent a specific entity? Let's add that
    if(isset($this->apiRequest['entity'])) {
      $url .= '/' . $this->apiRequest['entity'];
    }

    // Return the final URL
    return $url;
  }

  

  public function limit($pagination) {
    if($pagination) {
      $this->pagination = $pagination;
    } 

    return $this;
  }

  /**
   * Get the data
   *
   * Runs the _request() function, validates for errors and returns the data
   *
   * @see _request()
   *
   * @return string   The response data (as JSON)
   *
   * @author Chris Hutchinson <chris_hutchinson@me.com>
   *
   * @since 1.0.0
   *
   */
  public function get() {
    $this->url = $this->_buildUrl();

    $response = $this->_request();

    if($response['type'] === 'error') {
      return false;
    }

    return $response['response']; 
  }

  /**
   * Berries
   */
  public function berries($id = false) {
    $this->apiRequest['endpoint'] = 'berry';
    if($id) {
      $this->apiRequest['entity'] = $id;
    }

    return $this;
  }

  public function berryFirmness($id = false) {
    $this->apiRequest['endpoint'] = 'berry-firmness';
    
    return $this;
  }

  public function berryFlavor($id = false) {
    $this->apiRequest['endpoint'] = 'berry-flavor';
    
    return $this;
  }

}