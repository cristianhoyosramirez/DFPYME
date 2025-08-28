<?php

namespace App\Models;

use CodeIgniter\Model;

class ActualizacionesModel extends Model
{
    public function add_colum_url()
    {
        $datos = $this->db->query("
            DO $$
            BEGIN
            IF NOT EXISTS (
            SELECT 1 
            FROM information_schema.columns 
            WHERE table_name = 'configuracion_pedido' 
            AND column_name = 'url'
        ) THEN
        ALTER TABLE configuracion_pedido ADD COLUMN url VARCHAR(50);
        END IF;
        END $$;

         ");
        return $datos->getResultArray();
    }

    function add_colum_altura()
    {

        $datos = $this->db->query("
           DO $$
            BEGIN
            IF NOT EXISTS (
            SELECT 1 
            FROM information_schema.columns 
            WHERE table_name = 'configuracion_pedido' 
            AND column_name = 'altura'
            ) THEN
        ALTER TABLE configuracion_pedido ADD COLUMN altura INT;
    END IF;
END $$;


         ");
        return $datos->getResultArray();
    }
    function add_colum_codigo()
    {

        $datos = $this->db->query("
       DO $$
        BEGIN
        IF NOT EXISTS (
        SELECT 1 
        FROM information_schema.columns 
        WHERE table_name = 'configuracion_pedido' 
        AND column_name = 'codigo_pantalla'
        ) THEN
        ALTER TABLE configuracion_pedido ADD COLUMN codigo_pantalla boolean;
        ALTER TABLE configuracion_pedido ALTER COLUMN codigo_pantalla SET DEFAULT false;
        COMMENT ON COLUMN configuracion_pedido.codigo_pantalla IS 'Este campo me permite ver en pantalla al momento de llamar los productos si concateno el codigo con el nombre del producto';

    END IF;
    END $$;



         ");
        return $datos->getResultArray();
    }
    function add_colum_nombre_comercial()
    {

        $datos = $this->db->query("
            DO $$
            BEGIN
                IF NOT EXISTS (
                    SELECT 1
                    FROM information_schema.columns 
                    WHERE table_name = 'medio_pago' 
                    AND column_name = 'nombre_comercial'
                ) THEN
                    ALTER TABLE medio_pago ADD COLUMN nombre_comercial character varying(100);
                END IF;
            END $$;
         ");
        return $datos->getResultArray();
    }
    function update_nombre_comercial()
    {

        $datos = $this->db->query("
        UPDATE medio_pago SET nombre_comercial = 'INSTRUMENTO NO DEFINIDO' WHERE codigo = '1';
        UPDATE medio_pago SET nombre_comercial = 'EFECTIVO' WHERE codigo = '10';
        UPDATE medio_pago SET nombre_comercial = 'REVERSIÓN CRÉDITO AHORRO' WHERE codigo = '11';
        UPDATE medio_pago SET nombre_comercial = 'REVERSIÓN DÉBITO AHORRO' WHERE codigo = '12';
        UPDATE medio_pago SET nombre_comercial = 'CRÉDITO AHORRO' WHERE codigo = '13';
        UPDATE medio_pago SET nombre_comercial = 'DÉBITO AHORRO' WHERE codigo = '14';
        UPDATE medio_pago SET nombre_comercial = 'BOOKENTRY CRÉDITO' WHERE codigo = '15';
        UPDATE medio_pago SET nombre_comercial = 'BOOKENTRY DÉBITO' WHERE codigo = '16';
        UPDATE medio_pago SET nombre_comercial = 'CRÉDITO PAGO NEGOCIO CORPORATIVO (CTP)' WHERE codigo = '19';
        UPDATE medio_pago SET nombre_comercial = 'CRÉDITO ACH' WHERE codigo = '2';
        UPDATE medio_pago SET nombre_comercial = 'CHEQUE' WHERE codigo = '20';
        UPDATE medio_pago SET nombre_comercial = 'PROYECTO BANCARIO' WHERE codigo = '21';
        UPDATE medio_pago SET nombre_comercial = 'NOTA CAMBIARIA ESPERANDO ACEPTACIÓN' WHERE codigo = '24';
        UPDATE medio_pago SET nombre_comercial = 'CHEQUE CERTIFICADO' WHERE codigo = '25';
        UPDATE medio_pago SET nombre_comercial = 'CHEQUE LOCAL' WHERE codigo = '26';
        UPDATE medio_pago SET nombre_comercial = 'DÉBITO ACH' WHERE codigo = '3';
        UPDATE medio_pago SET nombre_comercial = 'TRANSFERENCIA DÉBITO' WHERE codigo = '31';
        UPDATE medio_pago SET nombre_comercial = 'PAGO Y DEPÓSITO PRE ACORDADO (PPD)' WHERE codigo = '34';
        UPDATE medio_pago SET nombre_comercial = 'PAGO NEGOCIO CORPORATIVO AHORROS CRÉDITO (CTP)' WHERE codigo = '37';
        UPDATE medio_pago SET nombre_comercial = 'REVERSIÓN DÉBITO DE DEMANDA ACH' WHERE codigo = '4';
        UPDATE medio_pago SET nombre_comercial = 'CONSIGNACIÓN BANCARIA' WHERE codigo = '42';
        UPDATE medio_pago SET nombre_comercial = 'NOTA CAMBIARIA' WHERE codigo = '44';
        UPDATE medio_pago SET nombre_comercial = 'TRANSFERENCIA CRÉDITO BANCARIO' WHERE codigo = '45';
        UPDATE medio_pago SET nombre_comercial = 'TRANSFERENCIA DÉBITO INTERBANCARIO' WHERE codigo = '46';
        UPDATE medio_pago SET nombre_comercial = 'TRANSFERENCIA DÉBITO BANCARIA' WHERE codigo = '47';
        UPDATE medio_pago SET nombre_comercial = 'TARJETA DE CRÉDITO' WHERE codigo = '48';
        UPDATE medio_pago SET nombre_comercial = 'TARJETA DÉBITO' WHERE codigo = '49';
        UPDATE medio_pago SET nombre_comercial = 'REVERSIÓN CRÉDITO DE DEMANDA ACH' WHERE codigo = '5';
        UPDATE medio_pago SET nombre_comercial = 'POSTGIRO' WHERE codigo = '50';
        UPDATE medio_pago SET nombre_comercial = 'PAGO COMERCIAL URGENTE' WHERE codigo = '52';
        UPDATE medio_pago SET nombre_comercial = 'PAGO TESORERÍA URGENTE' WHERE codigo = '53';
        UPDATE medio_pago SET nombre_comercial = 'CRÉDITO DE DEMANDA ACH' WHERE codigo = '6';
        UPDATE medio_pago SET nombre_comercial = 'NOTA PROMISORIA' WHERE codigo = '60';
        UPDATE medio_pago SET nombre_comercial = 'NOTA PROMISORIA FIRMADA POR EL ACREEDOR' WHERE codigo = '61';
        UPDATE medio_pago SET nombre_comercial = 'NOTA PROMISORIA FIRMADA POR EL ACREEDOR, AVALADA POR EL BANCO' WHERE codigo = '62';
        UPDATE medio_pago SET nombre_comercial = 'NOTA PROMISORIA FIRMADA POR EL ACREEDOR, AVALADA POR UN TERCERO' WHERE codigo = '63';
        UPDATE medio_pago SET nombre_comercial = 'NOTA PROMISORIA FIRMADA POR EL BANCO' WHERE codigo = '64';
        UPDATE medio_pago SET nombre_comercial = 'NOTA PROMISORIA FIRMADA POR UN BANCO AVALADA POR OTRO BANCO' WHERE codigo = '65';
        UPDATE medio_pago SET nombre_comercial = 'NOTA PROMISORIA FIRMADA' WHERE codigo = '66';
        UPDATE medio_pago SET nombre_comercial = 'NOTA PROMISORIA FIRMADA POR UN TERCERO AVALADA POR UN BANCO' WHERE codigo = '67';
        UPDATE medio_pago SET nombre_comercial = 'DÉBITO DE DEMANDA ACH' WHERE codigo = '7';
        UPDATE medio_pago SET nombre_comercial = 'BONOS' WHERE codigo = '71';
        UPDATE medio_pago SET nombre_comercial = 'VALES' WHERE codigo = '72';
        UPDATE medio_pago SET nombre_comercial = 'RETIRO DE UNA NOTA POR EL ACREEDOR SOBRE UN TERCERO' WHERE codigo = '77';
        UPDATE medio_pago SET nombre_comercial = 'RETIRO DE UNA NOTA POR EL ACREEDOR SOBRE UN TERCERO AVALADA POR UN BANCO' WHERE codigo = '78';
        UPDATE medio_pago SET nombre_comercial = 'CLEARING NACIONAL O REGIONAL' WHERE codigo = '9';
        UPDATE medio_pago SET nombre_comercial = 'GIRO REFERENCIADO' WHERE codigo = '93';
        UPDATE medio_pago SET nombre_comercial = 'GIRO URGENTE' WHERE codigo = '94';
        UPDATE medio_pago SET nombre_comercial = 'GIRO FORMATO ABIERTO' WHERE codigo = '95';
        UPDATE medio_pago SET nombre_comercial = 'MÉTODO DE PAGO SOLICITADO NO USADO' WHERE codigo = '96';
        UPDATE medio_pago SET nombre_comercial = 'CLEARING ENTRE PARTNERS' WHERE codigo = '97';
         ");
    }

    function add_column_ruta()
    {

        $datos = $this->db->query("
            DO $$
            BEGIN
                IF NOT EXISTS (
                    SELECT 1
                    FROM information_schema.columns 
                    WHERE table_name = 'forma_pago' 
                    AND column_name = 'ruta'
                ) THEN
                    ALTER TABLE forma_pago ADD COLUMN ruta character varying(100);
                END IF;
            END $$;
         ");
        return $datos->getResultArray();
    }

    public function once()
    {
        // Verifica si la columna ya existe (funciona en MySQL y MariaDB)
        $query = $this->db->query("
        SELECT * 
        FROM information_schema.columns 
        WHERE table_name = 'productos_borrados' 
        AND column_name = 'valor_unitario'
    ");

        if (empty($query->getResult())) {
            // Si no existe, la crea
            $this->db->query("ALTER TABLE productos_borrados ADD COLUMN valor_unitario INTEGER;");
            return ['mensaje' => 'Columna agregada exitosamente'];
        } else {
            return ['mensaje' => 'La columna ya existe'];
        }
    }


    function unregister_tick_function()
    {

        $datos = $this->db->query("
         -- ======================================
-- CREACIÓN DE TABLAS SOLO SI NO EXISTEN
-- ======================================

-- Table: estado_licencia
CREATE TABLE IF NOT EXISTS estado_licencia
(
  id_cliente uuid NOT NULL,
  estado_licencia character varying(50) NOT NULL,
  mensaje_licencia text,
  id_instalacion uuid,
  CONSTRAINT pk_estado_licencia PRIMARY KEY (id_cliente)
);

-- Insert solo si no existe
INSERT INTO estado_licencia (id_cliente, estado_licencia, mensaje_licencia, id_instalacion)
SELECT '960caf31-47f9-4b87-aa03-37fd5c03bf74', 'Activa', 'Su servicio esta temporalmente inactivo.', '337542e0-e18a-4e53-9cb2-19b976b146f1'
WHERE NOT EXISTS (
    SELECT 1 FROM estado_licencia WHERE id_cliente = '960caf31-47f9-4b87-aa03-37fd5c03bf74'
);

-- Table: clase_pago
CREATE TABLE IF NOT EXISTS clase_pago
(
  id serial NOT NULL,
  nombre character varying(50),
  CONSTRAINT pk_clase_pago PRIMARY KEY (id)
);
COMMENT ON TABLE clase_pago IS 'Esta almacena los valores para los pagos que son diferentes al efectivo y se mostranran el ventana de finalizar venta ';

-- Table: estado_pago_consumo
CREATE TABLE IF NOT EXISTS estado_pago_consumo
(
  id_cliente uuid NOT NULL,
  estado_consumo character varying(50) NOT NULL,
  mensaje_consumo text,
  id_instalacion uuid,
  CONSTRAINT pk_estado_pago_consumo PRIMARY KEY (id_cliente)
);

INSERT INTO estado_pago_consumo (id_cliente, estado_consumo, mensaje_consumo, id_instalacion)
SELECT '960caf31-47f9-4b87-aa03-37fd5c03bf74', 'Al día', 'Es en mora de factura No 369, favor reportar el pago al 3698852', '337542e0-e18a-4e53-9cb2-19b976b146f1'
WHERE NOT EXISTS (
    SELECT 1 FROM estado_pago_consumo WHERE id_cliente = '960caf31-47f9-4b87-aa03-37fd5c03bf74'
);

-- Table: grupo_impresion
CREATE TABLE IF NOT EXISTS grupo_impresion
(
  id serial NOT NULL,
  nombre character varying(50), -- Nombre del grupo de impresion
  id_impresora_asignada integer, -- Establece la relación con la tabla impresoras mediante su ID
  numero_copias integer DEFAULT 1,
  CONSTRAINT pk_grupo_impresion PRIMARY KEY (id),
  CONSTRAINT fk_impresora FOREIGN KEY (id_impresora_asignada)
      REFERENCES impresora (id)
);
COMMENT ON TABLE grupo_impresion IS 'Tabla utilizada para agrupar productos en comandas cuando la configuración de impresión es por grupo.';
COMMENT ON COLUMN grupo_impresion.nombre IS 'Nombre del grupo de impresion ';
COMMENT ON COLUMN grupo_impresion.id_impresora_asignada IS 'Establece la relación con la tabla impresoras mediante su ID, indicando la impresora asignada a cada grupo de impresión de comandas.';

-- ======================================
-- AGREGAR COLUMNAS SOLO SI NO EXISTEN
-- ======================================
DO $$
BEGIN
    -- configuracion_pedido
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='configuracion_pedido' AND column_name='reimpresion_comanda') THEN
        ALTER TABLE configuracion_pedido ADD COLUMN reimpresion_comanda boolean DEFAULT false;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='configuracion_pedido' AND column_name='mostrar_boton_imprimir_bono') THEN
        ALTER TABLE configuracion_pedido ADD COLUMN mostrar_boton_imprimir_bono boolean DEFAULT false;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='configuracion_pedido' AND column_name='terminos_condiones') THEN
        ALTER TABLE configuracion_pedido ADD COLUMN terminos_condiones text;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='configuracion_pedido' AND column_name='mostrar_boton_mitad') THEN
        ALTER TABLE configuracion_pedido ADD COLUMN mostrar_boton_mitad boolean;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='configuracion_pedido' AND column_name='recetasmodal') THEN
        ALTER TABLE configuracion_pedido ADD COLUMN recetasmodal boolean DEFAULT false;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='configuracion_pedido' AND column_name='espacios_comanda_encabezado') THEN
        ALTER TABLE configuracion_pedido ADD COLUMN espacios_comanda_encabezado integer DEFAULT 1;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='configuracion_pedido' AND column_name='espacios_comanda_pie') THEN
        ALTER TABLE configuracion_pedido ADD COLUMN espacios_comanda_pie integer DEFAULT 1;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='configuracion_pedido' AND column_name='tamano_comanda') THEN
        ALTER TABLE configuracion_pedido ADD COLUMN tamano_comanda character varying(50) DEFAULT 'grande';
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='configuracion_pedido' AND column_name='numero_copias_comanda') THEN
        ALTER TABLE configuracion_pedido ADD COLUMN numero_copias_comanda integer DEFAULT 1;
    END IF;

