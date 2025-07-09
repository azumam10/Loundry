<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaketResource\Pages;
use App\Filament\Admin\Resources\PaketResource\RelationManagers;
use App\Models\Paket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section; // Import Section
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ActionGroup; // Import ActionGroup
use Filament\Tables\Actions\EditAction; // Import EditAction
use Filament\Tables\Actions\DeleteAction; // Import DeleteAction
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Enums\FontWeight; // Import FontWeight
use Filament\Support\Colors\Color; // Import Color

class PaketResource extends Resource
{
    protected static ?string $model = Paket::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-open';

    // Tambahkan label navigasi dan model jika diperlukan
    protected static ?string $navigationLabel = 'Daftar Paket';
    protected static ?string $modelLabel = 'Paket Laundry';
    protected static ?string $pluralModelLabel = 'Daftar Paket';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Detail Paket') // Gunakan Section untuk mengelompokkan
                    ->description('Informasi lengkap mengenai paket laundry yang ditawarkan.')
                    ->icon('heroicon-o-cube') // Icon untuk section
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Paket') // Label yang lebih deskriptif
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Cuci Kering Express') // Placeholder
                            ->helperText('Nama unik untuk paket laundry.') // Helper text
                            ->prefixIcon('heroicon-o-tag'), // Icon di depan input nama

                        Forms\Components\TextInput::make('harga')
                            ->label('Harga Paket') // Label yang lebih deskriptif
                            ->required()
                            ->numeric()
                            ->inputMode('decimal') // Pastikan input numerik untuk desimal
                            ->prefix('Rp') // Tambahkan prefix "Rp"
                            ->suffix('/kg') // Contoh suffix, bisa disesuaikan
                            ->placeholder('Contoh: 10000') // Placeholder
                            ->helperText('Harga per satuan (misal: per kg, per potong).') // Helper text
                            ->prefixIcon('heroicon-o-currency-dollar'), // Icon di depan input harga
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Paket') // Label kolom
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium) // Ketebalan font
                    ->color(Color::Blue) // Warna teks
                    ->icon('heroicon-o-tag'), // Icon pada kolom

                Tables\Columns\TextColumn::make('harga')
                    ->label('Harga') // Label kolom
                    ->numeric()
                    ->sortable()
                    ->money('IDR') // Format sebagai mata uang Rupiah
                    ->color(Color::Green) // Warna teks
                    ->icon('heroicon-o-currency-dollar'), // Icon pada kolom

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada') // Label yang lebih mudah dipahami
                    ->dateTime('d M Y, H:i') // Format tanggal dan waktu yang lebih baik
                    ->sortable()
                    ->color(Color::Gray)
                    ->icon('heroicon-o-calendar')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui') // Label yang lebih mudah dipahami
                    ->dateTime('d M Y, H:i') // Format tanggal dan waktu yang lebih baik
                    ->sortable()
                    ->color(Color::Gray)
                    ->icon('heroicon-o-arrow-path')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Anda bisa menambahkan filter di sini di masa mendatang, misalnya berdasarkan rentang harga
            ])
            ->actions([
                ActionGroup::make([ // Mengelompokkan aksi dalam dropdown
                    EditAction::make()
                        ->color('warning') // Warna tombol edit
                        ->icon('heroicon-o-pencil'), // Icon edit
                    DeleteAction::make()
                        ->requiresConfirmation() // Konfirmasi sebelum menghapus
                        ->modalHeading('Hapus Paket') // Judul modal konfirmasi
                        ->modalDescription('Apakah Anda yakin ingin menghapus paket ini? Ini tidak dapat dibatalkan.') // Deskripsi modal
                        ->modalSubmitActionLabel('Ya, Hapus'), // Label tombol konfirmasi
                ])
                ->label('Aksi') // Label untuk action group
                ->color('primary') // Warna action group
                ->icon('heroicon-m-ellipsis-vertical') // Icon ellipsis vertikal
                ->size('sm')
                ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation() // Konfirmasi untuk bulk delete
                        ->modalHeading('Hapus Paket Terpilih')
                        ->modalDescription('Apakah Anda yakin ingin menghapus paket yang dipilih? Ini tidak dapat dibatalkan.'),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Buat Paket Pertama') // Label saat tabel kosong
                    ->icon('heroicon-o-plus'),
            ])
            ->defaultSort('created_at', 'desc') // Mengatur sorting default
            ->striped() // Memberi efek zebra stripe pada baris tabel
            ->paginated([10, 25, 50, 100]); // Opsi pagination
    }

    public static function getRelations(): array
    {
        return [
            // Anda bisa menambahkan relasi di sini jika ada
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPakets::route('/'),
            'create' => Pages\CreatePaket::route('/create'),
            'edit' => Pages\EditPaket::route('/{record}/edit'),
        ];
    }
}