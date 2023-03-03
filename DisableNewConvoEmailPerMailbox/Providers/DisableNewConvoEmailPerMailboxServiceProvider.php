<?php

namespace Modules\DisableNewConvoEmailPerMailbox\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DisableNewConvoEmailPerMailboxServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->hooks();
    }

    /**
     * Module hooks.
     */
    public function hooks()
    {
        
        \Eventy::addFilter('subscription.users_to_notify', function($users_to_notify, $event_type, $events, $thread) {
            // DisableNewConvoEmailPerMailbox- Allows users to disable the New Conversation email notification on a per mailbox basis.
            // by Jeff Sherk March 2023
            //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // If the user is already in the $users_to_notify array then we know that:
            //   (1) they already have access to mailbox the conversation is a part of, and
            //   (2) they have enabled the New Convo email notification for "There is new conversation".
            // All we need to do is check whether or not each user in $users_to_notify has disabled the New Convo notification for the specific mailbox that the convo belongs to. If yes, then we need to remove the user from $users_to_notify array.
            //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            
            // Check if $event_type=1 for NEW CONVERSATION, otherwise skip to end.
            if ($event_type == 1) {
                
                // Check if there is a conversation_id set in $thread, otherwise skip to end.
                if (isset($thread->conversation_id) && $thread->conversation_id > 0) {
                
                    // Set the conversation_id
                    $conv_id = $thread->conversation_id; 
                    
                    // Get the mailbox_id of the conversation
                    $resultConvo = \App\Conversation::select('mailbox_id')->where('id', $conv_id)->first();
                    
                    // Check if there is a mailbox_id associated with this conversation, otherwise skip to end/
                    if (isset($resultConvo->mailbox_id) && $resultConvo->mailbox_id > 0) {
                        
                        // Set the mailbox_id of the conversation
                        $mailbox_id = $resultConvo->mailbox_id; 
                        
                        // get mailbox name ... not required right now so comment out
                        //$resultMailboxName = \App\Mailbox::select('name')->where('id', $mailbox_id)->first(); 
                        //$mailbox_name = $resultMailboxName->name;
                        
                        // Check if there are any users that have disabled New Convo emails from this conversations mailbox, otherwise skip to end.
                        $subscriptions_disable_new_convo_results = DB::select("SELECT * FROM subscriptions_disable_new_convo WHERE mailbox_id='$mailbox_id' ");
                        if ( count($subscriptions_disable_new_convo_results) > 0 ) {
                            
                            // We will store list of users in this array that still have the New Convo email enabled. If a user has it disabled then we will NOT add them to this array.
                            $keep_users = array();
                            
                            // Loop thru all the users that are supposed to be notified about New Conversation, and check if they have disabled the notification for this mailbox.
                            foreach ($users_to_notify['1'] as $user) { 
                                $user_id = $user->id; // get the users id
                                $keep = true;
                                
                                // Loop thru the list of disabled users to see if there is a match.
                                foreach ($subscriptions_disable_new_convo_results as $disable_user) {
                                    if ($disable_user->user_id == $user_id) {
                                        $keep = false;
                                    }
                                }
                                
                                // Do we add them back to the list or not?
                                if ($keep == false) {
                                    // this user has disabled New Convo email notification for this mailbox so skip them and do not add them back to the new users_to_notify array
                                    // do nothing
                                } else {
                                    // this user still has new convo email notification enabled so add them back to the new users_to_notify array
                                    $keep_users[] = $user;
                                }
                            }
                            
                            // Replace the $users_to_notify array with new modified list that has any disabled users removed.
                            $new_users_to_notify = array();
                            $new_users_to_notify['1'] = $keep_users;
                            $users_to_notify = $new_users_to_notify;
                            
                        }
                    }
                }
            }
            
            return $users_to_notify;
        }, 10, 4);
        
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerTranslations();
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('disablenewconvoemailpermailbox.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'disablenewconvoemailpermailbox'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/disablenewconvoemailpermailbox');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/disablenewconvoemailpermailbox';
        }, \Config::get('view.paths')), [$sourcePath]), 'disablenewconvoemailpermailbox');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $this->loadJsonTranslationsFrom(__DIR__ .'/../Resources/lang');
    }

    /**
     * Register an additional directory of factories.
     * @source https://github.com/sebastiaanluca/laravel-resource-flow/blob/develop/src/Modules/ModuleServiceProvider.php#L66
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
