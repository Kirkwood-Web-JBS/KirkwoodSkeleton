<?php

namespace KirkwoodSkeleton\Elements;

use KirkwoodSkeleton\Helpers\Functions;

class Menu extends Base
{
  protected $groupings = [];
  protected $currentActionable = [];
  protected $currentStatic = "";

  protected $route;
  protected $explodedRoute;

  public function __construct($configFile = "")
  {
    $this->setDefaultConfig(__DIR__ . "/../DefaultConfigs/menu.json");
    parent::__construct($configFile);

    $this->route = Functions::getRoute();
    $this->explodedRoute = explode("/", trim($this->route, "/"));
  }

  public function draw()
  {
    // Make sure to grab any hanging groupings
    $this->createGrouping();


    echo "<div class='list-group list-group-flush'>";

    $this->drawGroupings();
    $this->drawLogoutElement();

    echo "</div>";
  }

  public function drawGroupings()
  {
    foreach ($this->groupings as $grouping) {
      switch ($grouping["type"]) {
        case "singleton":
          $this->drawActionableElement($grouping["element"]);
          break;
        case "group":
          $this->drawStaticElement($grouping["static"]);
          foreach ($grouping["elements"] as $element) {
            $this->drawActionableElement($element);
          }
          break;
        default:
      }
    }
  }

  public function drawLogoutElement()
  {

    // Add the logout element
    echo "<a class='list-group-item list-group-item-action' href='";
    echo Functions::getUriWithoutGetParams() . "?logout=1";
    echo "'>Logout <i class='fas fa-sign-out-alt'></i></a>";
  }

  public function drawActionableElement($element)
  {
    echo "<a class='list-group-item list-group-item-action " . (static::doesHighlightGoWithRoute($element["highlight"]) ? "active'" : "'");
    echo " href='" . Functions::getUriWithoutGetParams() . "?r={$element["route"]}'";
    echo ">{$element["name"]}</a>";
  }

  protected function doesHighlightGoWithRoute($highlight){
    return $highlight === $this->explodedRoute[0];
  }

  public function drawStaticElement($name)
  {
    echo "<div class='list-group-item pl-2'><b>$name</b></div>";
  }

  public function addActionableElement($name, $highlight, $route)
  {
    if (!empty($this->currentActionable) || $this->currentStatic !== "") {
      $this->currentActionable[] = [
          "name" => $name,
          "highlight" => $highlight,
          "route" => $route
      ];
    } else {
      $this->groupings[] = [
          "type" => "singleton",
          "element" => [
              "name" => $name,
              "highlight" => $highlight,
              "route" => $route
          ]
      ];
    }
  }

  public function addStaticElement($name)
  {
    if ($this->currentStatic !== "") {
      $this->createGrouping();
    }

    $this->currentStatic = $name;
  }

  protected function createGrouping()
  {
    if ("" === $this->currentStatic || empty($this->currentActionable)) {
      $this->currentStatic = [];
      $this->currentActionable = [];
      return;
    }

    $this->groupings[] = [
        "type" => "group",
        "static" => $this->currentStatic,
        "elements" => $this->currentActionable
    ];

    $this->currentStatic = [];
    $this->currentActionable = [];
  }
}