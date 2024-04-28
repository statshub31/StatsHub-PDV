<?php
include_once __DIR__ . '/layout/php/header.php';
?>

<div id="menu-nav">

    <li class="nav-option nav-select">
        <i class="fa-solid fa-burger"></i>
        <div class="ps-3">
            <small class="text-body"></small>
            <h6 class="mt-n1 mb-0">Tudo</h6>
        </div>
    </li>
    <?php
    $category_list = doDatabaseCategorysListEnabled();
    if ($category_list) {
        foreach ($category_list as $dataCategory) {
            $category_list_id = $dataCategory['id'];

            ?>
            <li class="nav-option">
                <i class="fa-solid <?php echo getDatabaseIconTitle(getDatabaseCategoryIconID($category_list_id)) ?>"></i>
                <div class="ps-3">
                    <small class="text-body"></small>
                    <h6 class="mt-n1 mb-0"><?php echo getDatabaseCategoryTitle($category_list_id) ?></h6>
                </div>
            </li>
            <?php
        }
    }
    ?>
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

    if ($product_list) {
        foreach ($product_list as $data) {
            $product_list_id = $data['id'];
            if (isProductInStock($product_list_id)) {
                ?>

                <div class="card" style="width: 15rem">
                    <img class="card-img-top" style="height: 10rem"
                        src="<?php echo getPathProductImage(getDatabaseProductPhotoName($product_list_id)); ?>"
                        alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo getDatabaseProductName($product_list_id) ?></h5>
                        <h5 class="category"><?php echo getDatabaseCategoryTitle(getDatabaseProductCategoryID($product_list_id)) ?>
                        </h5>
                        <p class="card-text">
                            <a class="btn btn-primary" data-bs-toggle="collapse" href="#collapseExample" role="button"
                                aria-expanded="false" aria-controls="collapseExample">
                                Detalhes
                            </a>
                        <div class="collapse" id="collapseExample">
                            <div class="card card-body w-100">
                                <?php echo getDatabaseProductDescription($product_list_id) ?>
                            </div>
                        </div>
                        </p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <?php
                        $price_list = doDatabaseProductPricesPriceListByProductID($product_list_id);
                        if ($price_list) {
                            foreach ($price_list as $dataPrice) {
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
                        <a href="/complement/product/<?php echo $product_list_id ?>" class="card-link"><i
                                class="fa-solid fa-cart-shopping"></i></a>
                        <a href="#" class="card-link"><i class="fa-solid fa-star"></i></a>
                    </div>
                </div>
                <?php
            }
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const navOptions = document.querySelectorAll('#menu-nav .nav-option');
        const cards = document.querySelectorAll('#menu-list .card');

        navOptions.forEach(function (option, index) {
            option.addEventListener('click', function () {
                // Remove a classe 'nav-select' de todas as opções de navegação
                navOptions.forEach(function (opt) {
                    opt.classList.remove('nav-select');
                });

                // Adiciona a classe 'nav-select' à opção de navegação clicada
                option.classList.add('nav-select');

                const selectedCategory = option.querySelector('h6').textContent.toLowerCase();

                // Se a categoria selecionada for "Tudo", mostra todos os produtos
                if (selectedCategory === 'tudo') {
                    cards.forEach(function (card) {
                        card.style.display = 'block';
                    });
                } else {
                    // Percorre todos os cartões de produtos
                    cards.forEach(function (card) {
                        const cardCategory = card.querySelector('.category').textContent.toLowerCase();

                        // Verifica se a categoria do produto corresponde à categoria selecionada
                        if (cardCategory.includes(selectedCategory)) {
                            card.style.display = 'block'; // Exibe o produto
                        } else {
                            card.style.display = 'none'; // Oculta o produto
                        }
                    });
                }
            });
        });
    });

</script>

<?php
include_once __DIR__ . '/layout/php/footer.php';
?>