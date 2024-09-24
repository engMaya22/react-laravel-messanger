
import {usePage} from "@inertiajs/react";
import { useState , useEffect } from 'react';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

const ChatLayout =({children})=>{
    const page = usePage();
    const conversations = page.props.conversations;
    const selectedConversations = page.props.selectedConversations;
    console.log('conversations',conversations);
    console.log('selectedConversations',selectedConversations);
    const [localConversations , setLocalConversations] = useState([]);
    const [sortedConversations , setSortedConversations] = useState([]);

    const [onlineUsers, setOnlineUsers] = useState({});
    const isUserOnline = (userId)=>onlineUsers[userId];
    useEffect(()=>{
        //this way gives me online and offline users
        Echo.join('online')
            .here((users)=>{//whenever I connect to channel , give me other connected to this channel
               const  onlineUserObj = Object.fromEntries(// makes object  from key id and user is value
                users.map((user)=> [user.id , user]));
                
                setOnlineUsers((previousOnlineUsers)=>{
                    return {
                        ...previousOnlineUsers ,
                        ... onlineUserObj
                    }
                });
            })
            .joining((user)=>{//whenver somebody connects ,give me this user
                setOnlineUsers((previousOnlineUsers)=>{
                    return {
                        ...previousOnlineUsers , [user.id]:user
                    };

                });
            }) 
            .leaving((user)=>{////whenver somebody leaves ,give me this user
                setOnlineUsers((previousOnlineUsers)=>{
                    const updatedUsers = {...previousOnlineUsers};
                    delete updatedUsers[user.id];//filter on state
                    return updatedUsers
                })
            })
            .error((error)=>{
                console.log('error',error)

            });
        return ()=>{
            Echo.leave('online')//to discnnect channel when leave channel
        }

    } , [])

    useEffect(()=>{//
        setSortedConversations( 
            localConversations.sort((a,b)=>{
                if(a.blocked_at && b.blocked_at){
                    return a.blocked_at > b.blocked_at ? 1 : -1;// a conversation blocked later?
                }else if(a.blocked_at ){
                    return 1;//If only one conversation is blocked 
                    //(a.blocked_at but not b.blocked_at), the blocked one
                    // is ranked lower (returns 1 to move it down).
                }else if(b.blocked_at ){
                    return -1
                }
                //If neither conversation is blocked, we proceed to compare by the last_message_date
                if (a.last_message_date && b.last_message_date){
                    return b.last_message_date.localCompare(
                        a.last_message_date
                    );
                } else if (a.last_message_date){
                    return -1
                }else if (b.last_message_date){
                    return 1
                }else{
                    return 0;
                }


            })
        )
    },[localConversations]);

    useEffect(()=>{//updated whenver conversation recieved
        setLocalConversations(conversations)
    },[conversations]);
    return  (
        <>
           
        </>


    )

}
export default ChatLayout;

