<?php
session_start();
require_once 'config.php';

$success = $error = '';
$view = $_GET['view'] ?? 'list';
$page_id = $_GET['id'] ?? null;

function generateSlug($title) {
    $slug = strtolower(trim($title));
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    return trim($slug, '-');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $title = trim($_POST['title']);
        $slug = generateSlug($title);
        $content = $_POST['content'];
        $meta_description = trim($_POST['meta_description']);
        $meta_keywords = trim($_POST['meta_keywords']);
        $status = $_POST['status'];
        $author = trim($_POST['author']);
        
        try {
            $stmt = $pdo->prepare("INSERT INTO pages (title, slug, content, meta_description, meta_keywords, status, author) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $slug, $content, $meta_description, $meta_keywords, $status, $author]);
            $success = "Page added successfully!";
            $view = 'list';
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "A page with this title already exists. Please use a different title.";
            } else {
                $error = "Error adding page.";
            }
        }
    } elseif ($action === 'edit') {
        $id = intval($_POST['id']);
        $title = trim($_POST['title']);
        $slug = generateSlug($title);
        $content = $_POST['content'];
        $meta_description = trim($_POST['meta_description']);
        $meta_keywords = trim($_POST['meta_keywords']);
        $status = $_POST['status'];
        $author = trim($_POST['author']);
        
        try {
            $stmt = $pdo->prepare("UPDATE pages SET title=?, slug=?, content=?, meta_description=?, meta_keywords=?, status=?, author=? WHERE id=?");
            $stmt->execute([$title, $slug, $content, $meta_description, $meta_keywords, $status, $author, $id]);
            $success = "Page updated successfully!";
            $view = 'list';
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "A page with this title already exists. Please use a different title.";
            } else {
                $error = "Error updating page.";
            }
        }
    } elseif ($action === 'delete') {
        $id = intval($_POST['id']);
        $stmt = $pdo->prepare("DELETE FROM pages WHERE id=?");
        $stmt->execute([$id]);
        $success = "Page deleted successfully!";
        $view = 'list';
    }
}

$pages = $pdo->query("SELECT * FROM pages ORDER BY updated_at DESC")->fetchAll();

$current_page = null;
if ($page_id && ($view === 'edit' || $view === 'view')) {
    $stmt = $pdo->prepare("SELECT * FROM pages WHERE id=?");
    $stmt->execute([$page_id]);
    $current_page = $stmt->fetch();
}

