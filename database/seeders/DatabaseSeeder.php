<?php

namespace Database\Seeders;

use App\Models\Deposit;
use App\Models\Expense;
use App\Models\LedgerEntry;
use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ────────────────────────────────────────────────────────────
        $admin = User::create([
            'name'     => 'Admin',
            'email'    => 'admin@sumity.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'status'   => 'active',
        ]);

        // ── Member data: name, email, deposits keyed by YYYY-MM-DD ──────────
        // Amounts exactly as per the spreadsheet. Date = 5th of each month.
        $membersData = [
            [
                'name'  => 'মোঃ গোলাম মোরতোজা',
                'email' => 'golam.mortoza@sumity.com',
                'deposits' => [
                    '2025-10-05' => 5000,
                    '2025-11-05' => 5000,
                    '2025-12-05' => 5000,
                    '2026-01-05' => 5000,
                    '2026-02-05' => 5000,
                ],
            ],
            [
                'name'  => 'পারভিন',
                'email' => 'parvin@sumity.com',
                'deposits' => [
                    '2025-10-05' => 5000,
                    '2025-11-05' => 5000,
                    '2025-12-05' => 5000,
                    '2026-01-05' => 5000,
                    '2026-02-05' => 5000,
                ],
            ],
            [
                'name'  => 'মোঃ সফিক উল্লাহ',
                'email' => 'shafiq.ullah@sumity.com',
                'deposits' => [
                    '2025-10-05' => 5100,
                    '2025-11-05' => 5100,
                    '2025-12-05' => 5100,
                    '2026-01-05' => 5100,
                ],
            ],
            [
                'name'  => 'সাহিদা আক্তার',
                'email' => 'sahida.akter@sumity.com',
                'deposits' => [
                    '2025-10-05' => 5100,
                    '2025-11-05' => 5100,
                    '2025-12-05' => 5100,
                    '2026-01-05' => 5100,
                ],
            ],
            [
                'name'  => 'সাবিয়া আক্তার',
                'email' => 'sabia.akter@sumity.com',
                'deposits' => [
                    '2025-10-05' => 5100,
                    '2025-11-05' => 5100,
                    '2025-12-05' => 5100,
                    '2026-01-05' => 5100,
                ],
            ],
            [
                'name'  => 'মালিয়া আক্তার',
                'email' => 'malia.akter@sumity.com',
                'deposits' => [
                    '2025-10-05' => 5100,
                    '2025-11-05' => 5100,
                    '2025-12-05' => 5100,
                    '2026-01-05' => 5100,
                ],
            ],
            [
                'name'  => 'বিলাল মৃধা',
                'email' => 'bilal.mridha@sumity.com',
                'deposits' => [
                    '2025-10-05' => 5100,
                    '2025-11-05' => 5100,
                    '2025-12-05' => 5100,
                    '2026-01-05' => 5100,
                ],
            ],
            [
                'name'  => 'মোঃ মোস্তাফিজুর রহমান সিদ্দিকী',
                'email' => 'mostafizur.siddiqui@sumity.com',
                'deposits' => [
                    '2025-10-05' => 5100,
                    '2025-11-05' => 5100,
                    '2025-12-05' => 5100,
                    '2026-01-05' => 5100,
                    '2026-02-05' => 5100,
                    '2026-03-05' => 5100,
                ],
            ],
            [
                'name'  => 'কানিজ ফাতেমা',
                'email' => 'kaniz.fatema@sumity.com',
                'deposits' => [
                    '2025-10-05' => 5100,
                    '2025-11-05' => 5100,
                    '2025-12-05' => 5100,
                    '2026-01-05' => 5100,
                    '2026-02-05' => 5100,
                    '2026-03-05' => 5100,
                ],
            ],
            [
                'name'  => 'মোঃ আবুল কালাম আজাদ',
                'email' => 'abul.kalam@sumity.com',
                'deposits' => [
                    '2025-10-05' => 5100,
                    '2025-11-05' => 5100,
                    '2025-12-05' => 5100,
                    '2026-01-05' => 5100,
                    '2026-02-05' => 5100,
                    '2026-03-05' => 5100,
                    '2026-04-05' => 5100,
                    '2026-05-05' => 5100,
                    '2026-06-05' => 5100,
                ],
            ],
            [
                'name'  => 'মোঃ মাহফুজুর রহমান',
                'email' => 'mahfuzur.rahman@sumity.com',
                'deposits' => [
                    '2025-10-05' => 5000,
                    '2025-11-05' => 5000,
                    '2025-12-05' => 5000,
                    '2026-01-05' => 5000,
                    '2026-02-05' => 5000,
                    '2026-03-05' => 5000,
                    '2026-04-05' => 19000,
                ],
            ],
            [
                'name'  => 'রিপন আহমেদ',
                'email' => 'ripon.ahmed@sumity.com',
                'deposits' => [
                    '2025-11-05' => 5000,
                    '2025-12-05' => 5000,
                    '2026-01-05' => 5500,
                    '2026-02-05' => 5000,
                    '2026-03-05' => 5500,
                    '2026-04-05' => 5000,
                    '2026-05-05' => 5000,
                ],
            ],
            [
                'name'  => 'মোঃ রকিবুল আলম',
                'email' => 'rakibul.alam@sumity.com',
                'deposits' => [
                    '2025-10-05' => 5100,
                    '2025-11-05' => 5100,
                    '2025-12-05' => 5100,
                    '2026-01-05' => 5100,
                    '2026-02-05' => 5100,
                ],
            ],
            [
                'name'  => 'মোঃ রবিন',
                'email' => 'robin@sumity.com',
                'deposits' => [
                    '2025-10-05' => 5000,
                    '2025-11-05' => 4000,
                    '2026-02-05' => 16500,
                ],
            ],
            [
                'name'  => 'লাকী আক্তার',
                'email' => 'lucky.akter@sumity.com',
                'deposits' => [
                    '2025-10-05' => 5000,
                    '2025-11-05' => 5000,
                ],
            ],
            [
                'name'  => 'মোঃ শরিফুল ইসলাম (K)',
                'email' => 'shariful.k@sumity.com',
                'deposits' => [
                    '2025-10-05' => 5100,
                    '2025-11-05' => 5100,
                    '2025-12-05' => 5100,
                    '2026-01-05' => 5100,
                    '2026-02-05' => 5100,
                    '2026-03-05' => 5100,
                    '2026-04-05' => 5100,
                    '2026-05-05' => 5100,
                    '2026-06-05' => 5100,
                ],
            ],
            [
                'name'  => 'মোছা. মৌসুমী সরকার',
                'email' => 'mousumi.sarkar@sumity.com',
                'deposits' => [
                    '2025-10-05' => 5100,
                    '2025-11-05' => 5100,
                    '2025-12-05' => 5100,
                    '2026-01-05' => 10200,
                    '2026-04-05' => 10200,
                ],
            ],
            [
                'name'  => 'নাজমুল হুদা',
                'email' => 'nazmul.sada@sumity.com',
                'deposits' => [
                    '2025-10-05' => 5100,
                    '2025-11-05' => 5100,
                    '2025-12-05' => 5100,
                    '2026-01-05' => 5100,
                    '2026-02-05' => 5100,
                    '2026-05-05' => 20400,
                ],
            ],
            [
                'name'  => 'শরিফুজ্জামান',
                'email' => 'sharifuzzaman@sumity.com',
                'deposits' => [
                    '2025-10-05' => 5100,
                    '2025-11-05' => 5100,
                    '2025-12-05' => 5100,
                    '2026-01-05' => 5100,
                    '2026-02-05' => 5100,
                    '2026-03-05' => 5100,
                    '2026-04-05' => 5100,
                    '2026-05-05' => 5100,
                    '2026-06-05' => 80100,
                ],
            ],
            [
                'name'  => 'মোঃ কামরুল ইসলাম',
                'email' => 'kamrul.islam@sumity.com',
                'deposits' => [
                    '2025-10-05' => 5100,
                    '2025-11-05' => 5100,
                    '2025-12-05' => 5100,
                    '2026-01-05' => 74900,
                    '2026-04-05' => 40000,
                ],
            ],
            [
                'name'  => 'মোঃ শরিফুল ইসলাম (P)',
                'email' => 'shariful.p@sumity.com',
                'deposits' => [
                    '2025-10-05' => 5100,
                    '2025-11-05' => 5100,
                    '2025-12-05' => 5100,
                    '2026-01-05' => 5100,
                    '2026-02-05' => 5100,
                    '2026-03-05' => 5100,
                    '2026-04-05' => 5100,
                    '2026-05-05' => 5100,
                    '2026-06-05' => 5100,
                ],
            ],
            [
                'name'  => 'নুরুল ইসলাম',
                'email' => 'nurul.islam@sumity.com',
                'deposits' => [
                    '2025-10-05' => 5100,
                    '2025-11-05' => 5100,
                    '2025-12-05' => 5100,
                    '2026-01-05' => 5100,
                    '2026-02-05' => 5100,
                    '2026-03-05' => 5100,
                    '2026-04-05' => 25100,
                    '2026-05-05' => 25100,
                ],
            ],
            [
                'name'  => 'জাহাঙ্গীর আলম',
                'email' => 'jahangir.alam@sumity.com',
                'deposits' => [
                    '2025-10-05' => 5100,
                    '2025-11-05' => 5100,
                    '2025-12-05' => 5100,
                    '2026-01-05' => 50000,
                    '2026-02-05' => 15100,
                    '2026-03-05' => 5100,
                    '2026-04-05' => 25100,
                    '2026-05-05' => 25100,
                    '2026-06-05' => 5100,
                ],
            ],
            [
                'name'  => 'সজিব খান',
                'email' => 'sajib.khan@sumity.com',
                'deposits' => [
                    '2025-10-05' => 5100,
                    '2025-11-05' => 5000,
                    '2025-12-05' => 5000,
                    '2026-01-05' => 5000,
                    '2026-02-05' => 5000,
                    '2026-03-05' => 5000,
                    '2026-05-05' => 110000,
                ],
            ],
            [
                'name'  => 'আব্দুর রাজ্জাক',
                'email' => 'abdur.razzak@sumity.com',
                'deposits' => [
                    '2025-10-05' => 5000,
                    '2025-11-05' => 5000,
                    '2025-12-05' => 5000,
                ],
            ],
            [
                'name'  => 'নাসির উদ্দিন',
                'email' => 'nasir.uddin@sumity.com',
                'deposits' => [
                    '2025-10-05' => 5100,
                    '2025-11-05' => 5100,
                    '2025-12-05' => 5100,
                    '2026-01-05' => 5100,
                    '2026-02-05' => 5100,
                ],
            ],
        ];

        // ── Insert members + deposits + ledger entries ───────────────────────
        foreach ($membersData as $i => $data) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make('password'),
                'role'     => 'member',
                'status'   => 'active',
            ]);

            $member = Member::create([
                'user_id'      => $user->id,
                'member_code'  => 'SMT-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'phone'        => '01736625062',
                'address'      => null,
                'join_date'    => '2025-10-01',
                'deposit_plan' => 'monthly',
                'status'       => 'active',
            ]);

            // Insert each month's deposit as approved + record ledger entry
            foreach ($data['deposits'] as $date => $amount) {
                $deposit = Deposit::create([
                    'member_id'      => $member->id,
                    'amount'         => $amount,
                    'date'           => $date,
                    'payment_method' => 'cash',
                    'status'         => 'approved',
                    'approved_by'    => $admin->id,
                    'approved_at'    => $date . ' 10:00:00',
                    'notes'          => null,
                ]);

                LedgerEntry::recordDeposit($deposit);
            }
        }
    }
}
