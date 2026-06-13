<?php
require_once __DIR__ . '/../../src/auth.php';
require_once __DIR__ . '/../../src/classes/BorrowRequest.php';
require_login();
$u = current_user();
$incoming = BorrowRequest::listIncoming($u['id']);
$outgoing = BorrowRequest::listOutgoing($u['id']);

include __DIR__ . '/../_layout/header.php';
?>

<div class="container">
    <div class="card">
        <h1>📬 Borrow Requests</h1>
        <p style="color: var(--text-muted); margin-bottom: 2rem;">Manage your incoming and outgoing book requests</p>

        <!-- Incoming Requests -->
        <div style="margin-bottom: 3rem;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                <h2 style="margin: 0;">📥 Incoming Requests</h2>
                <span class="status-badge" style="background: #dbeafe; color: #1e40af;">
                    <?= count($incoming) ?> request<?= count($incoming) !== 1 ? 's' : '' ?>
                </span>
            </div>

            <?php if (empty($incoming)): ?>
                <div style="text-align: center; padding: 3rem; color: var(--text-muted);">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
                    <h3>No incoming requests</h3>
                    <p>When someone requests your books, they'll appear here.</p>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Book Details</th>
                                <th>Requester</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($incoming as $r): ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 1rem;">
                                        <div style="width: 40px; height: 50px; background: #e2e8f0; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #64748b;">
                                            📖
                                        </div>
                                        <div>
                                            <strong style="display: block;"><?= htmlspecialchars($r['title']) ?></strong>
                                            <span style="color: var(--text-muted); font-size: 0.875rem;">by <?= htmlspecialchars($r['author']) ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($r['requester_name']) ?></strong>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = 'status-pending';
                                    $statusText = $r['status'];
                                    if ($r['status'] === 'accepted') $statusClass = 'status-available';
                                    if ($r['status'] === 'rejected') $statusClass = 'status-lent';
                                    if ($r['status'] === 'cancelled') $statusClass = 'status-badge';
                                    ?>
                                    <span class="status-badge <?= $statusClass ?>">
                                        <?= htmlspecialchars($r['status']) ?>
                                    </span>
                                </td>
                                <td style="color: var(--text-muted); font-size: 0.875rem;">
                                    <?= date('M j, Y', strtotime($r['created_at'])) ?>
                                </td>
                                <td>
                                    <?php if ($r['status'] === 'pending'): ?>
                                        <div style="display: flex; gap: 0.5rem;">
                                            <form method="post" action="<?= BASE_URL ?>requests/action.php" style="display:inline;">
                                                <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                                <button name="action" value="accept" class="btn btn-success" style="padding: 0.5rem 1rem;">
                                                    ✓ Accept
                                                </button>
                                            </form>
                                            <form method="post" action="<?= BASE_URL ?>requests/action.php" style="display:inline;">
                                                <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                                <button name="action" value="reject" class="btn btn-danger" style="padding: 0.5rem 1rem;">
                                                    ✗ Reject
                                                </button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        <span style="color: var(--text-muted); font-size: 0.875rem;">Completed</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Outgoing Requests -->
        <div>
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                <h2 style="margin: 0;">📤 Outgoing Requests</h2>
                <span class="status-badge" style="background: #f0fdf4; color: #166534;">
                    <?= count($outgoing) ?> request<?= count($outgoing) !== 1 ? 's' : '' ?>
                </span>
            </div>

            <?php if (empty($outgoing)): ?>
                <div style="text-align: center; padding: 3rem; color: var(--text-muted);">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">📤</div>
                    <h3>No outgoing requests</h3>
                    <p>When you request books from others, they'll appear here.</p>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Book Details</th>
                                <th>Owner</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($outgoing as $r): ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 1rem;">
                                        <div style="width: 40px; height: 50px; background: #e2e8f0; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #64748b;">
                                            📖
                                        </div>
                                        <div>
                                            <strong style="display: block;"><?= htmlspecialchars($r['title']) ?></strong>
                                            <span style="color: var(--text-muted); font-size: 0.875rem;">by <?= htmlspecialchars($r['author']) ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($r['owner_name']) ?></strong>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = 'status-pending';
                                    $statusText = $r['status'];
                                    if ($r['status'] === 'accepted') $statusClass = 'status-available';
                                    if ($r['status'] === 'rejected') $statusClass = 'status-lent';
                                    if ($r['status'] === 'cancelled') $statusClass = 'status-badge';
                                    ?>
                                    <span class="status-badge <?= $statusClass ?>">
                                        <?= htmlspecialchars($r['status']) ?>
                                    </span>
                                </td>
                                <td style="color: var(--text-muted); font-size: 0.875rem;">
                                    <?= date('M j, Y', strtotime($r['created_at'])) ?>
                                </td>
                                <td>
                                    <?php if ($r['status'] === 'pending'): ?>
                                        <form method="post" action="<?= BASE_URL ?>requests/action.php" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                            <button name="action" value="cancel" class="btn btn-secondary" style="padding: 0.5rem 1rem;">
                                                Cancel Request
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span style="color: var(--text-muted); font-size: 0.875rem;">
                                            <?= $r['status'] === 'accepted' ? 'Ready for pickup!' : 'Request closed' ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../_layout/footer.php'; ?>