/**
 * ========================================
 * RMIB FORM VALIDATION SYSTEM
 * ========================================
 * Fitur:
 * - Real-time validation untuk peringkat unik per kelompok
 * - Progress indicator per kelompok
 * - Visual feedback (hijau/merah) untuk status pengisian
 * - Validasi Top 1/2/3 tidak boleh sama
 * - Auto-filter dropdown Top 3 berdasarkan rank 1
 * - Submit validation
 */

// ========================================
// GLOBAL STATE
// ========================================
const formState = {
    totalGroups: 9,
    jobsPerGroup: 12,
    groupProgress: {}, // Track progress per kelompok
    groupValid: {}, // Track validitas per kelompok
};

// ========================================
// INITIALIZATION
// ========================================
document.addEventListener("DOMContentLoaded", function () {
    initializeForm();
    initializeTooltips();
    updateTop3Dropdowns();
});

/**
 * Initialize form event listeners
 */
function initializeForm() {
    const groups = document.querySelectorAll(".job-ranking-group");

    groups.forEach((group, groupIndex) => {
        const kelompok = getKelompokFromGroup(group);
        formState.groupProgress[kelompok] = 0;
        formState.groupValid[kelompok] = false;

        // Add progress indicator to group header
        addProgressIndicator(group, kelompok);

        // Add event listeners to all inputs in this group
        const inputs = group.querySelectorAll(".job-rank-input");
        inputs.forEach((input) => {
            // Real-time validation on change
            input.addEventListener("change", function () {
                handleInputChange(this, group, kelompok);
            });

            // Clear error on focus
            input.addEventListener("focus", function () {
                clearInputError(this);
            });

            // Prevent non-numeric input
            input.addEventListener("keypress", function (e) {
                if (!/[0-9]/.test(e.key)) {
                    e.preventDefault();
                }
            });
        });
    });

    // Add validation for Top 1/2/3 dropdowns
    ["top1", "top2", "top3"].forEach((id) => {
        const dropdown = document.getElementById(id);
        if (dropdown) {
            dropdown.addEventListener("change", validateTop3Selection);
        }
    });

    // Form submit validation
    const form = document.getElementById("rmibForm");
    if (form) {
        form.addEventListener("submit", handleFormSubmit);
    }
}

/**
 * Initialize Bootstrap tooltips
 */
