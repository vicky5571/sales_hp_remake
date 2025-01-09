<?php
include 'conn.php'; // Connection to the database
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch categories from the database
$query = "SELECT * FROM CATEGORIES";
$result = mysqli_query($conn, $query);

// Handle form submission to add a new category
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = $_POST['category_name'];
    $insert_query = "INSERT INTO CATEGORIES (CATEGORY_NAME) VALUES ('$category_name')";
    if (mysqli_query($conn, $insert_query)) {
        header("Location: categories.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<?php if (isset($_SESSION['save_alert'])) : ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const alertPlaceholder = document.getElementById('saveAlertPlaceholder');
            const wrapper = document.createElement('div');
            wrapper.innerHTML = [
                `<div class="alert alert-success alert-dismissible" role="alert">`,
                ` <div><?= $_SESSION['save_alert']; ?></div>`,
                ' <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
                '</div>'
            ].join('');
            alertPlaceholder.append(wrapper);

            // Automatically dismiss the alert after 3 seconds
            setTimeout(() => {
                wrapper.remove();
            }, 3000);
        });
    </script>
    <?php unset($_SESSION['save_alert']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['delete_alert'])) : ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const alertPlaceholder = document.getElementById('deleteAlertPlaceholder');
            const wrapper = document.createElement('div');
            wrapper.innerHTML = [
                `<div class="alert alert-success alert-dismissible fade show" role="alert">`,
                ` <div><?= htmlspecialchars($_SESSION['delete_alert']); ?></div>`,
                ' <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
                '</div>'
            ].join('');
            alertPlaceholder.append(wrapper);

            // Automatically dismiss the alert after 3 seconds
            setTimeout(() => {
                wrapper.remove();
            }, 3000);
        });
    </script>
    <?php unset($_SESSION['delete_alert']); ?>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./src/style.css">
    <script>
        function openEditModal(categoryId, categoryName) {
            document.getElementById('editCategoryId').value = categoryId;
            document.getElementById('editCategoryName').value = categoryName;
            new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
        }
    </script>
</head>

<body>
    <div class="container container-for-bg mt-5">
        <h1 class="text-center">Categories</h1>
        <a href="index.php">Home</a>
        <div id="saveAlertPlaceholder"></div>
        <div id="deleteAlertPlaceholder"></div>

        <div class="mt-4">
            <!-- Add New Category Form -->
            <form method="POST" class="d-flex mb-4">
                <input type="text" name="category_name" class="form-control me-2" placeholder="Enter Category Name" required>
                <button type="submit" class="btn btn-success">Add</button>
            </form>

            <!-- Categories Table -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?= $row['CATEGORY_ID']; ?></td>
                            <td><?= $row['CATEGORY_NAME']; ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="openEditModal(<?= $row['CATEGORY_ID']; ?>, '<?= htmlspecialchars($row['CATEGORY_NAME']); ?>')">Edit</button>
                                <a href="delete_categories.php?id=<?= $row['CATEGORY_ID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>


    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="edit_categories.php" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="category_id" id="editCategoryId">
                    <div class="mb-3">
                        <label for="editCategoryName" class="form-label">Category Name</label>
                        <input type="text" class="form-control" name="category_name" id="editCategoryName" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveAlertBtn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- <script>
        const alertPlaceholder = document.getElementById('saveAlertPlaceholder')
        const appendAlert = (message, type) => {
            const wrapper = document.createElement('div')
            wrapper.innerHTML = [
                `<div class="alert alert-${type} alert-dismissible" role="alert">`,
                ` <div>${message}</div>`,
                ' <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
                '</div>'
            ].join('')

            alertPlaceholder.append(wrapper)
        }

        const alertTrigger = document.getElementById('saveAlertBtn')
        if (alertTrigger) {
            alertTrigger.addEventListener('click', () => {
                appendAlert('Nice, you triggered this alert message!', 'success')
            })
        }
    </script> -->
</body>

</html>