    -- pagos
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='pagos' AND column_name='id_clase_pago') THEN
        ALTER TABLE pagos ADD COLUMN id_clase_pago integer;
    END IF;

    -- producto
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='producto' AND column_name='kit') THEN
        ALTER TABLE producto ADD COLUMN kit boolean DEFAULT false;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='producto' AND column_name='id_impresora') THEN
        ALTER TABLE producto ADD COLUMN id_impresora integer DEFAULT 1;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='producto' AND column_name='grupo_impresion_comanda') THEN
        ALTER TABLE producto ADD COLUMN grupo_impresion_comanda integer;
    END IF;

    -- producto_pedido
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='producto_pedido' AND column_name='id_grupo') THEN
        ALTER TABLE producto_pedido ADD COLUMN id_grupo integer;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='producto_pedido' AND column_name='porcentaje_descuento') THEN
        ALTER TABLE producto_pedido ADD COLUMN porcentaje_descuento integer;
    END IF;

    -- concepto_kardex
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='concepto_kardex' AND column_name='estado') THEN
        ALTER TABLE concepto_kardex ADD COLUMN estado boolean DEFAULT true;
    END IF;

    -- tipo_inventario
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='tipo_inventario' AND column_name='abreviacion') THEN
        ALTER TABLE tipo_inventario ADD COLUMN abreviacion character varying(50);
        COMMENT ON COLUMN tipo_inventario.abreviacion IS 'Es la abreviacion del tipo de inventario para ser pintado en la consulta de producto inventario ';
    END IF;
