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

namespace Apigee\MockClient\Generator;

use Apigee\Edge\Serializer\EntitySerializer;
use Apigee\Edge\Serializer\EntitySerializerInterface;
use GuzzleHttp\Psr7\Response;
use Apigee\MockClient\ResponseGeneratorInterface;

/**
 * A response generator that can convert an SDK entity to a API response.
 */
class ApigeeSdkEntityGenerator implements ResponseGeneratorInterface {

  /**
   * The SDK entity serializer.
   *
   * @var \Apigee\Edge\Serializer\EntitySerializerInterface
   */
  protected $serializer;

  /**
   * ApigeeSdkEntityGenerator constructor.
   *
   * @param \Apigee\Edge\Serializer\EntitySerializerInterface $serializer
   *   The entity serializer.
   */
  public function __construct(EntitySerializerInterface $serializer = NULL) {
    $this->serializer = $serializer ?? new EntitySerializer();
  }

  /**
   * {@inheritdoc}
   */
  public function appliesToSource($source) {
    return $source instanceof ApigeeSdkEntitySource;
  }

  /**
   * {@inheritdoc}
   */
  public function generateFromSource($source) {
    /** @var \Apigee\MockClient\Generator\ApigeeSdkEntitySource $source */
    $content = $this->serializer->serialize($source->getData(), $source->getFormat());

    return new Response($source->getResponseCode(), $source->getHeaders(), $content) ;
  }

}
