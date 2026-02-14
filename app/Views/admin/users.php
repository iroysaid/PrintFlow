<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="container py-4">
    <div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-dark fw-bold"><i class="fas fa-users-cog me-2 text-primary"></i>User Management</h2>
            <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addUserModal">
                + New User
            </button>
        </div>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success rounded-0 mb-3 bg-transparent border-success text-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger rounded-0 mb-3 bg-transparent border-danger text-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="glass-panel p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="text-primary">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Role</th>
                            <th>Created At</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($users as $u): ?>
                        <tr>
                            <td class="ps-4 text-muted"><?= $u['id'] ?></td>
                            <td class="fw-bold text-dark"><?= $u['username'] ?></td>
                            <td><?= $u['fullname'] ?></td>
                            <td>
                                <span class="badge rounded-pill border border-secondary text-uppercase <?= $u['role'] == 'admin' ? 'text-warning' : ($u['role'] == 'production' ? 'text-success' : 'text-info') ?>">
                                    <?= $u['role'] ?>
                                </span>
                            </td>
                            <td class="text-muted"><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="populateEdit(<?= htmlspecialchars(json_encode($u)) ?>)">Edit</button>
                                <?php if(session()->get('id') != $u['id']): ?>
                                    <a href="/admin/users/delete/<?= $u['id'] ?>" class="btn btn-sm btn-outline-danger ms-1 rounded-pill px-3" onclick="return confirm('Are you sure?')">Del</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-plus me-2"></i>Create New User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/admin/users/create" method="post">
                <div class="modal-body p-4 bg-light">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary text-uppercase small">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-user text-muted"></i></span>
                            <input type="text" name="username" class="form-control border-start-0 ps-0" placeholder="e.g. johndoe" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary text-uppercase small">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-lock text-muted"></i></span>
                            <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="At least 5 characters" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary text-uppercase small">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-id-card text-muted"></i></span>
                            <input type="text" name="fullname" class="form-control border-start-0 ps-0" placeholder="e.g. John Doe" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary text-uppercase small">Role</label>
                        <select name="role" class="form-select">
                            <option value="cashier">Cashier</option>
                            <option value="production">Production</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-link text-secondary text-decoration-none" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-pill">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-edit me-2"></i>Edit User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="post">
                <div class="modal-body p-4 bg-light">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary text-uppercase small">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-user text-muted"></i></span>
                            <input type="text" id="edit_username" name="username" class="form-control border-start-0 ps-0" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary text-uppercase small">Password <span class="fw-normal text-muted">(Optional)</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-lock text-muted"></i></span>
                            <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="Leave empty to keep current">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary text-uppercase small">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-id-card text-muted"></i></span>
                            <input type="text" id="edit_fullname" name="fullname" class="form-control border-start-0 ps-0" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary text-uppercase small">Role</label>
                        <select id="edit_role" name="role" class="form-select">
                            <option value="cashier">Cashier</option>
                            <option value="production">Production</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                 <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-link text-secondary text-decoration-none" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark px-4 rounded-pill">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function populateEdit(user) {
    document.getElementById('edit_username').value = user.username;
    document.getElementById('edit_fullname').value = user.fullname;
    document.getElementById('edit_role').value = user.role;
    document.getElementById('editForm').action = '/admin/users/update/' + user.id;
    
    var myModal = new bootstrap.Modal(document.getElementById('editUserModal'));
    myModal.show();
}
</script>

<?= $this->endSection() ?>
