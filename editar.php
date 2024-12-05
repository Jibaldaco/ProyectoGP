<?php
include('db.php');
include('push.php');

$id = $_GET['id'];
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "imagenes/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Comprueba si el archivo es una imagen real
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            echo "El archivo no es una imagen.";
            $uploadOk = 0;
        }

        // Verifica el tamaño del archivo
        if ($_FILES["image"]["size"] > 500000) {
            echo "Lo siento, tu archivo es demasiado grande.";
            $uploadOk = 0;
        }

        // Permitir ciertos formatos de archivo
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
            echo "Lo siento, solo se permiten archivos JPG, JPEG, PNG.";
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = $target_file;
            } else {
                echo "Lo siento, hubo un error al subir tu archivo.";
                $image_path = $product['image_path'];
            }
        } else {
            $image_path = $product['image_path'];
        }
    } else {
        $image_path = $product['image_path'];
    }

    $sql = "UPDATE products SET name='$name', description='$description', price='$price', image_path='$image_path' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        sendPushNotification("Producto actualizado exitosamente: $name"); 
        header("Location: index.php"); 
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Editar Producto</h1>
        <form action="editar.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Nombre del Producto</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $product['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Descripción</label>
                <textarea class="form-control" id="description" name="description" required><?php echo $product['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="price">Precio</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo $product['price']; ?>" required>
            </div>
            <div class="form-group">
                <label for="image">Imagen del Producto</label>
                <input type="file" class="form-control" id="image" name="image">
                <img src="<?php echo $product['image_path']; ?>" alt="Imagen Actual" width="150">
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
    </div>
</body>
</html>