/**
 * MAURICE APPLIANCES — Document Preview & Viewer Modal
 * Allows users to preview PDFs and high-res images directly on screen
 * or open them natively in a new browser tab before/without downloading.
 */

export function initDocViewer() {
  const modal = document.getElementById("docViewerModal");
  if (!modal) return;

  const backdrop = document.getElementById("docViewerBackdrop");
  const closeBtn = document.getElementById("docModalCloseBtn");
  const titleEl = document.getElementById("docModalTitle");
  const badgeEl = document.getElementById("docModalBadge");
  const tabBtn = document.getElementById("docModalTabBtn");
  const dlBtn = document.getElementById("docModalDlBtn");
  const frameEl = document.getElementById("docModalFrame");
  const imgWrapEl = document.getElementById("docModalImgWrap");
  const imgEl = document.getElementById("docModalImg");
  const loaderEl = document.getElementById("docModalLoader");

  function openDoc(docData) {
    const { url, title, type, size, download } = docData;

    // Update Header Metadata
    if (titleEl) titleEl.textContent = title || "Document Preview";
    if (badgeEl) {
      badgeEl.textContent = `${(type || "FILE").toUpperCase()} · ${size || "Official"}`;
    }

    // Update Toolbar Actions
    if (tabBtn) {
      tabBtn.href = url;
      tabBtn.title = `Open ${title} in a new browser tab`;
    }
    if (dlBtn) {
      dlBtn.href = url;
      dlBtn.setAttribute("download", download || title || "document");
      dlBtn.title = `Download ${title}`;
    }

    // Reset viewports
    if (loaderEl) {
      loaderEl.style.display = "flex";
      loaderEl.style.opacity = "1";
    }

    if (type === "image") {
      if (frameEl) {
        frameEl.style.display = "none";
        frameEl.src = "about:blank";
      }
      if (imgWrapEl && imgEl) {
        imgWrapEl.style.display = "flex";
        imgEl.onload = () => {
          if (loaderEl) {
            loaderEl.style.opacity = "0";
            setTimeout(() => {
              loaderEl.style.display = "none";
            }, 200);
          }
        };
        imgEl.onerror = () => {
          if (loaderEl) loaderEl.style.display = "none";
        };
        imgEl.src = url;
        imgEl.alt = title;
      }
    } else {
      // PDF or other document
      if (imgWrapEl && imgEl) {
        imgWrapEl.style.display = "none";
        imgEl.src = "";
      }
      if (frameEl) {
        frameEl.style.display = "block";
        frameEl.onload = () => {
          if (loaderEl) {
            loaderEl.style.opacity = "0";
            setTimeout(() => {
              loaderEl.style.display = "none";
            }, 200);
          }
        };
        // Load document
        frameEl.src = url;
        // Fallback hide loader after 1.2s in case iframe onload event does not fire for PDF plugin
        setTimeout(() => {
          if (loaderEl && loaderEl.style.opacity !== "0") {
            loaderEl.style.opacity = "0";
            setTimeout(() => {
              loaderEl.style.display = "none";
            }, 200);
          }
        }, 1200);
      }
    }

    // Open Modal
    modal.style.display = "flex";
    void modal.offsetWidth; // force reflow for smooth CSS transitions
    modal.classList.add("is-open");
    modal.setAttribute("aria-hidden", "false");
    document.body.style.overflow = "hidden";

    if (closeBtn) closeBtn.focus();
  }

  function closeDoc() {
    modal.classList.remove("is-open");
    modal.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";

    setTimeout(() => {
      if (!modal.classList.contains("is-open")) {
        modal.style.display = "none";
        if (frameEl) frameEl.src = "about:blank";
        if (imgEl) imgEl.src = "";
      }
    }, 250);
  }

  // Delegate clicks on preview buttons and rows
  document.addEventListener("click", (e) => {
    // 1. Direct preview button click
    const viewBtn = e.target.closest("[data-doc-view]");
    if (viewBtn) {
      e.preventDefault();
      const docData = {
        url: viewBtn.getAttribute("data-doc-url"),
        title: viewBtn.getAttribute("data-doc-title"),
        type: viewBtn.getAttribute("data-doc-type"),
        size: viewBtn.getAttribute("data-doc-size"),
        download: viewBtn.getAttribute("data-doc-download"),
      };
      if (docData.url) openDoc(docData);
      return;
    }

    // 2. Click on card row itself (unless clicking another link/button)
    const row = e.target.closest(".drow");
    if (row && !e.target.closest("a") && !e.target.closest("button")) {
      const primaryBtn = row.querySelector("[data-doc-view]");
      if (primaryBtn) {
        e.preventDefault();
        primaryBtn.click();
      }
    }

    // 3. Modal close click (close button or backdrop)
    if (e.target.closest("#docModalCloseBtn") || e.target === backdrop) {
      e.preventDefault();
      closeDoc();
    }
  });

  // Keyboard accessibility
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && modal.classList.contains("is-open")) {
      closeDoc();
    }
  });
}
