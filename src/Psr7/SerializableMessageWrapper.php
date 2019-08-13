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

namespace Apigee\MockClient\Psr7;

use Psr\Http\Message\MessageInterface;
use function GuzzleHttp\Psr7\stream_for;

/**
 * The serializable HTTP message wrapper.
 *
 * This class is necessary because the stream in the guzzle request/response
 * classes are not serializable for database storage. Streams references are
 * lost on serialization we compensate by storing the body contents to a
 * property and re-instantiating the decorated response with the body contents
 * during deserialization.
 */
class SerializableMessageWrapper {

  /**
   * The HTTP message body.
   *
   * This variable is only used during serialization. It is populated upon
   * serialization and unset after deserialization.
   *
   * @var string
   */
  private $body;

  /**
   * The original HTTP message.
   *
   * @var \Psr\Http\Message\MessageInterface
   */
  private $message;

  /**
   * SerializableResponseWrapper constructor.
   *
   * @param \Psr\Http\Message\MessageInterface $message
   *   The original HTTP message.
   */
  public function __construct(MessageInterface $message) {
    $this->message = $message;
  }

  /**
   * Get the original HTTP message.
   */
  public function getMessage() {
    return $this->message;
  }

  /**
   * {@inheritdoc}
   */
  public function __sleep() {
    // Populate the body variable for serialization.
    $this->body = (string) $this->message->getBody();

    return ['body', 'message'];
  }

  /**
   * {@inheritdoc}
   */
  public function __wakeup() {
    // Restore the response with the original body.
    $this->message = $this->message->withBody(stream_for($this->body));

    unset($this->body);
  }

}
