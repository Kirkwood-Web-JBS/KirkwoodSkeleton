<?php

namespace KirkwoodSkeleton\Elements;

class SideMenu extends Menu
{

  public function __construct($configFile = "")
  {
    $this->setDefaultConfig(__DIR__ . "/../DefaultConfigs/sideMenu.json");
    parent::__construct($configFile);

  }

  public function draw()
  {
    echo "<div " . $this->genClassStyles("wrapper") . "id='" . $this->configs["wrapper"]["id"] . "'>";

    echo "<div class='card h-100' style='overflow-x: auto;'><div class='card-body' style='padding-left: 55px;'>";

    parent::draw();

    echo "</div></div>";

    echo "</div>";
  }
}