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

namespace Apigee\MockClient\Tests;

use Apigee\MockClient\MatchableResult;
use Apigee\MockClient\SimpleMockStorage;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\RequestMatcher\RequestMatcher;
use PHPUnit\Framework\TestCase;

/**
 * Tests the simple mock client storage.
 */
class SimpleMockStorageTest extends TestCase {

  /**
   * @var \Apigee\MockClient\MockStorageInterface
   */
  protected $storage;

  /**
   * A sample response.
   *
   * @var \Psr\Http\Message\ResponseInterface
   */
  protected $response;

  /**
   * A sample request.
   *
   * @var \Psr\Http\Message\RequestInterface
   */
  protected $request;

  /**
   * A sample matchable result.
   *
   * @var \Apigee\MockClient\MatchableResultInterface
   */
  protected $matchableResult;

  public function setup() {
    $this->storage = new SimpleMockStorage();
    $generator = MessageFactoryDiscovery::find();
    $this->response = $generator->createResponse();
    $this->request = $generator->createRequest('GET', 'https://example.com');

    $this->matchableResult = new MatchableResult(new RequestMatcher(), function () { return $this->response; });
  }

  /**
   * Tests an uninitialized simple storage.
   */
  public function testEmptyStorage() {
    static::assertNull($this->storage->default());
    static::assertNull($this->storage->claim());
    static::assertSame(0, $this->storage->responseCount());
    static::assertSame([], $this->storage->requests());
    static::assertNull($this->storage->lastRequest());
    static::assertSame(0, $this->storage->totalRequests());
    static::assertSame([], $this->storage->matchableResults());
  }

  /**
   * Tests the default getters and setters.
   */
  public function testDefault() {
    $this->storage->setDefault($this->response);

    static::assertSame($this->response, $this->storage->default());
  }

  /**
   * Tests the queue in the mock storage.
   */
  public function testQueue() {
    $this->storage->add($this->response);
    $this->storage->add(new \Exception('Test exception.'));

    static::assertSame($this->response, $this->storage->claim());
    static::assertSame('Test exception.', (string) $this->storage->claim()->getMessage());
    static::assertSame(0, $this->storage->responseCount());
  }

  /**
   * Tests the request log.
   */
  public function testRequestLog() {
    $last_request = clone $this->request;

    $this->storage->addRequest($this->request);
    $this->storage->addRequest($this->request);
    $this->storage->addRequest($last_request);

    static::assertSame(3, $this->storage->totalRequests());

    static::assertSame($this->storage->requests(), [
      $this->request,
      $this->request,
      $last_request
    ]);

    static::assertSame($last_request, $this->storage->lastRequest());
  }

  /**
   * Tests the matchable results.
   */
  public function testMatchablesResults() {
    $this->storage->addMatchableResult($this->matchableResult);

    static::assertSame([$this->matchableResult], $this->storage->matchableResults());
  }

  /**
   * Tests resetting the mock client storage.
   */
  public function testReset() {
    $this->storage->setDefault($this->response);
    $this->storage->add($this->response);
    $this->storage->addRequest($this->request);
    $this->storage->addMatchableResult($this->matchableResult);

    static::assertNotNull($this->storage->default());
    static::assertSame(1, $this->storage->responseCount());
    static::assertSame([$this->request], $this->storage->requests());
    static::assertSame($this->request, $this->storage->lastRequest());
    static::assertSame(1, $this->storage->totalRequests());
    static::assertSame([$this->matchableResult], $this->storage->matchableResults());

    $this->storage->reset();

    $this->testEmptyStorage();
  }

}
