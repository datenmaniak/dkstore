(function () {
    document.addEventListener("DOMContentLoaded", function () {
        const closeButtons = document.querySelectorAll(".notification-bar .close-btn");

        closeButtons.forEach(btn => {
            btn.addEventListener("click", function () {
                const bar = this.closest(".notification-bar");
                if (bar) {
                    bar.style.transition = "opacity 0.3s ease";
                    bar.style.opacity = "0";
                    setTimeout(() => bar.remove(), 300);
                }
            });
        });
    });
})();
