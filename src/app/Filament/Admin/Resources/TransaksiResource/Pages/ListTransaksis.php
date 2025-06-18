<?php

namespace App\Filament\Admin\Resources\TransaksiResource\Pages;

use App\Filament\Admin\Resources\TransaksiResource;
use App\Models\Transaksi;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Action;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class ListTransaksis extends ListRecords implements HasTable
{
    protected static string $resource = TransaksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID'),
                TextColumn::make('client.name')->label('Nama Client'),
                TextColumn::make('paket_id')->label('ID Paket'), // Nanti bisa diubah ke relasi paket.nama
                TextColumn::make('berat')->label('Berat (Kg)'),
                TextColumn::make('total')->label('Total')->money('IDR'),
                TextColumn::make('tanggal')->label('Tanggal')->date(),
                TextColumn::make('metode')->label('Metode'),
            ])
            ->actions([
                Action::make('cetak_struk')
                    ->label('Cetak Struk')
                    ->icon('heroicon-o-printer')
                    ->url(fn (Transaksi $record): string => route('transaksi.struk', $record->id))
                    ->openUrlInNewTab()
            ]);
    }
}
