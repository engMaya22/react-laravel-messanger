<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function users(){
        return $this->belongsToMany(User::class  , 'group_users' );
    }

    public function messages(){
        return $this->hasMany(Message::class);
    }
    public function owner(){
        return $this->belongsTo(User::class);
    }
    public static function getGroupsForUser(User $user){
        //its groups wsith last message from each group if it exsists
       $query = self::select(['groups.*','messages.message as last_message',
                                        'messages.created_at as last_message_date'])
                    ->join('group_users','group_users.group_id','=','groups.id')
                    ->leftJoin('messages', 'messages.id','=','groups.last_message_id')//left join used there cause there is possibilites for no message
                    ->where('group_users.user_id',$user->id)//which I in it 
                    ->orderBy('messages.created_at','desc')
                    ->orderBy('groups.name');
        return $query->get();
    }
    public function toConversationArray(){
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'is_group' => true,//from group
            'owner_id' => $this->owner_id,
            'is_user' => false,
            'users' => $this->users,
            'user_ids' => $this->users->pluck('id'),

            'created_at' =>$this->created_at,
            'updated_at' =>$this->updated_at,
            'last_message' =>$this->last_message,//calculated
            'last_message_date' =>$this->last_message_date,//calculated

        ];
    }

}
