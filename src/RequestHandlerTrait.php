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
use Psr\Http\Message\ResponseInterface;

/**
 * A trait for processing requests with results from mock storage.
 */
trait RequestHandlerTrait {

  /**
   * Handles an incoming HTTP request and responds with a mock response.
   *
   * @param \Psr\Http\Message\RequestInterface $request
   *   The HTTP Request.
   *
   * @return \Psr\Http\Message\ResponseInterface
   *   The HTTP resposne.
   *
   * @throws \Exception
   *   Thrown when the next item in the queue is an exception.
   */
  private function handleRequest(RequestInterface $request) {
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

    throw $result instanceof \Exception ? $result : new \Exception('The response queue is empty.');
  }

}
