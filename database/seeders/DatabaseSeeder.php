<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Mack Hankins',
            'email' => 'admin@mackhankins.test',
            'password' => bcrypt('password'),
        ]);

        // Create tags
        $techTags = collect([
            'Laravel', 'PHP', 'Vue.js', 'React', 'Tailwind CSS',
            'Livewire', 'Alpine.js', 'TypeScript', 'Docker', 'Redis',
        ])->map(fn (string $name) => Tag::factory()->tech()->create(['name' => $name, 'slug' => str($name)->slug()]));

        $topicTags = collect([
            'Open Source', 'Internal Tools', 'DX', 'Performance',
            'Architecture', 'Testing', 'DevOps',
        ])->map(fn (string $name) => Tag::factory()->topic()->create(['name' => $name, 'slug' => str($name)->slug()]));

        // Create featured projects
        $projects = collect([
            [
                'name' => 'Dispatch Hub',
                'short_description' => 'A centralized logistics dashboard for managing fleet operations, driver assignments, and real-time delivery tracking across multiple regions.',
                'description' => "## Overview\n\nDispatch Hub is a comprehensive logistics management platform built to streamline fleet operations across multiple distribution centers.\n\n## Key Features\n\n- Real-time GPS tracking for 200+ vehicles\n- Automated driver assignment based on proximity and capacity\n- Route optimization reducing fuel costs by 15%\n- Integration with warehouse management systems\n- Custom reporting dashboard for operations managers\n\n## Technical Highlights\n\nBuilt with Laravel on the backend with a Vue.js SPA frontend. Uses WebSockets for real-time vehicle tracking and Redis for caching route calculations. The system processes over 10,000 deliveries daily across three distribution centers.",
                'tech_stack' => ['Laravel', 'Vue.js', 'Redis', 'PostgreSQL', 'WebSockets', 'Docker'],
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Forge Analytics',
                'short_description' => 'An internal business intelligence platform that transforms raw operational data into actionable insights with customizable dashboards.',
                'description' => "## Overview\n\nForge Analytics is an internal BI tool that replaced three separate reporting tools with a single, unified analytics platform.\n\n## Key Features\n\n- Drag-and-drop dashboard builder\n- Custom SQL query editor with autocomplete\n- Scheduled report generation and email delivery\n- Role-based access control for sensitive data\n- Export to PDF, CSV, and Excel\n\n## Technical Highlights\n\nThe frontend uses React with a custom charting library built on D3.js. The backend processes complex aggregation queries through a Laravel API with intelligent query caching. Average dashboard load time is under 800ms despite querying datasets with millions of rows.",
                'tech_stack' => ['Laravel', 'React', 'D3.js', 'MySQL', 'Redis', 'Tailwind CSS'],
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Pulse Monitor',
                'short_description' => 'A health monitoring system for microservices infrastructure with alerting, incident tracking, and automated recovery workflows.',
                'description' => "## Overview\n\nPulse Monitor provides real-time health visibility across a microservices architecture with 40+ services.\n\n## Key Features\n\n- Service health dashboard with dependency graphs\n- Configurable alerting via Slack, email, and PagerDuty\n- Automated incident creation and tracking\n- Runbook integration for common failure scenarios\n- Historical uptime and performance trends\n\n## Technical Highlights\n\nBuilt as a Livewire application for real-time updates without the complexity of a full SPA. Health checks run on configurable intervals using Laravel's scheduler. The dependency graph visualization helps operators quickly identify cascade failures.",
                'tech_stack' => ['Laravel', 'Livewire', 'Alpine.js', 'Tailwind CSS', 'Redis', 'Docker'],
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Config Vault',
                'short_description' => 'A secure configuration management tool for managing environment variables and secrets across multiple deployment environments.',
                'description' => "## Overview\n\nConfig Vault provides a centralized, auditable way to manage application configuration and secrets across development, staging, and production environments.\n\n## Key Features\n\n- Encrypted storage for sensitive values\n- Version history with diff comparison\n- Environment promotion workflows\n- API for CI/CD pipeline integration\n- Audit logging for compliance\n\n## Technical Highlights\n\nAll secrets are encrypted at rest using AES-256. The promotion workflow ensures configuration changes flow through proper review channels before reaching production. Integrates with GitHub Actions and GitLab CI for automated deployments.",
                'tech_stack' => ['Laravel', 'Vue.js', 'MySQL', 'Docker'],
                'is_featured' => false,
                'sort_order' => 4,
            ],
            [
                'name' => 'Queue Vision',
                'short_description' => 'A visual monitoring and management interface for Laravel queue workers and job pipelines.',
                'description' => "## Overview\n\nQueue Vision gives developers and operators real-time insight into job processing across multiple queue connections and workers.\n\n## Key Features\n\n- Real-time job throughput visualization\n- Failed job inspection and retry interface\n- Worker health monitoring\n- Job pipeline flow diagrams\n- Performance bottleneck identification\n\n## Technical Highlights\n\nBuilt as a Filament plugin for easy integration into existing Laravel admin panels. Uses Livewire for real-time updates and provides detailed job payload inspection for debugging failed jobs.",
                'tech_stack' => ['Laravel', 'Filament', 'Livewire', 'Tailwind CSS'],
                'is_featured' => false,
                'sort_order' => 5,
            ],
        ]);

        foreach ($projects as $projectData) {
            $project = Project::factory()->create([
                'name' => $projectData['name'],
                'slug' => str($projectData['name'])->slug(),
                'short_description' => $projectData['short_description'],
                'description' => $projectData['description'],
                'tech_stack' => $projectData['tech_stack'],
                'is_featured' => $projectData['is_featured'],
                'sort_order' => $projectData['sort_order'],
                'status' => 'published',
            ]);

            $project->tags()->attach($techTags->random(rand(2, 4)));
        }

        // Create blog posts
        $posts = collect([
            [
                'title' => 'Why I Build Internal Tools',
                'excerpt' => 'Most of my best work will never see a public GitHub repo. Here is why I think internal tooling is some of the most impactful software you can build.',
                'content' => "There's a quiet satisfaction in building something that 50 people use every single day to do their jobs better. No Product Hunt launch. No Twitter thread. Just a tool that works.\n\n## The invisible impact\n\nInternal tools don't get blog posts or conference talks. They get Slack messages like \"hey, that thing you built saved me 3 hours today.\" And honestly? That hits different than any star count on GitHub.\n\n## What makes internal tools hard\n\nYou'd think building for a known audience with direct access would be easier. In some ways it is. But internal tools come with their own challenges:\n\n- Users have very specific workflows that are hard to generalize\n- You're often integrating with legacy systems that have no API\n- Requirements change fast because the business changes fast\n- You're usually the only developer, so you're architect, designer, and support\n\n## The compound effect\n\nThe best internal tools don't just save time — they change how people think about their work. When you automate a painful manual process, people start asking \"what else could we automate?\" That's when things get interesting.\n\n## My approach\n\nI've settled on a stack that lets me move fast without sacrificing quality: Laravel on the backend, Livewire or Vue.js on the frontend, and Filament for admin interfaces. It's an incredibly productive combination for the kind of CRUD-heavy, data-rich applications that internal tools tend to be.\n\nThe key insight is that internal tools need to be maintainable above all else. You'll be the one maintaining them, often years after you've moved on to other projects. Keep it simple.",
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Filament is the Admin Panel I Always Wanted',
                'excerpt' => 'After years of building custom admin interfaces, Filament changed how I think about back-office applications. Here is what makes it special.',
                'content' => "I've built more admin panels than I can count. Custom ones, Laravel Nova, Backpack, Voyager — you name it, I've tried it. Filament is the first one that actually feels right.\n\n## What clicked for me\n\nFilament's component-based approach to form and table building is genius. Instead of fighting against a framework's opinions, you're composing interfaces from well-designed primitives:\n\n```php\nForms\\Components\\Section::make('Project Details')\n    ->schema([\n        Forms\\Components\\TextInput::make('name')\n            ->required()\n            ->live(onBlur: true),\n        Forms\\Components\\MarkdownEditor::make('description')\n            ->columnSpanFull(),\n    ])\n```\n\nThis is code that reads like what it does. No magic. No guessing.\n\n## The Livewire advantage\n\nBecause Filament is built on Livewire, you get reactivity without building a full SPA. For admin interfaces, this is the sweet spot. You don't need client-side routing. You don't need a build step for every change. You just write PHP and it works.\n\n## Where it really shines\n\nRelation managers, custom pages, and the action system are where Filament goes from \"good admin panel\" to \"application framework.\" I've built entire internal products as Filament panels.\n\n## The ecosystem\n\nThe plugin ecosystem is growing fast, and the community is one of the best in the Laravel world. Dan Harrin and the team have built something special.",
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Practical Tips for Laravel Queue Performance',
                'excerpt' => 'Lessons learned from processing millions of jobs. Queue configuration, worker tuning, and monitoring strategies that actually work.',
                'content' => "After running Laravel queues at scale for several years, I've accumulated a set of practices that have saved me countless hours of debugging and prevented numerous production incidents.\n\n## Right-size your workers\n\nThe default queue worker configuration is fine for development, but production needs tuning:\n\n- Set `--memory` limits based on your actual job memory usage\n- Use `--timeout` to prevent stuck jobs from blocking workers\n- Run multiple workers per queue, but don't overdo it\n\n## Separate your queues\n\nDon't put everything on the default queue. At minimum, separate:\n\n- **high** — User-facing operations that need fast processing\n- **default** — Standard background work\n- **low** — Reports, cleanups, and other deferrable work\n\n## Monitor everything\n\nLaravel Horizon is great if you're using Redis. If not, build your own monitoring. At minimum, track:\n\n- Queue depth over time\n- Job processing duration (p50, p95, p99)\n- Failure rate by job type\n- Worker uptime\n\n### The retry trap\n\nAutomatic retries are useful, but they can mask problems. If a job is failing and retrying 3 times before succeeding, you have a bug — not a resilient system. Log retry attempts separately and investigate patterns.\n\n## Database queues in production\n\nPeople say don't use database queues in production. I disagree — for moderate workloads (under 1000 jobs/hour), database queues are simpler to operate and perfectly adequate. Know your scale before over-engineering.",
                'published_at' => now()->subDays(18),
            ],
            [
                'title' => 'The Art of Writing Readable Code',
                'excerpt' => 'Code is read far more often than it is written. Here are the patterns I follow to make my code a pleasure to work with months later.',
                'content' => 'Six months from now, you\'ll read the code you wrote today and either thank yourself or curse yourself. I\'ve been on both sides enough times to develop some opinions.

## Name things for their purpose, not their type

Bad: `$data`, `$result`, `$items`

Good: `$activeSubscriptions`, `$failedDeliveries`, `$pendingApprovals`

The extra characters are worth it. Every time.

## Functions should do one thing

If you\'re writing a function and you use the word "and" to describe what it does, you need two functions.

## Early returns over deep nesting

The early return pattern reduces nesting and makes code easier to scan. Guard clauses at the top of a function let you handle edge cases first, then focus on the happy path without deep indentation.

The flatter version is easier to scan and easier to extend.

## Comments should explain why, not what

If your code needs a comment to explain what it does, rewrite the code. Comments should explain business decisions, workarounds, and the non-obvious "why" behind a choice.

## Consistency beats cleverness

A codebase with a consistent, slightly verbose style is infinitely better than one with clever tricks that require context to understand. Write boring code. Your future self will appreciate it.',
                'published_at' => now()->subDays(30),
            ],
            [
                'title' => 'Docker for Laravel Developers Who Hate DevOps',
                'excerpt' => 'A practical, no-nonsense guide to containerizing Laravel applications without getting lost in orchestration complexity.',
                'content' => 'I used to avoid Docker like the plague. Then I spent a weekend debugging a "works on my machine" issue across three different PHP versions. Never again.

## The minimum viable Dockerfile

You don\'t need a complex multi-stage build to get started. Here\'s what actually matters:

- Match your production PHP version exactly
- Install only the extensions you need
- Use a proper process manager (Supervisor or s6-overlay)

## Docker Compose for local development

Laravel Sail is great for getting started, but understanding what it does is better. Your docker-compose.yml needs:

- App container (PHP-FPM + Nginx or Caddy)
- Database container (MySQL/PostgreSQL)
- Redis container (if you use it)
- That\'s it. Seriously.

## Common pitfalls

### File permissions

The number one Docker + Laravel headache. Your container user needs to match your host user for storage and cache directories. Use the --user flag or set up proper permissions in your Dockerfile.

### Volume performance on macOS

Docker Desktop\'s file system performance on macOS is not great. Use cached or delegated mount options, or better yet, use Mutagen for syncing.

### Don\'t install Composer globally

Use multi-stage builds or copy the Composer binary from the official image. This keeps your production image clean and your dependencies reproducible.

## The payoff

Once you have a working Docker setup, onboarding new developers goes from "follow these 47 steps" to "run docker compose up." That alone makes it worth the initial investment.',
                'published_at' => now()->subDays(45),
            ],
        ]);

        foreach ($posts as $postData) {
            $post = Post::factory()->create([
                'title' => $postData['title'],
                'slug' => str($postData['title'])->slug(),
                'excerpt' => $postData['excerpt'],
                'content' => $postData['content'],
                'published_at' => $postData['published_at'],
                'status' => 'published',
            ]);

            $post->tags()->attach($topicTags->random(rand(1, 3)));
            $post->tags()->attach($techTags->random(rand(1, 2)));
        }
    }
}
