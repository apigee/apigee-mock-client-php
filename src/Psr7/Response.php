<?php

/*
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Apigee\MockClient\Psr7;

use GuzzleHttp\Psr7\Response as GuzzleResponse;
use function GuzzleHttp\Psr7\stream_for;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * The mock response class.
 *
 * This class is necessary because the stream in the guzzle response class is
 * not serializable for database storage. Streams references are lost on
 * serialization we compensate by storing the body contents to a property and
 * re-instantiating the decorated response with the body contents during
 * deserialization.
 */
class Response implements ResponseInterface {

  /**
   * The response body.
   *
   * This variable is only used during serialization. It is populated upon
   * serialization and unset after deserialization.
   *
   * @var string
   */
  private $body;

  /**
   * The decorated response.
   *
   * @var \GuzzleHttp\Psr7\Response
   */
  private $decorated;

  /**
   * {@inheritdoc}
   */
  public function __construct($status = 200, array $headers = [], $body = null, $version = '1.1', $reason = null) {
    $this->decorated = new GuzzleResponse($status, $headers, $body, $version, $reason);
  }

  /**
   * {@inheritdoc}
   */
  public function getProtocolVersion() {
    return $this->decorated->getProtocolVersion();
  }

  /**
   * {@inheritdoc}
   */
  public function withProtocolVersion($version) {
    return $this->decorated->withProtocolVersion($version);
  }

  /**
   * {@inheritdoc}
   */
  public function getHeaders() {
    return $this->decorated->getHeaders();
  }

  /**
   * {@inheritdoc}
   */
  public function hasHeader($name) {
    return $this->decorated->hasHeader($name);
  }

  /**
   * {@inheritdoc}
   */
  public function getHeader($name) {
    return $this->decorated->getHeader($name);
  }

  /**
   * {@inheritdoc}
   */
  public function getHeaderLine($name) {
    return $this->decorated->getHeaderLine($name);
  }

  /**
   * {@inheritdoc}
   */
  public function withHeader($name, $value) {
    return $this->decorated->withHeader($name, $value);
  }

  /**
   * {@inheritdoc}
   */
  public function withAddedHeader($name, $value) {
    return $this->decorated->withAddedHeader($name, $value);
  }

  /**
   * {@inheritdoc}
   */
  public function withoutHeader($name) {
    return $this->decorated->withoutHeader($name);
  }

  /**
   * {@inheritdoc}
   */
  public function getBody() {
    return $this->decorated->getBody();
  }

  /**
   * {@inheritdoc}
   */
  public function withBody(StreamInterface $body) {
    return $this->decorated->withBody($body);
  }

  /**
   * {@inheritdoc}
   */
  public function getStatusCode() {
    return $this->decorated->getStatusCode();
  }

  /**
   * {@inheritdoc}
   */
  public function withStatus($code, $reasonPhrase = '') {
    return $this->decorated->withStatus($code, $reasonPhrase);
  }

  /**
   * {@inheritdoc}
   */
  public function getReasonPhrase() {
    return $this->decorated->getReasonPhrase();
  }

  /**
   * {@inheritdoc}
   */
  public function __sleep() {
    $this->body = (string) $this->decorated->getBody();

    return ['body', 'decorated'];
  }

  /**
   * {@inheritdoc}
   */
  public function __wakeup() {
    $this->decorated = $this->decorated->withBody(stream_for($this->body));

    unset($this->body);
  }

}
