// ===== Navbar Mobile Menu =====
document.getElementById("menuToggle")?.addEventListener("click", () => {
    document.getElementById("mobileMenu").classList.remove("hidden");
});
document.getElementById("closeMenu")?.addEventListener("click", () => {
    document.getElementById("mobileMenu").classList.add("hidden");
});

// ===== Dark Mode Toggle =====
const darkModeToggle = document.getElementById("darkModeToggle");
darkModeToggle?.addEventListener("click", () => {
    document.documentElement.classList.toggle("dark");
});

// ===== Banner Carousel =====
const slides = document.querySelectorAll("#carousel > div");
let index = 0;
function showSlide(i) {
    slides.forEach(
        (slide, idx) => (slide.style.opacity = idx === i ? "1" : "0")
    );
}
setInterval(() => {
    index = (index + 1) % slides.length;
    showSlide(index);
}, 4000);

// ===== Today's Deals =====
const dealSlider = document.getElementById("dealSlider");
if (dealSlider) {
    const dealNext = document.getElementById("dealNext");
    const dealPrev = document.getElementById("dealPrev");
    const scrollAmount = 250;
    dealNext.addEventListener("click", () =>
        dealSlider.scrollBy({ left: scrollAmount, behavior: "smooth" })
    );
    dealPrev.addEventListener("click", () =>
        dealSlider.scrollBy({ left: -scrollAmount, behavior: "smooth" })
    );
    setInterval(() => {
        if (
            dealSlider.scrollLeft + dealSlider.clientWidth >=
            dealSlider.scrollWidth
        )
            dealSlider.scrollTo({ left: 0, behavior: "smooth" });
        else dealSlider.scrollBy({ left: scrollAmount, behavior: "smooth" });
    }, 4000);
}

// ===== Countdown Timer =====
let countdownEnd = new Date();
countdownEnd.setHours(countdownEnd.getHours() + 6);
const countdown = document.getElementById("dealCountdown");
if (countdown) {
    setInterval(() => {
        const now = new Date().getTime();
        const distance = countdownEnd - now;
        if (distance < 0) return (countdown.innerText = "Expired");
        const h = Math.floor((distance / (1000 * 60 * 60)) % 24);
        const m = Math.floor((distance / (1000 * 60)) % 60);
        const s = Math.floor((distance / 1000) % 60);
        countdown.innerText = `${String(h).padStart(2, "0")}:${String(
            m
        ).padStart(2, "0")}:${String(s).padStart(2, "0")}`;
    }, 1000);
}