$stats = $pdo->query("SELECT 
    COUNT(*) as total,
    COUNT(CASE WHEN status='published' THEN 1 END) as published,
    COUNT(CASE WHEN status='draft' THEN 1 END) as draft
    FROM pages")->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple CMS - Content Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="header-content">
                <h1>📄 Simple CMS</h1>
                <p class="subtitle">Content Management System</p>
            </div>
            <nav class="nav">
                <a href="?view=list" class="nav-link <?php echo $view === 'list' ? 'active' : ''; ?>">📋 All Pages</a>
                <a href="?view=add" class="nav-link <?php echo $view === 'add' ? 'active' : ''; ?>">➕ Add Page</a>
            </nav>
        </header>

        <?php if ($success): ?>
            <div class="message success">✅ <?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="message error">⚠️ <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($view === 'list'): ?>
            <div class="stats-grid">
                <div class="stat-card card-blue">
                    <div class="stat-icon">📄</div>
                    <div class="stat-content">
                        <div class="stat-value"><?php echo $stats['total']; ?></div>
                        <div class="stat-label">Total Pages</div>
                    </div>
                </div>
                <div class="stat-card card-green">
                    <div class="stat-icon">✅</div>
                    <div class="stat-content">
                        <div class="stat-value"><?php echo $stats['published']; ?></div>
                        <div class="stat-label">Published</div>
                    </div>
                </div>
                <div class="stat-card card-orange">
                    <div class="stat-icon">📝</div>
                    <div class="stat-content">
                        <div class="stat-value"><?php echo $stats['draft']; ?></div>
                        <div class="stat-label">Drafts</div>
                    </div>
                </div>
            </div>

            <div class="section-title">
                <h2>📋 All Pages</h2>
            </div>

            <div class="pages-grid">
                <?php foreach ($pages as $page): ?>
                    <div class="page-card">
                        <div class="page-header">
                            <div>
                                <h3><?php echo htmlspecialchars($page['title']); ?></h3>
                                <p class="page-slug">🔗 <?php echo htmlspecialchars($page['slug']); ?></p>
                            </div>
                            <span class="status-badge status-<?php echo $page['status']; ?>">
                                <?php echo ucfirst($page['status']); ?>
                            </span>
                        </div>
                        
                        <div class="page-meta">
                            <p><strong>👤 Author:</strong> <?php echo htmlspecialchars($page['author']); ?></p>
                            <p><strong>📅 Updated:</strong> <?php echo date('M j, Y g:i A', strtotime($page['updated_at'])); ?></p>
                        </div>

                        <?php if ($page['meta_description']): ?>
                            <p class="page-description"><?php echo htmlspecialchars($page['meta_description']); ?></p>
                        <?php endif; ?>

                        <div class="page-actions">
                            <a href="?view=view&id=<?php echo $page['id']; ?>" class="btn btn-small btn-blue">👁️ View</a>
                            <a href="?view=edit&id=<?php echo $page['id']; ?>" class="btn btn-small btn-green">✏️ Edit</a>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this page?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $page['id']; ?>">
                                <button type="submit" class="btn btn-small btn-red">🗑️ Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($pages)): ?>
                    <div class="no-data">
                        <p>No pages found. Create your first page!</p>
                    </div>
                <?php endif; ?>
            </div>

        <?php elseif ($view === 'add'): ?>
            <div class="form-container">
                <h2>➕ Add New Page</h2>
                <form method="POST">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="form-group">
                        <label>Page Title *</label>
                        <input type="text" name="title" required placeholder="Enter page title">
                        <small>Slug will be auto-generated from title</small>
                    </div>

                    <div class="form-group">
                        <label>Page Content *</label>
                        <textarea name="content" rows="12" required placeholder="Enter page content (HTML supported)"></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Status *</label>
                            <select name="status" required>
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Author *</label>
                            <input type="text" name="author" required value="Admin">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Meta Description</label>
                        <textarea name="meta_description" rows="2" placeholder="SEO meta description (recommended 150-160 characters)"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Meta Keywords</label>
                        <input type="text" name="meta_keywords" placeholder="keyword1, keyword2, keyword3">
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-success">💾 Save Page</button>
                        <a href="?view=list" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>

        <?php elseif ($view === 'edit' && $current_page): ?>
            <div class="form-container">
                <h2>✏️ Edit Page</h2>
                <form method="POST">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="<?php echo $current_page['id']; ?>">
                    
                    <div class="form-group">
                        <label>Page Title *</label>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($current_page['title']); ?>" required>
                        <small>Current slug: <?php echo htmlspecialchars($current_page['slug']); ?></small>
                    </div>

                    <div class="form-group">
                        <label>Page Content *</label>
                        <textarea name="content" rows="12" required><?php echo htmlspecialchars($current_page['content']); ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Status *</label>
                            <select name="status" required>
                                <option value="draft" <?php echo $current_page['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                                <option value="published" <?php echo $current_page['status'] === 'published' ? 'selected' : ''; ?>>Published</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Author *</label>
                            <input type="text" name="author" value="<?php echo htmlspecialchars($current_page['author']); ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Meta Description</label>
                        <textarea name="meta_description" rows="2"><?php echo htmlspecialchars($current_page['meta_description']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Meta Keywords</label>
                        <input type="text" name="meta_keywords" value="<?php echo htmlspecialchars($current_page['meta_keywords']); ?>">
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-success">💾 Update Page</button>
                        <a href="?view=list" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>

        <?php elseif ($view === 'view' && $current_page): ?>
            <div class="page-view">
                <div class="view-header">
                    <div>
                        <h2><?php echo htmlspecialchars($current_page['title']); ?></h2>
                        <p class="view-slug">🔗 <?php echo htmlspecialchars($current_page['slug']); ?></p>
                    </div>
                    <div class="view-badges">
                        <span class="status-badge status-<?php echo $current_page['status']; ?>">
                            <?php echo ucfirst($current_page['status']); ?>
                        </span>
                    </div>
                </div>

                <div class="view-meta">
                    <span>👤 <?php echo htmlspecialchars($current_page['author']); ?></span>
                    <span>📅 <?php echo date('F j, Y g:i A', strtotime($current_page['updated_at'])); ?></span>
                </div>

                <div class="view-content">
                    <?php echo $current_page['content']; ?>
                </div>

                <?php if ($current_page['meta_description'] || $current_page['meta_keywords']): ?>
                    <div class="view-seo">
                        <h3>🔍 SEO Information</h3>
                        <?php if ($current_page['meta_description']): ?>
                            <p><strong>Meta Description:</strong> <?php echo htmlspecialchars($current_page['meta_description']); ?></p>
                        <?php endif; ?>
                        <?php if ($current_page['meta_keywords']): ?>
                            <p><strong>Meta Keywords:</strong> <?php echo htmlspecialchars($current_page['meta_keywords']); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="view-actions">
                    <a href="?view=edit&id=<?php echo $current_page['id']; ?>" class="btn btn-green">✏️ Edit Page</a>
                    <a href="?view=list" class="btn btn-secondary">← Back to List</a>
                </div>
            </div>
        <?php endif; ?>

        <footer class="footer">
            <p>📄 Simple CMS - Manage Your Content Easily</p>
        </footer>
    </div>
</body>
</html>
