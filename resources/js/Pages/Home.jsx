import ChatLayout from '@/Layouts/ChatLayout'
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout"
function Home() {
    return (
            <>
               Messages
            </>
    );
}
//Persistent layouts to not recall header state if in auth if it isont change

Home.layout = (page) => {
    return (
        <AuthenticatedLayout 
                    user= {page.props.auth.user}
                   

        >
                    <ChatLayout  children={page} />

        </AuthenticatedLayout>
    )
}
export default Home
