<?php

/*
 * Copyright 2019 The Apigee Mock Client PHP Authors.
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

namespace Apigee\MockClient\Generator;

use Apigee\Edge\Entity\EntityInterface;

/**
 * A source type for the generating responses from Apigee SDK entities.
 */
class ApigeeSdkEntitySource {

  /**
   * The source edge entity or array of entities.
   *
   * @var \Apigee\Edge\Entity\EntityInterface
   */
  protected $data;

  /**
   * The response code.
   *
   * @var int
   */
  protected $responseCode;

  /**
   * The response headers.
   *
   * @var array
   */
  protected $headers;

  /**
   * The response payload format.
   *
   * @var string
   */
  protected $format;

  /**
   * Create a twig response generator source.
   *
   * @param mixed $data
   *   The Apigee Edge SDK entity or an array of entities.
   * @param int $response_code
   *   The response code of the generated response.
   * @param array $headers
   *   Headers to add to the response.
   * @param string $format
   *   The API response payload format.
   */
  public function __construct($data, $response_code = 200, $headers = [], $format = 'json') {
    $this->data = $data;
    $this->responseCode = $response_code;
    $this->headers = $headers + ['content-type' => 'application/json;charset=utf-8'];
    $this->format = $format;
  }

  /**
   * Get the Apigee Edge SDK entity.
   *
   * @return \Apigee\Edge\Entity\EntityInterface
   *   The Apigee SDK entity.
   */
  public function getData() {
    return $this->data;
  }

  /**
   * Get the response code.
   *
   * @return int
   *   The response code.
   */
  public function getResponseCode(): int {
    return $this->responseCode;
  }

  /**
   * Get the response headers
   *
   * @return array
   *   The response headers.
   */
  public function getHeaders(): array {
    return $this->headers;
  }

  /**
   * Get the response payload format.
   *
   * @return string
   *   The serialization format.
   */
  public function getFormat() {
    return $this->format;
  }

}
