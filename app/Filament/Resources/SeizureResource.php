<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeizureResource\Pages;
use App\Filament\Resources\SeizureResource\RelationManagers;
use App\Models\Seizure;
use Dom\Text;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;

use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SeizureResource extends Resource
{
    protected static ?string $model = Seizure::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function getModelLabel(): string
    {
        return 'Producto decomisado';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Productos decomisados';
    }

    public static function getNavigationLabel(): string
    {
        return 'Productos decomisados';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                //  group de form para producto
                Fieldset::make('')->label('Dados de registro del producto')
                ->schema([

                    Select::make('products_id')
                    ->relationship('product', 'nombre')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm(schema: [
                        TextInput::make('nombre')->required()->label('Nombre del producto '),
                        
                        Fieldset::make('')
                        ->schema([
                            TextInput::make('costo'),
                            TextInput::make('sku')->required(),
                            TextInput::make('modelo'),
                        ])
                        ->columns(3),
                    
                        TextInput::make('descripcion'),
                    
                            TextInput::make('precio_venta')->label('Precio de ventas')->default(0),
                            TextInput::make('stock')->numeric()->required()->default(1),
                    
                
                    ]),

                    Select::make('cliente_id')
                    ->relationship('cliente', 'nombre') // nombre del método de relación en el modelo, no de la tabla
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        TextInput::make('nombre')
                            ->label('Nombre del cliente')
                            ->required(),
                    ]),
                    Select::make('sucursal')->label('Sucursal')->required()
                    ->options([
                        'Cañoto' => 'Cañoto',
                        'Isabel' => 'Isabel',
                        'Brisa' => 'Brisa',
                        'Isuto' => 'Isuto',
                        'Aranjuez' => 'Aranjuez',
                        'Cielo Mall' => 'Cielo Mall',
                        'Ventura' => 'Ventura',
                    ]),

                    TextInput::make('recibido_por')->required()->label('Recibido por'),
                    Textarea::make('accesorio')->label('Accesorio')->columnSpan(2),
                    TextInput::make('quantity')->label('Cantidad')->default(1)
                    ->numeric()
                    ->step(1),
                    Select::make('status_producto')->label('Estado de producto')->required()
                    ->options([
                        'Bueno' => 'Bueno',
                        'Regular' => 'Regular',
                        'Malo' => 'Malo',
                        'Excelente' => 'Execelente'
                    ]),

                    Select::make('status')->label('Estado de registro')->required()
                    ->options([
                        'Disponible' => 'Disponible',
                        'Reventa' => 'Reventa',
                        'Baja' => 'Baja',
                        'Respuesto' => 'Respuesto',
                        'Activo Fijo' => 'Activo Fijo',
                    
                    ])->default('Disponible'),

                    DatePicker::make('fecha_decomiso')->required(),
                    Textarea::make('obs_product')->label('Obs...')->columnSpan(2),
                    ])
                ->disabled(fn () => auth()->user()->hasRole(['Product Manager', 'Almacen','Gerencia']))
                ->dehydrated(fn () => !auth()->user()->hasRole(['Product Manager', 'Almacen','Gerencia'])),
                
                //  form group pára los datos de la faturas u otros
                Fieldset::make('')->label('Dados de facturas')
                ->schema([
                    TextInput::make('factura')->required()->label('Nro Factura')->numeric()->required(),
                    DatePicker::make('fecha_factura')->required()->label('Fecha Facturacion'),
                    TextInput::make('sale_quota')->label('Coutas')->numeric()->default(0),
                    TextInput::make('saldo')->label('Saldo')->numeric()->beforeStateDehydrated(fn ($state) => 
                    (float) str_replace(['.', ','], ['', '.'], $state)),
                // ->visible(
                //     fn() => auth()->user()
                // ),}
                TextInput::make('monto_facturado')->label('Monto Factura Inicial')->required()->numeric()->beforeStateDehydrated(fn ($state) => 
                (float) str_replace(['.', ','], ['', '.'], $state)),

                DatePicker::make('fecha_entrega'),
                TextInput::make('monto_cancelado')->label('Monto cancelado')->numeric() ->beforeStateDehydrated(fn ($state) => 
                (float) str_replace(['.', ','], ['', '.'], $state)
                 ),

              
                 TextInput::make('area')
                 ->label('Área'),
                ])
                ->disabled(fn () => auth()->user()->hasRole(['Product Manager', 'Almacen','Gerencia']))
                ->dehydrated(fn () => !auth()->user()->hasRole(['Product Manager', 'Almacen','Gerencia']))
                ->columns(4),
               

              /* This part of the code is defining a form fieldset specifically for Product Managers.
              It includes input fields for `cost_price`, `sale_price`, and `suggested_price`, along
              with a textarea field for `observation_pm`. */
                //  group form para los product manager 
                Fieldset::make('')->label('Dados de registro para Product Manager')
                ->schema([
               
                    TextInput::make('cost_price')->label('Precio de costo')->default(0),
                    TextInput::make('sale_price')->label('Precio de ventas el que se vendio')->numeric()->default(0),
                    TextInput::make('suggested_price')->label('Precio de ventas sugerido')->numeric()->default(0),

                    Textarea::make('observation_pm')->label('Obs. Product Manager')->columnSpan(2)
                  
                ])
                ->disabled(fn () => auth()->user()->hasRole(['Credito','Almacen','Gerencia']))
                ->dehydrated(fn () => !auth()->user()->hasRole(['Credito','Almacen','Gerencia'])),
                // ->columns(4),


                 //  group form para los de amalcen 
                 Fieldset::make('')->label('Dados de registro los de Almacen')
                 ->schema([
                    Textarea::make('obs_Almacen')->label('Obs. Almacen')->columnSpan(2)
                ]) 
                 ->disabled(fn () => auth()->user()->hasRole(['Product Manager','Credito','Gerencia']))
                ->dehydrated(fn () => !auth()->user()->hasRole(['Product Manager','Credito','Gerencia'])),
                

                Fieldset::make('Gerencia')->label('Precio Autorizado para la venta')
                ->schema([
                    TextInput::make('suggested_price_gerencia')->label('Precio de costo')->default(0),
                ]) ->disabled(fn () => auth()->user()->hasRole(['Product Manager','Almacen','Credito']))
                ->dehydrated(fn () => !auth()->user()->hasRole(['Product Manager','Almacen','Credito'])),

                Fieldset::make('')->label('Solo si es para activo fijo')
                ->schema([
                    FileUpload::make('attachment')->label('Adjunto')
                    ->disk('public')
                    ->directory('form-attachments')
                    ->visibility('public')
                    ->reorderable()
                    ->appendFiles()
                ])  ->disabled(fn () => auth()->user()->hasRole(['Credito','Almacen','Product Manager','Gerencia']))
                ->dehydrated(fn () => !auth()->user()->hasRole(['Credito','Almacen','Product Manager','Gerencia'])),


                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('id')->searchable(),
                Tables\Columns\TextColumn::make('product.nombre')->searchable(),
                Tables\Columns\TextColumn::make('cliente.nombre'),
                Tables\Columns\TextColumn::make('factura')->label('Nro Factura'),
                Tables\Columns\TextColumn::make('sucursal'),
                Tables\Columns\TextColumn::make('area'),
                Tables\Columns\BadgeColumn::make('status')
                ->label('Estado')
                ->color(fn (string $state): string => match ($state) {
                    'Disponible' => 'warning',
                    'Reventa' => 'success',
                    'Baja' => 'danger',
                    'Respuesto' => 'gray',
                    'Activo Fijo' => 'info',
                    default => 'secondary',
                })
                ->searchable(),
            
                Tables\Columns\BadgeColumn::make('status_producto')
                ->label('Estado del producto')
                ->color(fn (string $state): string => match ($state) {
                    'Regular' => 'warning',
                    'Bueno' => 'success',
                    'Malo' => 'danger',
                    default => 'secondary',
                })
            
            ->searchable(),
            Tables\Columns\TextColumn::make('fecha_decomiso')->searchable()
            ])
            ->filters([
                SelectFilter::make('status')
                ->label('Estado')
                ->options([
                    'Disponible' => 'Disponible',
                    'Reventa' => 'Reventa',
                    'Baja' => 'Baja',
                ]),

            ])
            ->actions([
                EditAction::make()
                ->disabled(fn ($record) => $record->status === 'Reventa'),  // lo que se quiuere obtener esd que en esta verificacion sno se pueda modificar ni eliminar segun el status
                DeleteAction::make()
                    ->disabled(fn ($record) => $record->status === 'Reventa'),  // verificacion para eliminar segun el status
        
                Tables\Actions\ViewAction::make(),
                Action::make('pdf')
                ->label('PDF')
                ->icon('heroicon-o-document-text')
                ->color('primary')
                ->url(fn (Seizure $record) => route('pdf.example', ['id' => $record->id]))
                ->openUrlInNewTab(),

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
            'index' => Pages\ListSeizures::route('/'),
            'create' => Pages\CreateSeizure::route('/create'),
            'view' => Pages\ViewSeizure::route('/{record}'),
            'edit' => Pages\EditSeizure::route('/{record}/edit'),
        ];
    }
}
