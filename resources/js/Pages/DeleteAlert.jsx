import React from "react";

const DeleteAlert = ({ onConfirm, onCancel }) => {
    return (
        <div className="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center">
            <div className="card bg-white text-black w-96 p-5 rounded-lg">
                <div className="card-body items-center text-center">
                    <h2 className="card-title text-xl mb-4">Confirm Deletion</h2>
                    <p className="mb-6">Are you sure you want to delete this book?</p>
                    <div className="card-actions justify-end">
                        <button className="btn btn-red mr-2" onClick={onConfirm}>Delete</button>
                        <button className="btn btn-gray" onClick={onCancel}>Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default DeleteAlert;
