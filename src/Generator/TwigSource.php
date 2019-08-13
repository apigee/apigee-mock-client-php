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

/**
 * A source type for the generating responses from twig.
 */
class TwigSource {

  /**
   * The source twig template.
   *
   * @var string
   */
  protected $template;

  /**
   * The twig render context.
   *
   * @var array
   */
  protected $context;

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
   * Create a twig response generator source.
   *
   * @param string $template
   *   The twig template that should be used to render the response content.
   * @param array $context
   *   Any context that is used when generating the response.
   * @param int $response_code
   *   The response code of the generated response.
   * @param array $headers
   *   Headers to add to the response.
   */
  public function __construct($template, $context = [], $response_code = 200, $headers = []) {
    $this->template = $template;
    $this->context = $context;
    $this->responseCode = $response_code;
    $this->headers = $headers;
  }

  /**
   * Get the template.
   *
   * @return string
   *   The twig template.
   */
  public function getTemplate() {
    return $this->template;
  }

  /**
   * Get the twig render context.
   *
   * @return array
   *   The render context.
   */
  public function getContext() {
    return $this->context;
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

}
