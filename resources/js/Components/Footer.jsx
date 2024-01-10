import DarkModeToggle from "./DarkModeToggle";

export default function Footer() {
    const currentYear = new Date().getFullYear();

    return (
        <>
            <footer className="text-gray-900 dark:text-gray-300">
                <div className="fixed bottom-2 right-4">
                    &copy; {currentYear} Rusty Abbott
                </div>
                
                <div className="fixed bottom-0 left-2">
                    <DarkModeToggle></DarkModeToggle>
                </div>
            </footer>
        </>
    );
}
