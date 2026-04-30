<!doctype html>
<html lang="es">

<head>
	<meta charset="utf-8">
	<title>Ocurrió un error</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="height:100vh;">

	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card shadow-lg border-0 rounded-4">
					<div class="card-body text-center p-5">

						<h1 class="display-4 text-danger mb-3">⚠️ ¡Ups! Algo salió mal</h1>
						<p class="lead text-secondary mb-4">
							Lo sentimos, ocurrió un error inesperado en la aplicación.
						</p>

						<?php if (ENVIRONMENT === 'development') : ?>
							<div class="alert alert-warning text-start mt-4">
								<h5 class="fw-bold">Detalles técnicos:</h5>
								<pre class="small text-dark bg-light p-3 rounded">
<?= esc($message) ?>

Archivo: <?= esc($exception->getFile()) ?>  
Línea: <?= esc($exception->getLine()) ?>


Trace:
<?= esc($exception->getTraceAsString()) ?>
                </pre>
							</div>
						<?php else: ?>

							<p class="text-muted mt-4">
								Se presentó un inconveniente en la aplicación.
								Por favor comunícate con <strong>soporte técnico</strong> para recibir ayuda.
								Nuestro equipo está disponible para asistirte.
							</p>

						<?php endif; ?>

						<a href="<?= base_url() ?>" class="btn btn-primary btn-lg px-4 rounded-pill shadow-sm mt-4">
							Volver al inicio
						</a>

						<footer class="mt-5 text-muted small">
							<?= date('Y-m-d H:i:s') ?> | <?= ENVIRONMENT ?>
						</footer>

					</div>
				</div>
			</div>
		</div>
	</div>

</body>

</html>