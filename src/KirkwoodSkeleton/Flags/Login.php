<?php

namespace KirkwoodSkeleton\Flags;

class Login{
  const EMPTY_KNUMBER = 1;
  const INVALID_KNUMBER = 2;
  const EMPTY_PASSWORD = 4;
  const INVALID_KNUMBER_PASSWORD_COMBO = 8;
  const LOGGED_OUT = 16;
}