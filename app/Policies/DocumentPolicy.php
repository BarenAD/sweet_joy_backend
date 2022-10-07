<?php

namespace App\Policies;


class DocumentPolicy extends CorePolicy
{
    protected function setUpParams(): void
    {
        $this->baseRule = 'documents';
    }
}
