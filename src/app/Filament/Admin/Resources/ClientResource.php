<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ClientResource\Pages;
use App\Filament\Admin\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Colors\Color;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationLabel = 'Pelanggan';
    
    protected static ?string $modelLabel = 'Pelanggan';
    
    protected static ?string $pluralModelLabel = 'Pelanggan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Pelanggan')
                    ->description('Masukkan data lengkap pelanggan laundry')
                    ->icon('heroicon-o-user-circle')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Lengkap')
                                    ->required()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-user')
                                    ->placeholder('Masukkan nama lengkap pelanggan')
                                    ->helperText('Nama akan digunakan untuk identifikasi transaksi'),

                                Forms\Components\TextInput::make('kontak')
                                    ->label('Nomor Telepon')
                                    ->required()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-phone')
                                    ->placeholder('+62 812-3456-7890')
                                    ->helperText('Nomor telepon aktif untuk komunikasi'),
                            ]),

                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->required()
                            ->maxLength(255)
                            ->rows(3)
                            // ->prefixIcon('heroicon-o-map-pin') // BARIS INI DIHAPUS ATAU DIKOMENTARI
                            ->placeholder('Masukkan alamat lengkap pelanggan...')
                            ->helperText('Alamat akan digunakan untuk pengiriman jika diperlukan')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pelanggan')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium)
                    ->color(Color::Blue)
                    ->icon('heroicon-o-user'),

                Tables\Columns\TextColumn::make('kontak')
                    ->label('Nomor Telepon')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-phone')
                    ->color(Color::Green),

                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(function ($record) {
                        return $record->alamat;
                    })
                    ->icon('heroicon-o-map-pin')
                    ->color(Color::Gray),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Bergabung')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->color(Color::Gray)
                    ->icon('heroicon-o-calendar')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Update')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->color(Color::Gray)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make()
                        ->color('warning')
                        ->icon('heroicon-o-pencil'),
                    DeleteAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Pelanggan')
                        ->modalDescription('Apakah Anda yakin ingin menghapus pelanggan ini?')
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
                        ->modalHeading('Hapus Pelanggan Terpilih')
                        ->modalDescription('Apakah Anda yakin ingin menghapus pelanggan yang dipilih?'),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Pelanggan Pertama')
                    ->icon('heroicon-o-plus'),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
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
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}