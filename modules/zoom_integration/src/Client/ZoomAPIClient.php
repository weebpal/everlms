<?php

namespace Drupal\zoom_integration\Client;

use DateInterval;
use Firebase\JWT\JWT;
use DateTimeImmutable;
use Psr\Log\LoggerInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\ClientInterface;
use Drupal\Component\Datetime\Time;
use Drupal\key\KeyRepositoryInterface;
use Drupal\Component\Serialization\Json;
use GuzzleHttp\Exception\RequestException;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\zoom_integration\Client\ZoomAPIClientInterface;

/**
 * Uses http client to interact with the Zoom API.
 */
class ZoomAPIClient implements ZoomAPIClientInterface {

  /**
   * The Immutable Config Object.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $config;

  /**
   * An http client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * Psr\Log\LoggerInterface definition.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * Drupal\Component\Datetime\Time definition.
   *
   * @var \Drupal\Component\Datetime\Time
   */
  protected $time;

  /**
   * Zoom API Account Id.
   *
   * @var string
   */
  protected $accountId;

  /**
   * Zoom API Client Id.
   *
   * @var string
   */
  protected $clientId;

  /**
   * Zoom API Client Secret.
   *
   * @var string
   */
  protected $clientSecret;

  /**
   * Zoom base API.
   *
   * @var string
   */
  protected $baseUri;

  /**
   * Zoom get access token uri
   */
  protected $accessTokenUri;

  /**
   * Constructor.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   *   Client interface.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory interface.
   * @param \Psr\Log\LoggerInterface $logger
   *   Logger interface.
   * @param \Drupal\Component\Datetime\Time $time
   *   Time.
   */
  public function __construct(
    ClientInterface $http_client,
    ConfigFactoryInterface $config_factory,
    LoggerInterface $logger,
    Time $time
  ) {
    $this->httpClient = $http_client;
    $this->logger = $logger;
    $this->time = $time;
    $this->config = $config_factory->get('zoom_integration.settings');
    $this->accountId = $this->config->get('account_id');
    $this->clientId = $this->config->get('client_id');
    $this->clientSecret = $this->config->get('client_secret');
    $this->baseUri = $this->config->get('base_uri');
    $this->accessTokenUri = $this->config->get('access_token_uri');
  }

  /**
   * Utilizes Drupal's httpClient to connect to the Zoom API.
   *
   * Info: https://marketplace.zoom.us/docs/api-reference/introduction
   *
   * @param string $method
   *   get, post, patch, delete, etc. See Guzzle documentation.
   * @param string $endpoint
   *   The Zoom API endpoint (ex. users)
   * @param array $query
   *   Any Query Parameters defined in the API spec.
   * @param array $body
   *   Array that will get converted to JSON for some requests.
   *
   * @return mixed
   *   RequestException or \GuzzleHttp\Psr7\Response body
   */
  public function request(string $method, string $endpoint, array $query = [], array $body = []) {
    try {
      $response = $this->httpClient->{$method}(
        $this->baseUri . $endpoint,
        $this->buildOptions($query, $body)
      );
      // TODO: Add additional response options.
      $payload = Json::decode($response->getBody()->getContents());
      return $payload;
    } catch (RequestException $exception) {
      // Log Any exceptions.
      $this->logger->error('Failed to complete Zoom API Task "%error"', ['%error' => $exception->getMessage()]);
      throw $exception;
    }
  }

