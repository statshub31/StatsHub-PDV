<?php
include_once __DIR__ . '/layout/php/header.php';
$media = doAvailableGeneral();
?>
<div id="restaurant-info">
    <section id="status">
        <div id="status-info">
            <div id="status-icon" class="status-<?php echo (isOpen()) ? 'open' : 'close'; ?>"></div>
            <div id="three-info">
                <p><?php echo (isOpen()) ? 'Aberto' : 'Fechado'; ?></p>
                <p>Agendamento Indisponivel</p>
                <p>Faça o seu pedido</p>
            </div>
        </div>
    </section>
    <section id="time">
        <div id="time-info">
            <div id="time-icon">
                <i class="fa-regular fa-clock"></i>
            </div>
            <div id="time-description">
                <h6>Tempo de Entrega</h6>
                <small><?php echo getDatabaseSettingsDeliveryTimeMin(1) ?>-<?php echo getDatabaseSettingsDeliveryTimeMax(1) ?>
                    Min</small>
            </div>
        </div>
    </section>
    <section id="pays">
        <div id="pays-info">
            <div id="pays-icon">
                <i class="fa-solid fa-credit-card"></i>
            </div>
            <div id="time-description">
                <h6>Formas de Pagamento</h6>
            </div>
        </div>
    </section>

</div>
<br>

<div id="week-horary">
    <center>
        <h6>Horário Local</h6>
    </center>
    <hr>
    <p>Domingo
        <label><?php echo isOpenByDay(7) ? doTime(getDatabaseSettingsHoraryDayStart(1, 7)) . ' às ' . doTime(getDatabaseSettingsHoraryDayEnd(1, 7)) : 'Fechado'; ?></label>
    </p>
    <p>Segunda-Feira
        <label><?php echo isOpenByDay(1) ? doTime(getDatabaseSettingsHoraryDayStart(1, 1)) . ' às ' . doTime(getDatabaseSettingsHoraryDayEnd(1, 1)) : 'Fechado'; ?></label>
    </p>
    <p>Terça-Feira
        <label><?php echo isOpenByDay(2) ? doTime(getDatabaseSettingsHoraryDayStart(1, 2)) . ' às ' . doTime(getDatabaseSettingsHoraryDayEnd(1, 2)) : 'Fechado'; ?></label>
    </p>
    <p>Quarta-Feira
        <label><?php echo isOpenByDay(3) ? doTime(getDatabaseSettingsHoraryDayStart(1, 3)) . ' às ' . doTime(getDatabaseSettingsHoraryDayEnd(1, 3)) : 'Fechado'; ?></label>
    </p>
    <p>Quinta-Feira
        <label><?php echo isOpenByDay(4) ? doTime(getDatabaseSettingsHoraryDayStart(1, 4)) . ' às ' . doTime(getDatabaseSettingsHoraryDayEnd(1, 4)) : 'Fechado'; ?></label>
    </p>
    <p>Sexta-Feira
        <label><?php echo isOpenByDay(5) ? doTime(getDatabaseSettingsHoraryDayStart(1, 5)) . ' às ' . doTime(getDatabaseSettingsHoraryDayEnd(1, 5)) : 'Fechado'; ?></label>
    </p>
    <p>Sabado
        <label><?php echo isOpenByDay(6) ? doTime(getDatabaseSettingsHoraryDayStart(1, 6)) . ' às ' . doTime(getDatabaseSettingsHoraryDayEnd(1, 6)) : 'Fechado'; ?></label>
    </p>
</div>

<div id="pays-type">
    <center>
        <h6>Formas de Pagamento</h6>
        <hr>
        <?php
        $list_pay = doDatabaseSettingsPayListByStatus();

        if ($list_pay) {
            foreach ($list_pay as $data) {
                $list_pay_id = $data['id'];
                ?>
                <i class="fa-solid <?php echo getDatabaseIconTitle(getDatabaseSettingsPayIcon($list_pay_id)) ?>"></i>
                <?php echo getDatabaseSettingsPayType($list_pay_id) ?> |
                <?php
            }
        } else {
            ?>
            Nenhum método de pagamento cadastrado.
            <?php
        }
        ?>
    </center>
