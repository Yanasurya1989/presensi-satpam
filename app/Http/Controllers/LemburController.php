<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Lembur;
use Illuminate\Http\Request;
use App\Exports\LemburExport;
use Maatwebsite\Excel\Facades\Excel;

class LemburController extends Controller
{
    public function indexHapusIniKloIndexBawahBisa()
    {

        $lemburs = Lembur::with('user')
            ->where('user_id', auth()->id())
            ->orderBy('tanggal', 'desc')
            ->get();

        // Untuk admin bisa ambil semua, tanpa where user_id

        return view('lembur.index', compact('lemburs'));
    }

    public function indexPerbaikan()
    {
        if (auth()->user()->role->name === 'Kabid 4') {
            // Ambil semua data lembur bulan ini
            $semuaLembur = Lembur::with('user')
                ->whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->get();

            return view('lembur.index_admin', compact('semuaLembur'));
        }

        // Untuk user biasa: rekap total lembur per user
        $lemburs = Lembur::with('user')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->get()
            ->groupBy('user_id')
            ->map(function ($item) {
                return [
                    'user' => $item->first()->user,
                    'total' => $item->sum('total_lembur'),
                ];
            });

        return view('lembur.index_admin', compact('lemburs'));
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->role->name === 'Kabid 4') {
            $query = Lembur::with('user');

            // Filter tanggal
            if ($request->filled('from')) {
                $query->whereDate('tanggal', '>=', $request->from);
            }
            if ($request->filled('to')) {
                $query->whereDate('tanggal', '<=', $request->to);
            }

            // Filter user
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            $semuaLembur = $query->get();
            $users = \App\Models\User::all();
            $totalLemburMenit = $semuaLembur->sum('total_lembur');

            return view('lembur.index_admin', [
                'role' => 'Kabid 4',
                'semuaLembur' => $semuaLembur,
                'users' => $users,
                'request' => $request,
                'totalLemburMenit' => $totalLemburMenit,
            ]);
        }

        // Untuk user biasa tetap seperti sebelumnya
        $lemburUser = Lembur::with('user')
            ->where('user_id', $user->id)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->get();

        $totalLembur = $lemburUser->sum('total_lembur');
        return view('lembur.index_user', [
            'role' => 'user',
            'lemburUser' => $lemburUser,
            'totalLembur' => $totalLembur,
        ]);
    }

    public function create()
    {
        $users = User::all();
        return view('lembur.create', compact('users'));
    }

    public function edit($id)
    {
        $lembur = Lembur::findOrFail($id);
        $users = User::all();
        return view('lembur.edit', compact('lembur', 'users'));
    }

    private function calculateTotalLembur(array $data)
    {
        $totalMenit = 0;

        // Fungsi untuk hitung durasi menit dari dua waktu format H:i
        $calcDurasi = function ($mulai, $akhir) {
            if (!$mulai || !$akhir) return 0;

            $start = \DateTime::createFromFormat('H:i', $mulai);
            $end = \DateTime::createFromFormat('H:i', $akhir);

            if ($end < $start) {
                // Jika jam akhir lebih kecil (misal lembur melewati tengah malam)
                $end->modify('+1 day');
            }

            return ($end->getTimestamp() - $start->getTimestamp()) / 60;
        };

        $totalMenit += $calcDurasi($data['mulai_lembur_satu'] ?? null, $data['akhir_lembur_satu'] ?? null);
        $totalMenit += $calcDurasi($data['mulai_lembur_dua'] ?? null, $data['akhir_lembur_dua'] ?? null);
        $totalMenit += $calcDurasi($data['mulai_lembur_tiga'] ?? null, $data['akhir_lembur_tiga'] ?? null);

        return (int)$totalMenit;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'mulai_lembur_satu' => 'nullable|date_format:H:i',
            'akhir_lembur_satu' => 'nullable|date_format:H:i',
            'mulai_lembur_dua' => 'nullable|date_format:H:i',
            'akhir_lembur_dua' => 'nullable|date_format:H:i',
            'mulai_lembur_tiga' => 'nullable|date_format:H:i',
            'akhir_lembur_tiga' => 'nullable|date_format:H:i',
        ]);

        $total = $this->calculateTotalLembur($validated);
        $validated['total_lembur'] = $total;

        Lembur::create($validated);

        return redirect()->route('lembur.index')->with('success', 'Data lembur berhasil ditambahkan.');
    }

    public function updateProsesGantiBagianUpdateViewPersonal(Request $request, Lembur $lembur)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'mulai_lembur_satu' => 'nullable|date_format:H:i',
            'akhir_lembur_satu' => 'nullable|date_format:H:i',
            'mulai_lembur_dua' => 'nullable|date_format:H:i',
            'akhir_lembur_dua' => 'nullable|date_format:H:i',
            'mulai_lembur_tiga' => 'nullable|date_format:H:i',
            'akhir_lembur_tiga' => 'nullable|date_format:H:i',
        ]);

        $total = $this->calculateTotalLembur($validated);
        $validated['total_lembur'] = $total;

        $lembur->update($validated);

        return redirect()->route('lembur.index')->with('success', 'Data lembur berhasil diupdate.');
    }

    public function update(Request $request, Lembur $lembur)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'mulai_lembur_satu' => 'nullable|date_format:H:i',
            'akhir_lembur_satu' => 'nullable|date_format:H:i',
            'mulai_lembur_dua' => 'nullable|date_format:H:i',
            'akhir_lembur_dua' => 'nullable|date_format:H:i',
            'mulai_lembur_tiga' => 'nullable|date_format:H:i',
            'akhir_lembur_tiga' => 'nullable|date_format:H:i',
        ]);

        $total = $this->calculateTotalLembur($validated);
        $validated['total_lembur'] = $total;

        $lembur->update($validated);

        return redirect()->route('lembur.index_user')->with('success', 'Data lembur berhasil diupdate.');
    }

    public function destroy(Lembur $lembur)
    {
        $lembur->delete();
        return redirect()->route('lembur.index')->with('success', 'Data lembur berhasil dihapus.');
    }

    public function export(Request $request)
    {
        // Proses filter sama seperti di index()
        $query = Lembur::with('user');

        if ($request->filled('from')) {
            $query->whereDate('tanggal', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('tanggal', '<=', $request->to);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $filteredData = $query->get();

        return Excel::download(new LemburExport($filteredData), 'rekap_lembur.xlsx');
    }

    public function show($id)
    {
        $lembur = Lembur::with('user')->findOrFail($id);
        return view('lembur.show', compact('lembur'));
    }
}
