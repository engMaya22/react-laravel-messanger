<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function users(){
        return $this->belongsToMany(User::class  , 'group_users' );
    }

    public function lastMessage(){
        return $this->belongsTo(Message::class ,'last_message_id');
    }
    public function user1(){
        return $this->belongsTo(User::class ,'user_id1');
    }public function user2(){
        return $this->belongsTo(User::class ,'user_id2');
    }
    public static function getConversationsFoSidebar(User $user){
        $users = User::getUserExceptUser($user);
        $groups = Group::getGroupsForUser($user);
        
        return $users->map(function($user){
            return $user->toConversationArray();
        })->concat($groups->map(function($group){
            return $group->toConversationArray();

        }));
    }
}
