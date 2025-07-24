<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeizureResource\Pages;
use App\Filament\Resources\SeizureResource\RelationManagers;
use App\Models\Seizure;
// use Dom\Text; // Quitar esta importación si no la usas, puede causar conflictos
use Filament\Forms; // Mantener esta para el formulario
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

// --- Importaciones para Infolists ---
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\IconEntry; // Para campos booleanos o con íconos
use Filament\Infolists\Components\Grid; // Para organizar en columnas
use Filament\Infolists\Components\Section; // Para agrupar campos, similar a Fieldset

// Importaciones para columnas de tabla
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class SeizureResource extends Resource
{
    protected static ?string $model = Seizure::class;
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

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
                // group de form para producto
                Fieldset::make('Datos de registro del producto')
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
                                        TextInput::make('sku'),
                                        TextInput::make('modelo'),
                                    ])
                                    ->columns(3),
                                TextInput::make('descripcion'),
                                TextInput::make('precio_venta')->label('Precio de ventas')->default(0),
                                TextInput::make('stock')->numeric()->required()->default(1),
                            ]),

                        Select::make('cliente_id')
                            ->relationship('cliente', 'nombre')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('nombre')
                                    ->label('Nombre del cliente')
                                    ->required(),
                            ]),
                        Select::make('sucursal')
                        ->label('Sucursal')
                        ->required()
                        ->options([
                            'CAÑOTO' => 'CAÑOTO',
                            'ISABEL' => 'ISABEL',
                            'BRISA' => 'BRISA',
                            'ISUTO' => 'ISUTO',
                            'ARANJUEZ' => 'ARANJUEZ',
                            'CIELO MALL' => 'CIELO MALL',
                            'VENTURA' => 'VENTURA',
                        ]),
                        TextInput::make('recibido_por')->required()->label('Recibido por (Nombre Completo)'),
                        Textarea::make('accesorio')->label('Accesorio')->columnSpan(2),
                        TextInput::make('quantity')->label('Cantidad')->default(1)
                            ->numeric()
                            ->step(1),
                        Select::make('status_producto')->label('Estado de producto')->required()
                            ->options([
                                'BUENO' => 'BUENO',
                                'REGULAR' => 'REGULAR',
                                'MALO' => 'MALO',
                                'EXCELENTE' => 'EXCELENTE'
                            ]),
                        Select::make('status')->label('Estado de registro')->required()
                            ->options([
                                'DISPONIBLE' => 'DISPONIBLE',
                                'REVENTA' => 'REVENTA',
                                'BAJA' => 'BAJA',
                                'ACTIVO FIJO' => 'ACTIVO FIJO',
                            ])->default('DISPONIBLE'),
                        DatePicker::make('fecha_decomiso')->required(),
                        Textarea::make('obs_product')->label('Obs...')->columnSpan(2),
                    ])
                    ->columns(2)
                    ->disabled(fn () => auth()->user()->hasRole(['Product Manager', 'Almacen', 'Gerencia']))
                    ->dehydrated(fn () => !auth()->user()->hasRole(['Product Manager', 'Almacen', 'Gerencia'])),
                
                // form group para los datos de la facturas u otros
                Fieldset::make('Datos de facturas')
                    ->schema([
                        TextInput::make('factura')->required()->label('Nro Factura')->numeric(),
                        DatePicker::make('fecha_factura')->required()->label('Fecha Facturacion'),
                        TextInput::make('sale_quota')->label('Cuotas')->numeric()->default(0),
                        TextInput::make('saldo')->label('Saldo (Bs)')->numeric()->beforeStateDehydrated(fn ($state) => 
                            (float) str_replace(['.', ','], ['', '.'], $state)),
                        TextInput::make('monto_facturado')->label('Monto Factura Inicial (Bs)')->required()->numeric()->beforeStateDehydrated(fn ($state) => 
                            (float) str_replace(['.', ','], ['', '.'], $state)),
                        DatePicker::make('fecha_entrega'),
                        TextInput::make('monto_cancelado')->label('Monto cancelado (Bs)')->numeric()->beforeStateDehydrated(fn ($state) => 
                            (float) str_replace(['.', ','], ['', '.'], $state)),
                        TextInput::make('area')->label('Área'),
                    ])
                    ->columns(4)
                    ->disabled(fn () => auth()->user()->hasRole(['Product Manager', 'Almacen', 'Gerencia']))
                    ->dehydrated(fn () => !auth()->user()->hasRole(['Product Manager', 'Almacen', 'Gerencia'])),
                
                // group form para los product manager 
                Fieldset::make('Datos de registro para Product Manager')
                    ->schema([
                        TextInput::make('cost_price')->label('Precio de costo (Bs)')->default(0),
                        TextInput::make('suggested_price')->label('Precio de ventas sugerido (Bs)')->numeric()->default(0),
                        Textarea::make('observation_pm')->label('Obs. Product Manager')->columnSpan(2)
                    ])
                    ->columns(2)
                    ->disabled(fn () => auth()->user()->hasRole(['Credito', 'Almacen', 'Gerencia']))
                    ->dehydrated(fn () => !auth()->user()->hasRole(['Credito', 'Almacen', 'Gerencia'])),
                
                // group form para los de almacen 
                Fieldset::make('Datos de registro los de Almacen')
                    ->schema([
                        Textarea::make('obs_Almacen')->label('Obs. Almacen')->columnSpan(2)
                    ]) 
                    ->columns(1)
                    ->disabled(fn () => auth()->user()->hasRole(['Product Manager', 'Credito', 'Gerencia']))
                    ->dehydrated(fn () => !auth()->user()->hasRole(['Product Manager', 'Credito', 'Gerencia'])),
                
                Fieldset::make('Gerencia')->label('Precio Autorizado por Gerencia')
                    ->schema([
                        TextInput::make('suggested_price_gerencia')->label('Precio sugerido (Bs)')->default(0),
                    ])
                    ->columns(1)
                    ->disabled(fn () => auth()->user()->hasRole(['Product Manager', 'Almacen', 'Credito']))
                    ->dehydrated(fn () => !auth()->user()->hasRole(['Product Manager', 'Almacen', 'Credencia'])),

                Fieldset::make('Solo si es para activo fijo')
                    ->schema([
                        FileUpload::make('attachment')->label('Adjunto')
                            ->disk('public')
                            ->directory('form-attachments')
                            ->visibility('public')
                            ->reorderable()
                            ->appendFiles()
                            ->image() // Añadido para que muestre preview en el formulario
                            ->reorderable()     // Permite reordenar los archivos existentes
                            ->appendFiles()     // Permite añadir más archivos sin borrar los existentes
                            ->image()           // Limita la subida a tipos de imagen y muestra previews
                            ->downloadable()    // <-- Esta propiedad ya habilita la descarga.
                            ->previewable(true) // <-- Esta propiedad ya habilita la vista previa en el formulario.
                            // ->deleteAction(      // <-- Esto habilita la opción de borrar.
                            //     // Opcional: Puedes personalizar el texto o icono del botón de borrar aquí
                            //      fn (Forms\Components\Actions\Action $action) => $action->label('Borrar adjunto'),
                            // )
                            ->openable(),   // <-- Esto permite abrir el archivo directamente en el navegador (si es un tipo soportado) 
                    ]) 
                    ->columns(1)
                    ->disabled(fn () => auth()->user()->hasRole(['Almacen', 'Product Manager', 'Gerencia']))
                    ->dehydrated(fn () => !auth()->user()->hasRole(['Almacen', 'Product Manager', 'Gerencia'])),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        // FILTRO POR ROL: Gerencia no ve 'ACTIVO FIJO'
            ->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();
                // Opcional: Para depurar, descomenta la línea de abajo. Recuerda comentarla después.
                // dd($user, $user->roles); 
                if ($user && $user->hasRole('Gerencia')) {
                    $query->where('status', '!=', 'ACTIVO FIJO');
                }
            })
            ->columns([
                TextColumn::make('id')->label('N°')->searchable(),
                TextColumn::make('product.nombre')->label('producto')->searchable()->forceSearchCaseInsensitive(false),
                TextColumn::make('cliente.nombre')->label('cliente')->searchable(),
                TextColumn::make('factura')->label('factura')->searchable(),
                TextColumn::make('sucursal')->label('sucursal')->searchable(),
                TextColumn::make('area')->label('area')->searchable(),
                BadgeColumn::make('status')
                    ->label('status')
                    ->color(fn (string $state): string => match (strtoupper($state)) {
                        'DISPONIBLE' => 'danger',
                        'REVENTA' => 'success',
                        'BAJA' => 'danger',
                        'ACTIVO FIJO' => 'info',
                        default => 'secondary',
                    })
                    ->searchable(),
                
                BadgeColumn::make('status_producto')
                    ->label('status product')
                    ->color(fn (string $state): string => match (strtoupper($state)) {
                        'REGULAR' => 'warning',
                        'BUENO' => 'success',
                        'MALO' => 'danger',
                        'EXCELENTE' => 'success',
                        default => 'secondary',
                    })
                    ->searchable(),
                TextColumn::make('fecha_decomiso')->date()->searchable()->label('decomiso'),
                
                // Columna para mostrar el adjunto en la tabla (opcional, si quieres preview directo aquí)
                ImageColumn::make('attachment')
                    ->label('Adjunto')
                    ->disk('public')
                    ->circular()
                    ->size(40),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'DISPONIBLE' => 'DISPONIBLE',
                        'REVENTA' => 'REVENTA',
                        'BAJA' => 'BAJA',
                        'ACTIVO FIJO' => 'ACTIVO FIJO',
                    ]),
                
            ])
            
            ->actions([
                // Botón "View" que te lleva a la página de detalle
                Tables\Actions\ViewAction::make(), 
                
                EditAction::make()
                    ->disabled(fn ($record) => $record->status === 'REVENTA'),
                DeleteAction::make()
                    ->disabled(fn ($record) => $record->status === 'REVENTA'),
                
                // Tu acción de PDF existente
                Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->url(fn (Seizure $record) => route('pdf.example', ['id' => $record->id]))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    // --- ESTA ES LA FUNCIÓN CLAVE PARA LA PÁGINA DE "VER" ---
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Sección: Datos de registro del producto
                Section::make('Datos de registro del producto')
                    ->schema([
                        TextEntry::make('product.nombre')->label('Producto'),
                        TextEntry::make('cliente.nombre')->label('Cliente'),
                        TextEntry::make('sucursal')->label('Sucursal'),
                        TextEntry::make('recibido_por')->label('Recibido por'),
                        TextEntry::make('accesorio')->label('Accesorios'),
                        TextEntry::make('quantity')->label('Cantidad'),
                        TextEntry::make('status_producto')->label('Estado del producto')
                            ->badge()
                            ->color(fn (string $state): string => match (strtoupper($state)) {
                                'BUENO' => 'success',
                                'REGULAR' => 'warning',
                                'MALO' => 'danger',
                                'EXCELENTE' => 'success',
                                default => 'secondary',
                            }),
                        TextEntry::make('status')->label('Estado de registro')
                            ->badge()
                            ->color(fn (string $state): string => match (strtoupper($state)) {
                                'DISPONIBLE' => 'warning',
                                'REVENTA' => 'success',
                                'BAJA' => 'danger',
                                'ACTIVO FIJO' => 'info',
                                default => 'secondary',
                            }),
                        TextEntry::make('fecha_decomiso')->label('Fecha de Decomiso')->date(),
                        TextEntry::make('obs_product')->label('Obs. Producto'),
                    ])->columns(2), // Organiza estos campos en 2 columnas

                // Sección: Datos de facturas
                Section::make('Datos de facturas')
                    ->schema([
                        TextEntry::make('factura')->label('Nro Factura'),
                        TextEntry::make('fecha_factura')->label('Fecha Facturación')->date(),
                        TextEntry::make('sale_quota')->label('Cuotas'),
                        TextEntry::make('saldo')->label('Saldo (Bs)'),
                        TextEntry::make('monto_facturado')->label('Monto Factura Inicial (Bs)'),
                        TextEntry::make('fecha_entrega')->label('Fecha Entrega')->date(),
                        TextEntry::make('monto_cancelado')->label('Monto Cancelado (Bs)'),
                        TextEntry::make('area')->label('Área'),
                    ])->columns(4), // Organiza estos campos en 4 columnas
                
                // Sección: Datos de registro para Product Manager
                Section::make('Datos de registro para Product Manager')
                    ->schema([
                        TextEntry::make('cost_price')->label('Precio de Costo (Bs)'),
                        TextEntry::make('suggested_price')->label('Precio de Ventas Sugerido (Bs)'),
                        TextEntry::make('observation_pm')->label('Obs. Product Manager'),
                    ])->columns(2),

                // Sección: Datos de registro los de Almacen
                Section::make('Datos de registro los de Almacen')
                    ->schema([
                        TextEntry::make('obs_Almacen')->label('Obs. Almacén'),
                    ])->columns(1),

                // Sección: Gerencia
                Section::make('Gerencia')
                    ->schema([
                        TextEntry::make('suggested_price_gerencia')->label('Precio Sugerido por Gerencia (Bs)'),
                    ])->columns(1),

                // Sección: Solo si es para activo fijo (Aquí va tu imagen)
                Section::make('Solo si es para activo fijo')
                ->schema([
                    // Esto mostrará la imagen directamente
                    ImageEntry::make('attachment')
                        ->label('Adjunto (Acta de Entrega)')
                        ->disk('public')
                        ->columnSpanFull() // Para que la imagen ocupe todo el ancho disponible
                        // AÑADE ESTAS PROPIEDADES PARA CONTROLAR EL TAMAÑO:
                        ->width('100%') // Asegura que ocupe el 100% del ancho de su columna
                        ->height('auto') // Mantiene la proporción de la imagen
                        ->extraImgAttributes([ // Permite añadir atributos HTML directamente a la etiqueta <img>
                            'style' => 'max-height: 500px; object-fit: contain;', // Define una altura máxima y cómo se ajusta
                            // Puedes ajustar '500px' a tu preferencia (e.g., '300px', '600px', etc.)
                            // 'object-fit: contain;' asegura que la imagen no se recorte y se ajuste dentro del espacio.
                            // Si prefieres que la imagen cubra el espacio y se recorte si es necesario, usa 'object-fit: cover;'
                        ]),
                    
                    // CORRECCIÓN PARA EL ENLACE DE DESCARGA (ya la habíamos visto)
                    TextEntry::make('attachment')
                        ->label('Descargar o Ver Archivo Original')
                        ->formatStateUsing(fn (?string $state): ?string => 
                            $state ? '<a href="' . asset('storage/' . $state) . '" target="_blank" class="text-primary-600 hover:underline">Click para Ver/Descargar</a>' : 'N/A'
                        )
                        ->html()
                        ->visible(fn ($record) => !empty($record->attachment)),
                ])->columns(1), // Esta sección de adjuntos ocupa 1 columna de su contenedor padre.
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
            'view' => Pages\ViewSeizure::route('/{record}'), // La página de "Ver" principal
            'edit' => Pages\EditSeizure::route('/{record}/edit'),
        ];
    }
}