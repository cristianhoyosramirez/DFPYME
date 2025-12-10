<style>
    /* ====== TARJETAS ====== */
    .json-card {
        background: #ffffff;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        border: 1px solid #e3e3e3;
    }

    .json-card h3 {
        margin: 0 0 10px 0;
        font-size: 20px;
        font-weight: 600;
        color: #333;
    }

    /* ====== TEXTAREA ESTILO CODE EDITOR ====== */
    .json-textarea {
        width: 100%;
        height: 300px;
        padding: 14px 14px 14px 46px;
        /* Espacio para numeración izquierda */
        border-radius: 8px;
        border: 1px solid #d1d1d1;
        background: #fafafa;
        color: #222;
        font-family: "Consolas", "Courier New", monospace;
        font-size: 14px;
        line-height: 1.45;
        resize: vertical;
        position: relative;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.04);
        transition: border 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;

        background-image:
            linear-gradient(to right, #ececec 40px, transparent 41px),
            repeating-linear-gradient(to bottom,
                transparent 0,
                transparent 23px,
                #e6e6e6 24px);
        background-size: 100% 24px;
    }

    .json-textarea:hover {
        border-color: #b5b5b5;
    }

    .json-textarea:focus {
        background: #ffffff;
        border-color: #4e8df5;
        box-shadow: 0 0 0 3px rgba(78, 141, 245, 0.22);
        outline: none;
    }

    /* ====== SCROLLBAR MODERNA ====== */
    .json-textarea::-webkit-scrollbar {
        width: 8px;
    }

    .json-textarea::-webkit-scrollbar-track {
        background: #efefef;
        border-radius: 10px;
    }

    .json-textarea::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }

    .json-textarea::-webkit-scrollbar-thumb:hover {
        background: #a5a5a5;
    }

    /* ====== BOTÓN ====== */
    .btn-guardar {
        background: #4e8df5;
        color: #fff;
        border: none;
        padding: 10px 18px;
        font-size: 15px;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.2s, transform 0.1s;
    }

    .btn-guardar:hover {
        background: #3a70cc;
    }

    .btn-guardar:active {
        transform: scale(0.97);
    }

    .msg-estado {
        margin-top: 10px;
        font-weight: bold;
        font-size: 14px;
    }

    .msg-success {
        color: #28a745;
    }

    .msg-error {
        color: #d93025;
    }

    .btn-guardar {
        display: inline-block;
        margin-left: auto;
    }

    form {
        display: flex;
        flex-direction: column;
    }
</style>

<?php foreach ($archivos as $a): ?>

    <div class="json-card">

        <h3>📄 <?= htmlspecialchars($a['nombre']) ?></h3>

        <form onsubmit="guardarArchivo(event, '<?= $a['ruta'] ?>', '<?= $a['id_html'] ?>', 'msg_<?= $a['id_html'] ?>')">

            <textarea id="<?= $a['id_html'] ?>" class="json-textarea"><?= $a['contenido'] ?></textarea>

            <br><br>

            <button type="submit" class="btn-guardar">💾 Guardar</button>

            <p id="msg_<?= $a['id_html'] ?>" class="msg-estado"></p>

        </form>

    </div>

<?php endforeach; ?>