  /**
   * @inheritDoc
   */
  public function generateAccessToken() {
    // Base64 encode clientId:clientSecret
    $base64Credentials = base64_encode("$this->clientId:$this->clientSecret");

    // Set the request parameters
    $params = [
      'grant_type' => 'account_credentials',
      'account_id' => $this->accountId,
    ];

    // Set the headers
    $headers = [
      'Host' => 'zoom.us',
      'Authorization' => 'Basic ' . $base64Credentials,
    ];

    // Make a POST request
    /** @var Response $response */
    $response = $this->httpClient->post($this->accessTokenUri, [
      'form_params' => $params,
      'headers' => $headers,
    ]);
    if ($response->getStatusCode() == 200) {
      // Get the response body
      $responseBody = $response->getBody()->getContents();

      // Decode the JSON response
      $values = json_decode($responseBody, TRUE);
      $current_time = \Drupal::time()->getCurrentTime();
      $values['expires_time'] = $current_time + $values['expires_in'];
      return $values;
    }

    return NULL;
  }

  /**
   * @inheritDoc
   */
  public function getAccessToken() {
    $config = \Drupal::configFactory()->get('zoom_integration.access_token_settings');
    $current_time = \Drupal::time()->getCurrentTime();
    $expires_time = $config->get('expires_time');
    $access_token = $config->get('access_token');
    $generate_new_access_token = FALSE;
    if (empty($access_token) || empty($expires_time)) {
      $generate_new_access_token = TRUE;
    } elseif ($expires_time <= $current_time) {
      $generate_new_access_token = TRUE;
    }
    if ($generate_new_access_token) {
      $values = $this->generateAccessToken();
      $this->setAccessToken($values);
      return $values['access_token'];
    } else {
      return $access_token;
    }
  }

  /**
   * @inheritDoc
   */
  public function setAccessToken(array $values) {
    $config = \Drupal::configFactory()->getEditable('zoom_integration.access_token_settings');
    foreach ($values as $key => $value) {
      $config->set($key, $value);
    }
    $config->save();
  }

  /**
   * Build options for the client.
   *
   * @param array $query
   *   An array of querystring params for guzzle.
   * @param array $body
   *   An array of items that guzzle with json_encode.
   *
   * @return array
   *   An array of options for guzzle.
   */
  private function buildOptions(array $query = [], array $body = []) {
    $options = [];
    $options['headers'] = [
      'Authorization' => 'Bearer ' . $this->setAuthHeader(),
    ];
    if (!empty($body)) {
      // Json key converts array to json & adds appropriate content-type header.
      $options['json'] = $body;
    }
    if (!empty($query)) {
      $options['query'] = $query;
    }
    return $options;
  }

  /**
   * Generate JSON Web Token.
   *
   * TODO: allow for changing expiration.
   *
   * @return string
   */
  private function setAuthHeader() {
    return $this->getAccessToken();
  }

  /**
   * Generate and return an auth token.
   *
   * @return string
   */
  public function ensureAuthToken() {
    return $this->setAuthHeader();
  }

  /**
   * @inheritDoc
   */
  function generateSignature($key, $secret, $meeting_number, $role, $time_end = null) {
    $iIat = (new DateTimeImmutable())->getTimestamp();
    $iExp = (new DateTimeImmutable())->add(new DateInterval('PT2H'))->getTimestamp();

    if(!empty($time_end) && $time_end > $iIat){
      $time_remain = $time_end - $iIat;
      if($time_remain < 1800) {
        $time_remain = 1800;
      } elseif($time_remain > 172800) {
        $time_remain = 172800;
      }
      $iExp = $time_remain + $iIat;
    }

    $aPayload = [
      'appKey' => $key,
      'sdkKey' => $key,
      'mn' => $meeting_number,
      'role' => $role,
      'iat' => $iIat,
      'exp' => $iExp,
      'tokenExp' => $iExp
    ];
    // Firebase\JWT\JWT
    $sJWT = JWT::encode($aPayload, $secret, 'HS256');
    return $sJWT;
  }

  /**
   * @inheritDoc
   */
  function getUserByEmail($mail){
    if(!empty($mail)){
      $res = $this->request(
        'GET',
        "/users/email",
        ['email' => $mail],
      );
      if ($res["existed_email"]) {
        $data = $this->request(
          'GET',
          "/users/$mail",
        );
        return $data;
      }
    }
    return null;
  }
}
