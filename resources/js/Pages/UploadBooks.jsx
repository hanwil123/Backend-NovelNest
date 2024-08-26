import React, { useState, useEffect } from "react";
import NavbarDashboard from "@/Components/NavbarDashboard";
import { SidebarDashboard } from "@/Components/SidebarDashboard";
import axios from "axios";

const UploadBooks = () => {
    const [judul, setJudul] = useState("");
    const [penulis, setPenulis] = useState("");
    const [categoryId, setCategoryId] = useState("");
    const [sinopsis, setSinopsis] = useState("");
    const [isibuku, setIsibuku] = useState(null);
    const [coverImage, setCoverImage] = useState(null);
    const [categories, setCategories] = useState([]);

    // Fetch categories from the backend
    useEffect(() => {
        axios
            .get("/api/categories")
            .then((response) => setCategories(response.data))
            .catch((error) =>
                console.error("Error fetching categories:", error)
            );
    }, []);

    const handleSubmit = async (e) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append("judul", judul);
        formData.append("penulis", penulis);
        formData.append("category_id", categoryId);
        formData.append("sinopsis", sinopsis);
        if (isibuku) formData.append("isibuku", isibuku);
        if (coverImage) formData.append("cover_image", coverImage);

        try {
            const response = await axios.post("/api/books", formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });

            console.log("data buku : ", response.data);
            alert("Book uploaded successfully!");
            // Reset form fields
            setJudul("");
            setPenulis("");
            setCategoryId("");
            setSinopsis("");
            setIsibuku(null);
            setCoverImage(null);
        } catch (error) {
            console.error("Error uploading book:", error);
            alert("Failed to upload book");
        }
    };

    return (
        <>
            <div className=" container h-full flex overflow-y-auto overflow-x-hidden">
                <SidebarDashboard />
                <div className="flex-col w-full h-full">
                    <div className=" flex mx-auto my-5">
                        <h1>Upload Books</h1>
                    </div>
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
                                required
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
                                required
                                className="mt-2 p-2 w-full border rounded"
                            />
                        </div>
                        <button
                            type="submit"
                            className="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700"
                        >
                            Upload Book
                        </button>
                    </form>
                </div>
            </div>
        </>
    );
};

export default UploadBooks;
