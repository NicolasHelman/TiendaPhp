<?php
    include "../includes/head.php";
    include "../includes/navbar.php";
?>

<main>
    
    <div class="py-5">

        <div class="container">

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">

                <?php
                    require_once "../config/database.php";

                    require_once "../config/config.php";
                    // echo hash_hmac() nos permite cifrar informacion mediante una contraseña

                    $conectar = $conexion;

                    $sql = $conectar->prepare("SELECT * FROM producto WHERE activo=1");
                    $sql -> execute();

                    $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

                    // eliminamos la session y se elimina todo del carrito
                    //session_destroy();

                    foreach ($resultado as $dato) {

                ?>

                <div class="col">
                    <div class="card shadow-sm">
                        <img class="mx-auto" src="../img/products/<?php echo $dato["imagen"]; ?>" alt="" width="60%">
                        <div class="card-body">
                        <p class="card-title"><?php echo $dato["nombre"]; ?></p>
                        <p class="card-text"><?php echo MONEDA . number_format($dato["precio"], 2, ".", ","); ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                                <a href="details.php?id=<?php echo $dato["id_producto"]; ?>&token=<?php echo hash_hmac("sha1", $dato["id_producto"], KEY_TOKEN); ?>" class="btn btn-primary"><i class="fas fa-eye"></i></a>
                            </div>
                            <div class="btn-group">
                                <a href="" class="btn btn-success" onclick="addProducto(<?php echo $dato['id_producto']; ?>, '<?php echo hash_hmac('sha1', $dato['id_producto'], KEY_TOKEN); ?>')"><i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <?php
                    }
                ?>

            </div>

        </div>

    </div>

</main>

<script>
    function addProducto(id_producto, token) {
        let url = "../class/carrito.php";
        let formData = new FormData();
        formData.append('id', id_producto);
        formData.append('token', token);

        fetch(url, {
            method: "POST",
            body: formData,
            mode: "cors"
        }).then(response => response.json())
        .then(data => {
            if (data.ok) {
                let elemento = document.getElementById("num_cart");
                elemento.innerHTML = data.numero;
            }
        })
    }
</script>

<?php
    include "../includes/script.php";
?>