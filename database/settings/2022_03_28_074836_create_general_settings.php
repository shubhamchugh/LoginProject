<?php

use Illuminate\Support\Facades\Schema;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateGeneralSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_name', 'Website Name');
        $this->migrator->add('general.home_title', 'home Page');
        $this->migrator->add('general.google_forms_contact', 'http://google.com/form');
        $this->migrator->add('general.home_h1_title', 'Frequently Asked Questions & Answers Related to Attorney');
        $this->migrator->add('general.home_page_description', 'example.com is a crowd sourced law website dedicated to providing legal information and resources for all types of law-related topics.');
        $this->migrator->add('general.header_code', '');
        $this->migrator->add('general.theme_color', '1');
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }

}
