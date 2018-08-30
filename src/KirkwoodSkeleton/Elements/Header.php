<?php

namespace KirkwoodSkeleton\Elements;

use KirkwoodSkeleton\Helpers\LoginFunctions;
use KirkwoodSkeleton\Helpers\Functions;

class Header extends Base
{
  protected $defaultConfig;

  public function __construct($configFile = "")
  {
    $this->setDefaultConfig(__DIR__ . "/../DefaultConfigs/header.json");
    parent::__construct($configFile);
  }

  public function setTitle($newTitle)
  {
    $this->setStringConfig("title/text", $newTitle);
  }

  public function setLogoutButtonText($newText)
  {
    $this->setStringConfig("logout/text", $newText);
  }

  public function setLogoutHref($href)
  {
    $this->setStringConfig("logout/href", $href);
  }

  public function setTitleHref($href)
  {
    $this->setStringConfig("title/href", $href);
  }

  public function draw()
  {
    $this->createScript();

    $this->startWrapper();

    $this->createMenuButton();
    $this->createTitle();
    $this->createLogoutButton();

    echo "</div>"; // close header wrapper
  }

  protected function createScript()
  {
    $btnId = $this->configs["menuButton"]["id"];

    echo <<<SCRIPT
<script>
    var sideMenuClose = true;

    function handleSideMenuAnimation(){
      var sideMenu = $("#wrapper_side_menu");
      
      sideMenu.stop();
      if (sideMenuClose){
       sideMenu.animate({
       left: "-50px"
       }, 300);
      } else {
        sideMenu.animate({left: "-350px"}, 300);
      }
      
      sideMenuClose = !sideMenuClose;
    }

    $(function(){
      $("#$btnId").click(handleSideMenuAnimation);
    });
</script>
SCRIPT;

  }

  protected function startWrapper()
  {
    echo "<div id='{$this->configs["wrapper"]["id"]}' ";
    echo $this->formatClasses($this->configs["wrapper"]["classes"]);
    echo $this->formatStyles($this->configs["wrapper"]["styles"]) . ">";
  }

  protected function createMenuButton()
  {
    if (LoginFunctions::isLoggedIn()) { // only create the menu button if the user is logged in
      echo "<div " . $this->formatClasses($this->configs["menuWrapper"]["classes"]) . $this->formatStyles($this->configs["menuWrapper"]["styles"]) . ">";

      echo "<button id='" . $this->configs["menuButton"]["id"] . "' ";
      echo $this->formatClasses($this->configs["menuButton"]["classes"]);
      echo $this->formatStyles($this->configs["menuButton"]["styles"]);
      echo " type='button'>";

      echo $this->configs["menuButton"]["text"];

      echo "</button>";

      echo "</div>"; // close wrapper
    }
  }

  protected function createTitle()
  {
    echo "<div " . $this->formatStyles($this->configs["title"]["styles"]) . ">";

    $isLink = $this->configs["title"]["href"] != "";
    if ($isLink) {
      echo "<a href='" . $this->configs["title"]["href"] . "' style='color: inherit;'>";
    }

    echo $this->configs["title"]["text"];

    if ($isLink) {
      echo("</a>");
    }


    echo "</div>"; // close title div
  }

  protected function createLogoutButton()
  {
    if (LoginFunctions::isLoggedIn()) {
      // only create the button if the user is logged in

      echo "<div " . $this->genClassStyles("logoutWrapper") . ">";

      $href = $this->configs["logout"]["href"];
      if ($href === ""){
        $href = Functions::getUriWithoutGetParams() . "?logout=1";
      }

      $tag = "<a href='$href' id='{$this->configs["logout"]["id"]}' " . self::genClassStyles("logout") . ">{$this->configs["logout"]["text"]}</a>";

      echo $tag;

//      $tagStart = "<";
//      $isBtn = false;
//      if ($this->configs["logout"]["href"] !== "") {
//        $tagStart .= "a href='" . $this->configs["logout"]["href"] . "?logout=1' ";
//      } else {
//        $isBtn = true;
//        $tagStart .= "button type='button' ";
//      }
//
//      $tagStart .= "id='" . $this->configs["logout"]["id"] . "' ";
//      $tagStart .= self::genClassStyles("logout");
//      $tagStart .= ">";
//
//      echo $tagStart;
//      echo $this->configs["logout"]["text"];
//      echo $isBtn ? "</button>" : "</a>";

      echo "</div>"; // close button wrapper

    }
  }
}