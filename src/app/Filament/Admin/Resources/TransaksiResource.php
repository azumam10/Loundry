<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TransaksiResource\Pages;
use App\Models\Transaksi;
use App\Models\Paket;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Colors\Color;
use App\Models\StatusCucian;


class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    
    protected static ?string $navigationLabel = 'Transaksi';
    
    protected static ?string $modelLabel = 'Transaksi';
    
    protected static ?string $pluralModelLabel = 'Transaksi';
    
    protected static ?int $navigationSort = 1;
    
    protected static ?string $navigationGroup = 'Manajemen Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Transaksi')
                    ->description('Masukkan detail transaksi pelanggan')
                    ->icon('heroicon-o-document-text')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('metode')
                                    ->label('Metode Pembayaran')
                                    ->options([
                                        'tunai' => 'Tunai 💵',
                                        'non_tunai' => 'Non Tunai 💳'
                                    ])
                                    ->required()
                                    ->native(false)
                                    ->prefixIcon('heroicon-o-credit-card')
                                    ->helperText('Pilih metode pembayaran yang digunakan'),

                                DatePicker::make('tanggal')
                                    ->label('Tanggal Transaksi')
                                    ->required()
                                    ->native(false)
                                    ->displayFormat('d/m/Y')
                                    ->prefixIcon('heroicon-o-calendar-days')
                                    ->helperText('Tanggal pelaksanaan transaksi'),
                            ]),
                    ]),

                Section::make('Detail Pelanggan & Paket')
                    ->description('Pilih pelanggan dan paket laundry')
                    ->icon('heroicon-o-user-group')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('client_id')
                                    ->label('Pelanggan')
                                    ->relationship('client', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->native(false)
                                    ->prefixIcon('heroicon-o-user')
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nama Pelanggan')
                                            ->required(),
                                        Forms\Components\TextInput::make('phone')
                                            ->label('No. Telepon')
                                            ->tel(),
                                        Forms\Components\Textarea::make('address')
                                            ->label('Alamat')
                                            ->rows(2),
                                    ])
                                    ->createOptionUsing(function (array $data) {
                                        return Client::create($data)->id;
                                    })
                                    ->helperText('Pilih pelanggan atau buat baru'),

                                Select::make('paket_id')
                                    ->label('Paket Laundry')
                                    ->relationship('paket', 'nama')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->native(false)
                                    ->prefixIcon('heroicon-o-cube')
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $paket = Paket::find($state);
                                        if ($paket) {
                                            $set('harga', $paket->harga);
                                            
                                            // Trigger notification
                                            Notification::make()
                                                ->title('Paket Dipilih')
                                                ->body("Harga paket: Rp " . number_format($paket->harga, 0, ',', '.') . " per kg")
                                                ->success()
                                                ->send();
                                        }
                                    })
                                    ->helperText('Pilih jenis paket laundry'),
                            ]),
                    ]),

                Section::make('Perhitungan Biaya')
                    ->description('Detail berat dan total pembayaran')
                    ->icon('heroicon-o-calculator')
                    ->collapsible()
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('harga')
                                    ->label('Harga per Kg')
                                    ->readonly()
                                    ->reactive()
                                    ->numeric()
                                    ->required()
                                    ->prefix('Rp')
                                    ->prefixIcon('heroicon-o-banknotes')
                                    ->helperText('Harga otomatis dari paket'),

                                Forms\Components\TextInput::make('berat')
                                    ->label('Berat (Kg)')
                                    ->reactive()
                                    ->required()
                                    ->numeric()
                                    ->minValue(0.1)
                                    ->step(0.1)
                                    ->suffix('kg')
                                    ->prefixIcon('heroicon-o-scale')
                                    ->helperText('Masukkan berat cucian')
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $berat = floatval($state ?? 0);
                                        $harga = floatval($get('harga') ?? 0);
                                        $total = $berat * $harga;
                                        $set('total', $total);
                                        
                                        if ($total > 0) {
                                            Notification::make()
                                                ->title('Total Dihitung')
                                                ->body("Total biaya: Rp " . number_format($total, 0, ',', '.'))
                                                ->info()
                                                ->send();
                                        }
                                    }),

                                Forms\Components\TextInput::make('total')
                                    ->label('Total Biaya')
                                    ->readonly()
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->prefixIcon('heroicon-o-currency-dollar')
                                    ->helperText('Total otomatis dihitung'),
                            ]),
                        
                        // Summary placeholder
                        Placeholder::make('summary')
                            ->label('Ringkasan Transaksi')
                            ->content(function ($get) {
                                $berat = $get('berat') ?? 0;
                                $harga = $get('harga') ?? 0;
                                $total = $get('total') ?? 0;
                                
                                return new HtmlString("
                                    <div class='bg-gray-50 p-4 rounded-lg'>
                                        <div class='grid grid-cols-3 gap-4 text-sm'>
                                            <div class='text-center'>
                                                <div class='font-semibold text-gray-600'>Berat</div>
                                                <div class='text-lg font-bold text-blue-600'>{$berat} kg</div>
                                            </div>
                                            <div class='text-center'>
                                                <div class='font-semibold text-gray-600'>Harga/kg</div>
                                                <div class='text-lg font-bold text-green-600'>Rp " . number_format($harga, 0, ',', '.') . "</div>
                                            </div>
                                            <div class='text-center'>
                                                <div class='font-semibold text-gray-600'>Total</div>
                                                <div class='text-xl font-bold text-red-600'>Rp " . number_format($total, 0, ',', '.') . "</div>
                                            </div>
                                        </div>
                                    </div>
                                ");
                            }),
                    ]),
                    
                    Select::make('status_cucian_id')
                    ->label('Status Cucian')
                    ->relationship('statusCucian', 'nama_status')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->native(false)
                    ->prefixIcon('heroicon-o-adjustments-horizontal')
                    ->helperText('Status proses laundry saat ini'),

                Section::make('Pembayaran & Status')
                    ->description('Upload bukti pembayaran dan status')
                    ->icon('heroicon-o-document-check')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\FileUpload::make('bukti')
                                    ->label('Bukti Transfer')
                                    ->image()
                                    ->directory('bukti-transfer')
                                    ->visibility('public')
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([
                                        '16:9',
                                        '4:3',
                                        '1:1',
                                    ])
                                    ->maxSize(5120) // 5MB
                                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                    ->helperText('Upload bukti transfer (JPG/PNG, max 5MB)')
                                    ->columnSpanFull(),

                                Select::make('status_pembayaran')
                                    ->label('Status Pembayaran')
                                    ->options([
                                        'belum' => '⏳ Belum Lunas',
                                        'lunas' => '✅ Lunas',
                                    ])
                                    ->required()
                                    ->default('belum')
                                    ->native(false)
                                    ->prefixIcon('heroicon-o-check-circle')
                                    ->helperText('Status pembayaran saat ini'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\BadgeColumn::make('metode')
                    ->label('Metode')
                    ->colors([
                        'primary' => 'tunai',
                        'success' => 'non_tunai',
                    ])
                    ->icons([
                        'heroicon-o-banknotes' => 'tunai',
                        'heroicon-o-credit-card' => 'non_tunai',
                    ])
                    ->enum([
                        'tunai' => 'Tunai',
                        'non_tunai' => 'Non Tunai',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('client.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium)
                    ->color(Color::Blue),

                Tables\Columns\TextColumn::make('paket.nama')
                    ->label('Paket')
                    ->badge()
                    ->color(Color::Indigo)
                    ->sortable(),

                Tables\Columns\TextColumn::make('berat')
                    ->label('Berat')
                    ->numeric()
                    ->sortable()
                    ->suffix(' kg')
                    ->weight(FontWeight::Bold)
                    ->color(Color::Orange),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->numeric()
                    ->sortable()
                    ->money('IDR')
                    ->weight(FontWeight::Bold)
                    ->color(Color::Green),

                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(Color::Gray),

                Tables\Columns\ImageColumn::make('bukti')
                    ->label('Bukti')
                    ->height(40)
                    ->width(40)
                    ->circular()
                    ->defaultImageUrl(url('images/no-image.png'))
                    ->tooltip('Klik untuk memperbesar'),

                    // Di bagian columns()
Tables\Columns\BadgeColumn::make('statusCucian.nama_status')
    ->label('Status Cucian')
    ->formatStateUsing(fn ($state) => ucfirst($state)) // Format tampilan
    ->colors([
        'gray' => fn ($state) => $state === 'diterima',
        'warning' => fn ($state) => $state === 'proses',
        'info' => fn ($state) => $state === 'selesai',
        'success' => fn ($state) => $state === 'diambil',
    ])
    ->icons([
        'heroicon-o-inbox-arrow-down' => fn ($state) => $state === 'diterima',
        'heroicon-o-arrow-path' => fn ($state) => $state === 'proses',
        'heroicon-o-check-circle' => fn ($state) => $state === 'selesai',
        'heroicon-o-check-badge' => fn ($state) => $state === 'diambil',
    ])
    ->sortable()
    ->searchable(),
    
                Tables\Columns\BadgeColumn::make('status_pembayaran')
                    ->label('Status')
                    ->colors([
                        'danger' => 'belum',
                        'success' => 'lunas',
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'belum',
                        'heroicon-o-check-circle' => 'lunas',
                    ])
                    ->enum([
                        'belum' => 'Belum Lunas',
                        'lunas' => 'Lunas',
                    ])
                    ->sortable(),
                    
                    

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->color(Color::Gray)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('metode')
                    ->label('Metode Pembayaran')
                    ->options([
                        'tunai' => 'Tunai',
                        'non_tunai' => 'Non Tunai',
                    ])
                    ->multiple(),

                SelectFilter::make('status_pembayaran')
                    ->label('Status Pembayaran')
                    ->options([
                        'belum' => 'Belum Lunas',
                        'lunas' => 'Lunas',
                    ])
                    ->multiple(),

                SelectFilter::make('paket_id')
                    ->label('Paket')
                    ->relationship('paket', 'nama')
                    ->multiple(),

                Filter::make('tanggal')
                    ->form([
                        DatePicker::make('tanggal_dari')
                            ->label('Dari Tanggal'),
                        DatePicker::make('tanggal_sampai')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['tanggal_dari'], fn (Builder $query, $date): Builder => $query->whereDate('tanggal', '>=', $date))
                            ->when($data['tanggal_sampai'], fn (Builder $query, $date): Builder => $query->whereDate('tanggal', '<=', $date));
                    }),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make()
                        ->color('info'),
                    EditAction::make()
                        ->color('warning'),
                    DeleteAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Transaksi')
                        ->modalDescription('Apakah Anda yakin ingin menghapus transaksi ini? Tindakan ini tidak dapat dibatalkan.')
                        ->modalSubmitActionLabel('Ya, Hapus'),
                ])
                ->label('Aksi')
                ->color('primary')
                ->icon('heroicon-m-ellipsis-vertical')
                ->size('sm')
                ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Transaksi Terpilih')
                        ->modalDescription('Apakah Anda yakin ingin menghapus transaksi yang dipilih?'),
                    
                    Tables\Actions\BulkAction::make('mark_as_paid')
                        ->label('Tandai Lunas')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['status_pembayaran' => 'lunas']);
                            });
                            
                            Notification::make()
                                ->title('Berhasil')
                                ->body('Transaksi berhasil ditandai sebagai lunas')
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Buat Transaksi Pertama')
                    ->icon('heroicon-o-plus'),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransaksis::route('/'),
            'create' => Pages\CreateTransaksi::route('/create'),
            'edit' => Pages\EditTransaksi::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status_pembayaran', 'belum')->count();
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('status_pembayaran', 'belum')->count() > 0 ? 'danger' : 'success';
    }
}