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

use Apigee\MockClient\Psr7\SerializableMessageWrapper;
use Http\Message\RequestMatcher;
use Psr\Http\Message\ResponseInterface;
use Apigee\MockClient\MockSerializableClosure;

/**
 * A trait for dealing with the mock storage property.
 */
trait MockStoragePropertyTrait {

  /**
   * The response storage.
   *
   * @var \Apigee\MockClient\MockStorageInterface
   */
  protected $storage;

  /**
   * Add a conditional response
   *
   * @param \Http\Message\RequestMatcher $requestMatcher
   *   A request matcher.
   * @param callable|\Psr\Http\Message\ResponseInterface|\Exception $result
   *   A result must eather be a response, and exception or callable. Callable
   *   results must either return a response or throw an exception.
   *
   * @return $this
   */
  public function on(RequestMatcher $requestMatcher, $result) {
    // Validate the result.
    if ($result instanceof \Exception || $result instanceof ResponseInterface || is_callable($result)) {
      // Some storage provides will try to serialize the result.
      $result = $result instanceof ResponseInterface ? new SerializableMessageWrapper($result) : $result;

      // Normalize to a callable result.
      $callable = is_callable($result) ? $result : new MockSerializableClosure(function () use ($result) {
        if ($result instanceof \Exception) {
          throw $result;
        }
        // The only other available type for $result is `SerializableMessageWrapper`.
        return $result->getMessage();
      });

      $this->storage->addMatchableResult(new MatchableResult($requestMatcher, $callable));
    } else {
      throw new \InvalidArgumentException('Result must be either a response, an exception, or a callable');
    }

    return $this;
  }

  /**
   * Sets the default response in the mock storage.
   *
   * @param \Psr\Http\Message\ResponseInterface|NULL $defaultResponse
   *   the default response.
   *
   * @return $this
   */
  public function setDefaultResponse(ResponseInterface $defaultResponse = NULL) {
    $this->storage->setDefault($defaultResponse);

    return $this;
  }

  /**
   * Sets the default exception in the mock storage.
   *
   * @param \Exception|NULL $defaultException
   *   The default exception.
   *
   * @return $this
   */
  public function setDefaultException(\Exception $defaultException = null) {
    $this->storage->setDefault($defaultException);

    return $this;
  }

  /**
   * Sets the default resposne in the mock storage.
   *
   * @param \Psr\Http\Message\ResponseInterface $response
   *   The default resposne.
   *
   * @return $this
   */
  public function addResponse(ResponseInterface $response) {
    $this->storage->add($response);

    return $this;
  }

  /**
   * Adds an exception to the queue in the storage.
   *
   * @param \Exception $exception
   *   The exception that will be thrown when this ququq item is reached.
   *
   * @return $this
   */
  public function addException(\Exception $exception) {
    $this->storage->add($exception);

    return $this;
  }

  /**
   * Gets the total number of items in the queue.
   *
   * @return int
   *   The queue length.
   */
  public function responseCount() {
    return $this->storage->responseCount();
  }

  /**
   * Gets all the requests that have been logged.
   *
   * @return \Psr\Http\Message\RequestInterface[]
   *   All logged requests.
   */
  public function getRequests() {
    return $this->storage->requests();
  }

  /**
   * Gets the last request in the request log.
   *
   * @return \Psr\Http\Message\RequestInterface
   *   The last request in the request log.
   */
  public function getLastRequest() {
    return $this->storage->lastRequest();
  }

  /**
   * Completely resets the mock storage and clears all queues and logs.
   *
   * @return $this
   */
  public function reset() {
    $this->storage->reset();

    return $this;
  }

}
