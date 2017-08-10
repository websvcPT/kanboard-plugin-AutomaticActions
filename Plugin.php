<?php

namespace Kanboard\Plugin\AutomaticActions;

use Kanboard\Core\Plugin\Base;
use Kanboard\Plugin\AutomaticActions\Actions\TaskRename;
use Kanboard\Plugin\AutomaticActions\Actions\SetSpentTime;

class Plugin extends Base
{
    public function initialize()
    {
        $this->actionManager->register(new TaskRename($this->container));
        $this->actionManager->register(new SetSpentTime($this->container));
        
    }
}
