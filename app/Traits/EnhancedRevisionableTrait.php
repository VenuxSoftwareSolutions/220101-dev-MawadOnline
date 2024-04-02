<?php

namespace App\Traits;

use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Support\Arr;
trait EnhancedRevisionableTrait
{
    use RevisionableTrait{
        RevisionableTrait::getAdditionalFields as originalGetAdditionalFields;
    }
    

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

    public static function newModel()
    {
        $model = app('config')->get('revisionable.model');

        if (! $model) {
            $model = 'Venturecraft\Revisionable\Revision';
        }

        return new $model;
    }

    /**
     * Called after a model is successfully saved.
     *
     * @return void
     */
    public function postSave()
    {
        // Add your custom condition here
        if ($this->is_draft == 1) {
            return; // Skip saving the revision if is_draft is not 1
        }

        // Existing logic for saving revisions...
        if ((!isset($this->revisionEnabled) || $this->revisionEnabled) && $this->updating) {
            // Continue with the existing logic to save revisions
             // if it does, it means we're updating

             $changes_to_record = $this->changedRevisionableFields();
             $action_number = $this->getAdditionalFields();

             $revisions = array();
 
             foreach ($changes_to_record as $key => $change) {
                 $revisions[] = array(
                     'revisionable_type'     => $this->getMorphClass(),
                     'revisionable_id'       => $this->getKey(),
                     'key'                   => $key,
                     'old_value'             => Arr::get($this->originalData, $key),
                     'new_value'             => $this->updatedData[$key],
                     'user_id'               => $this->getSystemUserId(),
                     'action_number'         => $action_number['action_number'],
                     'created_at'            => new \DateTime(),
                     'updated_at'            => new \DateTime(),
                 );
             }

 
             if (count($revisions) > 0) {
                 $revision = static::newModel();
                 \DB::table($revision->getTable())->insert($revisions);
             }
        }
    }
}