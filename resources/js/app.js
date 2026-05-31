import * as bootstrap from "bootstrap";
import $ from "jquery";
import DataTable from "datatables.net-bs5";
import "datatables.net-responsive-bs5";
import Swal from "sweetalert2";

window.$ = window.jQuery = $;
window.bootstrap = bootstrap;
window.Swal = Swal;

document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.getElementById("sidebar");
    document.querySelectorAll("[data-sidebar-toggle]").forEach((button) => {
        button.addEventListener("click", () =>
            sidebar?.classList.toggle("open"),
        );
    });

    const resendButton = document.querySelector("[data-resend-otp]");
    if (resendButton) {
        let remaining = Number(resendButton.dataset.cooldown || 120);
        const originalText = resendButton.textContent;
        resendButton.disabled = true;

        const tick = () => {
            resendButton.textContent = `Resend OTP (${remaining}s)`;
            remaining -= 1;

            if (remaining < 0) {
                resendButton.disabled = false;
                resendButton.textContent = originalText;
                return;
            }

            window.setTimeout(tick, 1000);
        };

        tick();
    }

    const usersTable = document.getElementById("usersTable");
    if (usersTable?.dataset.apiUrl) {
        bootUsersTable({ apiUrl: usersTable.dataset.apiUrl });
    }
});

