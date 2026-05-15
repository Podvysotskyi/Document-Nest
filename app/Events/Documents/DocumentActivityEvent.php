<?php

namespace App\Events\Documents;

use App\DTOs\DocumentActivityPayload;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class DocumentActivityEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(public DocumentActivityPayload $payload) {}
}
