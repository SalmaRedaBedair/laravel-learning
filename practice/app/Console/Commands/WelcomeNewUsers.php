<?php

namespace App\Console\Commands;

use App\Mail\UserMailer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class WelcomeNewUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:newusers {userId} {--sendEmail}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'password:reset {userId : The ID of the user} {--sendEmail : Whether to send user an email}';

    public function __construct(UserMailer $userMailer)
    {
        parent::__construct();
        $this->userMailer = $userMailer;
    }

    public function handle(): void
    {
//        $winner = $this->choice(
//            'Who is the best football team?',
//            ['Gators', 'Wolverines'],
//            0
//        );
//
//        $this->info($winner);
//        $this->userMailer->build();
//        $totalUnits = 100;
////        $this->output->progressStart($totalUnits);
////        for ($i = 0; $i < $totalUnits; $i++) {
////            sleep(1);
////            $this->output->progressAdvance();
////        }
////        $this->output->progressFinish();

        $exitCode = Artisan::call('password:reset', [
            'userId' => 15,
            '--sendEmail' => true,
        ]);
        $this->info($exitCode);
    }
}