END $$;

-- ======================================
-- UPDATES INICIALES
-- ======================================

UPDATE configuracion_pedido SET recetasmodal = false;
UPDATE concepto_kardex SET estado = true;
DO $$
BEGIN
    -- version en configuracion_pedido
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.columns 
        WHERE table_name='configuracion_pedido' 
        AND column_name='version'
    ) THEN
        ALTER TABLE configuracion_pedido ADD COLUMN version text;
    END IF;
END $$;

UPDATE configuracion_pedido SET version = 11;
         ");
        return $datos->getResultArray();
    }



    public function uno()
    {
        $sql = <<<EOT
-- ======================================
-- CREACIÓN DE TABLAS SOLO SI NO EXISTEN
-- ======================================

-- Table: estado_licencia
CREATE TABLE IF NOT EXISTS estado_licencia
(
  id_cliente uuid NOT NULL,
  estado_licencia character varying(50) NOT NULL,
  mensaje_licencia text,
  id_instalacion uuid,
  CONSTRAINT pk_estado_licencia PRIMARY KEY (id_cliente)
);

-- Insert solo si no existe
INSERT INTO estado_licencia (id_cliente, estado_licencia, mensaje_licencia, id_instalacion)
SELECT '960caf31-47f9-4b87-aa03-37fd5c03bf74', 'Activa', 'Su servicio esta temporalmente inactivo.', '337542e0-e18a-4e53-9cb2-19b976b146f1'
WHERE NOT EXISTS (
    SELECT 1 FROM estado_licencia WHERE id_cliente = '960caf31-47f9-4b87-aa03-37fd5c03bf74'
);

-- Table: clase_pago
CREATE TABLE IF NOT EXISTS clase_pago
(
  id serial NOT NULL,
  nombre character varying(50),
  CONSTRAINT pk_clase_pago PRIMARY KEY (id)
);
COMMENT ON TABLE clase_pago IS 'Esta almacena los valores para los pagos que son diferentes al efectivo y se mostranran el ventana de finalizar venta ';

-- Table: estado_pago_consumo
CREATE TABLE IF NOT EXISTS estado_pago_consumo
(
  id_cliente uuid NOT NULL,
  estado_consumo character varying(50) NOT NULL,
  mensaje_consumo text,
  id_instalacion uuid,
  CONSTRAINT pk_estado_pago_consumo PRIMARY KEY (id_cliente)
);

INSERT INTO estado_pago_consumo (id_cliente, estado_consumo, mensaje_consumo, id_instalacion)
SELECT '960caf31-47f9-4b87-aa03-37fd5c03bf74', 'Al día', 'Es en mora de factura No 369, favor reportar el pago al 3698852', '337542e0-e18a-4e53-9cb2-19b976b146f1'
WHERE NOT EXISTS (
    SELECT 1 FROM estado_pago_consumo WHERE id_cliente = '960caf31-47f9-4b87-aa03-37fd5c03bf74'
);

-- Table: grupo_impresion
CREATE TABLE IF NOT EXISTS grupo_impresion
(
  id serial NOT NULL,
  nombre character varying(50),
  id_impresora_asignada integer,
  numero_copias integer DEFAULT 1,
  CONSTRAINT pk_grupo_impresion PRIMARY KEY (id),
  CONSTRAINT fk_impresora FOREIGN KEY (id_impresora_asignada)
      REFERENCES impresora (id)
);
COMMENT ON TABLE grupo_impresion IS 'Tabla utilizada para agrupar productos en comandas cuando la configuración de impresión es por grupo.';
COMMENT ON COLUMN grupo_impresion.nombre IS 'Nombre del grupo de impresion ';
COMMENT ON COLUMN grupo_impresion.id_impresora_asignada IS 'Establece la relación con la tabla impresoras mediante su ID, indicando la impresora asignada a cada grupo de impresión de comandas.';

-- ======================================
-- AGREGAR COLUMNAS SOLO SI NO EXISTEN
-- ======================================
DO $$
BEGIN
    -- configuracion_pedido
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='configuracion_pedido' AND column_name='reimpresion_comanda') THEN
        ALTER TABLE configuracion_pedido ADD COLUMN reimpresion_comanda boolean DEFAULT false;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='configuracion_pedido' AND column_name='mostrar_boton_imprimir_bono') THEN
        ALTER TABLE configuracion_pedido ADD COLUMN mostrar_boton_imprimir_bono boolean DEFAULT false;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='configuracion_pedido' AND column_name='terminos_condiones') THEN
        ALTER TABLE configuracion_pedido ADD COLUMN terminos_condiones text;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='configuracion_pedido' AND column_name='mostrar_boton_mitad') THEN
        ALTER TABLE configuracion_pedido ADD COLUMN mostrar_boton_mitad boolean;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='configuracion_pedido' AND column_name='recetasmodal') THEN
        ALTER TABLE configuracion_pedido ADD COLUMN recetasmodal boolean DEFAULT false;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='configuracion_pedido' AND column_name='espacios_comanda_encabezado') THEN
        ALTER TABLE configuracion_pedido ADD COLUMN espacios_comanda_encabezado integer DEFAULT 1;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='configuracion_pedido' AND column_name='espacios_comanda_pie') THEN
        ALTER TABLE configuracion_pedido ADD COLUMN espacios_comanda_pie integer DEFAULT 1;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='configuracion_pedido' AND column_name='tamano_comanda') THEN
        ALTER TABLE configuracion_pedido ADD COLUMN tamano_comanda character varying(50) DEFAULT 'grande';
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='configuracion_pedido' AND column_name='numero_copias_comanda') THEN
        ALTER TABLE configuracion_pedido ADD COLUMN numero_copias_comanda integer DEFAULT 1;
    END IF;

    -- pagos
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='pagos' AND column_name='id_clase_pago') THEN
        ALTER TABLE pagos ADD COLUMN id_clase_pago integer;
    END IF;

    -- producto
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='producto' AND column_name='kit') THEN
        ALTER TABLE producto ADD COLUMN kit boolean DEFAULT false;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='producto' AND column_name='id_impresora') THEN
        ALTER TABLE producto ADD COLUMN id_impresora integer DEFAULT 1;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='producto' AND column_name='grupo_impresion_comanda') THEN
        ALTER TABLE producto ADD COLUMN grupo_impresion_comanda integer;
    END IF;

    -- producto_pedido
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='producto_pedido' AND column_name='id_grupo') THEN
        ALTER TABLE producto_pedido ADD COLUMN id_grupo integer;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='producto_pedido' AND column_name='porcentaje_descuento') THEN
        ALTER TABLE producto_pedido ADD COLUMN porcentaje_descuento integer;
    END IF;

    -- concepto_kardex
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='concepto_kardex' AND column_name='estado') THEN
        ALTER TABLE concepto_kardex ADD COLUMN estado boolean DEFAULT true;
    END IF;

    -- tipo_inventario
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='tipo_inventario' AND column_name='abreviacion') THEN
        ALTER TABLE tipo_inventario ADD COLUMN abreviacion character varying(50);
        COMMENT ON COLUMN tipo_inventario.abreviacion IS 'Es la abreviacion del tipo de inventario para ser pintado en la consulta de producto inventario ';
    END IF;
