<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login_admin.php");
    exit();
}

require_once '../controllers/TaskController.php';
$taskController = new TaskController();
$tasks = $taskController->getTasks();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create_task'])) {
        $taskController->createTask();
    } elseif (isset($_POST['update_task'])) {
        $taskController->updateTask();
    } elseif (isset($_POST['delete_task'])) {
        $taskController->deleteTask();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Home</title>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Admin Home</h2>

    <h3>Create Task</h3>
    <form action="admin_home.php" method="post">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" required><br>
        <label for="description">Description:</label><br>
        <textarea id="description" name="description"></textarea><br>
        <label for="due_date">Due Date:</label><br>
        <input type="datetime-local" id="due_date" name="due_date" required><br>
        <label for="priority">Priority:</label><br>
        <select id="priority" name="priority">
            <option value="normal">Normal</option>
            <option value="urgent">Urgent</option>
            <option value="important">Important</option>
        </select><br>
        <label for="status">Status:</label><br>
        <select id="status" name="status">
            <option value="not started">Not Started</option>
            <option value="in progress">In Progress</option>
            <option value="completed">Completed</option>
        </select><br>
        <input type="submit" name="create_task" value="Create Task">
    </form>

    <h3>Task List</h3>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Due Date</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $tasks->fetch(PDO::FETCH_ASSOC)) : ?>
            <tr>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['description']); ?></td>
                <td><?php echo htmlspecialchars($row['due_date']); ?></td>
                <td><?php echo htmlspecialchars($row['priority']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td>
                    <button onclick="editTask(<?php echo htmlspecialchars(json_encode($row)); ?>)">Edit</button>
                    <form action="admin_home.php" method="post" style="display:inline;">
                        <input type="hidden" name="task_id" value="<?php echo $row['task_id']; ?>">
                        <input type="submit" name="delete_task" value="Delete">
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- The Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Task</h2>
            <form id="editTaskForm" action="admin_home.php" method="post">
                <input type="hidden" id="task_id" name="task_id">
                <label for="edit_title">Title:</label><br>
                <input type="text" id="edit_title" name="title" required><br>
                <label for="edit_description">Description:</label><br>
                <textarea id="edit_description" name="description"></textarea><br>
                <label for="edit_due_date">Due Date:</label><br>
                <input type="datetime-local" id="edit_due_date" name="due_date" required><br>
                <label for="edit_priority">Priority:</label><br>
                <select id="edit_priority" name="priority">
                    <option value="normal">Normal</option>
                    <option value="urgent">Urgent</option>
                    <option value="important">Important</option>
                </select><br>
                <label for="edit_status">Status:</label><br>
                <select id="edit_status" name="status">
                    <option value="not started">Not Started</option>
                    <option value="in progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select><br>
                <input type="submit" name="update_task" value="Update Task">
            </form>
        </div>
    </div>

    <script>
        
        var modal = document.getElementById('editModal'); 
        var span = document.getElementsByClassName('close')[0];
        span.onclick = function() {
            modal.style.display = 'none';
        }
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
        function editTask(task) {
            modal.style.display = 'block';
            document.getElementById('task_id').value = task.task_id;
            document.getElementById('edit_title').value = task.title;
            document.getElementById('edit_description').value = task.description;
            document.getElementById('edit_due_date').value = task.due_date.replace(' ', 'T');
            document.getElementById('edit_priority').value = task.priority;
            document.getElementById('edit_status').value = task.status;
        }
    </script>
</body>
</html>
