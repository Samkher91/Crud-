<?php
include 'db.php';

$action = isset($_POST['action']) ? $_POST['action'] : '';

$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;

$limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 3;

$offset = ($page - 1) * $limit;

if ($action == 'create' || $action == 'update') {
    $name = $_POST['name'];
    $email = $_POST['email'];

    if ($action == 'create') {
        $sql = "INSERT INTO users (name, email) VALUES ('$name', '$email')";
    } else if ($action == 'update') {
        $id = $_POST['id'];
        $sql = "UPDATE users SET name='$name', email='$email' WHERE id=$id";
    }

    if (mysqli_query($conn, $sql)) {
        echo "Enregistrement réussi.";
    } else {
        echo "Erreur : " . mysqli_error($conn);
    }
}
else if ($action == 'delete') {
    $id = $_POST['id'];

    $sql = "DELETE FROM users WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        echo "Suppression réussie.";
    } else {
        echo "Erreur : " . mysqli_error($conn);
    }
}
else if ($action == 'read') {
    $sql = "SELECT * FROM users LIMIT $limit OFFSET $offset";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>
            <button type='button' class='btn btn-success btn-sm showBtn' data-id='" . $row['id'] . "' data-name='" . $row['name'] . "' data-email='" . $row['email'] . "'>Afficher</button> 
            <button type='button' class='btn btn-info btn-sm editBtn' data-id='" . $row['id'] . "' data-name='" . $row['name'] . "' data-email='" . $row['email'] . "'>Éditer</button> 
            <button type='button' class='btn btn-danger btn-sm deleteBtn' data-id='" . $row['id'] . "'>Supprimer</button></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>Aucun utilisateur trouvé</td></tr>";
    }

    // Calculer le nombre total de pages
    $totalQuery = "SELECT COUNT(*) as total FROM users";
    $totalResult = mysqli_query($conn, $totalQuery);
    $totalRow = mysqli_fetch_assoc($totalResult);
    $total = $totalRow['total'];
    $totalPages = ceil($total / $limit);

    // Générer les boutons de pagination
    echo '<tr><td colspan="4"><nav aria-label="Page navigation example"><ul class="pagination justify-content-center">';
    for ($i = 1; $i <= $totalPages; $i++) {
        echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '"><a class="page-link" href="#" data-page="' . $i . '">' . $i . '</a></li>';
    }
    echo '</ul></nav></td></tr>';
}
// Vérifier si l'action est 'search'
else if ($action == 'search') {
    $input = $_POST['input'];
    $page = 1;
    $offset = ($page - 1) * $limit;

    $query = "SELECT * FROM users WHERE name LIKE '%$input%' OR email LIKE '%$input%' OR id LIKE '%$input%' LIMIT $limit OFFSET $offset";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<tbody>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>
            <button type='button' class='btn btn-success btn-sm showBtn' data-id='" . $row['id'] . "' data-name='" . $row['name'] . "' data-email='" . $row['email'] . "'>Afficher</button> 
            <button type='button' class='btn btn-info btn-sm editBtn' data-id='" . $row['id'] . "' data-name='" . $row['name'] . "' data-email='" . $row['email'] . "'>Éditer</button> 
            <button type='button' class='btn btn-danger btn-sm deleteBtn' data-id='" . $row['id'] . "'>Supprimer</button></td>";
            echo "</tr>";
        }
        echo "</tbody>";
    } else {
        echo "<h6 class='text-danger text-center mt-3'>Aucune donnée trouvée</h6>";
    }

    $totalQuery = "SELECT COUNT(*) as total FROM users WHERE name LIKE '%$input%' OR email LIKE '%$input%' OR id LIKE '%$input%'";
    $totalResult = mysqli_query($conn, $totalQuery);
    $totalRow = mysqli_fetch_assoc($totalResult);
    $total = $totalRow['total'];
    $totalPages = ceil($total / $limit);

    echo '<tr><td colspan="4"><nav aria-label="Page navigation example"><ul class="pagination justify-content-center">';
    for ($i = 1; $i <= $totalPages; $i++) {
        echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '"><a class="page-link" href="#" data-page="' . $i . '">' . $i . '</a></li>';
    }
    echo '</ul></nav></td></tr>';
}

?>