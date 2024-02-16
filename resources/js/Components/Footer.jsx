import DarkModeToggle from "./DarkModeToggle";

export default function Footer() {
    const currentYear = new Date().getFullYear();

    return (
        <>
            <footer className="text-gray-900 dark:text-gray-300 dark:bg-black">
                <div className="float-right p-2">
                    &copy; {currentYear} Rusty Abbott
                </div>
                
                <div className="float-left p-2">
                    <DarkModeToggle></DarkModeToggle>
                </div>
            </footer>
        </>
    );
}
