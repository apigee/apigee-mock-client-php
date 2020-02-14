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

namespace Apigee\MockClient\GuzzleHttp;


use Apigee\MockClient\MockStorageInterface;
use Apigee\MockClient\MockStoragePropertyTrait;
use Apigee\MockClient\RequestHandlerTrait;
use Apigee\MockClient\SimpleMockStorage;
use Psr\Http\Message\RequestInterface;

/**
 * A response handler that serves as a Guzzle client handler.
 */
class MockHandler {

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

  public function __invoke(RequestInterface $request, array $options) {
    return $this->handleRequest($request);
  }

}
