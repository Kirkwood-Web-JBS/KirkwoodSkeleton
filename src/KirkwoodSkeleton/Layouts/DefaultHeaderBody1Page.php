<?php

namespace KirkwoodSkeleton\Layouts;

use KirkwoodSkeleton\Layouts\Generics\GenericHeaderBody1Page;

class DefaultHeaderBody1Page extends GenericHeaderBody1Page{
  public function __construct(array $elements = [])
  {
    parent::__construct($elements);
  }

  public function draw()
  {
    $this->htmlHeader->draw();
    $this->header->draw();
    $this->body->draw();
  }
}