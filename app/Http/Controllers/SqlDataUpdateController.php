<?php

namespace App\Http\Controllers;

use App\Models\Setting;

class SqlDataUpdateController extends Controller
{
    public function updateSql()
    {

        $data = [
            'site_name'             => json_encode('Website Name'),
            'home_title'            => json_encode('Home Title'),
            'google_forms_contact'  => json_encode('http://google.com/form'),
            'home_h1_title'         => json_encode('Frequently Asked Questions & Answers Related to Attorney'),
            'home_page_description' => json_encode('example.com is a crowd sourced law website dedicated to providing legal information and resources for all types of law-related topics.'),
            'header_code'           => json_encode(''),
            'theme_color'           => json_encode("1"),
            'author_name'           => json_encode('AuthorName'),
            'bellow_title_ads'      => json_encode(''),
        ];

        foreach ($data as $name => $payload) {
            $exist = Setting::where('name', $name)->first();
            if (empty($exist)) {
                Setting::create([
                    'group'   => 'general',
                    'locked'  => 0,
                    'name'    => $name,
                    'payload' => $payload,
                ]);
            } else {
                echo "$name Already in Database</br>";
            }
        }
    }
}
