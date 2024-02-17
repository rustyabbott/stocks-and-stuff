import DarkModeToggle from "./DarkModeToggle";

export default function Footer() {
    const currentYear = new Date().getFullYear();

    return (
            <footer className="h-[48px] w-full bg-white dark:bg-black text-black dark:text-white">
                <div className="float-right p-2">
                    &copy; {currentYear} Rusty Abbott
                </div>
                
                <div className="float-left p-2">
                    <DarkModeToggle></DarkModeToggle>
                </div>
            </footer>
    );
}
