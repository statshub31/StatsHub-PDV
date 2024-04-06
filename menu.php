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
    <div class="card" style="width: 15rem">
        <img class="card-img-top" style="height: 10rem" src="/layout/images/model/no-image.png" alt="Card image cap">
        <div class="card-body">
            <h5 class="card-title">Produto 1Produto 1</h5>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's
                content.</p>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">R$ 15.00</li>
        </ul>
        <div class="card-body">
            <a href="/complement/1" class="card-link"><i class="fa-solid fa-cart-shopping"></i></a>
            <a href="#" class="card-link"><i class="fa-solid fa-star"></i></a>
        </div>
    </div>
    <div class="card" style="width: 15rem">
        <img class="card-img-top" style="height: 10rem" src="/layout/images/model/no-image.png" alt="Card image cap">
        <div class="card-body">
            <h5 class="card-title">Produto 2Produto 2</h5>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's
                content.</p>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">R$ 15.00</li>
        </ul>
        <div class="card-body">
            <a href="/menu/complement" class="card-link"><i class="fa-solid fa-cart-shopping"></i></a>
            <a href="#" class="card-link"><i class="fa-solid fa-star"></i></a>
        </div>
    </div>

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