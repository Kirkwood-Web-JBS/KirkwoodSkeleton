<?php

namespace KirkwoodSkeleton\Routers;

use KirkwoodSkeleton\Helpers\Functions;

class DefaultRouter
{
  public function __construct()
  {

  }

  public function draw()
  {
    echo "<div class='w-100 h-100' style='overflow: auto;'>";

    $this->drawContent();

    echo "</div>";
  }

  protected function drawContent(){
    echo "<div class='card m-3'><div class='card-body'>You are using the default router.<br/><br/>You should extend this class and override the drawContent() method!<br/><br/>Route: ";
    echo Functions::getRoute();
    echo "</div></div>";

  }
}