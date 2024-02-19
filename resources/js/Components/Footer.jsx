import DarkModeToggle from "./DarkModeToggle";

export default function Footer() {
    const currentYear = new Date().getFullYear();

    return (
            <footer className="flex items-center justify-between h-[48px] w-full bg-white dark:bg-black text-black dark:text-white">
                <div className="flex items-center p-2">
                    <DarkModeToggle></DarkModeToggle>
                </div>
                
                <div className="flex items-center p-2">
                    &copy; {currentYear} Rusty Abbott
                </div>
            </footer>
    );
}
