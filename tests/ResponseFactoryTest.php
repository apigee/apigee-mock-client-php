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

use Apigee\MockClient\Exception\MatchingGeneratorNotFoundException;
use Apigee\MockClient\ResponseFactory;
use Apigee\MockClient\ResponseGeneratorInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;

/**
 * Tests the response factory.
 */
class ResponseFactoryTest extends TestCase {

  /**
   * Test the response factory can find a generator for an object or throw an
   * error.
   */
  public function testResponseFactory() {
    $source = new \stdClass();
    $source->data = \random_bytes(16);

    // Create a mock generator.
    $generator = $this->prophesize(ResponseGeneratorInterface::class);
    $generator->appliesToSource($source)->willReturn(TRUE);
    $generator->appliesToSource(Argument::any())->willReturn(FALSE);
    $generator->generateFromSource($source)->willReturn($this->createMock(ResponseInterface::class));

    // Creates a response factory.
    $factory = new ResponseFactory();
    $factory->addGenerator($generator->reveal());

    // Get a response for the source object.
    $response = $factory->generateResponse($source);

    static::assertInstanceOf(ResponseInterface::class, $response);

    // Make sure a non matching response will throw an error.
    $this->expectException(MatchingGeneratorNotFoundException::class);
    $factory->generateResponse(new \stdClass());
  }

}