function initializeTooltips() {
    const tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// ========================================
// PROGRESS INDICATOR
// ========================================

/**
 * Add progress indicator to group header
 */
function addProgressIndicator(group, kelompok) {
    const header = group.querySelector("h4");
    if (!header) return;

    const progressContainer = document.createElement("div");
    progressContainer.className = "progress-indicator mt-2";
    progressContainer.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-1">
            <small class="progress-text">Progress: <span class="progress-count">0/${formState.jobsPerGroup}</span></small>
            <span class="status-badge badge bg-secondary">Belum Selesai</span>
        </div>
        <div class="progress" style="height: 8px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated"
                 role="progressbar"
                 style="width: 0%;"
                 aria-valuenow="0"
                 aria-valuemin="0"
                 aria-valuemax="${formState.jobsPerGroup}">
            </div>
        </div>
    `;

    header.insertAdjacentElement("afterend", progressContainer);
}

/**
 * Update progress indicator for a specific group
 */
function updateProgressIndicator(group, kelompok) {
    const inputs = group.querySelectorAll(".job-rank-input");
    const filledInputs = Array.from(inputs).filter((input) => input.value !== "");
    const progress = filledInputs.length;
    const percentage = (progress / formState.jobsPerGroup) * 100;

    formState.groupProgress[kelompok] = progress;

    // Update progress bar
    const progressBar = group.querySelector(".progress-bar");
    const progressCount = group.querySelector(".progress-count");
    const statusBadge = group.querySelector(".status-badge");

    if (progressBar) {
        progressBar.style.width = `${percentage}%`;
        progressBar.setAttribute("aria-valuenow", progress);
    }

    if (progressCount) {
        progressCount.textContent = `${progress}/${formState.jobsPerGroup}`;
    }

    // Validate if all inputs are filled and unique
    const isValid = validateGroupCompleteness(group, kelompok);
    formState.groupValid[kelompok] = isValid;

    // Update status badge
    if (statusBadge) {
        if (progress === 0) {
            statusBadge.className = "status-badge badge bg-secondary";
            statusBadge.textContent = "Belum Selesai";
        } else if (progress === formState.jobsPerGroup && isValid) {
            statusBadge.className = "status-badge badge bg-success";
            statusBadge.textContent = "✓ Selesai";
            if (progressBar) {
                progressBar.classList.remove("progress-bar-animated", "bg-warning", "bg-danger");
                progressBar.classList.add("bg-success");
            }
        } else if (progress === formState.jobsPerGroup && !isValid) {
            statusBadge.className = "status-badge badge bg-danger";
            statusBadge.textContent = "✗ Ada Duplikat";
            if (progressBar) {
                progressBar.classList.remove("progress-bar-animated", "bg-success");
                progressBar.classList.add("bg-danger");
            }
        } else {
            statusBadge.className = "status-badge badge bg-warning";
            statusBadge.textContent = "Dalam Progress";
            if (progressBar) {
                progressBar.classList.remove("bg-success", "bg-danger");
                progressBar.classList.add("progress-bar-animated");
            }
        }
    }

}

// ========================================
// INPUT VALIDATION
// ========================================

/**
 * Handle input change event
 */
function handleInputChange(input, group, kelompok) {
    const currentValue = input.value.trim();

    // Clear previous error
    clearInputError(input);

    // Skip validation if empty
    if (currentValue === "") {
        updateProgressIndicator(group, kelompok);
        updateTop3Dropdowns();
        return;
    }

    // Validate range (1-12)
    const numericValue = parseInt(currentValue);
    if (isNaN(numericValue) || numericValue < 1 || numericValue > 12) {
        showInputError(input, "Masukkan angka 1-12!");
        input.value = "";
        updateProgressIndicator(group, kelompok);
        updateTop3Dropdowns();
        return;
    }

    // Validate uniqueness
    const inputs = group.querySelectorAll(".job-rank-input");
    let duplicateFound = false;

    inputs.forEach((otherInput) => {
        if (otherInput !== input && otherInput.value === currentValue) {
            duplicateFound = true;
        }
    });

    if (duplicateFound) {
        showInputError(input, `Angka ${currentValue} sudah dipakai!`);
        input.value = "";
        updateProgressIndicator(group, kelompok);
        updateTop3Dropdowns();
        return;
    }

    // Mark input as valid
    input.classList.add("is-valid");
    updateProgressIndicator(group, kelompok);
    updateTop3Dropdowns();
}

/**
 * Update overall form progress (removed - no longer needed)
 */
function updateOverallProgress() {
    // Function kept for compatibility but does nothing
}

/**
 * Validate group completeness
 */
function validateGroupCompleteness(group, kelompok) {
    const inputs = group.querySelectorAll(".job-rank-input");
    const values = new Set();
    let allFilled = true;
    let hasDuplicate = false;

    inputs.forEach((input) => {
        const value = input.value.trim();
        if (value === "") {
            allFilled = false;
        } else {
            const numericValue = parseInt(value);
            if (values.has(numericValue)) {
                hasDuplicate = true;
            }
            values.add(numericValue);
        }
    });

    return allFilled && !hasDuplicate && values.size === formState.jobsPerGroup;
}

/**
 * Show input error with tooltip and styling
 */
function showInputError(input, message) {
    input.classList.add("is-invalid");
    input.classList.remove("is-valid");
    input.setAttribute("data-bs-toggle", "tooltip");
    input.setAttribute("data-bs-placement", "top");
    input.setAttribute("title", message);

    const tooltip = new bootstrap.Tooltip(input);
    tooltip.show();

    // Auto-hide tooltip after 3 seconds
    setTimeout(() => {
        tooltip.dispose();
    }, 3000);
}

/**
 * Clear input error state
 */
function clearInputError(input) {
    const tooltip = bootstrap.Tooltip.getInstance(input);
    if (tooltip) {
        tooltip.dispose();
    }
    input.removeAttribute("data-bs-toggle");
    input.removeAttribute("data-bs-placement");
    input.removeAttribute("title");
    input.classList.remove("is-invalid");
}

// ========================================
// TOP 3 DROPDOWN VALIDATION
// ========================================

/**
 * Update Top 1/2/3 dropdowns to only show jobs with rank 1
 */
function updateTop3Dropdowns() {
    const rank1Jobs = getRank1Jobs();

    ["top1", "top2", "top3"].forEach((dropdownId) => {
        const dropdown = document.getElementById(dropdownId);
        if (!dropdown) return;

        const currentValue = dropdown.value;

        // Clear dropdown
        dropdown.innerHTML = '<option value="" disabled selected>-- Pilih Pekerjaan --</option>';

        if (rank1Jobs.length === 0) {
            dropdown.innerHTML =
                '<option value="" disabled selected>-- Berikan rank 1 pada pekerjaan favorit Anda di atas --</option>';
            return;
        }

        // Group jobs by kelompok
        const groupedJobs = {};
        rank1Jobs.forEach((job) => {
            if (!groupedJobs[job.kelompok]) {
                groupedJobs[job.kelompok] = [];
            }
            groupedJobs[job.kelompok].push(job.nama);
        });

        // Add optgroups
        Object.keys(groupedJobs)
            .sort()
            .forEach((kelompok) => {
                const optgroup = document.createElement("optgroup");
                optgroup.label = kelompok;

                groupedJobs[kelompok].forEach((jobName) => {
                    const option = document.createElement("option");
                    option.value = jobName;
                    option.textContent = jobName;
                    optgroup.appendChild(option);
                });

                dropdown.appendChild(optgroup);
            });

        // Restore previous selection if still valid
        if (currentValue && rank1Jobs.some((job) => job.nama === currentValue)) {
            dropdown.value = currentValue;
        }
    });

    // Validate Top 3 selection after update
    validateTop3Selection();
}

/**
 * Get all jobs with rank 1
 */
function getRank1Jobs() {
    const rank1Jobs = [];

    document.querySelectorAll(".job-ranking-group").forEach((group) => {
        const kelompokTitle = group.querySelector("h4").textContent.split("\n")[0].trim();
        const inputs = group.querySelectorAll(".job-rank-input");

        inputs.forEach((input) => {
            if (input.value === "1") {
                const label = group.querySelector(`label[for="${input.id}"]`);
                if (label) {
                    rank1Jobs.push({
                        kelompok: kelompokTitle,
                        nama: label.textContent.trim(),
                    });
                }
            }
        });
    });

    return rank1Jobs;
}

/**
 * Validate that Top 1/2/3 are different
 */
function validateTop3Selection() {
    const top1 = document.getElementById("top1");
    const top2 = document.getElementById("top2");
    const top3 = document.getElementById("top3");

    if (!top1 || !top2 || !top3) return;

    const values = [top1.value, top2.value, top3.value].filter((v) => v !== "");

    // Clear previous errors
    [top1, top2, top3].forEach((dropdown) => {
        dropdown.classList.remove("is-invalid");
    });

    // Check for duplicates
    const uniqueValues = new Set(values);
    if (values.length > 0 && uniqueValues.size !== values.length) {
        [top1, top2, top3].forEach((dropdown) => {
            if (dropdown.value !== "") {
                dropdown.classList.add("is-invalid");
            }
        });
        return false;
    }

    return true;
}

// ========================================
// FORM SUBMIT VALIDATION
// ========================================

/**
 * Handle form submit
 */
function handleFormSubmit(event) {
    let isValid = true;
    const errors = [];

    // Validate all groups
    document.querySelectorAll(".job-ranking-group").forEach((group) => {
        const kelompok = getKelompokFromGroup(group);
        const inputs = group.querySelectorAll(".job-rank-input");
        const values = new Set();
        let groupComplete = true;
        let hasDuplicate = false;

        inputs.forEach((input) => {
            const value = input.value.trim();
            if (value === "") {
                groupComplete = false;
                input.classList.add("is-invalid");
            } else {
                const numericValue = parseInt(value);
                if (isNaN(numericValue) || numericValue < 1 || numericValue > 12) {
                    isValid = false;
                    input.classList.add("is-invalid");
                    errors.push(`${kelompok}: Nilai harus antara 1-12`);
                } else if (values.has(numericValue)) {
                    hasDuplicate = true;
                    input.classList.add("is-invalid");
                } else {
                    values.add(numericValue);
                }
            }
        });

        if (!groupComplete) {
            isValid = false;
            errors.push(`${kelompok}: Belum semua pekerjaan diberi peringkat`);
        }

        if (hasDuplicate) {
            isValid = false;
            errors.push(`${kelompok}: Ada angka yang digunakan lebih dari sekali`);
        }
    });

    // Validate Top 1/2/3
    const top1 = document.getElementById("top1");
    const top2 = document.getElementById("top2");
    const top3 = document.getElementById("top3");

    if (!top1.value || !top2.value || !top3.value) {
        isValid = false;
        errors.push("Harap pilih Top 3 pekerjaan favorit Anda");
    }

    const topValues = [top1.value, top2.value, top3.value];
    const uniqueTopValues = new Set(topValues);
    if (uniqueTopValues.size !== topValues.length) {
        isValid = false;
        errors.push("Top 1, 2, dan 3 tidak boleh sama");
        [top1, top2, top3].forEach((dropdown) => dropdown.classList.add("is-invalid"));
    }

    if (!isValid) {
        event.preventDefault();

        // Show error modal or alert
        const errorMessage = errors.length > 0 ? errors.join("\n• ") : "Harap periksa kembali form Anda";
        alert(`Validasi Gagal:\n\n• ${errorMessage}`);

        // Scroll to first error
        const firstError = document.querySelector(".is-invalid");
        if (firstError) {
            firstError.scrollIntoView({ behavior: "smooth", block: "center" });
            firstError.focus();
        }

        return false;
    }

    // Disable submit button to prevent double submission
    const submitButton = document.getElementById("submit-button");
    if (submitButton) {
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';
    }

    return true;
}

// ========================================
// UTILITY FUNCTIONS
// ========================================

/**
 * Get kelompok name from group element
 */
function getKelompokFromGroup(group) {
    const header = group.querySelector("h4");
    if (!header) return "";
    return header.textContent.split("\n")[0].trim();
}
