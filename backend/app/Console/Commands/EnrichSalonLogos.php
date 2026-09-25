<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use App\Models\Salon;

class EnrichSalonLogos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'salons:enrich-logos {--id= : Process a specific salon ID} {--limit= : Limit the number of salons to process} {--force : Force re-enrichment even if salon already has media}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Discovers or generates high-res branded logos for listed businesses and attaches them in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ini_set('memory_limit', '512M');
        gc_enable();

        $salonId = $this->option('id');
        $limit = $this->option('limit');
        $force = $this->option('force');

        $query = Salon::query();

        if ($salonId) {
            $query->where('id', $salonId);
        }

        if (!$force && !$salonId) {
            $this->info("Scanning salons for logo enrichment...");
        }

        if ($limit) {
            $query->limit((int) $limit);
        }

        $salons = $query->get();
        $this->info("Found " . $salons->count() . " salons to process.");

        $outputDir = public_path('images/salons');
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $processed = 0;
        $scriptPath = base_path('scripts/business_logo_parser.py');

        foreach ($salons as $salon) {
            $this->line("--------------------------------------------------");
            $this->info("Processing Salon [#{$salon->id}]: {$salon->name}");

            $firstService = $salon->eServices()->first();
            $categoryName = 'Servicios';
            if ($firstService && method_exists($firstService, 'categories')) {
                $categoryName = $firstService->categories()->first()?->name ?? 'Servicios';
            }

            // Execute Python logo finder & generator script
            $command = [
                'python3',
                $scriptPath,
                '--name', $salon->name,
                '--category', $categoryName,
                '--out-dir', $outputDir,
                '--no-ai'
            ];

            $process = new Process($command);
            $process->setTimeout(30);
            $process->run();

            if (!$process->isSuccessful()) {
                $this->warn("  Logo parser output: " . trim($process->getErrorOutput()));
            }

            // Target generated/fetched logo file
            $safeName = Str::slug($salon->name, '_');
            $expectedLogoPath = $outputDir . '/' . $safeName . '_logo.png';
            $expectedMetaPath = $outputDir . '/' . $safeName . '_meta.json';

            if (file_exists($expectedLogoPath) && @getimagesize($expectedLogoPath) === false) {
                @unlink($expectedLogoPath);
                @unlink($expectedMetaPath);
                // Re-run script to force clean monogram generation
                $process = new Process($command);
                $process->setTimeout(30);
                $process->run();
            }

            if (file_exists($expectedLogoPath)) {
                try {
                    $salon->clearMediaCollection('image');
                    $salon->addMedia($expectedLogoPath)->preservingOriginal()->toMediaCollection('image');
                    $this->info("  ✓ Attached brand logo to Salon [#{$salon->id}]");
                    $processed++;
                } catch (\Exception $e) {
                    $this->error("  Failed to attach media: " . $e->getMessage());
                }
            } else {
                $this->warn("  Could not locate output image at {$expectedLogoPath}");
            }

            gc_collect_cycles();
        }

        $this->line("==================================================");
        $this->info("Completed logo enrichment for {$processed} / " . $salons->count() . " salons!");
    }
}
