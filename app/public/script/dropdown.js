window.onload = () => {
    document.getElementById("profile").addEventListener("click", () => {
        var dropdown = document.getElementById("dropdown");
        if (dropdown.style.display == "none") {
            dropdown.style.display = "block";
        } else {
            dropdown.style.display = "none";
        }
})}