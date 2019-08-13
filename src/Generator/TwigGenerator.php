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

use GuzzleHttp\Psr7\Response;
use Apigee\MockClient\ResponseGeneratorInterface;

/**
 * A response generator that can convert a template and context to a response.
 */
class TwigGenerator implements ResponseGeneratorInterface {

  /**
   * The twig environment.
   *
   * @var \Twig_Environment
   */
  protected $twig;

  /**
   * TwigGenerator constructor.
   *
   * @param \Twig_Environment $twig
   */
  public function __construct(\Twig_Environment $twig) {
    $this->twig = $twig;
  }

  /**
   * {@inheritdoc}
   */
  public function appliesToSource($source) {
    return $source instanceof TwigSource;
  }

  /**
   * {@inheritdoc}
   */
  public function generateFromSource($source) {
    $content = $this->twig->render($source->getTemplate(), $source->getContext());

    return new Response($source->getResponseCode(), $source->getHeaders(), $content) ;
  }

}
