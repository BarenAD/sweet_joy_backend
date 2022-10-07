<?php

namespace App\Policies;

class DocumentLocationPolicy extends CorePolicy
{
    protected function setUpParams(): void
    {
        $this->baseRule = 'documents.locations';
    }
}
