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

        /* =========================================================
   GENERAL
========================================================= */

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

        /* =========================================================
   CONTENEDOR PRINCIPAL
========================================================= */

        .login-container {
            width: 100%;
            max-width: 430px;
            min-height: auto;
            display: flex;
            flex-direction: column;
            background: var(--blanco);
            border: 1px solid #e0e5ec;
            border-radius: 20px;
            box-shadow: var(--sombra);
            overflow: hidden;
        }

        /* =========================================================
   CONTENIDO
========================================================= */

        .login-content {
            width: 100%;
            max-width: 430px;
            margin: auto;
            padding: 24px 20px 18px;
        }

        /* =========================================================
   LOGO
========================================================= */

        .login-logo {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 14px;
        }

        .login-logo img {
            width: auto;
            max-width: 280px;
            max-height: 70px;
            object-fit: contain;
        }

        /* =========================================================
   TÍTULO
========================================================= */

        .login-title {
            margin: 0;
            text-align: center;
            font-size: 34px;
            line-height: 1.15;
            font-weight: 700;
            letter-spacing: -0.7px;
            color: var(--azul-oscuro);
        }

        /* =========================================================
   SUBTÍTULO
========================================================= */

        .login-subtitle {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 9px;
            margin-top: 14px;
            margin-bottom: 17px;
            color: var(--texto-secundario);
            font-size: 18px;
            font-weight: 400;
        }

        .security-icon {
            width: 23px;
            height: 23px;
            color: var(--azul);
            flex-shrink: 0;
        }

        /* =========================================================
   PIN
========================================================= */

        .pin-container {
            position: relative;
            display: flex;
            justify-content: center;
            gap: 8px;
            margin: 9px auto 12px;
        }

        .pin-box {
            width: 52px;
            height: 58px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #ffffff;
            border: 2px solid var(--borde);
            border-radius: 13px;
            color: var(--azul-oscuro);
            font-size: 28px;
            font-weight: 600;
            transition:
                border-color 0.18s ease,
                box-shadow 0.18s ease,
                transform 0.18s ease;
        }

        .pin-box.active {
            border-color: var(--azul);
            box-shadow:
                0 0 0 3px rgba(18, 100, 232, 0.08),
                0 7px 20px rgba(18, 100, 232, 0.12);
            transform: translateY(-1px);
        }

        .pin-box.filled {
            border-color: #bfcbdc;
        }

        /* =========================================================
   BARRA DE PROGRESO
========================================================= */

        .pin-progress {
            width: 240px;
            max-width: 80%;
            height: 6px;
            margin: 13px auto 7px;
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

        /* =========================================================
   CONTADOR
========================================================= */

        .pin-counter {
            text-align: center;
            font-size: 17px;
            color: var(--azul-oscuro);
            margin-bottom: 13px;
        }

        .pin-counter strong {
            color: var(--azul);
            font-weight: 700;
        }

        /* =========================================================
   MENSAJE
========================================================= */

        .login-message {
            min-height: 50px;
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 9px 13px;
            margin-bottom: 12px;
            background: #ffffff;
            border: 1px solid #dce3ec;
            border-radius: 11px;
            box-shadow: 0 4px 12px rgba(30, 50, 80, 0.05);
            font-size: 14px;
            color: var(--texto);
            transition: all 0.2s ease;
        }

        .login-message svg {
            width: 24px;
            height: 24px;
            flex-shrink: 0;
            color: var(--azul);
        }

        #error_login {
            flex: 1;
            min-height: 22px;
            color: #d63939;
            font-size: 14px;
            text-align: center;
        }

        /* =========================================================
   INPUT OCULTO
========================================================= */

        #code {
            position: absolute;
            left: 50%;
            top: 0;
            transform: translateX(-50%);
            width: 280px;
            height: 65px;
            opacity: 0.01;
            z-index: 10;
            border: 0;
            outline: 0;
            background: transparent;
            color: transparent;
            caret-color: transparent;
            padding: 0;
            margin: 0;
            cursor: text;
        }

        #code:focus {
            outline: none;
            box-shadow: none;
        }

        /* =========================================================
   TECLADO NUMÉRICO
========================================================= */

        .numeric-keyboard {
            width: 100%;
        }

        .table-numeric {
            width: 290px;
            max-width: 290px;
            margin: 0 auto;
            border-collapse: separate;
            border-spacing: 5px;
        }

        .table-numeric td {
            width: 33.333%;
            padding: 0;
        }

        /* =========================================================
   BOTONES
========================================================= */

        .numeric-button {
            width: 100%;
            height: 53px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 11px !important;
            background: #ffffff;
            border: 1px solid #dce3eb !important;
            color: var(--azul-oscuro);
            font-size: 25px;
            font-weight: 600;
            box-shadow: var(--sombra-boton);
            transition:
                transform 0.08s ease,
                box-shadow 0.12s ease,
                background 0.12s ease,
                border-color 0.12s ease;
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

        /* =========================================================
   BOTÓN BORRAR
========================================================= */

        .btn-delete {
            color: var(--azul-oscuro) !important;
            background: #ffffff !important;
            font-size: 26px !important;
        }

        .btn-delete svg {
            width: 29px;
            height: 29px;
        }

        /* =========================================================
   BOTÓN INGRESAR
========================================================= */

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
            width: 30px;
            height: 30px;
        }

        /* =========================================================
   SEGURIDAD
========================================================= */

        .security-footer {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 15px;
            margin-bottom: 3px;
            color: var(--texto-secundario);
            font-size: 13px;
        }

        .security-footer svg {
            width: 20px;
            height: 20px;
            color: var(--azul);
        }

        /* =========================================================
   FOOTER
========================================================= */

        .login-footer {
            min-height: 58px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-top: 1px solid #e1e5eb;
            color: var(--texto-secundario);
            font-size: 13px;
            gap: 7px;
            margin-top: auto;
            padding: 8px 12px;
            text-align: center;
        }

        .login-footer svg {
            width: 21px;
            height: 21px;
            margin-right: 4px;
            color: #536582;
        }

        .login-footer strong {
            color: var(--azul);
            font-weight: 700;
        }

        /* =========================================================
   TABLET
========================================================= */

        @media (max-width: 900px) {

            body {
                padding: 18px;
            }

            .login-container {
                max-width: 430px;
                min-height: auto;
                border-radius: 18px;
            }

            .login-content {
                max-width: 430px;
                padding: 23px 20px 17px;
            }

            .login-logo {
                margin-bottom: 12px;
            }

            .login-logo img {
                max-width: 270px;
                max-height: 66px;
            }

            .login-title {
                font-size: 33px;
            }

            .login-subtitle {
                font-size: 18px;
                margin-top: 13px;
                margin-bottom: 16px;
            }

            .pin-container {
                gap: 8px;
            }

            .pin-box {
                width: 52px;
                height: 58px;
                font-size: 28px;
            }

            .pin-progress {
                width: 235px;
            }

            .pin-counter {
                font-size: 16px;
                margin-bottom: 12px;
            }

            .login-message {
                min-height: 48px;
                padding: 8px 12px;
                margin-bottom: 11px;
            }

            .table-numeric {
                width: 290px;
                max-width: 290px;
                border-spacing: 5px;
            }

            .numeric-button {
                height: 52px;
                font-size: 25px;
            }

            .btn-delete {
                font-size: 25px !important;
            }

            .btn-login svg {
                width: 29px;
                height: 29px;
            }

            .security-footer {
                margin-top: 14px;
                font-size: 13px;
            }
        }

        /* =========================================================
   CELULAR
========================================================= */

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
                max-width: 430px;
                padding: 24px 17px 15px;
                margin: 0 auto;
            }

            .login-logo {
                margin-bottom: 12px;
            }

            .login-logo img {
                max-width: 250px;
                max-height: 65px;
            }

            .login-title {
                font-size: 32px;
                letter-spacing: -0.5px;
            }

            .login-subtitle {
                font-size: 18px;
                gap: 8px;
                margin-top: 14px;
                margin-bottom: 18px;
            }

            .security-icon {
                width: 22px;
                height: 22px;
            }

            .pin-container {
                gap: 7px;
                margin: 8px auto 11px;
            }

            .pin-box {
                width: 52px;
                height: 60px;
                border-radius: 12px;
                font-size: 30px;
            }

            .pin-progress {
                width: 235px;
                height: 5px;
                margin: 13px auto 7px;
            }

            .pin-counter {
                font-size: 17px;
                margin-bottom: 13px;
            }

            .login-message {
                min-height: 49px;
                padding: 9px 12px;
                gap: 9px;
                margin-bottom: 10px;
                border-radius: 11px;
                font-size: 14px;
            }

            .login-message svg {
                width: 23px;
                height: 23px;
            }

            #error_login {
                font-size: 14px;
            }

            /* PINPAD MÁS ANGOSTO */

            .table-numeric {
                width: 280px;
                max-width: 280px;
                border-spacing: 5px;
            }

            .numeric-button {
                height: 53px;
                border-radius: 11px !important;
                font-size: 26px;
            }

            .btn-delete {
                font-size: 25px !important;
            }

            .btn-delete svg {
                width: 27px;
                height: 27px;
            }

            .btn-login svg {
                width: 29px;
                height: 29px;
            }

            .security-footer {
                margin-top: 14px;
                margin-bottom: 3px;
                font-size: 13px;
            }

            .security-footer svg {
                width: 19px;
                height: 19px;
            }

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

        /* =========================================================
   CELULARES PEQUEÑOS
========================================================= */

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
                margin-bottom: 14px;
            }

            .security-icon {
                width: 20px;
                height: 20px;
            }

            .pin-container {
                gap: 6px;
                margin-top: 7px;
                margin-bottom: 8px;
            }

            .pin-box {
                width: 48px;
                height: 55px;
                border-radius: 11px;
                font-size: 27px;
            }

            .pin-progress {
                width: 220px;
                height: 5px;
                margin-top: 11px;
            }

            .pin-counter {
                font-size: 16px;
                margin-bottom: 11px;
            }

            .login-message {
                min-height: 44px;
                padding: 7px 9px;
                font-size: 13px;
                margin-bottom: 8px;
            }

            .login-message svg {
                width: 21px;
                height: 21px;
            }

            #error_login {
                font-size: 13px;
            }

            .table-numeric {
                width: 260px;
                max-width: 260px;
                border-spacing: 4px;
            }

            .numeric-button {
                height: 50px;
                border-radius: 10px !important;
                font-size: 24px;
            }

            .btn-delete svg {
                width: 25px;
                height: 25px;
            }

            .btn-login svg {
                width: 27px;
                height: 27px;
            }

            .security-footer {
                margin-top: 10px;
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

        /* =========================================================
   PANTALLAS CON POCA ALTURA
========================================================= */

        @media (max-height: 800px) and (min-width: 601px) {

            body {
                padding: 12px;
            }

            .login-container {
                max-width: 420px;
                min-height: auto;
            }

            .login-content {
                padding-top: 17px;
                padding-bottom: 13px;
            }

            .login-logo {
                margin-bottom: 7px;
            }

            .login-logo img {
                max-height: 58px;
            }

            .login-title {
                font-size: 33px;
            }

            .login-subtitle {
                margin-top: 8px;
                margin-bottom: 10px;
            }

            .pin-container {
                margin-top: 6px;
                margin-bottom: 7px;
            }

            .pin-box {
                width: 50px;
                height: 53px;
                font-size: 26px;
            }

            .pin-progress {
                width: 225px;
                margin-top: 9px;
                margin-bottom: 5px;
            }

            .pin-counter {
                margin-bottom: 8px;
            }

            .login-message {
                min-height: 44px;
                margin-bottom: 7px;
            }

            .table-numeric {
                width: 280px;
                max-width: 280px;
                border-spacing: 4px;
            }

            .numeric-button {
                height: 49px;
            }

            .security-footer {
                margin-top: 9px;
            }

            .login-footer {
                min-height: 50px;
            }
        }

        /* =========================================================
   CELULAR CON POCA ALTURA
========================================================= */

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
                margin-bottom: 8px;
            }

            .pin-container {
                margin-top: 4px;
                margin-bottom: 6px;
            }

            .pin-box {
                width: 46px;
                height: 52px;
                font-size: 26px;
            }

            .pin-progress {
                width: 210px;
                margin-top: 7px;
                margin-bottom: 4px;
            }

            .pin-counter {
                margin-bottom: 6px;
            }

            .login-message {
                min-height: 38px;
                margin-bottom: 5px;
            }

            .table-numeric {
                width: 250px;
                max-width: 250px;
                border-spacing: 4px;
            }

            .numeric-button {
                height: 47px;
            }

            .security-footer {
                margin-top: 6px;
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
                    inputmode="numeric">


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




                <span>

                    Tu información está protegida

                </span>

            </div>


        </div>


        <!-- =====================================================
         FOOTER
    ====================================================== -->

        <footer class="login-footer">




            <strong>DF PYME</strong>

            <span>

                © <?= date('Y') ?> - Todos los derechos reservados

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

        const inputPin = document.getElementById('code');
        const pinBoxes = document.querySelectorAll('.pin-box');
        const errorLogin = document.getElementById('error_login');
        const pinProgress = document.getElementById('pinProgress');
        const pinCount = document.getElementById('pinCount');
        const form = document.getElementById('form');

        let enviando = false;

        function enfocarPin() {
            if (!enviando) {
                inputPin.focus({
                    preventScroll: true
                });
            }
        }

        function actualizarPinVisual() {

            const valor = inputPin.value
                .replace(/\D/g, '')
                .slice(0, 4);

            inputPin.value = valor;

            pinBoxes.forEach((box, index) => {

                box.classList.remove('active', 'filled');

                if (index < valor.length) {
                    box.textContent = '•';
                    box.classList.add('filled');
                } else {
                    box.textContent = '';
                }
            });

            if (valor.length < 4) {
                pinBoxes[valor.length].classList.add('active');
            }

            pinCount.textContent = valor.length;

            pinProgress.style.width =
                ((valor.length / 4) * 100) + '%';
        }

        function agregarDigito(digito) {

            if (enviando || inputPin.value.length >= 4) {
                return;
            }

            inputPin.value += String(digito);

            actualizarPinVisual();
            borrar_error();
            enfocarPin();
        }

        function borrarDigito() {

            if (enviando) {
                return;
            }

            inputPin.value = inputPin.value.slice(0, -1);

            actualizarPinVisual();
            borrar_error();
            enfocarPin();
        }

        /* =========================================================
            TECLADO FÍSICO
        ========================================================= */

        inputPin.addEventListener('input', function() {

            if (enviando) {
                return;
            }

            inputPin.value = inputPin.value
                .replace(/\D/g, '')
                .slice(0, 4);

            actualizarPinVisual();
            borrar_error();

            // Ya no se envía automáticamente al completar 4 dígitos.
        });

        inputPin.addEventListener('keydown', function(event) {

            if (event.key === 'Enter') {
                event.preventDefault();
                login();
            }
        });

        /* =========================================================
            INICIAR SESIÓN
        ========================================================= */

        function login() {

            if (enviando) {
                return;
            }

            const pin = inputPin.value;

            if (pin === '') {
                errorLogin.textContent = 'No hay definido PIN.';
                actualizarPinVisual();
                enfocarPin();
                return;
            }

            if (pin.length !== 4) {
                errorLogin.textContent = 'El PIN debe tener 4 dígitos.';
                actualizarPinVisual();
                enfocarPin();
                return;
            }

            enviando = true;
            form.submit();
        }

        function borrar_error() {

            <?php if (!session('errors.pin')): ?>

                errorLogin.textContent =
                    'Ingresa tu PIN para continuar';

            <?php else: ?>

                errorLogin.textContent = '';

            <?php endif; ?>
        }

        /* =========================================================
            ENFOQUE DEL INPUT
        ========================================================= */

        document.addEventListener('click', function(event) {

            if (
                event.target.closest('.numeric-button') ||
                event.target.closest('.pin-container') ||
                event.target.closest('.login-content')
            ) {
                setTimeout(enfocarPin, 0);
            }
        });

        actualizarPinVisual();

        window.addEventListener('load', function() {
            setTimeout(enfocarPin, 100);
        });

        /* =========================================================
            MENSAJES
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
    </script>


</body>

</html>