END $$;

-- ======================================
-- UPDATES INICIALES
-- ======================================
UPDATE configuracion_pedido SET recetasmodal = false;
UPDATE concepto_kardex SET estado = true;

DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.columns 
        WHERE table_name='configuracion_pedido' 
        AND column_name='version'
    ) THEN
        ALTER TABLE configuracion_pedido ADD COLUMN version text;
    END IF;
END $$;

DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.columns 
        WHERE table_name='configuracion_pedido' 
        AND column_name='mostrar_boton_mitad'
    ) THEN
        ALTER TABLE configuracion_pedido ADD COLUMN mostrar_boton_mitad boolean;
    END IF;
END $$;


DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_name = 'producto'
          AND column_name = 'precio_3'
    ) THEN
        ALTER TABLE producto ADD COLUMN precio_3 integer DEFAULT 0;
    END IF;
END $$;


DO $$
BEGIN
    IF EXISTS (SELECT 1 FROM public.clase_pago WHERE id = 1) THEN
        -- Si existe, actualiza
        UPDATE public.clase_pago 
        SET nombre = 'Banco'
        WHERE id = 1;
    ELSE
        -- Si no existe, inserta
        INSERT INTO public.clase_pago (id, nombre) 
        VALUES (1, 'Banco');
    END IF;
