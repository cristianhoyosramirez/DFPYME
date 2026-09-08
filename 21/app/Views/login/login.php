<?php $session = session(); ?>

<!doctype html>
<html lang="es-CO">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, viewport-fit=cover">

    <meta
        http-equiv="X-UA-Compatible"
        content="ie=edge">

    <title>Bienvenido DF PYME</title>

    <!-- CSS -->
    <link
        href="<?= base_url() ?>/Assets/css/tabler.min.css"
        rel="stylesheet">

    <link
        rel="shortcut icon"
        href="<?= base_url() ?>/Assets/img/favicon.png">

    <style>
        :root {
            --azul: #1264e8;
            --azul-hover: #0d56ce;
            --azul-claro: #eaf2ff;
            --azul-oscuro: #172e52;
            --texto: #172e52;
            --texto-secundario: #536582;
            --borde: #dce3ed;
            --fondo: #f3f6fa;
            --blanco: #ffffff;
            --sombra: 0 10px 35px rgba(25, 45, 75, 0.10);
            --sombra-boton: 0 5px 14px rgba(25, 45, 75, 0.08);
        }

        /* ========================================================= GENERAL ========================================================= */
        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            min-height: 100%;
            font-family: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: var(--texto);
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #f6f8fb 0%, #eef2f7 100%);
            padding: 22px;
        }

        /* ========================================================= CONTENEDOR PRINCIPAL ========================================================= */
        .login-container {
            width: 100%;
            max-width: 900px;
            min-height: calc(100vh - 44px);
            display: flex;
            flex-direction: column;
            background: var(--blanco);
            border: 1px solid #e0e5ec;
            border-radius: 20px;
            box-shadow: var(--sombra);
            overflow: hidden;
        }

        /* ========================================================= CONTENIDO ========================================================= */
        .login-content {
            width: 100%;
            max-width: 800px;
            margin: auto;
            padding: 38px 45px 28px;
        }

        /* ========================================================= LOGO ========================================================= */
        .login-logo {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 17px;
        }

        .login-logo img {
            width: auto;
            max-width: 390px;
            max-height: 90px;
            object-fit: contain;
        }

        /* ========================================================= TÍTULO ========================================================= */
        .login-title {
            margin: 0;
            text-align: center;
            font-size: 44px;
            line-height: 1.15;
            font-weight: 700;
            letter-spacing: -1px;
            color: var(--azul-oscuro);
        }

        /* ========================================================= SUBTÍTULO ========================================================= */
        .login-subtitle {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 11px;
            margin-top: 18px;
            margin-bottom: 22px;
            color: var(--texto-secundario);
            font-size: 21px;
            font-weight: 400;
        }

        .security-icon {
            width: 25px;
            height: 25px;
            color: var(--azul);
            flex-shrink: 0;
        }

        /* ========================================================= PIN ========================================================= */
        .pin-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 12px auto 16px;
        }

        .pin-box {
            width: 108px;
            height: 108px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #ffffff;
            border: 2px solid var(--borde);
            border-radius: 17px;
            color: var(--azul-oscuro);
            font-size: 46px;
            font-weight: 600;
            transition: border-color 0.18s ease, box-shadow 0.18s ease, transform 0.18s ease;
        }

        .pin-box.active {
            border-color: var(--azul);
            box-shadow: 0 0 0 3px rgba(18, 100, 232, 0.08), 0 7px 20px rgba(18, 100, 232, 0.12);
            transform: translateY(-1px);
        }

        .pin-box.filled {
            border-color: #bfcbdc;
        }

        /* ========================================================= BARRA DE PROGRESO ========================================================= */
        .pin-progress {
            width: 360px;
            max-width: 80%;
            height: 7px;
            margin: 18px auto 9px;
            background: #e5e9ef;
            border-radius: 20px;
            overflow: hidden;
        }

        .pin-progress-bar {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #1769e8, #2f7cf0);
            border-radius: inherit;
            transition: width 0.2s ease;
        }

        /* ========================================================= CONTADOR ========================================================= */
        .pin-counter {
            text-align: center;
            font-size: 19px;
            color: var(--azul-oscuro);
            margin-bottom: 18px;
        }

        .pin-counter strong {
            color: var(--azul);
            font-weight: 700;
        }

        /* ========================================================= MENSAJE ========================================================= */
        .login-message {
            min-height: 58px;
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 11px 20px;
            margin-bottom: 18px;
            background: #ffffff;
            border: 1px solid #dce3ec;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(30, 50, 80, 0.05);
            font-size: 16px;
            color: var(--texto);
            transition: all 0.2s ease;
        }

        .login-message svg {
            width: 27px;
            height: 27px;
            flex-shrink: 0;
            color: var(--azul);
        }

        #error_login {
            flex: 1;
            min-height: 22px;
            color: #d63939;
            font-size: 15px;
            text-align: center;
        }

        /* ========================================================= INPUT OCULTO ========================================================= */
        #code {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            pointer-events: none;
            left: -9999px;
        }

        /* ========================================================= TECLADO ========================================================= */
        .numeric-keyboard {
            width: 100%;
        }

        .table-numeric {
            width: 100%;
            border-collapse: separate;
            border-spacing: 9px;
        }

        .table-numeric td {
            width: 33.333%;
            padding: 0;
        }

        /* ========================================================= BOTONES ========================================================= */
        .numeric-button {
            width: 100%;
            height: 82px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 14px !important;
            background: #ffffff;
            border: 1px solid #dce3eb !important;
            color: var(--azul-oscuro);
            font-size: 34px;
            font-weight: 600;
            box-shadow: var(--sombra-boton);
            transition: transform 0.08s ease, box-shadow 0.12s ease, background 0.12s ease, border-color 0.12s ease;
        }

        .numeric-button:hover {
            background: #f8faff;
            border-color: #c9d5e5 !important;
            box-shadow: 0 7px 18px rgba(25, 45, 75, 0.10);
        }

        .numeric-button:active {
            transform: scale(0.97);
            box-shadow: 0 2px 7px rgba(25, 45, 75, 0.08);
        }

        /* ========================================================= BORRAR ========================================================= */
        .btn-delete {
            color: var(--azul-oscuro) !important;
            background: #ffffff !important;
            font-size: 30px !important;
        }

        .btn-delete svg {
            width: 35px;
            height: 35px;
        }

        /* ========================================================= INGRESAR ========================================================= */
        .btn-login {
            background: linear-gradient(135deg, #2775ec, #075fe2) !important;
            border: none !important;
            color: #ffffff !important;
            box-shadow: 0 7px 18px rgba(18, 100, 232, 0.25);
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #1f6be0, #0555ce) !important;
            color: #ffffff !important;
        }

        .btn-login svg {
            width: 38px;
            height: 38px;
        }

        /* ========================================================= SEGURIDAD ========================================================= */
        .security-footer {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 9px;
            margin-top: 22px;
            margin-bottom: 5px;
            color: var(--texto-secundario);
            font-size: 15px;
        }

        .security-footer svg {
            width: 22px;
            height: 22px;
            color: var(--azul);
        }

        /* ========================================================= FOOTER ========================================================= */
        .login-footer {
            min-height: 62px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-top: 1px solid #e1e5eb;
            color: var(--texto-secundario);
            font-size: 14px;
            gap: 7px;
            margin-top: auto;
            padding: 8px 15px;
            text-align: center;
        }

        .login-footer svg {
            width: 23px;
            height: 23px;
            margin-right: 5px;
            color: #536582;
        }

        .login-footer strong {
            color: var(--azul);
            font-weight: 700;
        }

        /* ========================================================= TABLET ========================================================= */
        @media (max-width: 900px) {
            body {
                padding: 18px;
            }

            .login-container {
                max-width: 820px;
                min-height: calc(100vh - 36px);
                border-radius: 18px;
            }

            .login-content {
                max-width: 740px;
                padding: 30px 35px 22px;
            }

            .login-logo {
                margin-bottom: 13px;
            }

            .login-logo img {
                max-width: 340px;
                max-height: 78px;
            }

            .login-title {
                font-size: 39px;
            }

            .login-subtitle {
                font-size: 19px;
                margin-top: 15px;
                margin-bottom: 19px;
            }

            .pin-container {
                gap: 12px;
            }

            .pin-box {
                width: 95px;
                height: 95px;
                font-size: 42px;
            }

            .pin-progress {
                width: 330px;
            }

            .pin-counter {
                font-size: 18px;
                margin-bottom: 15px;
            }

            .login-message {
                min-height: 54px;
                padding: 10px 18px;
                margin-bottom: 15px;
            }

            .numeric-button {
                height: 75px;
                font-size: 32px;
            }

            .btn-delete {
                font-size: 28px !important;
            }

            .btn-login svg {
                width: 35px;
                height: 35px;
            }

            .security-footer {
                margin-top: 18px;
                font-size: 14px;
            }
        }

        /* ========================================================= CELULAR ========================================================= */
        @media (max-width: 600px) {
            body {
                padding: 0;
                align-items: stretch;
                background: #ffffff;
            }

            .login-container {
                width: 100%;
                max-width: none;
                min-height: 100vh;
                border: none;
                border-radius: 0;
                box-shadow: none;
            }

            .login-content {
                width: 100%;
                max-width: none;
                padding: 24px 17px 15px;
            }

            /* LOGO */
            .login-logo {
                margin-bottom: 13px;
            }

            .login-logo img {
                max-width: 250px;
                max-height: 68px;
            }

            /* TÍTULO */
            .login-title {
                font-size: 32px;
                letter-spacing: -0.5px;
            }

            /* SUBTÍTULO */
            .login-subtitle {
                font-size: 18px;
                gap: 8px;
                margin-top: 15px;
                margin-bottom: 20px;
            }

            .security-icon {
                width: 22px;
                height: 22px;
            }

            /* PIN */
            .pin-container {
                gap: 8px;
                margin: 10px auto 13px;
            }

            .pin-box {
                width: calc(25% - 6px);
                height: 78px;
                border-radius: 13px;
                font-size: 36px;
            }

            /* PROGRESO */
            .pin-progress {
                width: 78%;
                height: 6px;
                margin: 16px auto 8px;
            }

            /* CONTADOR */
            .pin-counter {
                font-size: 18px;
                margin-bottom: 16px;
            }

            /* MENSAJE */
            .login-message {
                min-height: 52px;
                padding: 10px 13px;
                gap: 10px;
                margin-bottom: 12px;
                border-radius: 11px;
                font-size: 14px;
            }

            .login-message svg {
                width: 24px;
                height: 24px;
            }

            #error_login {
                font-size: 14px;
            }

            /* TECLADO */
            .table-numeric {
                border-spacing: 6px;
            }

            .numeric-button {
                height: 67px;
                border-radius: 12px !important;
                font-size: 29px;
            }

            .btn-delete {
                font-size: 26px !important;
            }

            .btn-delete svg {
                width: 29px;
                height: 29px;
            }

            .btn-login svg {
                width: 31px;
                height: 31px;
            }

            /* SEGURIDAD */
            .security-footer {
                margin-top: 16px;
                margin-bottom: 3px;
                font-size: 13px;
            }

            .security-footer svg {
                width: 20px;
                height: 20px;
            }

            /* FOOTER */
            .login-footer {
                min-height: 56px;
                padding: 8px 12px;
                font-size: 12px;
            }

            .login-footer svg {
                width: 20px;
                height: 20px;
                margin-right: 4px;
            }
        }

        /* ========================================================= CELULARES PEQUEÑOS ========================================================= */
        @media (max-width: 380px) {
            .login-content {
                padding: 19px 13px 10px;
            }

            .login-logo {
                margin-bottom: 9px;
            }

            .login-logo img {
                max-width: 220px;
                max-height: 58px;
            }

            .login-title {
                font-size: 29px;
            }

            .login-subtitle {
                font-size: 16px;
                margin-top: 11px;
                margin-bottom: 16px;
            }

            .security-icon {
                width: 20px;
                height: 20px;
            }

            .pin-container {
                gap: 6px;
                margin-top: 8px;
            }

            .pin-box {
                height: 70px;
                border-radius: 12px;
                font-size: 32px;
            }

            .pin-progress {
                width: 72%;
                height: 5px;
                margin-top: 13px;
            }

            .pin-counter {
                font-size: 16px;
                margin-bottom: 12px;
            }

            .login-message {
                min-height: 46px;
                padding: 8px 10px;
                font-size: 13px;
                margin-bottom: 9px;
            }

            .login-message svg {
                width: 21px;
                height: 21px;
            }

            #error_login {
                font-size: 13px;
            }

            .table-numeric {
                border-spacing: 5px;
            }

            .numeric-button {
                height: 60px;
                border-radius: 10px !important;
                font-size: 26px;
            }

            .btn-delete svg {
                width: 26px;
                height: 26px;
            }

            .btn-login svg {
                width: 28px;
                height: 28px;
            }

            .security-footer {
                margin-top: 11px;
                font-size: 12px;
            }

            .security-footer svg {
                width: 18px;
                height: 18px;
            }

            .login-footer {
                min-height: 50px;
                font-size: 11px;
            }

            .login-footer svg {
                width: 18px;
                height: 18px;
            }
        }

        /* ========================================================= PANTALLAS CON POCA ALTURA ========================================================= */
        @media (max-height: 800px) and (min-width: 601px) {
            body {
                padding: 12px;
            }

            .login-container {
                min-height: calc(100vh - 24px);
            }

            .login-content {
                padding-top: 18px;
                padding-bottom: 14px;
            }

            .login-logo {
                margin-bottom: 8px;
            }

            .login-logo img {
                max-height: 62px;
            }

            .login-title {
                font-size: 36px;
            }

            .login-subtitle {
                margin-top: 9px;
                margin-bottom: 12px;
            }

            .pin-container {
                margin-top: 7px;
                margin-bottom: 10px;
            }

            .pin-box {
                width: 88px;
                height: 88px;
                font-size: 40px;
            }

            .pin-progress {
                margin-top: 12px;
                margin-bottom: 6px;
            }

            .pin-counter {
                margin-bottom: 10px;
            }

            .login-message {
                min-height: 48px;
                margin-bottom: 10px;
            }

            .numeric-button {
                height: 65px;
            }

            .security-footer {
                margin-top: 12px;
            }

            .login-footer {
                min-height: 52px;
            }
        }

        /* ========================================================= CELULAR CON POCA ALTURA ========================================================= */
        @media (max-width: 600px) and (max-height: 700px) {
            .login-content {
                padding-top: 13px;
                padding-bottom: 7px;
            }

            .login-logo {
                margin-bottom: 6px;
            }

            .login-logo img {
                max-height: 48px;
            }

            .login-title {
                font-size: 27px;
            }

            .login-subtitle {
                margin-top: 7px;
                margin-bottom: 9px;
            }

            .pin-container {
                margin-top: 5px;
                margin-bottom: 7px;
            }

            .pin-box {
                height: 61px;
                font-size: 29px;
            }

            .pin-progress {
                margin-top: 8px;
                margin-bottom: 4px;
            }

            .pin-counter {
                margin-bottom: 7px;
            }

            .login-message {
                min-height: 40px;
                margin-bottom: 6px;
            }

            .numeric-button {
                height: 52px;
            }

            .security-footer {
                margin-top: 7px;
            }

            .login-footer {
                min-height: 44px;
            }
        }
    </style>

