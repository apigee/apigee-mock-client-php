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

use Psr\Http\Message\RequestInterface;

/**
 * A response queue that returns claimed items in a FIFO order.
 */
interface MockStorageInterface {

  /**
   * Get the default response.
   *
   * @return \Psr\Http\Message\ResponseInterface|\Exception|null
   *   The default response.
   */
  public function default();

  /**
   * Sets the result for a request when the queue is empty.
   *
   * @param \Psr\Http\Message\ResponseInterface|\Exception|NULL $defaultResponse
   *
   * @return \Apigee\MockClient\MockStorageInterface
   *   Returns $this;
   */
  public function setDefault($default = null): MockStorageInterface;

  /**
   * Add to the queue.
   *
   * @param \Psr\Http\Message\ResponseInterface|\Exception $result
   *   This will either be a response or an exception.
   *
   * @return \Apigee\MockClient\MockStorageInterface
   *   Returns $this;
   */
  public function add($result): MockStorageInterface;

  /**
   * Claim an item from the result queue.
   *
   * @return \Psr\Http\Message\ResponseInterface|\Exception|null
   *   The HTTP Response if there are any left int he queue.
   */
  public function claim();

  /**
   * Gets the total amount of items that are in the queue.
   *
   * @return int
   */
  public function responseCount(): int;

  /**
   * Reset storage.
   *
   * Clears the response queue, the request queue, matched results and the
   * default result.
   *
   * @return \Apigee\MockClient\MockStorageInterface
   *   Returns $this;
   */
  public function reset(): MockStorageInterface;

  /**
   * Adds a request to the request log.
   *
   * @param \Psr\Http\Message\RequestInterface $request
   *   The HTTP request.
   *
   * @return \Apigee\MockClient\MockStorageInterface
   *   Returns $this;
   */
  public function addRequest(RequestInterface $request): MockStorageInterface;

  /**
   * Gets the last request from the request log.
   *
   * @return \Psr\Http\Message\RequestInterface|null
   *  The last request.
   */
  public function lastRequest(): ?RequestInterface;

  /**
   * Gets everything from the request log.
   *
   * @return array
   *   Gets all requests.
   */
  public function requests(): array;

  /**
   * Gets the total requests that have been logged.
   *
   * @return int
   *   The total requests that have been logged.
   */
  public function totalRequests(): int;

  /**
   * Adds a matchable result to the list.
   *
   * @param \Apigee\MockClient\MatchableResultInterface $matchableResult
   *   A HTTP request result matcher.
   *
   * @return \Apigee\MockClient\MockStorageInterface
   *   Returns $this;
   */
  public function addMatchableResult(MatchableResultInterface $matchableResult): MockStorageInterface;

  /**
   * Gets all matchable results.
   *
   * @return \Apigee\MockClient\MatchableResultInterface[]
   *   The full list of matchable results.
   */
  public function matchableResults(): array;

}