END $$;


DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 
        FROM information_schema.columns 
        WHERE table_name = 'grupo_impresion' 
        AND column_name = 'numero_copias'
    ) THEN
        ALTER TABLE grupo_impresion 
        ADD COLUMN numero_copias integer DEFAULT 1;
    END IF;
END $$;



UPDATE configuracion_pedido SET mostrar_boton_mitad = 'true';
UPDATE configuracion_pedido SET version = 12;

DO $$
BEGIN
    -- ========================================
    -- Columna abreviacion
    -- ========================================
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_name = 'tipo_inventario'
          AND column_name = 'abreviacion'
    ) THEN
        ALTER TABLE tipo_inventario ADD COLUMN abreviacion character varying(50);
        COMMENT ON COLUMN tipo_inventario.abreviacion IS
            'Es la abreviación del tipo de inventario para ser pintado en la consulta de producto inventario';
    END IF;

    -- ========================================
    -- Columna estado
    -- ========================================
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_name = 'tipo_inventario'
          AND column_name = 'estado'
    ) THEN
        ALTER TABLE tipo_inventario ADD COLUMN estado boolean DEFAULT true;
    END IF;
END $$;


-- ========================================
-- ACTUALIZAR O INSERTAR tipo_inventario
-- ========================================

DO $$
BEGIN
    -- id = 1
    IF EXISTS (SELECT 1 FROM public.tipo_inventario WHERE id = 1) THEN
        UPDATE public.tipo_inventario 
        SET abreviacion = 'ProInventario'
        WHERE id = 1;
    ELSE
        INSERT INTO public.tipo_inventario (id, nombre, descripcion, estado, abreviacion)
        VALUES (1, 'Productos no fabricados por la empresa para la venta', 'Producto compra y venta eje: coca cola', true, 'ProInventario');
    END IF;

    -- id = 2
    IF EXISTS (SELECT 1 FROM public.tipo_inventario WHERE id = 2) THEN
        UPDATE public.tipo_inventario 
        SET abreviacion = 'PC'
        WHERE id = 2;
    ELSE
        INSERT INTO public.tipo_inventario (id, nombre, descripcion, estado, abreviacion)
        VALUES (2, 'Producto materia prima (Solo Compra)', 'Producto solo compra eje: Leche Colanta', false, 'PC');
    END IF;

    -- id = 3
    IF EXISTS (SELECT 1 FROM public.tipo_inventario WHERE id = 3) THEN
        UPDATE public.tipo_inventario 
        SET abreviacion = 'Receta'
        WHERE id = 3;
    ELSE
        INSERT INTO public.tipo_inventario (id, nombre, descripcion, estado, abreviacion)
        VALUES (3, 'Producto terminado para la venta (Solo Venta)', 'Producto solo venta eje: Hamburguesa', true, 'Receta');
    END IF;

    -- id = 4
    IF EXISTS (SELECT 1 FROM public.tipo_inventario WHERE id = 4) THEN
        UPDATE public.tipo_inventario 
        SET abreviacion = 'Insumos'
        WHERE id = 4;
    ELSE
        INSERT INTO public.tipo_inventario (id, nombre, descripcion, estado, abreviacion)
        VALUES (4, 'Producto materia prima en proceso (Insumo )', 'Producto Insumo eje: Leche', true, 'Insumos');
    END IF;

    -- id = 5
    IF EXISTS (SELECT 1 FROM public.tipo_inventario WHERE id = 5) THEN
        UPDATE public.tipo_inventario 
        SET abreviacion = 'GG'
        WHERE id = 5;
    ELSE
        INSERT INTO public.tipo_inventario (id, nombre, descripcion, estado, abreviacion)
        VALUES (5, 'Gasto generales ', 'Pitillos', false, 'GG');
    END IF;

    -- id = 6
    IF EXISTS (SELECT 1 FROM public.tipo_inventario WHERE id = 6) THEN
        UPDATE public.tipo_inventario 
        SET abreviacion = 'GS'
        WHERE id = 6;
    ELSE
        INSERT INTO public.tipo_inventario (id, nombre, descripcion, estado, abreviacion)
        VALUES (6, 'Gastos servicios', 'Luz, agua', false, 'GS');
    END IF;

    -- id = 7
    IF EXISTS (SELECT 1 FROM public.tipo_inventario WHERE id = 7) THEN
        UPDATE public.tipo_inventario 
        SET abreviacion = 'SubRecetas'
        WHERE id = 7;
    ELSE
        INSERT INTO public.tipo_inventario (id, nombre, descripcion, estado, abreviacion)
        VALUES (7, 'Sub receta', 'Sub receta', false, 'SubRecetas');
    END IF;
