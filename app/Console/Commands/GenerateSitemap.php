<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap by crawling the site';

    public function handle()
    {
        $appUrl = config('app.url'); // Get the APP_URL from the .env file

        SitemapGenerator::create($appUrl)
            ->writeToFile(base_path('sitemap.xml'));

        $this->info('Sitemap generated using APP_URL and placed in the root folder!');
    }
}

