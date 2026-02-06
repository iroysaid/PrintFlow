<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-white">User Management</h2>
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
                    <thead>
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Role</th>
                            <th class="text-end pe-4">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($users as $u): ?>
                        <tr>
                            <td class="ps-4 text-muted"><?= $u['id'] ?></td>
                            <td class="fw-bold text-white"><?= $u['username'] ?></td>
                            <td><?= $u['fullname'] ?></td>
                            <td>
                                <span class="badge rounded-0 border border-secondary text-uppercase <?= $u['role'] == 'admin' ? 'text-warning' : 'text-info' ?>">
                                    <?= $u['role'] ?>
                                </span>
                            </td>
                            <td class="text-end pe-4 text-muted"><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content glass-panel" style="background: #1a1a1a; border: 1px solid #333;">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title">Create User</h5>
                <button type="button" class="btn-close filter-invert" data-bs-dismiss="modal"></button>
            </div>
            <form action="/admin/users/create" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Full Name</label>
                        <input type="text" name="fullname" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Role</label>
                        <select name="role" class="form-select">
                            <option value="cashier">Cashier</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary">
                    <button type="submit" class="btn btn-light w-100">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
