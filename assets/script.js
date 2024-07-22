document.addEventListener("DOMContentLoaded", function(_) {
    // Back to top button
    let back_to_top_button = document.getElementById("back-to-top");
    window.onscroll = function() { scrollFunction() };
    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            back_to_top_button.style.display = "block";
        } else {
            back_to_top_button.style.display = "none";
        }
    }
})
