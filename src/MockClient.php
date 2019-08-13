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

use Http\Client\Common\HttpAsyncClientEmulator;
use Http\Client\Common\VersionBridgeClient;
use Http\Client\HttpAsyncClient;
use Http\Client\HttpClient;
use Psr\Http\Message\RequestInterface;

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
  use MockStoragePropertyTrait;
  use RequestHandlerTrait;

  /**
   * MockClient constructor.
   *
   * @param \Apigee\MockClient\MockStorageInterface|NULL $storage
   */
  public function __construct(MockStorageInterface $storage = NULL) {
    $this->storage = $storage ?? new SimpleMockStorage();
  }

  /**
   * {@inheritdoc}
   */
  public function doSendRequest(RequestInterface $request) {
    return $this->handleRequest($request);
  }

}
