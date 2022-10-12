<?php
    include "../includes/head.php";
    include "../includes/navbar.php";
?>

<main>
    
    <div class="py-5">

        <?php
            require_once "../config/database.php";
            require_once "../config/config.php";

            $conectar = $conexion;

            $id_producto = isset($_GET["id"]) ? $_GET['id'] : '';
            $token = isset($_GET["token"]) ? $_GET["token"] : '';

            if ($id_producto == '' || $token == '') {
                echo "Error al procesar la peticion";
                exit;
            } else {

                $token_tmp = hash_hmac("sha1", $id_producto, KEY_TOKEN);

                if($token == $token_tmp) {
                    $sql = $conectar->prepare("SELECT COUNT(id_producto) FROM producto WHERE id_producto = ? AND activo=1");
                    $sql -> execute([$id_producto]);
        
                    if ($sql->fetchColumn() > 0) {
                        $sql = $conectar->prepare("SELECT nombre, descripcion, precio, descuento, imagen FROM producto WHERE id_producto = ? AND activo=1");
                        $sql -> execute([$id_producto]);
        
                        $dato = $sql->fetch(PDO::FETCH_ASSOC);
                        $nombre = $dato["nombre"];
                        $descripcion = $dato["descripcion"];
                        $precio = $dato["precio"];
                        $descuento = $dato["descuento"];
                        $precio_descuento = $precio - (($precio * $descuento) / 100);
                        $imagen = $dato["imagen"];
        
        
                        $imagenes = array();
                        $dir = dir("../img/products/");
        
                        while (($archivo = $dir -> read()) != false) {
                            if ($archivo != $imagen && (strpos($archivo, "png") || strpos($archivo, "jpg") || strpos($archivo, "jpeg"))) {
                                $imagenes[] = "../img/products/" . $archivo;
                            }
                        }
        
                        $dir->close();
        
                    } else{
                        echo "Error al procesar la peticion";
                        exit;
                    }

                } else{
                    echo "Error al procesar la peticion";
                    exit;
                }
            }
        ?>

        <div class="container">
            <div class="row">
                <div class="col-md-6 order-md-1">
                    <img class="mx-auto" src="../img/products/<?php echo $imagen; ?>" alt="" width="60%">
                </div>
                <div class="col-md-6 order-md-2">
                    <h2><?php echo $nombre; ?></h2>

                    <?php
                        if ($descuento > 0) {
                    ?>    

                    <p>
                        <del>
                            <?php echo MONEDA . number_format($precio, 2, ".", ","); ?>
                            <h2>
                                <?php echo MONEDA . number_format($precio_descuento, 2, ".", ","); ?>
                                <small class="text-success">
                                    <?php echo $descuento; ?>% descuento
                                </small>
                            </h2>
                        </del>
                    </p>
                    
                    <?php
                        } else {
                    ?>        
                        <h2><?php echo MONEDA . number_format($precio, 2, ".", ","); ?></h2>
                    <?php
                        }
                    ?>
                        
                    <p class="lead"><?php echo $descripcion; ?></p>

                    <div class="d-grid gap-3 col-10 mx-auto">
                        <button class="btn btn-success" type="button"><i class="fas fa-money-check-alt me-2"></i>Comprar</button>
                        <button class="btn btn-primary" type="button" onclick="addProducto(<?php echo $id_producto; ?>, '<?php echo $token_tmp; ?>')"><i class="fas fa-cart-plus me-2"></i>Agregar al carrito</button>
                    </div>
                </div>

            </div>

            <div class="mx-auto w-25">
                <h3 class="">Productos</h3>
                <div id="carouselImages" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner m-auto">
                        <div class="carousel-item active">
                            <img src="../img/products/<?php echo $imagen; ?>" class="d-block" alt="..." width="65%">
                        </div>
                        <?php
                            foreach ($imagenes as $img) {
                        ?>
                        <div class="carousel-item">
                            <img src="<?php echo $img; ?>" class="d-block" alt="..." width="65%">
                        </div>
                        <?php
                            }
                        ?>
                        
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselImages" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselImages" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
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