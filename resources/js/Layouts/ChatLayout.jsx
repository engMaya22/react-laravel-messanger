
import {usePage} from "@inertiajs/react"
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

const ChatLayout =({children})=>{
    const page = usePage();
    const conversations = page.props.conversations;
    const selectedConversations = page.props.selectedConversations;
    console.log('conversations',conversations);
    console.log('selectedConversations',selectedConversations);

    return  (
        <>
            <div>
                chat layout

            <div>
                {children}
            </div>
            </div>
        </>


    )

}
export default ChatLayout;

