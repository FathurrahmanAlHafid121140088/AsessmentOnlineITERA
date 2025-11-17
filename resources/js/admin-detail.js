/**
 * Admin Mental Health Detail - JavaScript
 * Halaman: admin-mental-health-detail.blade.php
 * Fitur: Detail Jawaban Kuesioner Mental Health & Export PDF
 * Update: 13 November 2025
 */

// Import dependencies jika ada
// import Swal from 'sweetalert2'; // Akan di-load via CDN

/**
 * Initialize page when DOM is ready
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin Detail Page Initialized');

    // Add fade-in animation to cards
    animateCards();

    // Setup table interactions
    setupTableInteractions();

    // Add keyboard shortcuts
    setupKeyboardShortcuts();
});

/**
 * Animate cards on page load
 */
function animateCards() {
    const cards = document.querySelectorAll('.info-card, .summary-card, .table-container');

    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('fade-in');
        }, index * 100);
    });
}

/**
 * Setup table row interactions
 */
function setupTableInteractions() {
    const tableRows = document.querySelectorAll('.detail-table tbody tr');

    tableRows.forEach(row => {
        // Highlight row on hover (already handled by CSS)

        // Add click event to show question details (optional)
        row.addEventListener('click', function() {
            const questionNo = this.querySelector('td:first-child')?.textContent;
            const questionText = this.querySelector('.question-text')?.textContent;
            const skor = this.querySelector('.skor-cell')?.textContent;

            console.log(`Question ${questionNo}: ${skor} points`);
            // Could show a modal or tooltip with more info
        });
    });
}

/**
 * Setup keyboard shortcuts
 */
function setupKeyboardShortcuts() {
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + P: Print PDF
        if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
            e.preventDefault();
            const printBtn = document.querySelector('.btn-print');
            if (printBtn && typeof printDetail === 'function') {
                printDetail();
            }
        }

        // ESC: Go back
        if (e.key === 'Escape') {
            const backBtn = document.querySelector('.btn-back');
            if (backBtn) {
                window.location.href = backBtn.href;
            }
        }
    });
}

/**
 * Utility: Scroll to top smoothly
 */
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

/**
 * Utility: Scroll to element
 */
function scrollToElement(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

/**
 * Show loading overlay
 */
function showLoading(message = 'Loading...') {
    const overlay = document.createElement('div');
    overlay.className = 'loading-overlay';
    overlay.id = 'loadingOverlay';
    overlay.innerHTML = `
        <div class="text-center">
            <div class="loading-spinner"></div>
            <p class="text-white mt-4 font-semibold">${message}</p>
        </div>
    `;
    document.body.appendChild(overlay);
}

/**
 * Hide loading overlay
 */
function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.remove();
    }
}

/**
 * Format date to Indonesian format
 */
function formatDateIndonesia(dateString) {
    const months = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    const date = new Date(dateString);
    const day = date.getDate();
    const month = months[date.getMonth()];
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');

    return `${day} ${month} ${year} - ${hours}:${minutes}`;
}

/**
 * Copy text to clipboard
 */
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Teks berhasil disalin',
                timer: 1500,
                showConfirmButton: false
            });
        } else {
            alert('Teks berhasil disalin');
        }
    }).catch(err => {
        console.error('Failed to copy:', err);
    });
}

/**
 * Export utilities
 */
window.AdminDetailUtils = {
    scrollToTop,
    scrollToElement,
    showLoading,
    hideLoading,
    formatDateIndonesia,
    copyToClipboard
};

// Log when script is loaded
console.log('Admin Detail Utils loaded successfully');
