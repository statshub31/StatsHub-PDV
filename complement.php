<?php
include_once __DIR__ . '/layout/php/header.php';
?>

<div class="card" style="width: 100%">
    <img class="card-img-top" style="height: 15rem" src="/layout/images/model/no-image.png" alt="Card image cap">
    <div class="card-body">
        <h5 class="card-title">Produto 1Produto 1</h5>
        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's
            content.</p>
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">R$ 15.00</li>
        <li class="list-group-item">
            <fieldset>
                <legend>Tamanho</legend>
                <div class="complement-option">
                    <input type="radio" name="size">
                    <section class="complement-description">
                        <h6>P</h6>
                        <small>400g</small>
                        <label class="v">R$ 10.00</label>
                    </section>
                </div>
                <hr>
                <div class="complement-option">
                    <input type="radio" name="complement">
                    <section class="complement-description">
                        <h6>M</h6>
                        <small>700g</small>
                        <label class="v">R$ 10.00</label>
                    </section>
                </div>
            </fieldset>

        </li>
        <li class="list-group-item">
            <fieldset>
                <legend>Complemento</legend>
                <div class="complement-option">
                    <input type="radio" name="complement">
                    <section class="complement-description">
                        <h6>Complemento 1</h6>
                        <small>Alface, Tomate</small>
                        <label class="v">R$ 10.00</label>
                    </section>
                </div>
                <hr>
                <div class="complement-option">
                    <input type="radio" name="complement">
                    <section class="complement-description">
                        <h6>Complemento 1</h6>
                        <small>Alface, Tomate</small>
                        <label class="v">R$ 10.00</label>
                    </section>
                </div>
            </fieldset>

        </li>
        <li class="list-group-item">
            <fieldset>
                <legend>Adicional</legend>
                <div class="complement-option">
                    <input type="checkbox" name="complement">
                    <section class="complement-description">
                        <h6>Adicional 1</h6>
                        <small>Alface, Tomate</small>
                        <label class="v">R$ 10.00</label>
                    </section>
                </div>
                <hr>
                <div class="complement-option">
                    <input type="checkbox" name="complement">
                    <section class="complement-description">
                        <h6>Adicional 1</h6>
                        <small>Alface, Tomate</small>
                        <label class="v">R$ 10.00</label>
                    </section>
                </div>
                <hr>
                <div class="complement-option">
                    <input type="checkbox" name="complement">
                    <section class="complement-description">
                        <h6>Adicional 1</h6>
                        <small>Alface, Tomate</small>
                        <label class="v">R$ 10.00</label>
                    </section>
                </div>
            </fieldset>
        </li>
        <li class="list-group-item list-quantity">
            <button class="btn btn-sm btn-secondary decrease">-</button>
            <input type="number" class="form-control quantity" value="1">
            <button class="btn btn-sm btn-secondary increase">+</button>
        </li>
        <li class="list-group-item">
            <div class="form-floating">
                <label for="floatingTextarea">Observações</label>
                <textarea class="form-control" placeholder="Exemplo: Sem cebola..." id="floatingTextarea"></textarea>
            </div>
        </li>
    </ul>
            <b>
                <p class="t">Total do Pedido
                    <label class="v">R$ 10.00</label>
                </p>
            </b>
    <div class="card-body">
        <button type="button" class="btn btn-primary">Adicionar ao Carrinho</button>
        <a href="#" class="card-link"><i class="fa-solid fa-star"></i></a>
        <a href="#" class="card-link"><i class="fa-solid fa-trash"></i></a>
    </div>
</div>

<script>
    // Selecionar todos os botões de diminuir e adicionar um evento de clique
    document.querySelectorAll('.decrease').forEach(function (btn) {
        btn.addEventListener('click', function () {
            // Selecionar o input associado a este botão
            var input = this.nextElementSibling;
            // Obter o valor atual do input
            var value = parseInt(input.value);
            // Decrementar o valor, garantindo que não seja menor que 1
            if (value > 1) {
                input.value = value - 1;
            }
        });
    });

    // Selecionar todos os botões de aumentar e adicionar um evento de clique
    document.querySelectorAll('.increase').forEach(function (btn) {
        btn.addEventListener('click', function () {
            // Selecionar o input associado a este botão
            var input = this.previousElementSibling;
            // Obter o valor atual do input
            var value = parseInt(input.value);
            // Incrementar o valor
            input.value = value + 1;
        });
    });

</script>
<?php
include_once __DIR__ . '/layout/php/footer.php';
?>