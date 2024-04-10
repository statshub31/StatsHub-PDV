<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- ===== CSS ===== -->
        <link rel="stylesheet" href="/layout/css/login.css">
    
        <!-- ===== BOX ICONS ===== -->
        <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>

        <title>Login</title>
    </head>
    <body>
        <div class="login">
            <div class="login__content">
                <div class="login__img">
                    <img src="/layout/images/config/img-login.svg" alt="">
                </div>

                <div class="login__forms">
                    <form action="" class="login__registre" id="login-in">
                        <h1 class="login__title">Login</h1>
    
                        <div class="login__box">
                            <i class='bx bx-user login__icon'></i>
                            <input type="text" placeholder="Usuário" class="login__input">
                        </div>
    
                        <div class="login__box">
                            <i class='bx bx-lock-alt login__icon'></i>
                            <input type="password" placeholder="Senha" class="login__input">
                        </div>

                        <a href="#" class="login__forgot">Esqueceu sua senha?</a>

                        <a href="#" class="login__button">Entrar</a>

                        <div>
                            <span class="login__account">Não tem uma conta?</span>
                            <span class="login__signin" id="sign-up">Cadastre</span>
                        </div>
                    </form>

                    <form action="" class="login__create none" id="login-up">
                        <h1 class="login__title">Criar Conta</h1>
    
                        <div class="login__box">
                            <i class='bx bx-user login__icon'></i>
                            <input type="text" placeholder="Usuário" class="login__input">
                        </div>
    
                        <div class="login__box">
                            <i class='bx bx-lock-alt login__icon'></i>
                            <input type="password" placeholder="Senha" class="login__input">
                        </div>

                        <div class="login__box">
                            <i class='bx bx-user login__icon'></i>
                            <input type="text" placeholder="Nome" class="login__input">
                        </div>

                        <div class="login__box">
                            <i class='bx bx-at login__icon'></i>
                            <input type="text" placeholder="Email" class="login__input">
                        </div>

                        <div class="login__box">
                            <i class='bx bx-phone login__icon'></i>
                            <input type="text" placeholder="Celular" class="login__input">
                        </div>

                        <a href="#" class="login__button">Cadastrar</a>

                        <div>
                            <span class="login__account">Já tem uma conta ?</span>
                            <span class="login__signup" id="sign-in">Entre</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!--===== MAIN JS =====-->
        <script src="/layout/js/login.js"></script>
    </body>
</html>