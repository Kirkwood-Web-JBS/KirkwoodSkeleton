<?php

namespace KirkwoodSkeleton\Layouts;

use KirkwoodSkeleton\Helpers\LoginFunctions;
use KirkwoodSkeleton\Layouts\Generics\GenericHeaderBody1Page;
use KirkwoodSkeleton\Flags\Login as LoginFlags;
use KirkwoodSkeleton\Forms\Login;

class LogInOut extends GenericHeaderBody1Page
{
  protected $pathToPage = "";
  protected $loginFunction = null;

  public function __construct($pathToPage, array $elements = [])
  {
    parent::__construct($elements);
    $this->pathToPage = $pathToPage;
    $this->loginFunction = new LoginFunctions();
  }

  public function setTitle($newTitle)
  {
    $this->header->setTitle($newTitle);
  }

  public function setTitleHref($href)
  {
    $this->header->setTitleHref($href);
  }

  public function setTitleInfo($newTitle, $href)
  {
    $this->setTitle($newTitle);
    $this->setTitleHref($href);
  }

  public function draw()
  {
    $loggedOut = false;
    if ($this->loginFunction->didLogout()) {
      $this->loginFunction->logout();
      $loggedOut = false;
    }

    if (!$loggedOut && $this->loginFunction->isLoggedIn()) {
      return false;
    }

    $flag = $loggedOut ? LoginFlags::LOGGED_OUT : 0;
    if ($this->loginFunction->didAttemptLogin()) {
      $flag = $this->loginFunction->attemptLogin();
    }

    if ($flag === 0 && $this->loginFunction->isLoggedIn()) {
      return false;
    }

    $this->htmlHeader->draw();
    $this->createHeader();
    $this->createBody();


    $this->drawForm($flag);

    return true;
  }

  protected function drawForm($flag)
  {

    echo "<div class='fluid-container'>";
    echo "<div class='row m-0 py-4 justify-content-center'>";
    echo "<div class='col-11 col-md-10 col-lg-6'>";

    $login = new Login();
    $login->export($this->pathToPage, $flag);
//    Login::export($this->pathToPage, $flag);

    echo "</div></div></div>";
  }
}