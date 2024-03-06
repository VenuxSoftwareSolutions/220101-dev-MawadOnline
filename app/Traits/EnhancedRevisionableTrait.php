<?php

namespace App\Traits;

use Venturecraft\Revisionable\RevisionableTrait;

trait EnhancedRevisionableTrait
{
    use RevisionableTrait{
        RevisionableTrait::getAdditionalFields as originalGetAdditionalFields;
    }
    protected $dontKeepRevisionOf = ['is_draft', 'approved'];

    /**
     * This trait override takes the original getAdditionalFields method
     * and adds entity_id
     *
     * @return array
     */
    private function getAdditionalFields(): array
    {
        return array_merge($this->originalGetAdditionalFields(), ['action_number' => $this->getLastActionNumber()]);
    }
}