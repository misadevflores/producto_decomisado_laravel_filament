<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = \App\Models\Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
   

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('nombre')->required(),
              
                    TextInput::make('costo'),
                    TextInput::make('sku')->required(),
                    TextInput::make('modelo'),
              
                TextInput::make('descripcion'),
              
                    TextInput::make('precio_venta'),
                    TextInput::make('stock')->numeric()->required(),
             
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            //
            Tables\Columns\TextColumn::make('nombre')->searchable(),
            Tables\Columns\TextColumn::make('sku')->searchable(),
            Tables\Columns\TextColumn::make('modelo')->searchable(),

            Tables\Columns\TextColumn::make('costo')->default(0),
                Tables\Columns\TextColumn::make('stock')->default(1),
            ])
            ->filters([
            // Tables\Filters\Filter::make('stock_mayor_que_cero')
            //     ->label('Con stock disponible')
            //     ->query(fn($query) => $query->where('stock', '>', 0)),

            // Tables\Filters\SelectFilter::make('modelo')
            //     ->label('Filtrar por modelo')
            //     ->options(fn() => \App\Models\Product::pluck('modelo', 'modelo')->toArray())
            //     ->searchable(),

            // Tables\Filters\SelectFilter::make('costo')
            //     ->label('Costo')
            //     ->options(fn() => \App\Models\Product::distinct()->pluck('costo', 'costo')->toArray()),

        ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
