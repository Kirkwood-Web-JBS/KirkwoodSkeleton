<?php

namespace KirkwoodSkeleton\Layouts;

use KirkwoodSkeleton\Layouts\Generics\GenericHeaderBody1Page2Col;

class DefaultHeaderBody1Page2Col extends GenericHeaderBody1Page2Col {
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