]<head> 
    <link rel="stylesheet" href="style.css">
</head>

<div class="form-container">

<?php
$id = isset($_GET['id']) ? $_GET['id'] : 0;

// Default values
$label = "";
$action = "save.php";
$title = "Add Type";
$button = "Create";

if ($id > 0) {
    $db = mysqli_connect("127.0.0.1", "root", "", "testschool") or die("DB error");

    $stmt = $db->prepare("SELECT label FROM types WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_assoc();

    if ($result) {
        $label = $result['label'];
        $title = "Update Type";
        $button = "Update";
    }

    $db->close();
}
?>

<h1><?php echo $title; ?></h1>

<form action="<?php echo $action; ?>" method="post">

    <?php if ($id > 0): ?>
        <input type="hidden" name="old_id" value="<?php echo $id; ?>">
    <?php endif; ?>

    <label>Label</label>
    <input name="label" value="<?php echo htmlspecialchars($label); ?>" required>

    <button type="submit"><?php echo $button; ?></button>

</form>

<a class="back-link" href="overview.php">← Back</a>

</div>