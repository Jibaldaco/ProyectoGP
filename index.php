<?php include('db.php'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Lista de Productos</h1>
        <a href="agregar.php" class="btn btn-success mb-3">Agregar Producto</a>
        <input type="text" id="search" placeholder="Buscar productos" class="form-control mb-3">
        <div class="row" id="product-list">
            <?php
            $result = $conn->query("SELECT * FROM products");
            while($row = $result->fetch_assoc()):
            ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="<?php echo $row['image_path']; ?>" class="card-img-top" alt="Producto">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['name']; ?></h5>
                        <p class="card-text"><?php echo $row['description']; ?></p>
                        <p class="card-text">$<?php echo $row['price']; ?></p>
                        <a href="editar.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Editar</a>
                        <a href="eliminar.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Eliminar</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    <script>
        document.getElementById('search').addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const productList = document.getElementById('product-list');
            const products = productList.getElementsByClassName('card');
            for (let product of products) {
                const productName = product.querySelector('.card-title').textContent.toLowerCase();
                product.style.display = productName.includes(searchValue) ? 'block' : 'none';
            }
        });
    </script>
</body>
</html>