END $$;


-- ========================================
-- TABLAS
-- ========================================

-- Tabla: producto_atributos
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.tables
        WHERE table_name = 'producto_atributos'
    ) THEN
        CREATE TABLE producto_atributos
        (
            id serial NOT NULL,
            id_producto integer,
            id_atributo integer,
            numero_componentes integer,
            CONSTRAINT producto_atributos_pkey PRIMARY KEY (id)
        );
        ALTER TABLE producto_atributos OWNER TO postgres;
    END IF;
END $$;

-- Tabla: atributos
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.tables
        WHERE table_name = 'atributos'
    ) THEN
        CREATE TABLE atributos
        (
            id serial NOT NULL,
            nombre character varying(50),
            CONSTRAINT pk_atributo PRIMARY KEY (id)
        );
        ALTER TABLE atributos OWNER TO postgres;
    END IF;
END $$;

-- Tabla: componentes
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.tables
        WHERE table_name = 'componentes'
    ) THEN
        CREATE TABLE componentes
        (
            id serial NOT NULL,
            nombre character varying(50) NOT NULL,
            id_atributo integer,
            CONSTRAINT "idComponente" PRIMARY KEY (id),
            CONSTRAINT "idAtributo" FOREIGN KEY (id_atributo)
                REFERENCES atributos (id) MATCH SIMPLE
                ON UPDATE NO ACTION ON DELETE NO ACTION
        );
        ALTER TABLE componentes OWNER TO postgres;
    END IF;
END $$;

