<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateGeneralSettings extends SettingsMigration
{
    public function up()
    {
        $this->migrator->add('general.site_name', 'FAQ Scraper');
        $this->migrator->add('general.home_title', 'Best FAQ site in the world');
        $this->migrator->add('general.home_heading', 'FAQ- A Blog Template Made For Developers');
        $this->migrator->add('general.home_description', 'Welcome to my blog. Subscribe and get my latest blog post in your inbox.');
        $this->migrator->add('general.search_bar_text', 'Search Faq');
        $this->migrator->add('general.header_code', "<!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src='https://www.googletagmanager.com/gtag/js?id=UA-154127898-1'></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'UA-154127898-1);
        </script>
        ");
    }
}
