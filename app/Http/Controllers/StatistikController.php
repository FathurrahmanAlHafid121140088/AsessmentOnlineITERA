<?php

namespace App\Http\Controllers;

use App\Models\DataDiris;
use Illuminate\Http\Request;

class StatistikController extends Controller
{
    public function totalUsers()
    {
        // Hitung total NIM unik yang memiliki hasil kuesioner
        $total = DataDiris::whereHas('hasilKuesioners')
            ->distinct('nim')
            ->count('nim');

        return response()->json([
            'total_users' => $total
        ]);
    }
}
