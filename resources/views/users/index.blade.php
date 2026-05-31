<x-layouts.app title="Users">
    <div class="page-heading">
        <div>
            <h1>Users</h1>
            <p>Manage active and inactive accounts.</p>
        </div>
        <button type="button" class="btn btn-primary" id="addUserButton">
            <i class="bi bi-plus-lg me-1"></i>Add User
        </button>
    </div>

    <div class="table-surface">
        <div class="table-status alert alert-warning d-none" id="usersTableError"></div>
        <table id="usersTable" class="table table-hover align-middle w-100" data-api-url="{{ url('/api/users') }}">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Last Login Time</th>
                    <th>Status</th>
                    <th data-orderable="false">Actions</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" id="userForm">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="userModalTitle">Add User</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="userId">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label" for="modalFirstName">First Name</label>
                            <input id="modalFirstName" name="first_name" class="form-control" required>
                            <div class="invalid-feedback" data-error-for="first_name"></div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label" for="modalLastName">Last Name</label>
                            <input id="modalLastName" name="last_name" class="form-control" required>
                            <div class="invalid-feedback" data-error-for="last_name"></div>
                        </div>
                        <div class="col-12" data-email-field>
                            <label class="form-label" for="modalEmail">Email</label>
                            <input id="modalEmail" name="email" type="email" class="form-control" required>
                            <div class="invalid-feedback" data-error-for="email"></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="modalPhone">Phone Number</label>
                            <input id="modalPhone" name="phone_number" class="form-control" required>
                            <div class="invalid-feedback" data-error-for="phone_number"></div>
                        </div>
                        <div class="col-12" data-password-field>
                            <label class="form-label" for="modalPassword">Temporary Password</label>
                            <input id="modalPassword" name="password" type="password" class="form-control">
                            <div class="invalid-feedback" data-error-for="password"></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="modalStatus">Status</label>
                            <select id="modalStatus" name="status" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">InActive</option>
                            </select>
                            <div class="invalid-feedback" data-error-for="status"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveUserButton">
                        <span class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
                        <span>Save User</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
