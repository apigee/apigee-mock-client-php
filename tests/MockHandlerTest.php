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

use Apigee\MockClient\GuzzleHttp\MockHandler;
use Apigee\MockClient\MockClient;
use Apigee\MockClient\SimpleMockStorage;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Tests the mock handler.
 */
class MockHandlerTest extends TestCase {

  private $generic_request;

  function setUp() {
    parent::setUp();

    $this->generic_request = new Request('GET', 'http://example.com');
  }

  function testDefaultException() {
    // Create a new mock client.
    $client = new Client([
      'handler' => new MockHandler(),
    ]);

    $this->expectException(\Exception::class);
    $client->send($this->generic_request);
  }

  function testChronologicalResponse() {
    $handler = new MockHandler();
    // Create a new mock client.
    $client = new Client([
      'handler' => $handler,
    ]);

    $handler->addResponse($this->createMock(ResponseInterface::class));
    $handler->addResponse($this->createMock(ResponseInterface::class));

    static::assertInstanceOf(ResponseInterface::class, $client->send($this->generic_request));
    static::assertInstanceOf(ResponseInterface::class, $client->send($this->generic_request));

    $this->expectException(\Exception::class);
    $client->send($this->generic_request);
  }

}
