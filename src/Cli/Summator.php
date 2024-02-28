<?php

namespace MyProject\Cli\Summator;
use MyProject\Cli\AbstractCommand;

require_once 'AbstractCommand.php';
use MyProject\Exceptions\CliException;

class Summator extends AbstractCommand
{
   protected function checkParams()
   {
    $this->ensureParamExists('a');
    $this->ensureParamExists('b');
   }

   public function execute()
   {
    echo $this->getParam('a') + $this->getParam('b');
   }
}