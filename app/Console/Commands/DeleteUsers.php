<?php

namespace App\Console\Commands;

use App\Services\ApigeeService;
use App\User;
use App\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Null_;

class DeleteUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command delete users linked to MTN Developer Portal and APIGEE';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        return $this->deleteUsers();
    }

    /**
     * @return void
     */
    public function deleteUsers()
    {
        // Get the current year
        $currentYear = Carbon::now()->year;
        $yesterday = Carbon::yesterday();

        $numberOfYears = $this->ask('How many years back do you want to delete non-verified users?');

        // Generate an array of years based on user input
        $years = [];
        for ($i = $currentYear; $i >= $currentYear - $numberOfYears; $i--) {
            $years[] = (string) $i;
        }

        // Allow the user to choose from the generated array of years
        $choice_of_year = $this->choice(
            'From which year do you want to delete users that are not verified?',
            $years
        );

        // Get users that are not verified and registered between the day before yesterday and yesterday
        $nonVerifiedUsers = User::whereNull('email_verified_at')
            ->whereBetween('created_at', [$choice_of_year.'-01-01', $yesterday])
            ->get();

        $nonVerifiedUserCount = $nonVerifiedUsers->count();

        $skippedModels = []; // Store the model names that are skipped

        $deleteConfirmation = $this->confirm('Are you sure you want to delete ' . $nonVerifiedUserCount . ' non-verified users');

        if ($deleteConfirmation) {
            $this->info('Starting to delete users...');

            $deletedUserCount = 0;

            foreach ($nonVerifiedUsers as $user) {

                try {
                    if($user->notifications()){
                        foreach($user->notifications as $notification){
                            $notification->delete();
                        }
                    }
    
                    if($user->authentications()){
                        foreach($user->authentications as $authLog){
                            $authLog->delete();
                        }
                    }
    
                    if($user->assignedProducts()){
                        $user->assignedProducts()->detach();
                    }
    
                    if($user->roles()){
                        $user->roles()->detach();
                    }    

                    ApigeeService::deleteUser($user);
                    $user->delete(); // Delete non-verified user
                    $deletedUserCount++;

                } catch (\Illuminate\Database\QueryException $e) {
                    // Handle foreign key constraint violation
                    $skippedModels[] = get_class($user);
                    $this->info('Skipped deletion of user ' . $user->id . ' due to foreign key constraint violation.');

                    $user->load([
                        'roles',
                        'apps',
                        'assignedProducts',
                        'teams',
                        'countries',
                        'responsibleCountries',
                        'responsibleGroups',
                        'authentications',
                        'twoFaResetRequest',
                    ]);

                    foreach ($user->getRelations() as $relationName => $relation) {

                        if ($relation instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
                            $relatedModel = get_class($relation->getRelated());
                            $relatedCount = $relatedModel::where('user_id', $user->id)->count();
                            $this->info($relatedCount . ' records in ' . $relatedModel . ' related to user ' . $user->id);
                        }
                    }
                }
            }

            $this->info($deletedUserCount . ' users deleted.');
            $this->info('Deletion process completed.');

            if (!empty($skippedModels)) {
                $this->info('Error: Models skipped due to foreign key constraint violation: ' . implode(', ', array_unique($skippedModels)));
            }
        } else {
            $this->info('The delete process is terminated!');
        }
    }
}
