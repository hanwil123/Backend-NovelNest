import React, { useEffect, useState } from "react";
import NavbarDashboard from "@/Components/NavbarDashboard";
import { SidebarDashboard } from "@/Components/SidebarDashboard";
import { Table } from "flowbite-react";
import axios from "axios";
import DeleteAlert from "./DeleteAlert";

export default function Dashboard({ auth }) {
    const [bookDatas, setBookDatas] = useState([]);
    const [selectedBookId, setSelectedBookId] = useState(null);
    const [showDeleteAlert, setShowDeleteAlert] = useState(false);

    useEffect(() => {
        const params = new URLSearchParams(window.location.search);
        const token = params.get('token');

        if (token) {
            localStorage.setItem('token', token);
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            // Hapus token dari URL
            window.history.replaceState({}, document.title, "/dashboard");
        }

        fetchBooks();
    }, []);
    const fetchBooks = async () => {
        try {
            const response = await axios.get('/api/books');
            setBookDatas(response.data.data);
        } catch (error) {
            console.error('Error fetching books:', error);
        }
    };

    const handleDeleteClick = (bookId) => {
        setSelectedBookId(bookId);
        setShowDeleteAlert(true);
    };

    const handleDeleteConfirm = async () => {
        try {
            if (selectedBookId) {
                await axios.delete(`/api/books/${selectedBookId}`);
                setBookDatas((prevBooks) =>
                    prevBooks.filter((b) => b.id !== selectedBookId)
                );
                setShowDeleteAlert(false);
                setSelectedBookId(null);
            }
        } catch (error) {
            console.error("Error deleting book:", error);
        }
    };

    return (
        <>
            <div className="container h-full flex overflow-y-auto overflow-x-auto w-full">
                <SidebarDashboard />
                <div className="flex-col w-full h-full">
                    <div className="flex mx-auto px-6 my-5">
                        <h1 className=" text-black text-bold text-xl">Dashboard</h1>
                    </div>
                    <div className="">
                        <div className="">
                            <Table hoverable>
                                <Table.Head>
                                    <Table.HeadCell>Id</Table.HeadCell>
                                    <Table.HeadCell>Judul Buku</Table.HeadCell>
                                    <Table.HeadCell>Penulis</Table.HeadCell>
                                    <Table.HeadCell>Category</Table.HeadCell>
                                    <Table.HeadCell>Sinopsis</Table.HeadCell>
                                    <Table.HeadCell>Isi Buku</Table.HeadCell>
                                    <Table.HeadCell>
                                        Total Halaman
                                    </Table.HeadCell>
                                    <Table.HeadCell>Cover Image</Table.HeadCell>
                                    <Table.HeadCell>Rating</Table.HeadCell>
                                    <Table.HeadCell>
                                        <span className="sr-only">Edit</span>
                                    </Table.HeadCell>
                                    <Table.HeadCell>
                                        <span className="sr-only">Delete</span>
                                    </Table.HeadCell>
                                </Table.Head>
                                <Table.Body className="divide-y">
                                    {bookDatas.map((book) => (
                                        <Table.Row
                                            key={book.id}
                                            className="bg-white dark:border-gray-700 dark:bg-gray-800"
                                        >
                                            <Table.Cell className="whitespace-nowrap font-medium text-gray-900 dark:text-white">
                                                {book.id}
                                            </Table.Cell>
                                            <Table.Cell>
                                                {book.judul}
                                            </Table.Cell>
                                            <Table.Cell>
                                                {book.penulis}
                                            </Table.Cell>
                                            <Table.Cell>
                                                {book.category_id}
                                            </Table.Cell>
                                            <Table.Cell>
                                                {book.sinopsis}
                                            </Table.Cell>
                                            <Table.Cell>
                                                {book.isibuku}
                                            </Table.Cell>
                                            <Table.Cell>
                                                {book.halaman}
                                            </Table.Cell>
                                            <Table.Cell>
                                                <img
                                                    src={book.cover_image}
                                                    alt="Cover"
                                                    className="w-20 h-auto"
                                                />
                                            </Table.Cell>
                                            <Table.Cell>
                                                {book.rating}
                                            </Table.Cell>
                                            <Table.Cell>
                                                <a
                                                    href={route(
                                                        "dashboard.edit-books",
                                                        { id: book.id }
                                                    )}
                                                    className="font-medium text-cyan-600 hover:underline dark:text-cyan-500"
                                                    onClick={() => {
                                                        // Logic untuk memanggil ulang fetchBooks setelah edit
                                                        fetchBooks();
                                                    }}
                                                >
                                                    Edit
                                                </a>
                                            </Table.Cell>
                                            <Table.Cell>
                                                <a
                                                    href="#"
                                                    className="font-medium text-cyan-600 hover:underline dark:text-cyan-500"
                                                    onClick={() =>
                                                        handleDeleteClick(
                                                            book.id
                                                        )
                                                    }
                                                >
                                                    Delete
                                                </a>
                                            </Table.Cell>
                                        </Table.Row>
                                    ))}
                                </Table.Body>
                            </Table>
                        </div>
                    </div>
                </div>
            </div>

            {showDeleteAlert && (
                <DeleteAlert
                    onConfirm={handleDeleteConfirm}
                    onCancel={() => setShowDeleteAlert(false)}
                />
            )}
        </>
    );
}
