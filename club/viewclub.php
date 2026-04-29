<?php
include_once 'config.php';

// insert data
if(isset($_POST["submit"])){

    $Club_Name = $_POST['Club_Name'];
    $Club_Image = $_FILES['Club_Image']['name'];

    $ext = pathinfo($Club_Image, PATHINFO_EXTENSION);
    $allowedTypes = array("jpg", "jpeg", "png", "gif");

    $tempname = $_FILES['Club_Image']['tmp_name'];
    $targetpath = "club_image/" . $Club_Image;

    if(in_array($ext, $allowedTypes)){

        move_uploaded_file($tempname, $targetpath);

        $query = "INSERT INTO club (Club_Name, Club_Image)
                  VALUES ('$Club_Name', '$Club_Image')";

        mysqli_query($conn, $query);

        header("Location: viewclub.php?msg=inserted");
        exit;
    }
}

// UPDATE
if(isset($_POST['update'])){

    $id = $_POST['Club_Id'];
    $name = $_POST['Club_Name'];

    $image = $_FILES['Club_Image']['name'];
    $tempname = $_FILES['Club_Image']['tmp_name'];

    if($image != ""){

        move_uploaded_file($tempname, "club_image/".$image);

        $query = "UPDATE club 
                  SET Club_Name='$name', Club_Image='$image'
                  WHERE Club_Id='$id'";

    } else {

        $query = "UPDATE club 
                  SET Club_Name='$name'
                  WHERE Club_Id='$id'";
    }

    mysqli_query($conn, $query);

    header("Location: viewclub.php?msg=updated");
    exit;
}

// DELETE
if(isset($_GET['delete'])){

    $id = $_GET['delete'];

    mysqli_query($conn, "DELETE FROM club WHERE Club_Id='$id'");

    header("Location: viewclub.php?msg=deleted");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Club</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>

<?php if(isset($_GET['msg'])): ?>

    <?php if($_GET['msg'] == "deleted"): ?>
        <div class="alert alert-danger">You have successfully deleted it</div>
    <?php endif; ?>

    <?php if($_GET['msg'] == "updated"): ?>
        <div class="alert alert-success">You have successfully updated</div>
    <?php endif; ?>

<?php endif; ?>



<?php
include_once("adminclub.php");

$query = "SELECT * FROM club";
$result = mysqli_query($conn, $query);

if($result && mysqli_num_rows($result) > 0){

    while($row = mysqli_fetch_array($result)){

        $Club_Name = $row['Club_Name'];
        $Club_Image = $row['Club_Image'];
        $Club_Id = $row["Club_Id"];

        echo "<div class='profile mt-4'>";

        echo "<img src='club_image/$Club_Image' style='width:100px; height:auto;'>";
        echo "<h3>$Club_Name</h3>";

     echo "<button class='btn btn-danger deleteBtn' data-id='$Club_Id'>Delete</button>";

        echo "<button 
            class='btn btn-warning editBtn'
            data-id='$Club_Id'
            data-name='$Club_Name'
            data-image='$Club_Image'
        >Edit</button>";

       echo "<a href='viewclubevent.php?id=$Club_Id' class='btn btn-info'>View</a>";

        echo "</div>";
    }
}
?>

</div>


<!-- DELETE MODAL -->
<div class="modal fade" id="deleteModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-body text-center">
        <h5>Do you wish to delete it permanently?</h5>
        <input type="hidden" id="delete_id">
      </div>

      <div class="modal-footer justify-content-center">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button id="confirmDeleteBtn" class="btn btn-danger">Confirm</button>
      </div>

    </div>
  </div>
</div>


<!-- EDIT MODAL -->
<div class="modal fade" id="editModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" enctype="multipart/form-data">

        <input type="hidden" name="Club_Id" id="edit_id">

        <div class="modal-body">
          <input type="text" name="Club_Name" id="edit_name" class="form-control">
          <input type="file" name="Club_Image" class="form-control mt-2">
        </div>

        <div class="modal-footer">
          <button type="submit" name="update" class="btn btn-success">Update</button>
        </div>

      </form>

    </div>
  </div>
</div>


</div>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {

    //edit button
    document.querySelectorAll('.editBtn').forEach(btn => {
        btn.addEventListener('click', function() {

            document.getElementById('edit_id').value = this.dataset.id;
            document.getElementById('edit_name').value = this.dataset.name;

            let modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
        });
    });

});

// delete button

document.addEventListener("DOMContentLoaded", function() {

    let deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

    // open modal
    document.querySelectorAll('.deleteBtn').forEach(btn => {
        btn.addEventListener('click', function() {

            document.getElementById('delete_id').value = this.dataset.id;
            deleteModal.show();
        });
    });

    // confirm delete
    document.getElementById('confirmDeleteBtn').addEventListener('click', function(){

        let id = document.getElementById('delete_id').value;

        window.location.href = "viewclub.php?delete=" + id;
    });

});

// pop up message timeout
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(el => el.remove());
}, 3000);
</script>

</body>
</html>