</head>


<body>


    <div class="login-container">


        <!-- =====================================================
         CONTENIDO
    ====================================================== -->

        <div class="login-content">


            <!-- =================================================
             LOGO
        ================================================== -->

            <div class="login-logo">

                <a href=".">

                    <img
                        src="<?= base_url() ?>/Assets/img/logo.png"
                        alt="DF PYME">

                </a>

            </div>


            <!-- =================================================
             TITULO
        ================================================== -->

            <h1 class="login-title">

                Inicio de sesión

            </h1>


            <!-- =================================================
             SUBTITULO
        ================================================== -->

            <div class="login-subtitle">

                <svg
                    class="security-icon"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round">

                    <rect
                        x="5"
                        y="11"
                        width="14"
                        height="10"
                        rx="2" />

                    <path d="M8 11V7a4 4 0 0 1 8 0v4" />

                    <path d="M12 15v2" />

                </svg>


                <span>

                    Ingresa tu PIN de 4 dígitos

                </span>

            </div>


            <!-- =================================================
             FORMULARIO
        ================================================== -->

            <form
                action="<?= base_url('login/login') ?>"
                id="form"
                method="post"
                autocomplete="off">


                <!-- Campo real que conserva la funcionalidad -->
                <input
                    type="password"
                    id="code"
                    name="pin"
                    maxlength="4"
                    autocomplete="off"
                    tabindex="-1">


                <!-- =================================================
                 PIN VISUAL
            ================================================== -->

                <div
                    class="pin-container"
                    id="pinContainer">

                    <div
                        class="pin-box active"
                        data-index="0"></div>

                    <div
                        class="pin-box"
                        data-index="1"></div>

                    <div
                        class="pin-box"
                        data-index="2"></div>

                    <div
                        class="pin-box"
                        data-index="3"></div>

                </div>


                <!-- =================================================
                 PROGRESO
            ================================================== -->

                <div class="pin-progress">

                    <div
                        class="pin-progress-bar"
                        id="pinProgress"></div>

                </div>


                <div class="pin-counter">

                    <strong id="pinCount">0</strong>
                    de 4

                </div>


                <!-- =================================================
                 MENSAJE / ERROR
            ================================================== -->

                <div class="login-message">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">

                        <path d="M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7l8-4z" />

                        <path d="m9 12 2 2 4-4" />

                    </svg>


                    <div id="error_login">

                        <?php if (session('errors.pin')): ?>

                            <?= session('errors.pin') ?>

                        <?php else: ?>

                            Ingresa tu PIN para continuar

                        <?php endif; ?>

                    </div>

                </div>


            </form>


            <!-- =================================================
             TECLADO NUMERICO
        ================================================== -->

            <div class="numeric-keyboard">

                <table class="table-numeric">

                    <tbody>


                        <!-- FILA 1 -->

                        <tr>

                            <td>

                                <button
                                    type="button"
                                    class="btn numeric-button"
                                    onclick="agregarDigito('1')">

                                    1

                                </button>

                            </td>


                            <td>

                                <button
                                    type="button"
                                    class="btn numeric-button"
                                    onclick="agregarDigito('2')">

                                    2

                                </button>

                            </td>


                            <td>

                                <button
                                    type="button"
                                    class="btn numeric-button"
                                    onclick="agregarDigito('3')">

                                    3

                                </button>

                            </td>

                        </tr>


                        <!-- FILA 2 -->

                        <tr>

                            <td>

                                <button
                                    type="button"
                                    class="btn numeric-button"
                                    onclick="agregarDigito('4')">

                                    4

                                </button>

                            </td>


                            <td>

                                <button
                                    type="button"
                                    class="btn numeric-button"
                                    onclick="agregarDigito('5')">

                                    5

                                </button>

                            </td>


                            <td>

                                <button
                                    type="button"
                                    class="btn numeric-button"
                                    onclick="agregarDigito('6')">

                                    6

                                </button>

                            </td>

                        </tr>


                        <!-- FILA 3 -->

                        <tr>

                            <td>

                                <button
                                    type="button"
                                    class="btn numeric-button"
                                    onclick="agregarDigito('7')">

                                    7

                                </button>

                            </td>


                            <td>

                                <button
                                    type="button"
                                    class="btn numeric-button"
                                    onclick="agregarDigito('8')">

                                    8

                                </button>

                            </td>


                            <td>

                                <button
                                    type="button"
                                    class="btn numeric-button"
                                    onclick="agregarDigito('9')">

                                    9

                                </button>

                            </td>

                        </tr>


                        <!-- FILA 4 -->

                        <tr>


                            <!-- BORRAR -->

                            <td>

                                <button
                                    type="button"
                                    class="btn numeric-button btn-delete"
                                    onclick="borrarDigito()"
                                    aria-label="Borrar">

                                    <svg
                                        width="45"
                                        height="45"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                        stroke-linejoin="round">

                                        <path d="M20 5H9l-5 7 5 7h11a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2z" />

                                        <path d="m10 9 4 4" />

                                        <path d="m14 9-4 4" />

                                    </svg>

                                </button>

                            </td>


                            <!-- CERO -->

                            <td>

                                <button
                                    type="button"
                                    class="btn numeric-button"
                                    onclick="agregarDigito('0')">

                                    0

                                </button>

                            </td>


                            <!-- INGRESAR -->

                            <td>

                                <button
                                    type="button"
                                    class="btn numeric-button btn-login"
                                    onclick="login()"
                                    aria-label="Ingresar">

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round">

                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="9" />

                                        <path d="m8 12 3 3 5-6" />

                                    </svg>

                                </button>

                            </td>

                        </tr>


                    </tbody>

                </table>

            </div>


            <!-- =================================================
             SEGURIDAD
        ================================================== -->

            <div class="security-footer">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round">

                    <path d="M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7l8-4z" />

                    <path d="m9 12 2 2 4-4" />

                </svg>


                <span>

                    Tu información está protegida

                </span>

            </div>


        </div>


        <!-- =====================================================
         FOOTER
    ====================================================== -->

        <footer class="login-footer">

            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
                stroke-linecap="round"
                stroke-linejoin="round">

                <path d="M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7l8-4z" />

                <path d="m9 12 2 2 4-4" />

            </svg>


            <strong>DF PYME</strong>

            <span>

                © 2024 - Todos los derechos reservados

            </span>

        </footer>


    </div>


    <!-- =========================================================
     LIBRERIAS
