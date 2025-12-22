<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LiveDropController extends Controller
{
    public function show()
    {
        $row = DB::table('live_drop_settings')->orderByDesc('id')->first();
        if (!$row) {
            $uuid = (string) Str::uuid();
            DB::table('live_drop_settings')->insert([
                'uuid' => $uuid,
                'hero_title' => 'Buka Bal Selanjutnya',
                'hero_description' => 'Jangan lewatkan sesi buka bal eksklusif kami via TikTok Live dengan penawaran spesial!',
                'event_title' => 'Buka Bal Spesial Kaos Band Vintage',
                'event_subtitle' => 'Sabtu, 15 Juli 2023 - Pukul 20:00 WIB',
                'event_date_time' => now()->addDays(7),
                'cta_label' => 'Ingatkan Saya',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $row = DB::table('live_drop_settings')->where('uuid', $uuid)->first();
        }

        return response()->json(['data' => $row]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_description' => ['nullable', 'string'],
            'event_title' => ['nullable', 'string', 'max:255'],
            'event_subtitle' => ['nullable', 'string', 'max:255'],
            'event_date_time' => ['nullable', 'date'],
            'cta_label' => ['nullable', 'string', 'max:255'],
        ]);

        $row = DB::table('live_drop_settings')->orderByDesc('id')->first();
        if (!$row) {
            $this->show();
            $row = DB::table('live_drop_settings')->orderByDesc('id')->first();
        }

        DB::table('live_drop_settings')->where('id', $row->id)->update(array_merge($data, [
            'updated_at' => now(),
        ]));

        return response()->json(['data' => DB::table('live_drop_settings')->where('id', $row->id)->first()]);
    }
}