function bootUsersTable(config) {
    if (!DataTable) {
        showUsersTableError(
            "The users table could not be loaded because DataTables is unavailable.",
        );
        return;
    }

    const modalElement = document.getElementById("userModal");
    const modal = new bootstrap.Modal(modalElement);
    const form = document.getElementById("userForm");
    const saveButton = document.getElementById("saveUserButton");
    const spinner = saveButton.querySelector(".spinner-border");
    const title = document.getElementById("userModalTitle");
    const passwordField = modalElement.querySelector("[data-password-field]");
    const emailInput = document.getElementById("modalEmail");
    const userIdInput = document.getElementById("userId");

    const table = new DataTable("#usersTable", {
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        lengthChange: false,
        searching: true,
        dom: '<"datatable-top"f>rt<"datatable-bottom"ip>',
        ajax: {
            url: config.apiUrl,
            type: "GET",
            xhrFields: {
                withCredentials: true,
            },
            headers: {
                Accept: "application/json",
                "X-CSRF-TOKEN": csrfToken(),
            },
            error(xhr) {
                const message =
                    xhr.status === 401
                        ? "Your session expired. Please log in again."
                        : "The users list could not be loaded. Please refresh the page.";

                showUsersTableError(message);
            },
        },
        language: {
            emptyTable: "No users found.",
            processing: "Loading users...",
            search: "",
            searchPlaceholder: "Search all users",
        },
        columns: [
            { data: "id" },
            { data: "first_name" },
            { data: "last_name" },
            { data: "email" },
            { data: "phone_number" },
            { data: "last_login_at" },
            {
                data: "status",
                render(status) {
                    const label = status === "active" ? "Active" : "InActive";
                    const css =
                        status === "active"
                            ? "text-bg-success"
                            : "text-bg-secondary";
                    return `<span class="badge badge-status ${css}">${label}</span>`;
                },
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render(row) {
                    return `
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary" data-edit-user="${row.id}" title="Edit User"><i class="bi bi-pencil"></i></button>
                            <button type="button" class="btn btn-outline-danger" data-delete-user="${row.id}" title="Delete User"><i class="bi bi-trash"></i></button>
                        </div>
                    `;
                },
            },
        ],
    });

    document.getElementById("addUserButton").addEventListener("click", () => {
        resetUserForm();
        title.textContent = "Add User";
        passwordField.classList.remove("d-none");
        emailInput.disabled = false;
        modal.show();
    });

    $("#usersTable").on("click", "[data-edit-user]", async function () {
        resetUserForm();
        title.textContent = "Edit User";
        passwordField.classList.add("d-none");
        emailInput.disabled = true;

        const response = await fetch(
            `${config.apiUrl}/${this.dataset.editUser}`,
            {
                credentials: "same-origin",
                headers: { Accept: "application/json" },
            },
        );
        const payload = await response.json();
        const user = payload.data;

        userIdInput.value = user.id;
        document.getElementById("modalFirstName").value = user.first_name;
        document.getElementById("modalLastName").value = user.last_name;
        emailInput.value = user.email;
        document.getElementById("modalPhone").value = user.phone_number || "";
        document.getElementById("modalStatus").value = user.status;
        modal.show();
    });

    $("#usersTable").on("click", "[data-delete-user]", async function () {
        const result = await Swal.fire({
            title: "Delete user?",
            text: "This user will be removed from the interface.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Delete",
            confirmButtonColor: "#dc3545",
        });

        if (!result.isConfirmed) {
            return;
        }

        const response = await fetch(
            `${config.apiUrl}/${this.dataset.deleteUser}`,
            {
                method: "DELETE",
                credentials: "same-origin",
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": csrfToken(),
                },
            },
        );

        if (!response.ok) {
            const payload = await response.json();
            Swal.fire("Unable to delete", firstError(payload), "error");
            return;
        }

        Swal.fire("Deleted", "User deleted successfully.", "success");
        table.ajax.reload(null, false);
    });

    form.addEventListener("submit", async (event) => {
        event.preventDefault();
        clearFormErrors();
        setSaving(true);

        const userId = userIdInput.value;
        const body = {
            first_name: document.getElementById("modalFirstName").value,
            last_name: document.getElementById("modalLastName").value,
            phone_number: document.getElementById("modalPhone").value,
            status: document.getElementById("modalStatus").value,
        };

        if (!userId) {
            body.email = emailInput.value;
            body.password = document.getElementById("modalPassword").value;
        }

        const response = await fetch(
            userId ? `${config.apiUrl}/${userId}` : config.apiUrl,
            {
                method: userId ? "PUT" : "POST",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken(),
                },
                credentials: "same-origin",
                body: JSON.stringify(body),
            },
        );

        setSaving(false);

        if (!response.ok) {
            const payload = await response.json();
            applyFormErrors(payload.errors || {});
            return;
        }

        modal.hide();
        Swal.fire("Saved", "User saved successfully.", "success");
        table.ajax.reload(null, false);
    });

    function resetUserForm() {
        form.reset();
        userIdInput.value = "";
        clearFormErrors();
    }

    function clearFormErrors() {
        form.querySelectorAll(".is-invalid").forEach((element) =>
            element.classList.remove("is-invalid"),
        );
        form.querySelectorAll("[data-error-for]").forEach((element) => {
            element.textContent = "";
        });
    }

    function applyFormErrors(errors) {
        Object.entries(errors).forEach(([field, messages]) => {
            const input = form.querySelector(`[name="${field}"]`);
            const feedback = form.querySelector(`[data-error-for="${field}"]`);
            input?.classList.add("is-invalid");
            if (feedback) {
                feedback.textContent = messages[0];
            }
        });
    }

    function setSaving(saving) {
        saveButton.disabled = saving;
        spinner.classList.toggle("d-none", !saving);
    }
}

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content || "";
}

function showUsersTableError(message) {
    const errorBox = document.getElementById("usersTableError");

    if (!errorBox) {
        return;
    }

    errorBox.textContent = message;
    errorBox.classList.remove("d-none");
}

function debounce(callback, wait) {
    let timeout;

    return (...args) => {
        window.clearTimeout(timeout);
        timeout = window.setTimeout(() => callback.apply(null, args), wait);
    };
}

function firstError(payload) {
    if (payload?.errors) {
        return Object.values(payload.errors)[0]?.[0] || "Please try again.";
    }

    return payload?.message || "Please try again.";
}
