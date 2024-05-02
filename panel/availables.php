<?php
include_once (realpath(__DIR__ . "/layout/php/header.php"));
getGeneralSecurityAttendantAccess();

?>

<div id="availables">
    <?php
    $available_list = doDatabaseRequestOrderAvailableList();
    if ($available_list) {
        foreach ($available_list as $data) {
            $available_list_id = $data['id'];
            $order_id = getDatabaseRequestOrderAvailableRequestID($available_list_id);
            $cart_id = getDatabaseRequestOrderCartID($order_id);
            $user_id = getDatabaseCartUserID($cart_id);
            ?>
            <div class="available">
                <div class="first-available-frame">
                    <div class="user-photo-circle">
                        <img src="<?php echo getPathAvatarImage(getDatabaseUserPhotoName($user_id)); ?>">
                    </div>
                    <label><?php echo getDatabaseUserName($user_id); ?></label>
                </div>

                <div class="second-available-frame">
                    <section>
                        <label>Comida</label>
                        <div id="stars">
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableFoodAvailable($available_list_id, 1)) ? 'colorstar' : ''; ?>">
                                <img src="/layout/images/model/star-a.svg">
                            </section>
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableFoodAvailable($available_list_id, 2)) ? 'colorstar' : ''; ?>">
                                <img src="/layout/images/model/star-a.svg">
                            </section>
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableFoodAvailable($available_list_id, 3)) ? 'colorstar' : ''; ?>">
                                <img src="/layout/images/model/star-a.svg">
                            </section>
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableFoodAvailable($available_list_id, 4)) ? 'colorstar' : ''; ?>">
                                <img src="/layout/images/model/star-a.svg">
                            </section>
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableFoodAvailable($available_list_id, 5)) ? 'colorstar' : ''; ?>">
                                <img src="/layout/images/model/star-a.svg">
                            </section>
                        </div>
                    </section>
                    <section>
                        <label>Embalagem</label>
                        <div id="stars">
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableBoxAvailable($available_list_id, 1)) ? 'colorstar' : ''; ?>">
                                <img src="/layout/images/model/star-a.svg">
                            </section>
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableBoxAvailable($available_list_id, 2)) ? 'colorstar' : ''; ?>">
                                <img src="/layout/images/model/star-a.svg">
                            </section>
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableBoxAvailable($available_list_id, 3)) ? 'colorstar' : ''; ?>">
                                <img src="/layout/images/model/star-a.svg">
                            </section>
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableBoxAvailable($available_list_id, 4)) ? 'colorstar' : ''; ?>">
                                <img src="/layout/images/model/star-a.svg">
                            </section>
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableBoxAvailable($available_list_id, 5)) ? 'colorstar' : ''; ?>">
                                <img src="/layout/images/model/star-a.svg">
                            </section>
                        </div>
                    </section>
                    <section>
                        <label>Tempo de Entrega</label>
                        <div id="stars">
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableDeliveryTimeAvailable($available_list_id, 1)) ? 'colorstar' : ''; ?>">
                                <img src="/layout/images/model/star-a.svg">
                            </section>
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableDeliveryTimeAvailable($available_list_id, 2)) ? 'colorstar' : ''; ?>">
                                <img src="/layout/images/model/star-a.svg">
                            </section>
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableDeliveryTimeAvailable($available_list_id, 3)) ? 'colorstar' : ''; ?>">
                                <img src="/layout/images/model/star-a.svg">
                            </section>
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableDeliveryTimeAvailable($available_list_id, 4)) ? 'colorstar' : ''; ?>">
                                <img src="/layout/images/model/star-a.svg">
                            </section>
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableDeliveryTimeAvailable($available_list_id, 5)) ? 'colorstar' : ''; ?>">
                                <img src="/layout/images/model/star-a.svg">
                            </section>
                        </div>
                    </section>
                    <section>
                        <label>Custo Beneficio</label>
                        <div id="stars">
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableCostBenefitAvailable($available_list_id, 1)) ? 'colorstar' : ''; ?>">
                                <img src="/layout/images/model/star-a.svg">
                            </section>
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableCostBenefitAvailable($available_list_id, 2)) ? 'colorstar' : ''; ?>">
                                <img src="/layout/images/model/star-a.svg">
                            </section>
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableCostBenefitAvailable($available_list_id, 3)) ? 'colorstar' : ''; ?>">
                                <img src="/layout/images/model/star-a.svg">
                            </section>
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableCostBenefitAvailable($available_list_id, 4)) ? 'colorstar' : ''; ?>">
                                <img src="/layout/images/model/star-a.svg">
                            </section>
                            <section
                                class="star <?php echo (isDatabaseRequestOrderAvailableCostBenefitAvailable($available_list_id, 5)) ? 'colorstar' : ''; ?>">
                                <img src="/layout/images/model/star-a.svg">
                            </section>
                        </div>
                    </section>
                </div>

                <div class="third-available-frame comments">
                    <?php echo getDatabaseRequestOrderAvailableComment($available_list_id) ?>

                    <label class="comments-date">Pedido #<?php echo $order_id ?> -
                        <?php echo doDate(getDatabaseRequestOrderAvailableCreated($available_list_id)) ?> às
                        <?php echo doTime(getDatabaseRequestOrderAvailableCreated($available_list_id)) ?></label>
                </div>
            </div>
            <?php
        }
    } else {
        ?>
        Não existe nenhuma avaliação ainda.
        <?php
    }
    ?>
</div>

<div>
    <button id="prevPage">Anterior</button>
    <button id="nextPage">Próxima</button>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Seleciona os elementos relevantes
        const container = document.querySelector('#availables');
        const items = container.querySelectorAll('.available');
        const prevPageButton = document.getElementById('prevPage');
        const nextPageButton = document.getElementById('nextPage');

        let currentPage = 0;
        const itemsPerPage = 10;

        // Função para exibir os itens da página atual
        function showItems(page) {
            const totalPages = Math.ceil(items.length / itemsPerPage);

            if (page >= totalPages) {
                // Se a página atual for maior ou igual ao número total de páginas, define a página atual para a última página disponível
                currentPage = totalPages - 1;
            } else {
                currentPage = page;
            }

            const start = currentPage * itemsPerPage;
            const end = start + itemsPerPage;

            items.forEach((item, index) => {
                if (index >= start && index < end) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });

            // Atualiza o estado dos botões de paginação após mostrar os itens
            updatePaginationButtons();
        }

        // Inicializa a exibição dos itens na página atual
        showItems(currentPage);

        // Função para ir para a próxima página
        nextPageButton.addEventListener('click', () => {
            currentPage++;
            showItems(currentPage);
            updatePaginationButtons();
        });

        // Função para ir para a página anterior
        prevPageButton.addEventListener('click', () => {
            currentPage--;
            showItems(currentPage);
            updatePaginationButtons();
        });

        // Função para atualizar o estado dos botões de paginação
        function updatePaginationButtons() {
            const totalPages = Math.ceil(items.length / itemsPerPage);
            prevPageButton.disabled = currentPage === 0;
            nextPageButton.disabled = currentPage === totalPages - 1 || totalPages === 0;
        }

    });
</script>

<?php
include_once (realpath(__DIR__ . "/layout/php/footer.php"));
?>