</div>
<br>


<div id="rating">
    <div id="general-rating">
        <p>Média Geral</p>
        <label><?php echo $media['general'] ?></label><br>
        <div id="stars">
            <section class="star <?php echo $media['general'] >= 1 ? 'colorstar' : ''; ?>">
                <img src="/layout/images/model/star-a.svg">
            </section>
            <section class="star <?php echo $media['general'] >= 2 ? 'colorstar' : ''; ?>">
                <img src="/layout/images/model/star-a.svg">
            </section>
            <section class="star <?php echo $media['general'] >= 3 ? 'colorstar' : ''; ?>">
                <img src="/layout/images/model/star-a.svg">
            </section>
            <section class="star <?php echo $media['general'] >= 4 ? 'colorstar' : ''; ?>">
                <img src="/layout/images/model/star-a.svg">
            </section>
            <section class="star <?php echo $media['general'] >= 5 ? 'colorstar' : ''; ?>">
                <img src="/layout/images/model/star-a.svg">
            </section>
        </div>
    </div>
    <div id="ratings">
        <section>
            <label>Comida</label>

            <div id="stars">
                <section class="star <?php echo $media['food'] >= 1 ? 'colorstar' : ''; ?>">
                    <img src="/layout/images/model/star-a.svg">
                </section>
                <section class="star <?php echo $media['food'] >= 2 ? 'colorstar' : ''; ?>">
                    <img src="/layout/images/model/star-a.svg">
                </section>
                <section class="star <?php echo $media['food'] >= 3 ? 'colorstar' : ''; ?>">
                    <img src="/layout/images/model/star-a.svg">
                </section>
                <section class="star <?php echo $media['food'] >= 4 ? 'colorstar' : ''; ?>">
                    <img src="/layout/images/model/star-a.svg">
                </section>
                <section class="star <?php echo $media['food'] >= 5 ? 'colorstar' : ''; ?>">
                    <img src="/layout/images/model/star-a.svg">
                </section>
            </div>
        </section>
        <section>
            <label>Embalagem</label>

            <div id="stars">
                <section class="star <?php echo $media['box'] >= 1 ? 'colorstar' : ''; ?>">
                    <img src="/layout/images/model/star-a.svg">
                </section>
                <section class="star <?php echo $media['box'] >= 2 ? 'colorstar' : ''; ?>">
                    <img src="/layout/images/model/star-a.svg">
                </section>
                <section class="star <?php echo $media['box'] >= 3 ? 'colorstar' : ''; ?>">
                    <img src="/layout/images/model/star-a.svg">
                </section>
                <section class="star <?php echo $media['box'] >= 4 ? 'colorstar' : ''; ?>">
                    <img src="/layout/images/model/star-a.svg">
                </section>
                <section class="star <?php echo $media['box'] >= 5 ? 'colorstar' : ''; ?>">
                    <img src="/layout/images/model/star-a.svg">
                </section>
            </div>
        </section>
        <section>
            <label>Tempo de Entrega</label>

            <div id="stars">
                <section class="star <?php echo $media['deliverytime'] >= 1 ? 'colorstar' : ''; ?>">
                    <img src="/layout/images/model/star-a.svg">
                </section>
                <section class="star <?php echo $media['deliverytime'] >= 2 ? 'colorstar' : ''; ?>">
                    <img src="/layout/images/model/star-a.svg">
                </section>
                <section class="star <?php echo $media['deliverytime'] >= 3 ? 'colorstar' : ''; ?>">
                    <img src="/layout/images/model/star-a.svg">
                </section>
                <section class="star <?php echo $media['deliverytime'] >= 4 ? 'colorstar' : ''; ?>">
                    <img src="/layout/images/model/star-a.svg">
                </section>
                <section class="star <?php echo $media['deliverytime'] >= 5 ? 'colorstar' : ''; ?>">
                    <img src="/layout/images/model/star-a.svg">
                </section>
            </div>
        </section>
        <section>
            <label>Custo Beneficio</label>

            <div id="stars">
                <section class="star <?php echo $media['costbenefit'] >= 1 ? 'colorstar' : ''; ?>">
                    <img src="/layout/images/model/star-a.svg">
                </section>
                <section class="star <?php echo $media['costbenefit'] >= 2 ? 'colorstar' : ''; ?>">
                    <img src="/layout/images/model/star-a.svg">
                </section>
                <section class="star <?php echo $media['costbenefit'] >= 3 ? 'colorstar' : ''; ?>">
                    <img src="/layout/images/model/star-a.svg">
                </section>
                <section class="star <?php echo $media['costbenefit'] >= 4 ? 'colorstar' : ''; ?>">
                    <img src="/layout/images/model/star-a.svg">
                </section>
                <section class="star <?php echo $media['costbenefit'] >= 5 ? 'colorstar' : ''; ?>">
                    <img src="/layout/images/model/star-a.svg">
                </section>
            </div>
        </section>
    </div>
