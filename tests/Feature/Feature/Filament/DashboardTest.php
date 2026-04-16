<?php

use App\Filament\Pages\Dashboard;
use App\Models\AgentConversation;
use App\Models\Post;
use App\Models\Project;
use App\Models\User;

test('authenticated users can load the custom admin dashboard', function () {
    $user = User::factory()->withAppAuthentication()->create();

    Post::factory()->create();
    Post::factory()->draft()->create();
    Project::factory()->create();

    AgentConversation::query()->create([
        'id' => (string) str()->uuid(),
        'user_id' => $user->id,
        'title' => 'Draft ideas for database drills',
    ]);

    $this->actingAs($user)
        ->get(Dashboard::getUrl())
        ->assertOk()
        ->assertSee('Draft posts')
        ->assertSee('Recent Posts')
        ->assertSee('Recent Projects')
        ->assertSee('Recent Writing Chats');
});
