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

namespace Apigee\MockClient;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;

class SimpleMockClientStorage implements MockClientStorageInterface {

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
  public function setDefault($default = NULL): MockClientStorageInterface {
    $this->default = $default;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function add($result): MockClientStorageInterface {
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
  public function totalInQueue(): int {
    return count($this->queue);
  }

  /**
   * {@inheritdoc}
   */
  public function reset(): MockClientStorageInterface {
    $this->default = NULL;
    $this->requests = [];
    $this->queue = [];
    $this->matchers = [];

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function addRequest(RequestInterface $request): MockClientStorageInterface {
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
  public function addMatchableResult(MatchableResultInterface $matchableResult): MockClientStorageInterface {
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
