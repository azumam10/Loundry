<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PegawaiResource\Pages;
use App\Filament\Admin\Resources\PegawaiResource\RelationManagers;
use App\Models\Pegawai;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section; // Import Section
use Filament\Forms\Components\Grid;    // Import Grid
use Filament\Forms\Components\Textarea; // Import Textarea untuk alamat
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ActionGroup; // Import ActionGroup
use Filament\Tables\Actions\EditAction;  // Import EditAction
use Filament\Tables\Actions\DeleteAction; // Import DeleteAction
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Enums\FontWeight; // Import FontWeight
use Filament\Support\Colors\Color;     // Import Color

class PegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;

    protected static ?string $navigationIcon = 'heroicon-o-users'; // Ganti ikon agar lebih sesuai

    // Tambahkan label navigasi dan model
    protected static ?string $navigationLabel = 'Manajemen Pegawai';
    protected static ?string $modelLabel = 'Pegawai';
    protected static ?string $pluralModelLabel = 'Pegawai';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Pegawai') // Gunakan Section untuk mengelompokkan input
                    ->description('Masukkan data lengkap detail pegawai.')
                    ->icon('heroicon-o-user-group') // Icon untuk section
                    ->schema([
                        Grid::make(2) // Gunakan Grid untuk layout 2 kolom
                            ->schema([
                                Forms\Components\TextInput::make('nama')
                                    ->label('Nama Lengkap') // Label yang lebih deskriptif
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Contoh: Budi Santoso') // Placeholder
                                    ->helperText('Nama lengkap pegawai.') // Helper text
                                    ->prefixIcon('heroicon-o-user'), // Icon di depan input nama

                                Forms\Components\TextInput::make('kontak') // Ganti urutan kontak di sini agar sejajar dengan nama
                                    ->label('Nomor Telepon')
                                    ->required()
                                    ->maxLength(255)
                                    ->tel() // Mengatur input sebagai tipe telepon
                                    ->placeholder('Contoh: 081234567890')
                                    ->helperText('Nomor telepon aktif pegawai.')
                                    ->prefixIcon('heroicon-o-phone'),
                            ]),

                        Textarea::make('alamat') // Gunakan Textarea untuk alamat
                            ->label('Alamat Lengkap')
                            ->required()
                            ->maxLength(255)
                            ->rows(3) // Tinggi textarea
                            ->placeholder('Contoh: Jl. Merdeka No. 10, Jakarta Pusat')
                            ->helperText('Alamat domisili pegawai.')
                            ->columnSpanFull(), // Agar mengambil lebar penuh di grid

                        Forms\Components\TextInput::make('posisi')
                            ->label('Posisi Jabatan')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Kasir')
                            ->helperText('Jabatan atau posisi pegawai saat ini.')
                            ->prefixIcon('heroicon-o-briefcase'), // Icon untuk posisi
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Pegawai')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium) // Ketebalan font
                    ->color(Color::Blue) // Warna teks
                    ->icon('heroicon-o-user'), // Icon pada kolom

                Tables\Columns\TextColumn::make('posisi') // Tampilkan posisi setelah nama
                    ->label('Posisi')
                    ->searchable()
                    ->sortable()
                    ->color(Color::Teal) // Warna berbeda untuk posisi
                    ->icon('heroicon-o-briefcase'),

                Tables\Columns\TextColumn::make('kontak')
                    ->label('Nomor Telepon')
                    ->searchable()
                    ->icon('heroicon-o-phone')
                    ->color(Color::Green),

                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->searchable()
                    ->limit(50) // Batasi panjang teks untuk tampilan tabel
                    ->tooltip(function ($record) { // Tampilkan alamat lengkap saat dihover
                        return $record->alamat;
                    })
                    ->icon('heroicon-o-map-pin')
                    ->color(Color::Gray),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y, H:i') // Format tanggal dan waktu yang lebih baik
                    ->sortable()
                    ->color(Color::Gray)
                    ->icon('heroicon-o-calendar')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->color(Color::Gray)
                    ->icon('heroicon-o-arrow-path')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Anda bisa menambahkan filter di sini di masa mendatang, misalnya berdasarkan posisi
            ])
            ->actions([
                ActionGroup::make([ // Mengelompokkan aksi dalam dropdown
                    EditAction::make()
                        ->color('warning')
                        ->icon('heroicon-o-pencil'),
                    DeleteAction::make()
                        ->requiresConfirmation() // Konfirmasi sebelum menghapus
                        ->modalHeading('Hapus Pegawai') // Judul modal konfirmasi
                        ->modalDescription('Apakah Anda yakin ingin menghapus data pegawai ini? Tindakan ini tidak dapat dibatalkan.') // Deskripsi modal
                        ->modalSubmitActionLabel('Ya, Hapus'), // Label tombol konfirmasi
                ])
                ->label('Aksi') // Label untuk action group
                ->color('primary')
                ->icon('heroicon-m-ellipsis-vertical') // Icon ellipsis vertikal
                ->size('sm')
                ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation() // Konfirmasi untuk bulk delete
                        ->modalHeading('Hapus Pegawai Terpilih')
                        ->modalDescription('Apakah Anda yakin ingin menghapus pegawai yang dipilih? Tindakan ini tidak dapat dibatalkan.'),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Pegawai Baru') // Label saat tabel kosong
                    ->icon('heroicon-o-plus'),
            ])
            ->defaultSort('created_at', 'desc') // Mengatur sorting default
            ->striped() // Memberi efek zebra stripe pada baris tabel
            ->paginated([10, 25, 50, 100]); // Opsi pagination
    }

    public static function getRelations(): array
    {
        return [
            // Anda bisa menambahkan relasi di sini jika ada, misalnya ke transaksi yang ditangani pegawai
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPegawais::route('/'),
            'create' => Pages\CreatePegawai::route('/create'),
            'edit' => Pages\EditPegawai::route('/{record}/edit'),
        ];
    }
}