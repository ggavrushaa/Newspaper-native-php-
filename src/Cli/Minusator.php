<?php 

namespace MyProject\Cli;

require_once 'AbstractCommand.php';
use MyProject\Exceptions\CliException;

class Minusator extends AbstractCommand
{
    protected function checkParams()
    {
        $this->ensureParamExists('x');
        $this->ensureParamExists('y');
    }

    public function execute()
    {
        echo $this->getParam('x') - $this->getParam('y');
    }
}