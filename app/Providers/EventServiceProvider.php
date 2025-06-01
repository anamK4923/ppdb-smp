<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use JeroenNoten\LaravelAdminLte\Events\DarkModeWasToggled;
use JeroenNoten\LaravelAdminLte\Events\ReadingDarkModePreference;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        // Register listener for ReadingDarkModePreference event. We use this
        // event to setup dark mode initial status for AdminLTE package.

        Event::listen(
            ReadingDarkModePreference::class,
            [$this, 'handleReadingDarkModeEvt']
        );

        // Register listener for DarkModeWasToggled AdminLTE event.

        Event::listen(
            DarkModeWasToggled::class,
            [$this, 'handleDarkModeWasToggledEvt']
        );
    }

    /**
     * Handle the ReadingDarkModePreference AdminLTE event.
     *
     * @param ReadingDarkModePreference $event
     * @return void
     */
    public function handleReadingDarkModeEvt(ReadingDarkModePreference $event)
    {
        // TODO: Implement the next method to get the dark mode preference for the
        // current authenticated user. Usually this preference will be stored on a database,
        // it is your task to get it.

        $darkModeCfg = $this->getDarkModeSettingFromDB();

        // Setup initial dark mode preference.

        if ($darkModeCfg) {
            $event->darkMode->enable();
        } else {
            $event->darkMode->disable();
        }
    }

    /**
     * Handle the DarkModeWasToggled AdminLTE event.
     *
     * @param DarkModeWasToggled $event
     * @return void
     */
    public function handleDarkModeWasToggledEvt(DarkModeWasToggled $event)
    {
        // Get the new dark mode preference (enabled or not).

        $darkModeCfg = $event->darkMode->isEnabled();

        if ($darkModeCfg) {
            Log::debug("Dark mode preference is now enabled!");
        } else {
            Log::debug("Dark mode preference is now disabled!");
        }

        // Store the new dark mode preference on the database.

        $this->storeDarkModeSettingOnDB($darkModeCfg);

        // TODO: Implement previous method to store the new dark mode
        // preference for the authenticated user. Usually this preference will
        // be stored on a database, it is your task to store it.
    }

    protected function getDarkModeSettingFromDB()
    {
        return Auth::check() ? Auth::user()->dark_mode : false;
    }

    protected function storeDarkModeSettingOnDB($enabled)
    {
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $user->dark_mode = $enabled;
            $user->save();
        }
    }
}
