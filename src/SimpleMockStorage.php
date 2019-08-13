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

namespace Apigee\MockClient;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;

class SimpleMockStorage implements MockStorageInterface {

  /**
   * The default HTTP request result.
   *
   * @var \Psr\Http\Message\ResponseInterface|\Exception
   */
  protected $default;

  /**
   * All requests that have been received.
   *
   * @var \Psr\Http\Message\RequestInterface[]
   */
  protected $requests = [];

  /**
   * The HTTP result queue for unmatched requests.
   *
   * @var mixed[]
   */
  protected $queue = [];

  /**
   * All request matchers.
   *
   * @var \Apigee\MockClient\MatchableResultInterface[]
   */
  protected $matchers = [];

  /**
   * {@inheritdoc}
   */
  public function default() {
    return $this->default;
  }

  /**
   * {@inheritdoc}
   */
  public function setDefault($default = NULL): MockStorageInterface {
    $this->default = $default;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function add($result): MockStorageInterface {
    $this->queue[] = $result;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function claim() {
    return array_shift($this->queue);
  }

  /**
   * {@inheritdoc}
   */
  public function responseCount(): int {
    return count($this->queue);
  }

  /**
   * {@inheritdoc}
   */
  public function reset(): MockStorageInterface {
    $this->default = NULL;
    $this->requests = [];
    $this->queue = [];
    $this->matchers = [];

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function addRequest(RequestInterface $request): MockStorageInterface {
    $this->requests[] = $request;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function lastRequest(): ?RequestInterface {
    return ($last = end($this->requests)) ? $last : NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function requests(): array {
    return $this->requests;
  }

  /**
   * {@inheritdoc}
   */
  public function totalRequests(): int {
    return count($this->requests);
  }

  /**
   * {@inheritdoc}
   */
  public function addMatchableResult(MatchableResultInterface $matchableResult): MockStorageInterface {
    $this->matchers[] = $matchableResult;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function matchableResults(): array {
    return $this->matchers;
  }

}
