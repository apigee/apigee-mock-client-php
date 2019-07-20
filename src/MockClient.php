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

use Http\Client\Common\HttpAsyncClientEmulator;
use Http\Client\Common\VersionBridgeClient;
use Http\Client\HttpAsyncClient;
use Http\Client\HttpClient;
use Http\Message\RequestMatcher;
use Http\Message\ResponseFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * A response queue that returns claimed items in a FIFO order.
 *
 * This mock client is modeled after `Http\Mock\Client` with the exception that
 * it defaults to throwing an error if the mock queue is empty and it allows
 * a configurable storage for the response queue, request log and the default
 * response.
 */
class MockClient implements HttpClient, HttpAsyncClient {

  use HttpAsyncClientEmulator;
  use VersionBridgeClient;

  const EMPTY_MESSAGE = 'The response queue is empty.';
  /**
   * The response queue will be responsible for storing responses.
   *
   * @var \Apigee\MockClient\ResponseQueueInterface|\Apigee\MockClient\SimpleResponseQueue
   */
  protected $storage;

  /**
   * MockClient constructor.
   *
   * @param \Apigee\MockClient\MockClientStorageInterface|NULL $storage
   */
  public function __construct(MockClientStorageInterface $storage = NULL) {
    $this->storage = $storage ?? new SimpleMockClientStorage();
  }

  /**
   * {@inheritdoc}
   */
  public function doSendRequest(RequestInterface $request) {
    $this->storage->addRequest($request);

    foreach ($this->storage->matchableResults() as $result) {
      if ($result->matches($request)) {
        return $result();
      }
    }

    $result = ($result = $this->storage->claim()) ? $result : $this->storage->default();

    if ($result instanceof ResponseInterface) {
      return $result;
    }

    throw $result instanceof \Exception ? $result : new \Exception(static::EMPTY_MESSAGE);
  }

  /**
   * {@inheritdoc}
   */
  public function on(RequestMatcher $requestMatcher, $result) {
    // Validate the result.
    if ($result instanceof \Exception || $result instanceof ResponseInterface || is_callable($result)) {
      // Normalize to a callable result.
      $callable = is_callable($result) ? $result : function () use ($result) {
        if ($result instanceof \Exception) {
          throw $result;
        }

        return $result;
      };

      $this->storage->addMatchableResult(new MatchableResult($requestMatcher, $callable));
    } else {
      throw new \InvalidArgumentException('Result must be either a response, an exception, or a callable');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setDefaultResponse(ResponseInterface $defaultResponse = NULL) {
    $this->storage->setDefault($defaultResponse);
  }

  /**
   * {@inheritdoc}
   */
  public function setDefaultException(\Exception $defaultException = null) {
    $this->storage->setDefault($defaultException);
  }

  /**
   * {@inheritdoc}
   */
  public function addResponse(ResponseInterface $response) {
    $this->storage->add($response);
  }

  /**
   * {@inheritdoc}
   */
  public function addException(\Exception $exception) {
    $this->storage->add($exception);
  }

  /**
   * {@inheritdoc}
   */
  public function getRequests() {
    return $this->storage->requests();
  }

  /**
   * {@inheritdoc}
   */
  public function getLastRequest() {
    return $this->storage->lastRequest();
  }

  /**
   * {@inheritdoc}
   */
  public function reset() {
    $this->storage->reset();
  }


}
