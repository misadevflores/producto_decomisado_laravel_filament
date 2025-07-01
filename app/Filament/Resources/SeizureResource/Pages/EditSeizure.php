<?php

namespace App\Filament\Resources\SeizureResource\Pages;

use App\Filament\Resources\SeizureResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSeizure extends EditRecord
{
    protected static string $resource = SeizureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

   

}
