<?php

namespace KirkwoodSkeleton\Layouts\Generics;

use KirkwoodSkeleton\Elements\Body1Page2Cols;

abstract class GenericHeaderBody1Page2Col extends GenericHeaderBody1Page{
  public function __construct(array $elements=[])
  {
    if (!isset($elements["body"])){
      $elements["body"] = new Body1Page2Cols();
    } elseif (!($elements["body"] instanceof Body1Page2Cols)){
      self::handleException(self::createBadElementException("body", "Body1Page2Cols", $elements["body"]));
    }

    parent::__construct($elements);

//    $this->body = new Body1Page2Cols();
  }

}