========================================================= -->

    <script src="<?= base_url() ?>/Assets/js/jquery-3.5.1.js"></script>

    <script src="<?= base_url() ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>


    <!-- Scripts existentes -->

    <script src="<?= base_url() ?>/Assets/script_js/bloqueos/licencia/licencia.js"></script>

    <script src="<?= base_url() ?>/Assets/script_js/bloqueos/consumo/consumo.js"></script>


    <script>
        /* =========================================================
       ELEMENTOS
    ========================================================= */

        const inputPin =
            document.getElementById('code');

        const pinBoxes =
            document.querySelectorAll('.pin-box');

        const errorLogin =
            document.getElementById('error_login');

        const pinProgress =
            document.getElementById('pinProgress');

        const pinCount =
            document.getElementById('pinCount');


        /* =========================================================
           AGREGAR DIGITO
        ========================================================= */

        function agregarDigito(digito) {

            if (inputPin.value.length >= 4) {

                return;
            }


            inputPin.value += digito;


            actualizarPinVisual();


            borrar_error();


            /*
             * Cuando completa los 4 dígitos
             * realiza automáticamente el login.
             */

            if (inputPin.value.length === 4) {

                setTimeout(() => {

                    login();

                }, 100);

            }

        }


        /* =========================================================
           BORRAR DIGITO
        ========================================================= */

        function borrarDigito() {

            inputPin.value =
                inputPin.value.slice(0, -1);


            actualizarPinVisual();


            borrar_error();

        }


        /* =========================================================
           ACTUALIZAR PIN VISUAL
        ========================================================= */

        function actualizarPinVisual() {

            const valor =
                inputPin.value;


            pinBoxes.forEach((box, index) => {

                box.classList.remove('active');

                box.classList.remove('filled');


                if (index < valor.length) {

                    box.textContent = '•';

                    box.classList.add('filled');

                } else {

                    box.textContent = '';

                }

            });


            /*
             * Siguiente posición activa
             */

            if (valor.length < 4) {

                pinBoxes[valor.length]
                    .classList.add('active');

            }


            /*
             * Contador
             */

            pinCount.textContent =
                valor.length;


            /*
             * Barra de progreso
             */

            const porcentaje =
                (valor.length / 4) * 100;


            pinProgress.style.width =
                porcentaje + '%';

        }


        /* =========================================================
           LOGIN
        ========================================================= */

        function login() {

            const pin =
                inputPin.value;


            if (pin === '') {

                errorLogin.textContent =
                    'No hay definido PIN.';

                actualizarPinVisual();

                return;
            }


            if (pin.length !== 4) {

                errorLogin.textContent =
                    'El PIN debe tener 4 dígitos.';

                actualizarPinVisual();

                return;
            }


            /*
             * Mantiene exactamente
             * el formulario y endpoint actual.
             */

            document
                .getElementById('form')
                .submit();

        }


        /* =========================================================
           LIMPIAR ERROR
        ========================================================= */

        function borrar_error() {

            <?php if (!session('errors.pin')): ?>

                errorLogin.textContent =
                    'Ingresa tu PIN para continuar';

            <?php else: ?>

                errorLogin.textContent = '';

            <?php endif; ?>

        }


        /* =========================================================
           TECLADO FISICO
        ========================================================= */

        document.addEventListener(
            'keydown',
            function(event) {


                /*
                 * Números
                 */

                if (/^[0-9]$/.test(event.key)) {

                    event.preventDefault();

                    agregarDigito(event.key);

                    return;
                }


                /*
                 * Backspace
                 */

                if (event.key === 'Backspace') {

                    event.preventDefault();

                    borrarDigito();

                    return;
                }


                /*
                 * Enter
                 */

                if (event.key === 'Enter') {

                    event.preventDefault();

                    login();

                }

            }
        );


        /* =========================================================
           MENSAJES DE SESION
        ========================================================= */

        const mensaje =
            <?= json_encode(session()->getFlashdata('mensaje')) ?>;


        const iconoMensaje =
            <?= json_encode(session()->getFlashdata('iconoMensaje')) ?>;


        const estado =
            <?= json_encode(session()->getFlashdata('estado')) ?>;


        if (mensaje) {

            Swal.fire({

                title: estado,

                text: mensaje,

                icon: iconoMensaje ?
                    iconoMensaje.toLowerCase() : 'info',

                confirmButtonText: 'ACEPTAR',

                confirmButtonColor: '#1264e8'

            });

        }


        /* =========================================================
           ESTADO INICIAL
        ========================================================= */

        actualizarPinVisual();
    </script>


</body>

</html>