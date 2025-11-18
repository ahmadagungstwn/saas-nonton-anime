<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Anime;
use App\Models\Season;
use App\Models\Episode;
use Illuminate\Support\Str;

class AnimeSeeder extends Seeder
{
    public function run(): void
    {
        $animeData = [
            [
                'title' => 'Jujutsu Kaisen',
                'release_year' => 2020,
                'season_name' => 'Jujutsu Kaisen',
                'episodes' => [
                    'Ryomen Sukuna',
                    'For Myself',
                    'Girl of Steel',
                    'Curse Womb Must Die',
                    'Curse Womb Must Die II',
                    'After Rain',
                    'Assault',
                    'Boredom',
                ],
            ],
            [
                'title' => 'One Piece',
                'release_year' => 1999,
                // one piece tidak pakai season → kita pakai Arc pertama
                'season_name' => 'East Blue Saga',
                'episodes' => [
                    'I\'m Luffy! The Man Who Will Become the Pirate King!',
                    'Enter the Great Swordsman! Pirate Hunter Roronoa Zoro!',
                    'Morgan vs. Luffy! Who\'s the Mysterious Pretty Girl?',
                    'Luffy\'s Past! The Red-Haired Shanks Appears!',
                    'A Terrifying Mysterious Power! Captain Buggy, the Clown Pirate!',
                    'Desperate Situation! Beast Tamer Mohji vs. Luffy!',
                    'Epic Showdown! Swordsman Zoro vs. Acrobat Cabaji!',
                    'Who is the Victor? Devil Fruit Power Showdown!',
                ],
            ],
            [
                'title' => 'Demon Slayer',
                'release_year' => 2019,
                'season_name' => 'Kimetsu no Yaiba',
                'episodes' => [
                    'Cruelty',
                    'Trainer Sakonji Urokodaki',
                    'Sabito and Makomo',
                    'Final Selection',
                    'My Own Steel',
                    'Swordsman Accompanying a Demon',
                    'Muzan Kibutsuji',
                    'The Smell of Enchanting Blood',
                ],
            ],
            [
                'title' => 'Naruto',
                'release_year' => 2002,
                'season_name' => 'Prologue — Land of Waves',
                'episodes' => [
                    'Enter: Naruto Uzumaki!',
                    'My Name is Konohamaru!',
                    'Sasuke and Sakura: Friends or Foes?',
                    'Pass or Fail: Survival Test',
                    'You Failed! Kakashi\'s Final Decision',
                    'A Dangerous Mission!',
                    'The Assassin of the Mist!',
                    'The Oath of Pain',
                ],
            ],
            [
                'title' => 'Bleach',
                'release_year' => 2004,
                'season_name' => 'The Substitute',
                'episodes' => [
                    'A Shinigami is Born!',
                    'The Shinigami\'s Work',
                    'The Older Brother\'s Wish, the Younger Sister\'s Wish',
                    'Cursed Parakeet',
                    'Death Triangle',
                    'Fight to the Death! Ichigo vs. Ichigo',
                    'Greetings from a Stuffed Lion',
                    'June 17, Memories in the Rain',
                ],
            ],
            [
                'title' => 'Attack on Titan',
                'release_year' => 2013,
                'season_name' => 'Shingeki no Kyojin',
                'episodes' => [
                    'To You, in 2000 Years',
                    'That Day',
                    'A Dim Light Amid Despair',
                    'The Night of the Closing Ceremony',
                    'First Battle',
                    'The World the Girl Saw',
                    'Small Blade',
                    'I Can Hear His Heartbeat',
                ],
            ],
            [
                'title' => 'My Hero Academia',
                'release_year' => 2016,
                'season_name' => 'Izuku Midoriya: Origin',
                'episodes' => [
                    'Izuku Midoriya: Origin',
                    'What It Takes to Be a Hero',
                    'Roaring Muscles',
                    'Start Line',
                    'What I Can Do for Now',
                    'Rage, You Damn Nerd',
                    'Deku vs. Kacchan',
                    'Bakugo\'s Start Line',
                ],
            ],
            [
                'title' => 'Tokyo Revengers',
                'release_year' => 2021,
                'season_name' => 'Revengers',
                'episodes' => [
                    'Reborn',
                    'Resist',
                    'Resolve',
                    'Return',
                    'Releap',
                    'Regret',
                    'Revive',
                    'Rival',
                ],
            ],
            [
                'title' => 'Chainsaw Man',
                'release_year' => 2022,
                'season_name' => 'Chainsaw Man',
                'episodes' => [
                    'Dog & Chainsaw',
                    'Arrival in Tokyo',
                    'Meowys Whereabouts',
                    'Rescue',
                    'Gun Devil',
                    'Kill Denji',
                    'The Taste of a Kiss',
                    'Gunfire',
                ],
            ],
            [
                'title' => 'Black Clover',
                'release_year' => 2017,
                'season_name' => 'The Magic Knights Entrance Exam',
                'episodes' => [
                    'Asta and Yuno',
                    'The Boys Promise',
                    'To the Royal Capital!',
                    'The Magic Knights Entrance Exam',
                    'The Path to the Wizard King',
                    'The Black Bulls',
                    'The Other New Recruit',
                    'Go! Go! First Mission',
                ],
            ],
        ];

        foreach ($animeData as $anime) {
            $animeModel = Anime::create([
                'title' => $anime['title'],
                'slug' => Str::slug($anime['title']),
                'description' => fake()->sentence(100),
                'thumbnail' => 'anime-thumbnail/01K9RZ3V9YBGHZV2BW3JTG8865.jpg',
                'rating' => rand(75, 99) / 10,
                'release_year' => $anime['release_year'],
                'is_trending' => rand(0, 1),
                'is_top_choice' => rand(0, 1),
            ]);

            foreach ($anime['episodes'] as $index => $episode) {
                Episode::create([
                    'anime_id' => $animeModel->id,
                    'episode_number' => $index + 1,
                    'title' => $episode,
                    'slug' => Str::slug($episode . '-' . $animeModel->id),
                    'thumbnail' => 'episode-thumbnail/01K9S42Y0KVC13347RCMYDRHJ9.jpg',
                    'rating' => rand(70, 100) / 10,
                    'synopsis' => fake()->sentence(60),
                    'video' => '01K9S46YDQ80PE5TMJKQQWRXTN.mp4',
                    'duration' => '24:00',
                    'is_locked' => true,
                    'unlock_cost' => 10,
                ]);
            }
        }
    }
}
