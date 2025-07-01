<?php

namespace App\Filament\Resources\SeizureResource\Pages;

use App\Filament\Resources\SeizureResource;
use Filament\Resources\Pages\ListRecords;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Seizure;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;

class ListSeizures extends ListRecords
{
    protected static string $resource = SeizureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            // ->actions([
            //     Action::make('CreatePDF')
            //         ->label('Crea PDF')
            //         ->color('primary')
            //         ->requiresConfirmation()
            //         ->url(fn (Seizure $record): string => route('pdf.example', ['seizures' => $record])),
            // ]),
            // Action::make('CreatePDF')
            // ->label('Crea Pdf')
            // ->color('primary') 
            // ->requiresConfirmation()
            // ->url(
            //     fn (Seizure $record): string => route('pdf.example', ['seizures' => $record]),
            //     shouldOpenInNewTab: true
            // )
            
        ];
      
    
    }
}
