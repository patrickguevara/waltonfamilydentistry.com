<?php

namespace App\Console\Commands;

use App\Models\Service;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.xml file for the website';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Generating sitemap...');

        $sitemap = Sitemap::create();

        // Add static pages
        $staticPages = [
            ['url' => route('home'), 'priority' => 0.8],
            ['url' => route('about'), 'priority' => 0.8],
            ['url' => route('services.index'), 'priority' => 0.8],
            ['url' => route('contact'), 'priority' => 0.8],
        ];

        foreach ($staticPages as $page) {
            $sitemap->add(
                Url::create($page['url'])
                    ->setPriority($page['priority'])
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );
        }

        // Add active service pages
        $services = Service::active()->ordered()->get();

        foreach ($services as $service) {
            $sitemap->add(
                Url::create(route('services.show', $service->slug))
                    ->setLastModificationDate($service->updated_at)
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );
        }

        // Write to public directory
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $totalUrls = count($staticPages) + $services->count();
        $this->info("Sitemap generated successfully with {$totalUrls} URLs!");
        $this->info('Location: ' . public_path('sitemap.xml'));

        return Command::SUCCESS;
    }
}
