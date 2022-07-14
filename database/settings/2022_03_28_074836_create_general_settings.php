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
        $this->migrator->add('general.author_name', 'AuthorName');
        $this->migrator->add('general.bellow_title_ads', '');
        $this->migrator->add('general.about_us', 'FAQ is an open global project. Our aim is to provide explanations of possible technical topics and problem solving techniques in the form of questions and answers. We hope that our project will provide people with understanding difficult problems, how to solve them, through the engineering perspective.');
        $this->migrator->add('general.before_title', '');
        $this->migrator->add('general.after_title', '');
        $this->migrator->add('general.loop_ads_1', '');
        $this->migrator->add('general.loop_ads_2', '');
        $this->migrator->add('general.loop_ads_3', '');
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }

}
