<?php
    require "../config/config.php";
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="../index.php">Tienda</a>

        <div class="collapse navbar-collapse" id="navbarTogglerDemo03">

            <ul class="mx-auto navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="products.php">Catálogo</a>
                </li>
            </ul>

        </div>

        <a href="#carrito" class="carrito-btn">
            <i class="fas fa-cart-plus"></i>
            <span id="num_cart" class="badge bg-dark text-white ms-1"><?php echo $num_cart ?></span>
        </a>
        <a href="" class="login-btn me-3">
            <i class="fas fa-user-circle"></i>
        </a>
    </div>
</nav>