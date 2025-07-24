<?php

namespace App\Filament\Resources\SeizureResource\Pages;

use App\Filament\Resources\SeizureResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSeizure extends ViewRecord
{
    protected static string $resource = SeizureResource::class;
    protected function getHeaderActions(): array
    {
        return [
            // Actions\ViewAction::make(),
             Actions\EditAction::make(),
        ];
    }
}
