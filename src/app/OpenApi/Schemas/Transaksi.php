<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="Transaksi",
 *     type="object",
 *     title="Transaksi",
 *     required={"id", "metode", "client_id", "paket_id", "harga", "berat", "total", "tanggal"},
 *     
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="metode", type="string", example="cash"),
 *     @OA\Property(property="client_id", type="integer", example=1),
 *     @OA\Property(property="paket_id", type="integer", example=2),
 *     @OA\Property(property="harga", type="integer", example=10000),
 *     @OA\Property(property="berat", type="integer", example=5),
 *     @OA\Property(property="total", type="integer", example=50000),
 *     @OA\Property(property="tanggal", type="string", format="date", example="2025-07-21"),
 *     @OA\Property(property="bukti", type="string", nullable=true, example="bukti_transfer.jpg"),
 *     @OA\Property(property="status_cucian", type="string", nullable=true, example="proses"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-21T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-21T12:10:00Z")
 * )
 */
class Transaksi {}
