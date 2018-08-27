<?php

namespace KirkwoodSkeleton\Elements;

use KirkwoodSkeleton\Routers\DefaultRouter;

class Body1Page extends Base
{
  protected $nested = false;

  /** @var SideMenu */
  protected $sideMenu;

  /** @var DefaultRouter */
  protected $router;

  public function __construct(DefaultRouter $router = null, SideMenu $sideMenu = null, $configFile = "")
  {
    $this->setDefaultConfig(__DIR__ . "/../DefaultConfigs/body1page.json");
    parent::__construct($configFile);
    $this->sideMenu = $sideMenu ? $sideMenu : new SideMenu();

    $this->router = $router;
  }

  public function setNested($bool)
  {
    $this->nested = $bool;
  }

  public function isNested()
  {
    return $this->nested;
  }

  public function drawScripts()
  {
    echo <<<SCRIPT
<script>
    function doResizeCalculations(){
        $("#wrapper_body").height($(window).height() - $("#wrapper_header").outerHeight());
    }
    
    $(function(){
      $(window).resize(doResizeCalculations);
      doResizeCalculations();
    });
</script>
SCRIPT;
  }

  public function draw()
  {
    if (!$this->isNested()) {
      $this->drawScripts();
    }

    echo "<div id='" . $this->configs["wrapper"]["id"] . "' ";
    echo $this->genClassStyles("wrapper");
    echo ">";

    $this->sideMenu->draw();

    if ($this->router) {
      $this->router->draw();

      echo "</div>"; // close the wrapper
    } // otherwise, leave the body open for more stuff

  }
}