import React, { useState, useEffect } from "react";
import NavbarDashboard from "@/Components/NavbarDashboard";
import { SidebarDashboard } from "@/Components/SidebarDashboard";
import axios from "axios";
import { usePage } from "@inertiajs/react";

const EditBooks = ({bookId}) => {
    const [judul, setJudul] = useState("");
    const [penulis, setPenulis] = useState("");
    const [categoryId, setCategoryId] = useState("");
    const [sinopsis, setSinopsis] = useState("");
    const [isibuku, setIsibuku] = useState(null);
    const [coverImage, setCoverImage] = useState(null);
    const [categories, setCategories] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchCategoriesAndBook = async () => {
            try {
                const categoryResponse = await axios.get("/api/categories");
                setCategories(categoryResponse.data);

                if (bookId) {
                    const bookResponse = await axios.get(
                        `/api/books/${bookId}`
                    );
                    const book = bookResponse.data;
                    setJudul(book.judul);
                    setPenulis(book.penulis);
                    setCategoryId(book.category_id);
                    setSinopsis(book.sinopsis);
                    setIsibuku(null); // Reset to null as the user may or may not upload a new file
                    setCoverImage(null); // Same for cover image
                } else {
                    throw new Error("Book ID is missing");
                }
            } catch (error) {
                console.error("Error fetching data:", error);
                setError("Failed to load book data");
            } finally {
                setLoading(false);
            }
        };

        fetchCategoriesAndBook();
    }, [bookId]);

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            const formData = new FormData();
            formData.append("judul", judul);
            formData.append("penulis", penulis);
            formData.append("category_id", categoryId);
            formData.append("sinopsis", sinopsis);

            if (isibuku && isibuku instanceof File) {
                formData.append("isibuku", isibuku);
            }
            if (coverImage && coverImage instanceof File) {
                formData.append("cover_image", coverImage);
            }

            // Log FormData contents
            for (let [key, value] of formData.entries()) {
                console.log(`${key}:`, value);
            }

            const response = await axios.post(`/api/books/${bookId}`, formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });

            if (response.data.success) {
                const editBook = response.data.book;
                setJudul(editBook.judul);
                setPenulis(editBook.penulis);
                setCategoryId(editBook.category_id);
                setSinopsis(editBook.sinopsis);
                setIsibuku(editBook.isibuku);
                setCoverImage(editBook.cover_image);

                console.log("Update response:", response.data.book);
                alert("Book updated successfully!");
                window.location.href = "/dashboard";
            } else {
                throw new Error(response.data.error || "Unknown error occurred");
            }
        } catch (error) {
            console.error(
                "Error updating book:",
                error.response?.data || error.message
            );
            alert(
                "Failed to update book: " +
                (error.response?.data?.error || error.message)
            );
        }
    };



    if (loading) {
        return <div>Loading...</div>;
    }

    return (
        <>
            <div className="container h-full flex overflow-y-auto overflow-x-hidden">
                <SidebarDashboard />
                <div className="flex-col w-full h-full">
                    <div>
                        <NavbarDashboard />
                    </div>
                    <div className="flex mx-auto my-5">
                        <h1>Edit Book</h1>
                    </div>
                    {error && <div className="text-red-500 mb-4">{error}</div>}
                    <form onSubmit={handleSubmit}>
                        <div className="mb-4">
                            <label
                                htmlFor="judul"
                                className="block text-gray-700"
                            >
                                Judul Buku
                            </label>
                            <input
                                type="text"
                                id="judul"
                                value={judul}
                                onChange={(e) => setJudul(e.target.value)}
                                required
                                className="mt-2 p-2 w-full border rounded"
                            />
                        </div>
                        <div className="mb-4">
                            <label
                                htmlFor="penulis"
                                className="block text-gray-700"
                            >
                                Penulis
                            </label>
                            <input
                                type="text"
                                id="penulis"
                                value={penulis}
                                onChange={(e) => setPenulis(e.target.value)}
                                required
                                className="mt-2 p-2 w-full border rounded"
                            />
                        </div>
                        <div className="mb-4">
                            <label
                                htmlFor="category_id"
                                className="block text-gray-700"
                            >
                                Category
                            </label>
                            <select
                                id="category_id"
                                value={categoryId}
                                onChange={(e) => setCategoryId(e.target.value)}
                                required
                                className="mt-2 p-2 w-full border rounded"
                            >
                                <option value="">Select a category</option>
                                {categories.map((category) => (
                                    <option
                                        key={category.id}
                                        value={category.id}
                                    >
                                        {category.name}
                                    </option>
                                ))}
                            </select>
                        </div>
                        <div className="mb-4">
                            <label
                                htmlFor="sinopsis"
                                className="block text-gray-700"
                            >
                                Sinopsis
                            </label>
                            <textarea
                                id="sinopsis"
                                value={sinopsis}
                                onChange={(e) => setSinopsis(e.target.value)}
                                required
                                className="mt-2 p-2 w-full border rounded"
                                rows="4"
                            ></textarea>
                        </div>
                        <div className="mb-4">
                            <label
                                htmlFor="isibuku"
                                className="block text-gray-700"
                            >
                                Isi Buku (PDF)
                            </label>
                            <input
                                type="file"
                                id="isibuku"
                                accept="application/pdf"
                                onChange={(e) => setIsibuku(e.target.files[0])}
                                className="mt-2 p-2 w-full border rounded"
                            />
                        </div>
                        <div className="mb-4">
                            <label
                                htmlFor="cover_image"
                                className="block text-gray-700"
                            >
                                Cover Image
                            </label>
                            <input
                                type="file"
                                id="cover_image"
                                accept="image/*"
                                onChange={(e) =>
                                    setCoverImage(e.target.files[0])
                                }
                                className="mt-2 p-2 w-full border rounded"
                            />
                        </div>
                        <button
                            type="submit"
                            className="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700"
                        >
                            Update Book
                        </button>
                    </form>
                </div>
            </div>
        </>
    );
};

export default EditBooks;
