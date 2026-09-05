<?php

namespace Database\Factories;

use App\Enums\ContentType;
use App\Enums\PublishingStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ContentItemFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->sentence(6);
        return [
            'type' => ContentType::News, 'status' => PublishingStatus::Draft,
            'title' => $title, 'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1, 999999),
            'summary' => fake()->sentence(), 'content' => ['body' => fake()->paragraphs(3, true)],
            'locale' => 'en', 'author_id' => User::factory(), 'current_revision' => 1,
        ];
    }
}
