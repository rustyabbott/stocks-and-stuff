import Footer from '@/Components/Footer';
import TickerPanel from '@/Components/TickerPanel';
import { Link, Head } from '@inertiajs/react';

export default function Home({ auth, laravelVersion, phpVersion }) {
    const panelInstances = Array.from({ length: 10 }, (_, index) => (
        <TickerPanel key={index} />
    ));

    return (
        <>
            <Head title="Home" />
            <div className="relative min-h-screen bg-center bg-gray-100 dark:bg-neutral-800 selection:bg-red-500 selection:text-white">
                <div className="p-6 text-right">
                    {auth.user ? (
                        <Link
                            href={route('dashboard')}
                            className="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                        >
                            Dashboard
                        </Link>
                    ) : (
                        <>
                            <Link
                                href={route('login')}
                                className="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                            >
                                Log in
                            </Link>

                            <Link
                                href={route('register')}
                                className="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                            >
                                Register
                            </Link>
                        </>
                    )}
                </div>

                <div className="w-full">
                    <div className="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-0 outline">
                        { panelInstances }
                    </div>
                </div>
            </div>

            <Footer></Footer>
        </>
    );
}
