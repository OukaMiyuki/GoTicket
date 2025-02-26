document.addEventListener("DOMContentLoaded", function () {
    var quill = new Quill("#detail-editor", {
        theme: "snow",
        modules: {
            toolbar: [
                [{ header: [1, 2, false] }],
                ["bold", "italic", "underline"],
                [{ list: "ordered" }, { list: "bullet" }],
                ["link", "image", "video"],
                ["clean"]
            ]
        }
    });

    document.querySelector("#package-form").addEventListener("submit", function () {
        document.querySelector("#detail").value = quill.root.innerHTML;
    });
});
