-- ========================================
-- LIMPIEZA GENERAL DE DATOS
-- ========================================

-- Limpieza de productos y categorías
TRUNCATE TABLE producto CASCADE;
TRUNCATE TABLE categoria CASCADE;
TRUNCATE TABLE producto_catego_sub;
TRUNCATE TABLE producto_fabricado;
TRUNCATE TABLE producto_proceso;
DELETE FROM marca WHERE idmarca <> 1;

-- Pedidos y facturación
TRUNCATE TABLE pedido CASCADE;
TRUNCATE TABLE factura_venta CASCADE;
TRUNCATE TABLE factura_proveedor CASCADE;
TRUNCATE TABLE factura_propina;
TRUNCATE TABLE remision_proveedor CASCADE;
TRUNCATE TABLE item_documento_electronico;
TRUNCATE TABLE documento_electronico_payment;
TRUNCATE TABLE documento_electronico;

-- Clientes y terceros
TRUNCATE TABLE cliente CASCADE;
DELETE FROM proveedor WHERE codigointernoproveedor <> 1;
DELETE FROM cuenta_retiro WHERE id != 1;
DELETE FROM rubro_cuenta_retiro WHERE id != 1;

-- Resoluciones y DIAN
TRUNCATE TABLE resolucion_electronica CASCADE;
TRUNCATE TABLE DIAN;

-- Otros registros
TRUNCATE TABLE pagos CASCADE;
TRUNCATE TABLE apertura CASCADE;
TRUNCATE TABLE apertura_registro;
TRUNCATE TABLE mesas CASCADE;
TRUNCATE TABLE salones CASCADE;
TRUNCATE TABLE retiro CASCADE;
TRUNCATE TABLE egreso CASCADE;
TRUNCATE TABLE bono CASCADE;
TRUNCATE TABLE productos_borrados CASCADE;
TRUNCATE TABLE pedidos_borrados CASCADE;
TRUNCATE TABLE credencials_web_service;
TRUNCATE TABLE entradas_salidas CASCADE;
TRUNCATE TABLE entradas_salidas_manuales CASCADE;
TRUNCATE TABLE movimiento_insumos CASCADE;
TRUNCATE TABLE corte;

-- ========================================
-- REINICIO DE SECUENCIAS
-- ========================================

ALTER SEQUENCE pedido_id_seq RESTART WITH 1;
ALTER SEQUENCE producto_id_seq RESTART WITH 1;
ALTER SEQUENCE categoria_id_seq RESTART WITH 1;
ALTER SEQUENCE cliente_id_seq RESTART WITH 1;
ALTER SEQUENCE salones_id_seq RESTART WITH 1;
SELECT setval('consecutivo_informe_id_seq', 1, true);
SELECT setval('impresora_id_seq', 2);
SELECT setval('cuenta_retiro_id_seq', 2);
SELECT setval('rubro_cuenta_retiro_id_seq', 2);
SELECT setval('sub_categoria_id_seq', 2);

-- Reiniciar todas las secuencias automáticamente
DO
$$
DECLARE
    seq_name text;
BEGIN
    FOR seq_name IN SELECT sequence_name FROM information_schema.sequences WHERE sequence_schema = 'public' LOOP
        EXECUTE 'SELECT setval(' || quote_literal(seq_name) || ', 1, false)';
    END LOOP;
END;
$$;

-- ========================================
-- ACTUALIZACIONES DE VALORES BASE
-- ========================================

-- Datos de empresa
UPDATE empresa SET
    nitempresa = 'xxxxx',
    idregimen = 1,
    nombrecomercialempresa = 'xxxxx',
    nombrejuridicoempresa = 'xxxxx',
    telefonoempresa = 'xxxxx',
    celularempresa = 'xxxxx',
    faxempresa = 'xxxxx',
    emailempresa = 'xxxxx',
    pagwebempresa = 'xxxxx',
    representantelegalempresa = 'xxxxx',
    direccionempresa = 'xxxxx',
    descripcion = 'xxxx';

-- Datos de consecutivos
UPDATE consecutivos SET numeroconsecutivo = '1' WHERE idconsecutivos IN (2, 5, 8, 14);
UPDATE consecutivos SET numeroconsecutivo = '2' WHERE idconsecutivos = 5;

-- Usuario
DELETE FROM usuario_sistema WHERE usuariousuario_sistema <> 'admin';

-- ========================================
-- INSERCIONES DE DATOS BASE
-- ========================================

-- Categoría general
INSERT INTO categoria (
    codigocategoria, nombrecategoria, estadocategoria,
    permitir_categoria, impresora
) VALUES ('1', 'GENERAL', TRUE, TRUE, 1);

-- Cliente por defecto (Consumidor Final)
INSERT INTO cliente (
    nitcliente, idregimen, nombrescliente, telefonocliente,
    celularcliente, emailcliente, idciudad, direccioncliente,
    estadocliente, idtipo_cliente, punto, id_clasificacion,
    id, name, last_name, dv, type_person, type_document,
    name_comercial, is_customer
) VALUES (
    '222222222222', 2, 'CONSUMIDOR FINAL', '6064349084',
    '6064349084', 'fe.puntodeventa@cafedecolombia.com', 829, 'CR 9 No 36 -43',
    TRUE, 1, 0, 1,
    870, 'CONSUMIDOR', 'FINAL', 0, 2, 13,
    'CONSUMIDOR FINAL', TRUE
);

-- Salón y mesa por defecto
INSERT INTO salones (nombre) VALUES ('PUNTO DE VENTA');
INSERT INTO mesas (fk_salon, nombre, estado, valor_pedido, fk_usuario)
VALUES (1, 'VENTA 1', 0, 0, 6),
       (NULL, 'VENTAS DE MOSTRADOR', 1, 0, 8);

-- DIAN
INSERT INTO dian (
    numeroresoluciondian, fechadian, rangoinicialdian, rangofinaldian,
    inicialestatica, finalestatica, texto_inicial, texto_final,
    id_modalidad, vigencia, id_caja, vigencia_mes, alerta_facturacion
) VALUES (
    '1', '2024-04-26', 1, 10000,
    '0', '0', 'Resolución DIAN No.', 'Autorización',
    1, '2025-04-26', 1, 6, 200
);

-- Detalles tributarios del cliente
INSERT INTO details_tributary_client (nit_cliente, codigo, nombre, descripcion)
VALUES ('222222222222', 'ZZ', 'No aplica', 'Otros tributos, tasas, contribuciones, y similares');

-- Detalles RUT
INSERT INTO details_rut_client (nit_cliente, codigo, descripcion)
VALUES ('222222222222', 'R-99-PN', 'ORDINARIO');

-- Corrección de datos cliente
UPDATE cliente
SET emailcliente = 'correo@corre.com',
    direccioncliente = 'xxx'
WHERE nitcliente = '222222222222';
truncate producto_atributos;
truncate item_impuesto;
truncate consecutivo_informe;
truncate consecutivo_informe_general;
-- ========================================
-- NOTAS PENDIENTES / TAREAS
-- ========================================

-- Verificar que en la tabla "marca" hay un id = 226 (¿dejar o eliminar?)
-- En la tabla "consecutivos", remisión, devolución venta, egreso e ingreso deben estar en 1
-- Limpiar campos de credenciales web manualmente si es necesario

