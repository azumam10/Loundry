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
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BadgeColumn;

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
                TextColumn::make('paket_id')->label('ID Paket'),
                TextColumn::make('berat')->label('Berat (Kg)'),
                TextColumn::make('total')->label('Total')->money('IDR'),
                TextColumn::make('tanggal')->label('Tanggal')->date(),
                TextColumn::make('metode')->label('Metode'),

                // 🔽 Kolom Status Pembayaran
                BadgeColumn::make('status_pembayaran')
                    ->label('Status')
                    ->colors([
                        'success' => 'lunas',
                        'danger' => 'belum',
                    ]),

                // 🔽 Gambar Bukti Transfer
                ImageColumn::make('bukti')->label('Bukti TF')
                    ->height(50)
                    ->width(50),
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
