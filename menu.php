<?php
include_once __DIR__ . '/layout/php/header.php';
?>

<div id="menu-nav">
    <li class="nav-option">
        <i class="fa-solid fa-burger"></i>
        <div class="ps-3">
            <small class="text-body">Popular</small>
            <h6 class="mt-n1 mb-0">Breakfast</h6>
        </div>
    </li>
    <li class="nav-option nav-select">
        <i class="fa-solid fa-burger"></i>
        <div class="ps-3">
            <small class="text-body">Popular</small>
            <h6 class="mt-n1 mb-0">Breakfast</h6>
        </div>
    </li>
</div>

<div id="menu-search">
    <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Buscar Produto" aria-label="Buscar Produto"
            aria-describedby="button-addon2" id="search-input">
    </div>
</div>


<div id="menu-list">
    <?php 
    $product_list = doDatabaseProductsList();

    if($product_list) {
        foreach($product_list as $data) {
            $product_list_id = $data['id'];
            ?>

    <div class="card" style="width: 15rem">
        <img class="card-img-top" style="height: 10rem" src="<?php echo getPathProductImage(getDatabaseProductPhotoName($product_list_id)); ?>" alt="Card image cap">
        <div class="card-body">
            <h5 class="card-title"><?php echo getDatabaseProductName($product_list_id) ?></h5>
            <p class="card-text"><?php echo getDatabaseProductDescription($product_list_id) ?></p>
        </div>
        <ul class="list-group list-group-flush">
            <?php 
            $price_list = doDatabaseProductPricesPriceListByProductID($product_list_id);
            if($price_list) {
            foreach($price_list as $dataPrice) {
                $price_list_id = $dataPrice['id'];
                ?>
                <li class="list-group-item">R$ <?php echo getDatabaseProductPrice($price_list_id) ?>
                <br>
                <small>(<?php echo getDatabaseProductPriceDescription($price_list_id) ?>)</small>
                </li>
                <?php
                }
            }
            ?>
        </ul>
        <div class="card-body">
            <a href="/complement/product/<?php echo $product_list_id ?>" class="card-link"><i class="fa-solid fa-cart-shopping"></i></a>
            <a href="#" class="card-link"><i class="fa-solid fa-star"></i></a>
        </div>
    </div>
    <?php 
        }
    } else {
        echo "Nenhum produto cadastrado.";
    }
    ?>
</div>

<script>
    document.getElementById('search-input').addEventListener('input', function () {
        var searchText = this.value.toLowerCase();
        var cards = document.querySelectorAll('#menu-list .card');

        cards.forEach(function (card) {
            var title = card.querySelector('.card-title').textContent.toLowerCase();
            if (title.includes(searchText)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });

</script>
<?php
include_once __DIR__ . '/layout/php/footer.php';
?>