-- Tabla: atributos_producto
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.tables
        WHERE table_name = 'atributos_producto'
    ) THEN
        CREATE TABLE atributos_producto
        (
            id serial NOT NULL,
            id_componente integer,
            id_tabla_producto integer,
            id_atributo integer,
            id_producto integer,
            CONSTRAINT pk_atributos_producto PRIMARY KEY (id),
            CONSTRAINT fk_componentes FOREIGN KEY (id_componente)
                REFERENCES componentes (id) MATCH SIMPLE
                ON UPDATE NO ACTION ON DELETE NO ACTION
        );
        ALTER TABLE atributos_producto OWNER TO postgres;
    END IF;
END $$;


-- ========================================
-- COLUMNAS
-- ========================================

-- Columna idUsuario en producto_pedido
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.columns
        WHERE table_name='producto_pedido'
          AND column_name='idUsuario'
    ) THEN
        ALTER TABLE producto_pedido ADD COLUMN "idUsuario" integer;
    END IF;
END $$;

-- Columna fecha en producto_pedido
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.columns
        WHERE table_name='producto_pedido'
          AND column_name='fecha'
    ) THEN
        ALTER TABLE producto_pedido ADD COLUMN fecha date;
    END IF;
END $$;

-- Columna hora en producto_pedido
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.columns
        WHERE table_name='producto_pedido'
          AND column_name='hora'
    ) THEN
        ALTER TABLE producto_pedido ADD COLUMN hora time with time zone;
    END IF;
END $$;

-- Columna mostrarmesero en configuracion_pedido
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.columns
        WHERE table_name='configuracion_pedido'
          AND column_name='mostrarmesero'
    ) THEN
        ALTER TABLE configuracion_pedido ADD COLUMN mostrarmesero boolean DEFAULT false;
    END IF;
END $$;

-- ========================================
-- Columna notaPedido en configuracion_pedido
-- ========================================
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.columns
        WHERE table_name = 'configuracion_pedido'
          AND column_name = 'notaPedido'
    ) THEN
        ALTER TABLE configuracion_pedido ADD COLUMN "notaPedido" boolean;
    END IF;
END $$;

-- ========================================
-- Columna id_mesa en pagos
-- ========================================
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.columns
        WHERE table_name = 'pagos'
          AND column_name = 'id_mesa'
    ) THEN
        ALTER TABLE pagos ADD COLUMN id_mesa integer;
    END IF;
END $$;


-- ======================================
-- SCRIPT SEGURO Y COMPATIBLE PARA CONFIGURAR PK EN proveedor
-- ======================================

-- 1. Crear columna si no existe
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_name = 'proveedor'
          AND column_name = 'id'
          AND table_schema = 'public'
    ) THEN
        EXECUTE 'ALTER TABLE public.proveedor ADD COLUMN id integer';
    END IF;
END
$$ LANGUAGE plpgsql;

-- 2. Crear secuencia si no existe
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM pg_class
        WHERE relkind = 'S'
          AND relname = 'proveedor_id_seq'
    ) THEN
        EXECUTE 'CREATE SEQUENCE public.proveedor_id_seq';
    END IF;
END
$$ LANGUAGE plpgsql;

-- 3. Asignar valores a los registros que tengan id nulo
DO $$
BEGIN
    EXECUTE 'UPDATE public.proveedor
             SET id = nextval(''proveedor_id_seq'')
             WHERE id IS NULL';
END
$$ LANGUAGE plpgsql;

-- 4. Ajustar la secuencia al máximo valor existente
DO $$
DECLARE
    max_id bigint;
BEGIN
    SELECT COALESCE(MAX(id), 0) INTO max_id FROM public.proveedor;
    PERFORM setval('public.proveedor_id_seq', GREATEST(max_id, 1), true);
END
$$ LANGUAGE plpgsql;

-- 5. Asignar la secuencia como default de la columna
DO $$
BEGIN
    EXECUTE 'ALTER TABLE public.proveedor
             ALTER COLUMN id SET DEFAULT nextval(''public.proveedor_id_seq''::regclass)';
END
$$ LANGUAGE plpgsql;

-- 6. Asegurar que la columna no permita nulos
DO $$
BEGIN
    EXECUTE 'ALTER TABLE public.proveedor ALTER COLUMN id SET NOT NULL';
END
$$ LANGUAGE plpgsql;

-- 7. Ligar la secuencia a la columna (como lo hace SERIAL)
DO $$
BEGIN
    EXECUTE 'ALTER SEQUENCE public.proveedor_id_seq OWNED BY public.proveedor.id';
END
$$ LANGUAGE plpgsql;

-- 8. Crear clave primaria si no existe
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.table_constraints
        WHERE table_name = 'proveedor'
          AND table_schema = 'public'
          AND constraint_type = 'PRIMARY KEY'
    ) THEN
        EXECUTE 'ALTER TABLE public.proveedor ADD CONSTRAINT proveedor_pkey PRIMARY KEY (id)';
    END IF;
END
$$ LANGUAGE plpgsql;



-- ========================================
-- ACTUALIZAR SI EXISTEN
-- ========================================
UPDATE tipo SET descripciontipo = 'Super Administrador' WHERE idtipo = 0;
UPDATE tipo SET descripciontipo = 'Facturador'          WHERE idtipo = 1;
UPDATE tipo SET descripciontipo = 'Mesero'              WHERE idtipo = 2;
UPDATE tipo SET descripciontipo = 'Toma pedidos'        WHERE idtipo = 3;
UPDATE tipo SET descripciontipo = 'Super Facturador'    WHERE idtipo = 4;
UPDATE tipo SET descripciontipo = 'Administrador'       WHERE idtipo = 5;

