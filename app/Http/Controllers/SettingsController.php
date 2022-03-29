<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\GeneralSettings;
use App\Http\Requests\GeneralSettingsRequest;

class SettingsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(GeneralSettings $settings, GeneralSettingsRequest $request)
    {
        $settings->site_name             = $request->input('site_name');
        $settings->home_title            = $request->input('home_title');
        $settings->google_forms_contact  = $request->input('google_forms_contact');
        $settings->home_h1_title         = $request->input('home_h1_title');
        $settings->home_page_description = $request->input('home_page_description');
        $settings->header_code           = $request->input('header_code');
        $settings->theme_color           = $request->input('theme_color');
        $settings->save();

        return redirect()->back();
    }

    public function show(GeneralSettings $settings)
    {

        return view('settings.show', [
            'settings'              => $settings,
            'site_name'             => $settings->site_name,
            'home_title'            => $settings->home_title,
            'google_forms_contact'  => $settings->google_forms_contact,
            'home_h1_title'         => $settings->home_h1_title,
            'home_page_description' => $settings->home_page_description,
            'header_code'           => $settings->header_code,
            'theme_color'           => $settings->theme_color,

        ]);
    }
}
