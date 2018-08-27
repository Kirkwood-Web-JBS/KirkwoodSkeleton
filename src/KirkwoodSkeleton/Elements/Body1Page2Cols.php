<?php

namespace KirkwoodSkeleton\Elements;

use KirkwoodSkeleton\Routers\DefaultRouter;

class Body1Page2Cols extends Body1Page
{

  /**
   * @var DefaultRouter
   */
  protected $leftPane;
  /** @var DefaultRouter */
  protected $rightPane;

  protected $leftBreak;
  protected $leftCols;

  protected static $breaks = ["xs", "sm", "md", "lg", "xl"];

  public function __construct(DefaultRouter $leftPane = null, DefaultRouter $rightPane = null, SideMenu $sideMenu = null, array $leftCols = [0, 0, 3, 3, 2], $leftBreak = "sm", $configFile = "")
  {
    $this->setDefaultConfig(__DIR__ . "/../DefaultConfigs/body1page2cols.json");
    parent::__construct(null, $sideMenu, $configFile);
    $this->leftPane = $leftPane ? $leftPane : new DefaultRouter();
    $this->rightPane = $rightPane ? $rightPane : new DefaultRouter();
    $this->leftCols = $leftCols;
    $this->leftBreak = $leftBreak;
  }

  private function generateLeftDisplays()
  {
    $ret = "";
    for ($i = 0; $i < count(self::$breaks); $i++) {
      $cols = $this->leftCols[$i];
      $break = self::$breaks[$i];
      switch ($break) {
        case "xs":
          if ($cols == 0) {
            $ret .= "d-none ";
          } else {
            $ret .= "d-block col-$cols ";
          }
          break;
        default:
          if ($cols == 0) {
            $ret .= "d-$break-none ";
          } else {
            $ret .= "d-$break-block col-$break-$cols ";
          }
      }
    }

    return $ret;
  }

  public function draw()
  {
    parent::draw();

    echo "<div class='container-fluid w-100 h-100 p-0'>";
    echo "<div class='row w-100 h-100 m-0'>";

    echo "<div class='" . self::createClassString($this->configs["leftPane"]["classes"]) . $this->generateLeftDisplays() . "' " . self::formatStyles($this->configs["leftPane"]["styles"]) . " id='{$this->configs["leftPane"]["id"]}'>";
    $this->leftPane->draw();
    echo "</div>";


    echo "<div class='col " . self::createClassString($this->configs["rightPane"]["classes"]) . "'" . self::formatStyles($this->configs["rightPane"]["styles"]) . " id='{$this->configs["rightPane"]["id"]}'>";
    $this->rightPane->draw();
    echo "</div>";
  }

  public function getLeftPane(){
    return $this->leftPane;
  }

  public function getRightPane(){
    return $this->rightPane;
  }


}