-- ========================================
-- INSERTAR SI NO EXISTEN
-- ========================================
INSERT INTO tipo (idtipo, descripciontipo) 
SELECT 0, 'Super Administrador' WHERE NOT EXISTS (SELECT 1 FROM tipo WHERE idtipo = 0);

INSERT INTO tipo (idtipo, descripciontipo) 
SELECT 1, 'Facturador' WHERE NOT EXISTS (SELECT 1 FROM tipo WHERE idtipo = 1);

INSERT INTO tipo (idtipo, descripciontipo) 
SELECT 2, 'Mesero' WHERE NOT EXISTS (SELECT 1 FROM tipo WHERE idtipo = 2);

INSERT INTO tipo (idtipo, descripciontipo) 
SELECT 3, 'Toma pedidos' WHERE NOT EXISTS (SELECT 1 FROM tipo WHERE idtipo = 3);

INSERT INTO tipo (idtipo, descripciontipo) 
SELECT 4, 'Super Facturador' WHERE NOT EXISTS (SELECT 1 FROM tipo WHERE idtipo = 4);

INSERT INTO tipo (idtipo, descripciontipo) 
SELECT 5, 'Administrador' WHERE NOT EXISTS (SELECT 1 FROM tipo WHERE idtipo = 5);

-- ==========================
-- productos_borrados.valor_unitario
-- ==========================
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_name='productos_borrados'
          AND column_name='valor_unitario'
    ) THEN
        ALTER TABLE productos_borrados ADD COLUMN valor_unitario integer;
    END IF;
END$$;

-- ==========================
-- configuracion_pedido.terminos_condiones
-- ==========================
DO $$
BEGIN
    IF EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_name='configuracion_pedido'
          AND column_name='terminos_condiones'
    ) THEN
        ALTER TABLE configuracion_pedido DROP COLUMN terminos_condiones;
    END IF;
END$$;

-- ==========================
-- configuracion_pedido.texto_propina
-- ==========================
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_name='configuracion_pedido'
          AND column_name='texto_propina'
    ) THEN
        ALTER TABLE configuracion_pedido ADD COLUMN texto_propina text;
    END IF;
END$$;

-- ==========================
-- configuracion_pedido.permitir_impresion_texto_propina
-- ==========================
DO $$
BEGIN
    IF EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_name='configuracion_pedido'
          AND column_name='permitir_impresion_texto_propina'
    ) THEN
        ALTER TABLE configuracion_pedido DROP COLUMN permitir_impresion_texto_propina;
    END IF;
END$$;

DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_name='configuracion_pedido'
          AND column_name='permitir_impresion_texto_propina'
    ) THEN
        ALTER TABLE configuracion_pedido ADD COLUMN permitir_impresion_texto_propina boolean DEFAULT true;
    END IF;
END$$;

-- aseguramos que siempre tenga default true
ALTER TABLE configuracion_pedido 
    ALTER COLUMN permitir_impresion_texto_propina SET DEFAULT true;



EOT;

        // Ejecutar todo el bloque de una vez
        $this->db->simpleQuery($sql);

        return true;
    }


function doce(){
    $sql = <<<EOT
-- ==========================
-- productos_borrados.valor_unitario
-- ==========================
DO \$\$
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_name='productos_borrados'
          AND column_name='valor_unitario'
    ) THEN
        ALTER TABLE productos_borrados ADD COLUMN valor_unitario integer;
    END IF;
END
\$\$;

-- ==========================
-- configuracion_pedido.terminos_condiones
-- ==========================
DO \$\$
BEGIN
    IF EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_name='configuracion_pedido'
          AND column_name='terminos_condiones'
    ) THEN
        ALTER TABLE configuracion_pedido DROP COLUMN terminos_condiones;
    END IF;
END
\$\$;

-- ==========================
-- configuracion_pedido.texto_propina
-- ==========================
DO \$\$
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_name='configuracion_pedido'
          AND column_name='texto_propina'
    ) THEN
        ALTER TABLE configuracion_pedido ADD COLUMN texto_propina text;
    END IF;
END
\$\$;

-- ==========================
-- configuracion_pedido.permitir_impresion_texto_propina
-- ==========================
DO \$\$
BEGIN
    IF EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_name='configuracion_pedido'
          AND column_name='permitir_impresion_texto_propina'
    ) THEN
        ALTER TABLE configuracion_pedido DROP COLUMN permitir_impresion_texto_propina;
    END IF;
END
\$\$;

DO \$\$
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_name='configuracion_pedido'
          AND column_name='permitir_impresion_texto_propina'
    ) THEN
        ALTER TABLE configuracion_pedido ADD COLUMN permitir_impresion_texto_propina boolean DEFAULT true;
    END IF;
END
\$\$;

-- aseguramos que siempre tenga default true
ALTER TABLE configuracion_pedido 
    ALTER COLUMN permitir_impresion_texto_propina SET DEFAULT true;
EOT;
     // Ejecutar todo el bloque de una vez
        $this->db->simpleQuery($sql);

        return true;
}


}
