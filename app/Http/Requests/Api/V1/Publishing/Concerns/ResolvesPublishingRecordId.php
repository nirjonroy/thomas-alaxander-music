<?php

namespace App\Http\Requests\Api\V1\Publishing\Concerns;

use Illuminate\Database\Eloquent\Model;

trait ResolvesPublishingRecordId
{
    protected function routeRecordId(string $key): ?int
    {
        $record = $this->route($key);

        if ($record instanceof Model) {
            return (int) $record->getKey();
        }

        if (is_numeric($record)) {
            return (int) $record;
        }

        $inputId = $this->input($key . '_id', $this->input('id'));

        return is_numeric($inputId) ? (int) $inputId : null;
    }
}
