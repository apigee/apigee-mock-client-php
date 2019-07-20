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

namespace Apigee\MockClient\Tests;

use Apigee\MockClient\MockClient;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Tests the mock client.
 *
 * @group client
 * @group mock
 * @small
 */
class ClientTest extends TestCase {

  function testDefaultException() {
    // Create a new mock client.
    $client = new MockClient();

    $this->expectException(\Exception::class);
    $client->sendRequest($this->createMock(RequestInterface::class));
  }

  function testChronologicalResponse() {
    $client = new MockClient();

    $client->addResponse($this->createMock(ResponseInterface::class));
    $client->addResponse($this->createMock(ResponseInterface::class));

    static::assertInstanceOf(ResponseInterface::class, $client->sendRequest($this->createMock(RequestInterface::class)));
    static::assertInstanceOf(ResponseInterface::class, $client->sendRequest($this->createMock(RequestInterface::class)));

    $this->expectException(\Exception::class);
    $client->sendRequest($this->createMock(RequestInterface::class));
  }

}
