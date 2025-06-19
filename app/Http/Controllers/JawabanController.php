<?php

namespace App\Http\Controllers;

use App\Models\Jawaban;
use App\Models\JawabansDetails;
use Illuminate\Http\Request;

class JawabanController extends Controller
{
    public function index()
    {
        $jawabans = Jawaban::with('dataDiri', 'details')->get();
        return view('jawaban.index', compact('jawabans'));
    }

    public function create()
    {
        return view('jawaban.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'data_diri_id' => 'required|exists:data_diris,id',
            'total_skor' => 'required|integer|min:0',
            'details' => 'required|array',
            'details.*.nomor_soal' => 'required|integer|min:1|max:38',
            'details.*.skor' => 'required|integer|min:1|max:6',
        ]);

        $jawaban = Jawaban::create([
            'data_diri_id' => $validated['data_diri_id'],
            'total_skor' => $validated['total_skor'],
        ]);

        foreach ($validated['details'] as $detail) {
            $jawaban->details()->create($detail);
        }

        return redirect()->route('jawaban.index')->with('success', 'Jawaban berhasil disimpan.');
    }

    public function show(Jawaban $jawaban)
    {
        $jawaban->load('details', 'dataDiri');
        return view('jawaban.show', compact('jawaban'));
    }

    public function edit(Jawaban $jawaban)
    {
        $jawaban->load('details');
        return view('jawaban.edit', compact('jawaban'));
    }

    public function update(Request $request, Jawaban $jawaban)
    {
        $validated = $request->validate([
            'total_skor' => 'required|integer|min:0',
            'details' => 'required|array',
            'details.*.id' => 'sometimes|exists:jawabans_details,id',
            'details.*.nomor_soal' => 'required|integer|min:1|max:38',
            'details.*.skor' => 'required|integer|min:1|max:6',
        ]);

        $jawaban->update([
            'total_skor' => $validated['total_skor'],
        ]);

        // Update or create details
        foreach ($validated['details'] as $detail) {
            if (isset($detail['id'])) {
                $jawaban->details()->where('id', $detail['id'])->update([
                    'nomor_soal' => $detail['nomor_soal'],
                    'skor' => $detail['skor'],
                ]);
            } else {
                $jawaban->details()->create($detail);
            }
        }

        return redirect()->route('jawaban.index')->with('success', 'Jawaban berhasil diperbarui.');
    }

    public function destroy(Jawaban $jawaban)
    {
        $jawaban->delete();
        return redirect()->route('jawaban.index')->with('success', 'Jawaban berhasil dihapus.');
    }
}
