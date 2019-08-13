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

use Apigee\MockClient\MockClient;
use GuzzleHttp\Psr7\Request;
use Http\Discovery\Exception\NotFoundException;
use Http\Message\RequestMatcher\RequestMatcher;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Tests the mock client.
 */
class ClientTest extends TestCase {

  protected $client;

  public function setUp() {
    parent::setUp();

    // Create a test client.
    $this->client = new MockClient();
  }

  public function testDefaultResponse() {
    $default_response = $this->createMock(ResponseInterface::class);
    $this->client->setDefaultResponse($default_response);

    static::assertSame($default_response, $this->client->sendRequest($this->createMock(RequestInterface::class)));
  }

  public function testDefaultException() {
    $this->client->setDefaultException(new \Exception('Default exception.'));

    $this->expectException(\Exception::class);
    $this->client->sendRequest($this->createMock(RequestInterface::class));
  }

  public function testMatchableResponse() {
    $request_matcher = new RequestMatcher('foo');

    $response = $this->createMock(ResponseInterface::class);
    $this->client->on($request_matcher, $response);

    static::assertSame($response, $this->client->sendRequest(new Request('GET', 'https://example.com/foo')));

    // Test trying to add an invalid matchable result.
    $this->expectException(\InvalidArgumentException::class);
    $this->client->on($request_matcher, 'foo');
  }

  public function testMatchableException() {
    $request_matcher = new RequestMatcher('foo');

    $response = $this->createMock(ResponseInterface::class);
    $this->client->on($request_matcher, new NotFoundException('The requested resource was not found.'));

    $this->expectException(NotFoundException::class);
    $this->client->sendRequest(new Request('GET', 'https://example.com/foo'));
  }

  public function testChronologicalResponse() {
    // Add a couple of responses.
    $this->client->addResponse($this->createMock(ResponseInterface::class));
    $this->client->addResponse($this->createMock(ResponseInterface::class));
    // Make sure the total count is correct.
    static::assertSame(2, $this->client->responseCount());

    static::assertInstanceOf(ResponseInterface::class, $this->client->sendRequest($this->createMock(RequestInterface::class)));
    static::assertInstanceOf(ResponseInterface::class, $this->client->sendRequest($this->createMock(RequestInterface::class)));
    // The queue should now be empty.
    static::assertSame(0, $this->client->responseCount());

    // Test the request log.
    static::assertInstanceOf(RequestInterface::class, $this->client->getLastRequest());
    static::assertCount(2, $this->client->getRequests());
    // Reset the client.
    $this->client->reset();
    static::assertCount(0, $this->client->getRequests());

    $this->expectException(\Exception::class);
    $this->client->sendRequest($this->createMock(RequestInterface::class));
  }

  public function testExceptionResponse() {
    // Add an exception.
    $this->client->addException(new NotFoundException());
    $this->expectException(NotFoundException::class);

    $this->client->sendRequest($this->createMock(RequestInterface::class));
  }

}
