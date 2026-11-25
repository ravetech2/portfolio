const menuToggle = document.getElementById("menuToggle");
const navLinks = document.getElementById("navLinks");
if (menuToggle) {
  menuToggle.addEventListener("click", () =>
    navLinks.classList.toggle("active"),
  );
}
const themeToggle = document.getElementById("themeToggle");
const savedTheme = localStorage.getItem("theme");
if (savedTheme === "light") document.body.classList.add("light-mode");
if (themeToggle) {
  themeToggle.addEventListener("click", () => {
    document.body.classList.toggle("light-mode");
    localStorage.setItem(
      "theme",
      document.body.classList.contains("light-mode") ? "light" : "dark",
    );
    if (typeof lucide !== "undefined") lucide.createIcons();
  });
}
document.querySelectorAll(".nav-links a").forEach((link) =>
  link.addEventListener("click", () => {
    if (navLinks) navLinks.classList.remove("active");
  }),
);
const observer = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) entry.target.classList.add("show");
    });
  },
  { threshold: 0.1 },
);
document
  .querySelectorAll(
    ".feature-card,.project-card,.timeline-item,.contact-form-container",
  )
  .forEach((element) => {
    element.classList.add("animate");
    observer.observe(element);
  });

const signUpButton = document.getElementById("signUp");
const signInButton = document.getElementById("signIn");
const container = document.getElementById("container");

if (signUpButton && signInButton && container) {
  signUpButton.addEventListener("click", () => {
    container.classList.add("right-panel-active");
  });

  signInButton.addEventListener("click", () => {
    container.classList.remove("right-panel-active");
  });
}
