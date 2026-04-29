<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AiScaffold extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:scaffold';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bootstrap feature structure from .github/structure.json';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting AI Scaffolding...');

        // 1. Read structure.json
        $structurePath = base_path('.github/structure.json');
        
        if (!File::exists($structurePath)) {
            $this->error('❌ .github/structure.json not found');
            return 1;
        }

        $structure = json_decode(File::get($structurePath), true);
        
        if (!$structure) {
            $this->error('❌ Invalid JSON in structure.json');
            return 1;
        }

        $basePath = $structure['base_path'] ?? 'app/Features';
        $features = $structure['features'] ?? [];
        $aiDirectory = $structure['ai_directory'] ?? '.ai';
        $aiFiles = $structure['ai_files'] ?? ['requirements.md', 'plan.md', 'tasks.md', 'decisions.md'];

        $this->info("📍 Base path: {$basePath}");
        $this->info("📦 Features: " . implode(', ', $features));

        // 2. Create base directory
        $featuresBasePath = base_path($basePath);
        if (!File::isDirectory($featuresBasePath)) {
            File::makeDirectory($featuresBasePath, 0755, true);
            $this->info("✅ Created {$basePath}");
        }

        // 3. Scaffold each feature
        foreach ($features as $feature) {
            $this->scaffoldFeature($featuresBasePath, $feature, $aiDirectory, $aiFiles);
        }

        $this->info('✨ Scaffolding completed!');
        $this->info('Next: Use feature-requirements skill to generate requirements.md for each feature');
        
        return 0;
    }

    /**
     * Scaffold a single feature
     */
    private function scaffoldFeature($basePath, $feature, $aiDirectory, $aiFiles)
    {
        $featurePath = $basePath . '/' . $feature;
        
        // Create feature directory
        if (!File::isDirectory($featurePath)) {
            File::makeDirectory($featurePath, 0755, true);
            $this->line("  ✓ Created feature directory: {$feature}");
        } else {
            $this->line("  ℹ Feature directory exists: {$feature}");
        }

        // Create .ai directory
        $aiPath = $featurePath . '/' . $aiDirectory;
        if (!File::isDirectory($aiPath)) {
            File::makeDirectory($aiPath, 0755, true);
            $this->line("    ✓ Created .ai directory");
        }

        // Create markdown files
        foreach ($aiFiles as $file) {
            $filePath = $aiPath . '/' . $file;
            
            if (File::exists($filePath)) {
                $this->line("    ℹ Already exists: {$file}");
            } else {
                $template = $this->getTemplateContent($file, $feature);
                File::put($filePath, $template);
                $this->line("    ✓ Created {$file}");
            }
        }
    }

    /**
     * Get template content for markdown files
     */
    private function getTemplateContent($filename, $feature)
    {
        $templates = [
            'requirements.md' => "# Requirements — {$feature}\n\n## Objetivo\n\n\n## Actores\n\n\n## Reglas de negocio\n\n\n## Entradas\n\n\n## Salidas\n\n\n## Casos límite\n\n\n## Dependencias\n\n\n## Dudas abiertas\n\n",
            
            'plan.md' => "# Plan — {$feature}\n\n## Arquitectura\n\n\n## Flujo técnico\n\n\n## Persistencia\n\n\n## Testing\n\n\n## Riesgos\n\n\n## Decisiones abiertas\n\n",
            
            'tasks.md' => "# Tasks — {$feature}\n\n## Context\n\n\n## Task List\n\n### 1. Preparar estructura base\n\n### 2. Implementar lógica principal\n\n### 3. UI / Integración frontend\n\n### 4. Persistencia / datos\n\n### 5. Testing\n\n### 6. Documentación\n\n",
            
            'decisions.md' => "# Decisiones — {$feature}\n\n## Decisiones técnicas\n\n| Decisión | Razón | Alternativas descartadas |\n|----------|-------|------------------------|\n| | | |\n\n## Trade-offs\n\n\n## Riesgos identificados\n\n",
        ];

        return $templates[$filename] ?? "# {$filename}\n\n";
    }
}
