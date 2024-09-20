<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Group;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        User::factory()->create([
            'name' => 'Admin',
            'email'  => 'admin@mail.com',
            'password'  => 'password',
            'is_admin' => 1,
        ]);
        User::factory()->create([
            'name' => 'User',
            'email'  => 'user@mail.com',
            'password'  => 'password',
        ]);
        User::factory(10)->create();


        for($i=0;$i<5;$i++){
            $group = Group::factory()->create([
                'owner_id' =>  1,
            ]);
            $usersId = User::inRandomOrder()->limit(rand(2,5))->pluck('id');
            $group->users()->attach(array_unique([1 , ...$usersId]));//attach owner inside the group
        }
        Message::factory(1000)->create();
        $messages = Message::whereNull('group_id')->orderBy('created_at')->get();//direct message 
        $conversations = $messages->groupBy(function($message){
            return collect ([$message->sender_id , $message->receiver_id])
                    ->sort()
                    ->implode('_');
        })
        
        ->map(function($groupedMessages){
            return [
                 'user_id1' => $groupedMessages->first()->sender_id,//original message object
                 'user_id2' => $groupedMessages->first()->receiver_id,
                 'last_message_id' => $groupedMessages->last()->id,
                 'created_at' => new Carbon(),
                 'updated_at' => new Carbon(),



            ];

        })
        ->values();
        Conversation::insertOrIgnore($conversations->toArray());



    }
}
// [1,2]
// [1,3]
// [2,1]
// [1,2]

// //i want to group by conversation according to one convesation : 
// [
//     1-2  => [
//         [1,2],
//         [2,1], -> sort [1,2] -> implode -> 1_2 string ("1_2") 
///that can be used as a key to group messages. This string is then used as the unique identifier for the conversation
//         [1,2],
//     ],
//     1-3 => [
//         [1,3]
//     ]
// ]