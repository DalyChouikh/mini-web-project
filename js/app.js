/**
 * Minimal Blog - JavaScript Application
 * Handles: Theme toggle, scroll indicator, back-to-top, AJAX comments, toast notifications
 */

const themeToggle = document.getElementById("theme-toggle");
const themeIcon = themeToggle?.querySelector(".theme-icon");

function getTheme() {
  return localStorage.getItem("blog-theme") || "light";
}

function setTheme(theme) {
  document.documentElement.setAttribute("data-theme", theme);
  localStorage.setItem("blog-theme", theme);
  document.cookie = `theme=${theme};path=/;max-age=31536000`;
  if (themeIcon) {
    themeIcon.textContent = theme === "dark" ? "🌙" : "☀️";
  }
}

setTheme(getTheme());

themeToggle?.addEventListener("click", () => {
  const currentTheme = getTheme();
  setTheme(currentTheme === "dark" ? "light" : "dark");
});

const scrollIndicator = document.getElementById("scroll-indicator");

function updateScrollIndicator() {
  if (!scrollIndicator) return;

  const scrollTop = window.scrollY;
  const docHeight = document.documentElement.scrollHeight - window.innerHeight;
  const progress = docHeight > 0 ? scrollTop / docHeight : 0;

  scrollIndicator.style.transform = `scaleX(${progress})`;
}

window.addEventListener("scroll", updateScrollIndicator);
updateScrollIndicator();

const backToTopBtn = document.getElementById("back-to-top");

function updateBackToTop() {
  if (!backToTopBtn) return;

  if (window.scrollY > 300) {
    backToTopBtn.classList.add("visible");
  } else {
    backToTopBtn.classList.remove("visible");
  }
}

window.addEventListener("scroll", updateBackToTop);
updateBackToTop();

backToTopBtn?.addEventListener("click", () => {
  window.scrollTo({
    top: 0,
    behavior: "smooth",
  });
});

const toast = document.getElementById("toast");

function showToast(message, type = "success") {
  if (!toast) return;

  toast.textContent = message;
  toast.className = `toast ${type} visible`;

  setTimeout(() => {
    toast.classList.remove("visible");
  }, 3000);
}

window.showToast = showToast;

const commentForm = document.getElementById("comment-form");

commentForm?.addEventListener("submit", async (e) => {
  e.preventDefault();

  const articleId = commentForm.dataset.articleId;
  const authorInput = document.getElementById("comment-name");
  const contentInput = document.getElementById("comment-text");
  const submitBtn = commentForm.querySelector(".form-submit");

  const author = authorInput?.value.trim() || "";
  const content = contentInput?.value.trim() || "";

  if (!content) {
    showToast("Please write a comment before posting", "error");
    return;
  }

  if (submitBtn) {
    submitBtn.disabled = true;
    submitBtn.textContent = "Posting...";
  }

  try {
    const formData = new FormData();
    formData.append("article_id", articleId);
    formData.append("author", author);
    formData.append("content", content);

    const response = await fetch("/ajax/comment.php", {
      method: "POST",
      body: formData,
    });

    const result = await response.json();

    if (result.success) {
      showToast(result.message, "success");
      if (authorInput) authorInput.value = "";
      if (contentInput) contentInput.value = "";
    } else {
      showToast(result.message || "Failed to post comment", "error");
    }
  } catch (error) {
    console.error("Error posting comment:", error);
    showToast("Network error. Please try again.", "error");
  } finally {
    if (submitBtn) {
      submitBtn.disabled = false;
      submitBtn.textContent = "Post Comment";
    }
  }
});

const articleCards = document.querySelectorAll(".article-card");

articleCards.forEach((card, index) => {
  card.style.opacity = "0";
  card.style.transform = "translateY(20px)";
  card.style.transition = "opacity 0.4s ease, transform 0.4s ease";

  setTimeout(() => {
    card.style.opacity = "1";
    card.style.transform = "translateY(0)";
  }, index * 100);
});

console.log("Minimal Blog JS loaded successfully!");
