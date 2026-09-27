function applyTheme(theme) {
  const isDark = theme === "dark";
  document.body.classList.toggle("dark-theme", isDark);

  const button = document.getElementById("themeBtn");
  if (button) {
    button.textContent = isDark ? "Light Mode" : "Dark Mode";
    button.setAttribute("aria-label", isDark ? "Switch to light mode" : "Switch to dark mode");
  }

  localStorage.setItem("theme", isDark ? "dark" : "light");
}

function createThemeButton() {
  let button = document.getElementById("themeBtn");

  if (!button) {
    button = document.createElement("button");
    button.id = "themeBtn";
    button.type = "button";
    button.className = "theme-toggle";

    const navList = document.querySelector(".main-nav ul");
    if (navList) {
      navList.appendChild(button);
    } else {
      document.body.insertBefore(button, document.body.firstChild);
    }
  }

  return button;
}

const button = createThemeButton();
const savedTheme = localStorage.getItem("theme");
applyTheme(savedTheme === "dark" ? "dark" : "light");

button.addEventListener("click", function () {
  const nextTheme = document.body.classList.contains("dark-theme") ? "light" : "dark";
  applyTheme(nextTheme);
});