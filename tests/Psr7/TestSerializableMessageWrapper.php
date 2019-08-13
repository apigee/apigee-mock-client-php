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

namespace Apigee\MockClient\Tests\Psr7;

use Apigee\MockClient\Psr7\SerializableMessageWrapper;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Tests the serializable HTTP message wrapper.
 */
class TestSerializableMessageWrapper extends TestCase {

  /**
   * Test that a response can be serialized and unserialized without data loss.
   */
  function testResponseSerialization() {
    // Use a unique ID for a bit of random data.
    $uuid = uniqid();
    $response = new Response(200, ['content-type' => 'application/json;charset=utf-8'], "{\"uuid\": \"{$uuid}\"}");
    $wrapped_response = new SerializableMessageWrapper($response);
    $unserialized_response = unserialize(serialize($wrapped_response))->getMessage();

    // Compare the response to the unserialized version.
    static::assertEquals($response->getStatusCode(), $unserialized_response->getStatusCode());
    static::assertEquals($response->getHeaders(), $unserialized_response->getHeaders());
    static::assertEquals((string) $response->getBody(), (string) $unserialized_response->getBody());
    static::assertEquals($response->getProtocolVersion(), $unserialized_response->getProtocolVersion());
    static::assertEquals($response->getReasonPhrase(), $unserialized_response->getReasonPhrase());
  }

  /**
   * Test that a response can be serialized and unserialized without data loss.
   */
  function testRequestSerialization() {
    // Use a unique ID for a bit of random data.
    $uuid = uniqid();
    $content = "{\"uuid\": \"{$uuid}\"}";
    $request = new Request('POST', 'HTTP:://example.com/', ['content-type' => 'application/json;charset=utf-8', 'content-length' => strlen($content)], $content);
    $wrapped_request = new SerializableMessageWrapper($request);
    $unserialized_request = unserialize(serialize($wrapped_request))->getMessage();

    // Compare the response to the unserialized version.
    static::assertEquals($request->getMethod(), $unserialized_request->getMethod());
    static::assertEquals($request->getUri(), $unserialized_request->getUri());
    static::assertEquals($request->getHeaders(), $unserialized_request->getHeaders());
    static::assertEquals((string) $request->getBody(), (string) $unserialized_request->getBody());
    static::assertEquals($request->getProtocolVersion(), $unserialized_request->getProtocolVersion());
    static::assertEquals($request->getRequestTarget(), $unserialized_request->getRequestTarget());
  }

}
