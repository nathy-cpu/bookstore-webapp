<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .form-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .checkbox-group {
            margin-top: 10px;
        }
        .button {
            display: inline-block;
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }
        .button.cancel {
            background-color: #f44336;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
        .note {
            color: #666;
            font-size: 0.9em;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Edit User</h1>
        
        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="/admin/users/edit/<?= htmlspecialchars($user['id']) ?>">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required 
                       value="<?= htmlspecialchars($user['email']) ?>">
            </div>

            <div class="form-group">
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password">
                <p class="note">Leave blank to keep current password</p>
            </div>

            <div class="checkbox-group">
                <label>
                    <input type="checkbox" name="is_admin" value="1" 
                           <?= $user['is_admin'] ? 'checked' : '' ?>>
                    Administrator privileges
                </label>
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="button">Save Changes</button>
                <a href="/admin" class="button cancel" style="margin-left: 10px;">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html> 