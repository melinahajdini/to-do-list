<?php

namespace App\Console\Commands;

use App\Models\ToDo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class DeletedNotes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notes:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $date = now()->format('y.m.d');
        $expiredNotes = DB::table('todo-list')
            ->select('users.email')
            ->join('users','todo-list.user_id','=','users.id')
            ->where('todo-list.deletion_date', '=', $date)
            ->get();
        foreach ($expiredNotes as $note){
            $email = $note->email;
            Mail::to($email)
                ->send(new \App\Mail\DeletedNotes());
            ToDo::where('deletion_date', '=', $date)->delete();

        }
      return  0;
    }
}
