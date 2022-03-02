<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Helpers\GeneralSettings;

class GeneralSettingsView
{

    public function compose(View $view)
    {
        $this->composeGeneralSettings($view);
    }

    public function composeGeneralSettings(View $View)
    {
        $settings = app(GeneralSettings::class);

        $View->with([
            'settings' => $settings,
        ]);
    }
}
