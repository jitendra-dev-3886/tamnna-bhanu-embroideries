<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use Illuminate\Console\Command;

class ActivityLogClone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activity_log:prune {--days=2 : The number of days to retain Activity Log data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune stale entries from the Activity Log database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $reqDaySeconds = (86400 * $this->option('days'));// 86400 is single day no of seconds
        $currentTimestamp = time();

        $dateOfActivityLogRetain = \Carbon\Carbon::parse(\Carbon\Carbon::createFromTimestamp(($currentTimestamp-$reqDaySeconds),'UTC')
            ->toDateTimeString())->format('Y-m-d H:i:s');

        ActivityLog::where('created_at','<=',$dateOfActivityLogRetain)->forceDelete();
    }
}