</div>
<br>

<div id="users-rating">

    <div class="rating-wrapper">
        <?php
        $available_list = doDatabaseRequestOrderAvailableListLimit(10);

        if ($available_list) {
            foreach ($available_list as $dataAvailable) {
                $available_list_id = $dataAvailable['id'];
                $order_id = getDatabaseRequestOrderAvailableRequestID($available_list_id);
                $cart_id = getDatabaseRequestOrderCartID($order_id);
                $user_id = getDatabaseCartUserID($cart_id);
                ?>
                <section class="rating-container">
                    <div class="user-photo">
                        <img src="<?php echo getPathAvatarImage(getDatabaseUserPhotoName($user_id)); ?>">
                    </div>
                    <h6><?php echo getDatabaseUserName($user_id); ?></h6>
                    <div class="second-available-frame" style="font-size: 0.9em !important;">
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
                    <small><?php echo doDate(getDatabaseRequestOrderAvailableCreated($available_list_id)) ?> às
                        <?php echo doTime(getDatabaseRequestOrderAvailableCreated($available_list_id)) ?></small>
                    <p class="user-comment" style="min-height: 30px">
                        <?php echo getDatabaseRequestOrderAvailableComment($available_list_id) ?>
                    </p>
                </section>
                <?php
            }
        } ?>
        <div class="scroll-left"></div>
        <div class="scroll-right"></div>
    </div>
</div>
<br>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        const divClicavel = document.getElementById('status');
        const elementoParaExibir = document.getElementById('week-horary');

        divClicavel.addEventListener('click', () => {
            elementoParaExibir.style.display = elementoParaExibir.style.display === 'block' ? 'none' : 'block';
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const divClicavel = document.getElementById('pays');
        const elementoParaExibir = document.getElementById('pays-type');

        divClicavel.addEventListener('click', () => {
            elementoParaExibir.style.display = elementoParaExibir.style.display === 'block' ? 'none' : 'block';
        });
    });


    document.addEventListener('DOMContentLoaded', function () {
        const scrollLeft = document.querySelector('.scroll-left');
        const scrollRight = document.querySelector('.scroll-right');
        const ratingWrapper = document.querySelector('.rating-wrapper');

        scrollLeft.addEventListener('click', function () {
            ratingWrapper.scrollLeft -= 100; // Altere este valor para ajustar a quantidade de rolagem
        });

        scrollRight.addEventListener('click', function () {
            ratingWrapper.scrollLeft += 100; // Altere este valor para ajustar a quantidade de rolagem
        });
    });

</script>
<?php
include_once __DIR__ . '/layout/php/footer.php';
?>