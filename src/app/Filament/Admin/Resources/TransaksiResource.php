<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TransaksiResource\Pages;
use App\Filament\Admin\Resources\TransaksiResource\RelationManagers;
use App\Models\Transaksi;
use App\Models\Paket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('metode')
                    ->label('Metode Pembayaran')
                    ->options([
                        'tunai' => 'Tunai',
                        'non_tunai' => 'Non Tunai'
                    ])
                    ->required(),

                Select::make('client_id')
                    ->label('Client')
                    ->relationship('client', 'name')
                    ->required(),

                Select::make('paket_id')
                    ->label('Paket')
                    ->relationship('paket', 'nama')
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $harga = Paket::find($state)?->harga ?? 0;
                        $set('harga', $harga); // Set harga di state
                    })
                    ->required(),

                // Tambahkan field harga tersembunyi
                Forms\Components\TextInput::make('harga')
                    ->label('Harga Paket')
                    ->readonly() // Tidak bisa diubah secara manual
                    ->reactive()
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('berat')
                    ->reactive()
                    ->required()
                    ->numeric()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        $total = ($state ?? 0) * ($get('harga') ?? 0);
                        $set('total', $total);
                    }),

                Forms\Components\TextInput::make('total')
                    ->label('Total')
                    ->readonly()
                    ->numeric(),

                DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->required(),

                Forms\Components\FileUpload::make('bukti')
                    ->image(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('metode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('client.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('paket.nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('berat')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bukti')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListTransaksis::route('/'),
            'create' => Pages\CreateTransaksi::route('/create'),
            'edit' => Pages\EditTransaksi::route('/{record}/edit'),
        ];
    }
}
