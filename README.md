# Apigee Mock Client for PHP
A mock client library to replace calls to Apigee Edge with mock calls for testing.

## Roadmap

- Add a templating system for creating JSON responses from Apigee client PHP objects.
- Evaluate serialization as an option for mock response generation.
- Add traits for queuing responses for common objects.

## The Twig generator.

There is a Twig generator included in this library. To use it, you must first include
Twig in your project. Using the Twig generator is not required for this library to
operate so it is not required by default.
