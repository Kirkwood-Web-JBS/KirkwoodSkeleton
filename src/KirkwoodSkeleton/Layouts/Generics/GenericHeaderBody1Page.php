<?php

namespace KirkwoodSkeleton\Layouts\Generics;

use KirkwoodSkeleton\Elements\Body1Page;
use KirkwoodSkeleton\Elements\Header;
use KirkwoodSkeleton\HtmlHeaders\HeaderHtml;
use KirkwoodSkeleton\HtmlHeaders\HeaderHtmlBootstrap4_1_1;
use KirkwoodSkeleton\Routers\DefaultRouter;

abstract class GenericHeaderBody1Page
{
  /** @var HeaderHtml */
  protected $htmlHeader;
  /** @var Header */
  protected $header;
  /** @var Body1Page */
  protected $body;
  /** @var DefaultRouter */
  protected $router;

  public function __construct(array $elements=[])
  {
    if (!isset($elements["htmlHeader"])){
      $this->htmlHeader = new HeaderHtmlBootstrap4_1_1();
    } elseif (!($elements["htmlHeader"] instanceof HeaderHtml)){
      self::handleException(self::createBadElementException("htmlHeader", "HeaderHtml", $elements["htmlHeader"]));
    } else {
      $this->htmlHeader = $elements["htmlHeader"];
    }

    if (!isset($elements["header"])){
      $this->header = new Header();
    } elseif (!($elements["header"] instanceof Header)){
      self::handleException(self::createBadElementException("header", "Header", $elements["header"]));
    } else {
      $this->header = $elements["header"];
    }

    if (!isset($elements["router"])){
      $this->router = null;
    } elseif (!($elements["router"] instanceof DefaultRouter)){
      self::handleException(self::createBadElementException("router", "DefaultRouter", $elements["router"]));
    } else {
      $this->router = $elements["router"];
    }

    if (!isset($elements["body"])){
      $this->body = new Body1Page($this->router);
    } elseif (!($elements["body"] instanceof Body1Page)){
      self::handleException(self::createBadElementException("body", "Body1Page", $elements["body"]));
    } else {
      $this->body = $elements["body"];
    }
  }

  protected static function createBadElementException($key, $expectedClass, $element){
    $found = gettype($element);
    if ($found === "object"){
      $found = get_class($element);
    }

    return new \Exception("Element passed as $key config must extend $expectedClass - found $found");
  }

  protected static function handleException(\Exception $e){
    error_log($e->getMessage());
    error_log($e->getTraceAsString());
    die();
  }

  protected function createHeader(){
    $this->header->draw();
  }

  protected function createBody(){
    $this->body->draw();
  }

  // return true iff the method echoed to the screen
  abstract function draw();

  public function getHeader(){
    return $this->header;
  }

  public function getHtmlHeader(){
    return $this->htmlHeader;
  }

  public function getBody(){
    return $this->body;
  }
}