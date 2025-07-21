<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;


class TransaksiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/transaksis",
     *     tags={"Transaksis"},
     *     summary="Ambil semua transaksi",
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\Response(response=200, description="Sukses")
     * )
     */
    public function index()
    {
        return response()->json(Transaksi::with(['client', 'paket'])->get());
    }

    /**
     * @OA\Post(
     *     path="/api/transaksis",
     *     tags={"Transaksis"},
     *     summary="Buat transaksi baru",
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"metode", "client_id", "paket_id", "harga", "berat", "total", "tanggal"},
     *             @OA\Property(property="metode", type="string", example="cash"),
     *             @OA\Property(property="client_id", type="integer", example=1),
     *             @OA\Property(property="paket_id", type="integer", example=2),
     *             @OA\Property(property="harga", type="integer", example=10000),
     *             @OA\Property(property="berat", type="integer", example=5),
     *             @OA\Property(property="total", type="integer", example=50000),
     *             @OA\Property(property="tanggal", type="string", format="date", example="2025-07-21"),
     *             @OA\Property(property="bukti", type="string", format="binary", nullable=true),
     *             @OA\Property(property="status_cucian", type="string", example="proses")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Berhasil dibuat")
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'metode' => 'required|string',
            'client_id' => 'required|exists:clients,id',
            'paket_id' => 'required|exists:pakets,id',
            'harga' => 'required|integer',
            'berat' => 'required|integer',
            'total' => 'required|integer',
            'tanggal' => 'required|date',
            'bukti' => 'nullable|string', // bisa juga file base64 atau path
            'status_cucian' => 'nullable|string',
        ]);

        $transaksi = Transaksi::create($data);

        return response()->json($transaksi, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/transaksis/{id}",
     *     tags={"Transaksis"},
     *     summary="Lihat detail transaksi",
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Sukses")
     * )
     */
    public function show($id)
    {
        return response()->json(Transaksi::with(['client', 'paket'])->findOrFail($id));
    }

    /**
     * @OA\Put(
     *     path="/api/transaksis/{id}",
     *     tags={"Transaksis"},
     *     summary="Update transaksi",
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="metode", type="string", example="cash"),
     *             @OA\Property(property="client_id", type="integer", example=1),
     *             @OA\Property(property="paket_id", type="integer", example=2),
     *             @OA\Property(property="harga", type="integer", example=10000),
     *             @OA\Property(property="berat", type="integer", example=5),
     *             @OA\Property(property="total", type="integer", example=50000),
     *             @OA\Property(property="tanggal", type="string", format="date", example="2025-07-21"),
     *             @OA\Property(property="bukti", type="string", nullable=true),
     *             @OA\Property(property="status_cucian", type="string", example="selesai")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Berhasil diperbarui")
     * )
     */
    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update($request->all());
        return response()->json($transaksi);
    }

    /**
     * @OA\Delete(
     *     path="/api/transaksis/{id}",
     *     tags={"Transaksis"},
     *     summary="Hapus transaksi",
     *     security={{"ApiKeyAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Berhasil dihapus")
     * )
     */
    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();
        return response()->json(null, 204);
    }
}
