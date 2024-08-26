import { Sidebar } from "flowbite-react";
import { Link } from "@inertiajs/react";

import {
    HiArrowSmRight,
    HiChartPie,
    HiInbox,
    HiShoppingBag,
    HiTable,
    HiUser,
    HiViewBoards,
} from "react-icons/hi";
import { RiAccountCircleFill } from "react-icons/ri";

export function SidebarDashboard() {
    return (
        <Sidebar
            aria-label="Sidebar with logo branding example"
            className=" h-full"
        >
            <Sidebar.Logo
                href="#"
                img="/novelnestlogo.png"
                imgAlt="Novel Nest logo"
            >
                Novel Nest
            </Sidebar.Logo>
            <Sidebar.Items>
                <Sidebar.ItemGroup>
                    <Sidebar.Item href={route("dashboard")} icon={HiChartPie}>
                        Dashboard
                    </Sidebar.Item>
                    <Sidebar.Item
                        href={route("dashboard.upload-books")}
                        icon={HiInbox}
                    >
                        input buku
                    </Sidebar.Item>
                    <Sidebar.Item icon={RiAccountCircleFill}>
                        <Link
                            href={route("logoutGoogle")}
                            method="post"
                            as="button"
                        >
                            Logout
                        </Link>
                    </Sidebar.Item>
                </Sidebar.ItemGroup>
            </Sidebar.Items>
        </Sidebar>
    );
}
