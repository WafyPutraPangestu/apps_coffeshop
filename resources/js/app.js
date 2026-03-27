import "./bootstrap";

// 🚀 Ubah menjadi window.applyTheme agar bisa dipanggil dari luar (termasuk Livewire)
window.applyTheme = function () {
    let theme = document.cookie.match(/theme=([^;]+)/)?.[1];

    if (!theme) {
        theme = localStorage.getItem("theme") || "dark";
    }

    const html = document.documentElement;

    if (theme === "light") {
        html.classList.add("light");
    } else {
        html.classList.remove("light");
    }

    localStorage.setItem("theme", theme);
};

// Panggil fungsi globalnya
document.addEventListener("DOMContentLoaded", window.applyTheme);
document.addEventListener("livewire:navigated", window.applyTheme);
window.addEventListener("popstate", window.applyTheme);

document.addEventListener("livewire:navigating", () => {});
