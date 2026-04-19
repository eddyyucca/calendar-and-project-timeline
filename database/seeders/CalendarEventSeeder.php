<?php

namespace Database\Seeders;

use App\Models\CalendarEvent;
use Illuminate\Database\Seeder;

class CalendarEventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            ['2026-01-01', 'Tahun Baru 2026 Masehi', 'holiday'],
            ['2026-01-16', "Isra Mikraj Nabi Muhammad SAW", 'holiday'],
            ['2026-02-16', 'Cuti Bersama Tahun Baru Imlek 2577 Kongzili', 'leave'],
            ['2026-02-17', 'Tahun Baru Imlek 2577 Kongzili', 'holiday'],
            ['2026-03-18', 'Cuti Bersama Hari Suci Nyepi', 'leave'],
            ['2026-03-19', 'Hari Suci Nyepi Tahun Baru Saka 1948', 'holiday'],
            ['2026-03-20', 'Cuti Bersama Idul Fitri 1447 Hijriah', 'leave'],
            ['2026-03-21', 'Idul Fitri 1447 Hijriah', 'holiday'],
            ['2026-03-22', 'Idul Fitri 1447 Hijriah', 'holiday'],
            ['2026-03-23', 'Cuti Bersama Idul Fitri 1447 Hijriah', 'leave'],
            ['2026-03-24', 'Cuti Bersama Idul Fitri 1447 Hijriah', 'leave'],
            ['2026-04-03', 'Wafat Yesus Kristus', 'holiday'],
            ['2026-04-05', 'Kebangkitan Yesus Kristus / Paskah', 'holiday'],
            ['2026-05-01', 'Hari Buruh Internasional', 'holiday'],
            ['2026-05-14', 'Kenaikan Yesus Kristus', 'holiday'],
            ['2026-05-15', 'Cuti Bersama Kenaikan Yesus Kristus', 'leave'],
            ['2026-05-27', 'Idul Adha 1447 Hijriah', 'holiday'],
            ['2026-05-28', 'Cuti Bersama Idul Adha 1447 Hijriah', 'leave'],
            ['2026-05-31', 'Hari Raya Waisak 2570 BE', 'holiday'],
            ['2026-06-01', 'Hari Lahir Pancasila', 'holiday'],
            ['2026-06-16', 'Tahun Baru Islam 1448 Hijriah', 'holiday'],
            ['2026-08-17', 'Proklamasi Kemerdekaan RI', 'holiday'],
            ['2026-08-25', 'Maulid Nabi Muhammad SAW', 'holiday'],
            ['2026-12-24', 'Cuti Bersama Kelahiran Yesus Kristus', 'leave'],
            ['2026-12-25', 'Kelahiran Yesus Kristus', 'holiday'],
        ];

        foreach ($events as [$date, $title, $type]) {
            CalendarEvent::updateOrCreate(
                [
                    'event_date' => $date,
                    'title' => $title,
                ],
                [
                    'type' => $type,
                    'description' => 'Hari libur nasional/cuti bersama Indonesia 2026 berdasarkan SKB 3 Menteri.',
                    'is_national_holiday' => true,
                ]
            );
        }
    }
}
