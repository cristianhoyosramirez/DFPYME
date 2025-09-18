-- ======================================
-- CREACIÓN DE TABLAS SOLO SI NO EXISTEN
-- ======================================

-- ===========================
-- Tabla estado_licencia
-- ===========================
DO $$
BEGIN
   -- Crear tabla solo si no existe
   IF NOT EXISTS (
       SELECT 1 FROM information_schema.tables 
       WHERE table_name = 'estado_licencia'
         AND table_schema = 'public'
   ) THEN
      CREATE TABLE estado_licencia
      (
        id_cliente uuid NOT NULL,
        estado_licencia character varying(50) NOT NULL,
        mensaje_licencia text,
        id_instalacion uuid,
        CONSTRAINT pk_estado_licencia PRIMARY KEY (id_cliente)
      );
   END IF;
END$$;

-- ===========================
-- Agregar columna id_instalacion si no existe
-- ===========================
DO $$
BEGIN
   IF NOT EXISTS (
       SELECT 1
       FROM information_schema.columns
       WHERE table_name = 'estado_licencia'
         AND column_name = 'id_instalacion'
         AND table_schema = 'public'
   ) THEN
      ALTER TABLE estado_licencia ADD COLUMN id_instalacion uuid;
   END IF;
END$$;


-- ===========================
-- Insertar solo si la tabla está vacía
-- ===========================
INSERT INTO estado_licencia (id_cliente, estado_licencia, mensaje_licencia, id_instalacion)
SELECT '960caf31-47f9-4b87-aa03-37fd5c03bf74',
       'Activa',
       'Su servicio esta temporalmente inactivo.',
       '337542e0-e18a-4e53-9cb2-19b976b146f1'
WHERE NOT EXISTS (
    SELECT 1 FROM estado_licencia
);


-- ===========================
-- Tabla clase_pago
-- ===========================
DO $$
BEGIN
   -- Crear tabla solo si no existe
   IF NOT EXISTS (
       SELECT 1 FROM information_schema.tables 
       WHERE table_name = 'clase_pago'
         AND table_schema = 'public'
   ) THEN
      CREATE TABLE clase_pago
      (
        id serial NOT NULL,
        nombre character varying(50),
        CONSTRAINT pk_clase_pago PRIMARY KEY (id)
      );
   END IF;
END$$;

-- ===========================
-- Comentario (se aplica solo si la tabla existe)
-- ===========================
DO $$
BEGIN
   IF EXISTS (
       SELECT 1 FROM information_schema.tables
       WHERE table_name = 'clase_pago'
         AND table_schema = 'public'
   ) THEN
      COMMENT ON TABLE clase_pago IS 'Esta almacena los valores para los pagos que son diferentes al efectivo y se mostraran en la ventana de finalizar venta';
   END IF;
END$$;

-- ===========================
-- Tabla estado_pago_consumo
-- ===========================
DO $$
BEGIN
   -- Crear tabla solo si no existe
   IF NOT EXISTS (
       SELECT 1 FROM information_schema.tables 
       WHERE table_name = 'estado_pago_consumo'
         AND table_schema = 'public'
   ) THEN
      CREATE TABLE estado_pago_consumo
      (
        id_cliente uuid NOT NULL,
        estado_consumo character varying(50) NOT NULL,
        mensaje_consumo text,
        id_instalacion uuid,
        CONSTRAINT pk_estado_pago_consumo PRIMARY KEY (id_cliente)
      );
   END IF;
END$$;

-- ===========================
-- Agregar columna id_instalacion si no existe
-- ===========================
DO $$
BEGIN
   IF NOT EXISTS (
       SELECT 1
       FROM information_schema.columns
       WHERE table_name = 'estado_pago_consumo'
         AND column_name = 'id_instalacion'
         AND table_schema = 'public'
   ) THEN
      ALTER TABLE estado_pago_consumo ADD COLUMN id_instalacion uuid;
   END IF;
END$$;


-- ===========================
-- Insertar solo si no existe el cliente
-- ===========================
INSERT INTO estado_pago_consumo (id_cliente, estado_consumo, mensaje_consumo, id_instalacion)
SELECT '960caf31-47f9-4b87-aa03-37fd5c03bf74',
       'Al día',
       'Es en mora de factura No 369, favor reportar el pago al 3698852',
       '337542e0-e18a-4e53-9cb2-19b976b146f1'
WHERE NOT EXISTS (
    SELECT 1 
    FROM estado_pago_consumo 
    WHERE id_cliente = '960caf31-47f9-4b87-aa03-37fd5c03bf74'
);


-- ===========================
-- Tabla grupo_impresion
-- ===========================
DO $$
BEGIN
   -- Crear tabla solo si no existe
   IF NOT EXISTS (
       SELECT 1 FROM information_schema.tables 
       WHERE table_name = 'grupo_impresion'
         AND table_schema = 'public'
   ) THEN
      CREATE TABLE grupo_impresion
      (
        id serial NOT NULL,
        nombre character varying(50),
        id_impresora_asignada integer,
        numero_copias integer DEFAULT 1,
        CONSTRAINT pk_grupo_impresion PRIMARY KEY (id),
        CONSTRAINT fk_impresora FOREIGN KEY (id_impresora_asignada)
            REFERENCES impresora (id)
      );
   END IF;
END$$;

-- ===========================
-- Comentarios (se ejecutan solo si la tabla existe)
-- ===========================
DO $$
BEGIN
   IF EXISTS (
       SELECT 1 FROM information_schema.tables
       WHERE table_name = 'grupo_impresion'
         AND table_schema = 'public'
   ) THEN
      COMMENT ON TABLE grupo_impresion IS 'Tabla utilizada para agrupar productos en comandas cuando la configuración de impresión es por grupo.';
      COMMENT ON COLUMN grupo_impresion.nombre IS 'Nombre del grupo de impresion ';
      COMMENT ON COLUMN grupo_impresion.id_impresora_asignada IS 'Establece la relación con la tabla impresoras mediante su ID, indicando la impresora asignada a cada grupo de impresión de comandas.';
   END IF;
END$$;

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


-- ===========================
-- Columnas en configuracion_pedido
-- ===========================
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.columns 
        WHERE table_name='configuracion_pedido' AND column_name='altura'
    ) THEN
        ALTER TABLE configuracion_pedido ADD COLUMN altura integer;
    END IF;
END$$;

DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.columns 
        WHERE table_name='configuracion_pedido' AND column_name='codigo_pantalla'
    ) THEN
        ALTER TABLE configuracion_pedido ADD COLUMN codigo_pantalla boolean DEFAULT false;
        COMMENT ON COLUMN configuracion_pedido.codigo_pantalla IS 'Este campo me permite ver en pantalla al momento de llamar los productos si concateno el codigo con el nombre del producto';
    END IF;
END$$;

-- ===========================
-- Columna en categoria
-- ===========================
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.columns 
        WHERE table_name='categoria' AND column_name='orden'
    ) THEN
        ALTER TABLE categoria ADD COLUMN orden integer;
    END IF;
END$$;

-- ===========================
-- Columna en medio_pago
-- ===========================
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.columns 
        WHERE table_name='medio_pago' AND column_name='nombre_comercial'
    ) THEN
        ALTER TABLE medio_pago ADD COLUMN nombre_comercial character varying(100);
    END IF;
END$$;

-- ===========================
-- Tabla entradas_salidas
-- ===========================
-- ============================================
-- Tabla entradas_salidas (idempotente)
-- ============================================

DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.tables 
        WHERE table_name = 'entradas_salidas'
    ) THEN
        CREATE TABLE entradas_salidas
        (
            id serial PRIMARY KEY,
            cantidad double precision,
            id_documento integer,
            id_concepto_kardex integer,
            id_operacion integer,
            fecha date,
            inventario_anterior double precision,
            tabla character varying(50)
        );
    END IF;
END$$;


DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_name = 'entradas_salidas'
          AND column_name = 'id_concepto_kardex'
    ) THEN
        ALTER TABLE entradas_salidas
        ADD COLUMN id_concepto_kardex integer;
    END IF;
END;
$$;



-- ============================================
-- Constraint fk_id_concepto
-- ============================================
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.table_constraints 
        WHERE constraint_name = 'fk_id_concepto'
          AND table_name = 'entradas_salidas'
    ) THEN
        ALTER TABLE entradas_salidas
        ADD CONSTRAINT fk_id_concepto
        FOREIGN KEY (id_concepto_kardex)
        REFERENCES concepto_kardex (id)
        ON UPDATE NO ACTION
        ON DELETE NO ACTION;
    END IF;
END$$;

-- ============================================
-- Constraint fk_operacion
-- ============================================
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.table_constraints 
        WHERE constraint_name = 'fk_operacion'
          AND table_name = 'entradas_salidas'
    ) THEN
        ALTER TABLE entradas_salidas
        ADD CONSTRAINT fk_operacion
        FOREIGN KEY (id_operacion)
        REFERENCES operacion (id)
        ON UPDATE NO ACTION
        ON DELETE NO ACTION;
    END IF;
END$$;

-- ============================================
-- Comentarios (idempotentes)
-- Nota: COMMENT no es condicional, pero se puede repetir sin error.
-- ============================================
COMMENT ON COLUMN entradas_salidas.id_documento IS
  'Este campo se refiere a la llave primaria de los movimientos que se generan (Ventas , devoluciones , remisiones etc...)';

COMMENT ON COLUMN entradas_salidas.id_concepto_kardex IS
  'Este sirve para referirse a la tabla concepto kardex para ver el nombre del concepto y formar el constraint hacia esa tabla ';

COMMENT ON COLUMN entradas_salidas.id_operacion IS
  'Este campo sirve para determinar si es entrada o salida y formar constraint a la tabla operacion que es la que la que me dice si es entrada o salida ';

-- ===========================
-- Columnas en item_documento_electronico
-- ===========================
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.columns 
        WHERE table_name='item_documento_electronico' AND column_name='inventario_anterior'
    ) THEN
        ALTER TABLE item_documento_electronico ADD COLUMN inventario_anterior double precision;
    END IF;
END$$;

DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.columns 
        WHERE table_name='item_documento_electronico' AND column_name='inventario_actual'
    ) THEN
        ALTER TABLE item_documento_electronico ADD COLUMN inventario_actual double precision;
    END IF;
END$$;

-- ===========================
-- Columnas en kardex
-- ===========================
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.columns 
        WHERE table_name='kardex' AND column_name='saldo_anterior'
    ) THEN
        ALTER TABLE kardex ADD COLUMN saldo_anterior double precision;
    END IF;
END$$;

DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.columns 
        WHERE table_name='kardex' AND column_name='nuevo_saldo'
    ) THEN
        ALTER TABLE kardex ADD COLUMN nuevo_saldo double precision;
    END IF;
END$$;


DO $$
BEGIN
    -- 1. Verificar si la columna id existe
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_name = 'medio_pago'
          AND column_name = 'id'
    ) THEN
        -- 2. Agregar columna id
        ALTER TABLE medio_pago ADD COLUMN id integer;

        -- 3. Crear secuencia si no existe
        IF NOT EXISTS (
            SELECT 1 FROM pg_class WHERE relname = 'medio_pago_id_seq'
        ) THEN
            CREATE SEQUENCE medio_pago_id_seq;
        END IF;

        -- 4. Asignar valores a registros existentes
        UPDATE medio_pago
        SET id = nextval('medio_pago_id_seq')
        WHERE id IS NULL;

        -- 5. Configurar default con la secuencia
        ALTER TABLE medio_pago
        ALTER COLUMN id SET DEFAULT nextval('medio_pago_id_seq');

        -- 6. Definir como PRIMARY KEY (opcional, si quieres que sea pk)
        -- ALTER TABLE medio_pago ADD CONSTRAINT pk_medio_pago_id PRIMARY KEY (id);
    END IF;
END;
$$;




-- ===========================
-- Updates a medio_pago
-- ===========================
-- ===========================
-- Updates a medio_pago
-- ===========================
UPDATE medio_pago SET nombre = 'Instrumento no definido ', estado = FALSE, nombre_comercial = 'INSTRUMENTO NO DEFINIDO' WHERE id = 1;
UPDATE medio_pago SET nombre = 'CASH', estado = TRUE, nombre_comercial = 'EFECTIVO' WHERE id = 10;
UPDATE medio_pago SET nombre = 'Reversión Crédito Ahorro ', estado = FALSE, nombre_comercial = 'REVERSIÓN CRÉDITO AHORRO' WHERE id = 11;
UPDATE medio_pago SET nombre = 'Reversión Débito Ahorro ', estado = FALSE, nombre_comercial = 'REVERSIÓN DÉBITO AHORRO' WHERE id = 12;
UPDATE medio_pago SET nombre = 'Crédito Ahorro ', estado = FALSE, nombre_comercial = 'CRÉDITO AHORRO' WHERE id = 13;
UPDATE medio_pago SET nombre = 'Débito Ahorro ', estado = FALSE, nombre_comercial = 'DÉBITO AHORRO' WHERE id = 14;
UPDATE medio_pago SET nombre = 'Bookentry Crédito ', estado = FALSE, nombre_comercial = 'BOOKENTRY CRÉDITO' WHERE id = 15;
UPDATE medio_pago SET nombre = 'Bookentry Débito ', estado = FALSE, nombre_comercial = 'BOOKENTRY DÉBITO' WHERE id = 16;
UPDATE medio_pago SET nombre = 'Crédito Pago negocio corporativo (CTP) ', estado = FALSE, nombre_comercial = 'CRÉDITO PAGO NEGOCIO CORPORATIVO (CTP)' WHERE id = 19;
UPDATE medio_pago SET nombre = 'Crédito ACH ', estado = FALSE, nombre_comercial = 'CRÉDITO ACH' WHERE id = 2;

update configuracion_pedido set codigo_pantalla= 'false';

-- ====================================
-- Columna: tipo en salones
-- ====================================
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_name = 'salones'
        AND column_name = 'tipo'
    ) THEN
        ALTER TABLE salones ADD COLUMN tipo integer;
        ALTER TABLE salones ALTER COLUMN tipo SET DEFAULT 0;
        COMMENT ON COLUMN salones.tipo IS '0=general 
1=whatsapp';
    END IF;
END
$$;

-- ====================================
-- Columna: tiene_pedido en mesas
-- ====================================
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_name = 'mesas'
        AND column_name = 'tiene_pedido'
    ) THEN
        ALTER TABLE mesas ADD COLUMN tiene_pedido boolean;
        ALTER TABLE mesas ALTER COLUMN tiene_pedido SET DEFAULT false;
    END IF;
END
$$;

-- ====================================
-- Columna: consultar_pedidos_whatsapp en configuracion_pedido
-- ====================================
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_name = 'configuracion_pedido'
        AND column_name = 'consultar_pedidos_whatsapp'
    ) THEN
        ALTER TABLE configuracion_pedido ADD COLUMN consultar_pedidos_whatsapp boolean;
        ALTER TABLE configuracion_pedido ALTER COLUMN consultar_pedidos_whatsapp SET DEFAULT false;
        UPDATE configuracion_pedido SET consultar_pedidos_whatsapp = false;
    END IF;
END
$$;

-- ====================================
-- Columna: precios_comanda en configuracion_pedido
-- ====================================
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_name = 'configuracion_pedido'
        AND column_name = 'precios_comanda'
    ) THEN
        ALTER TABLE configuracion_pedido ADD COLUMN precios_comanda boolean;
        COMMENT ON COLUMN configuracion_pedido.precios_comanda IS 'Este campo permite decidir si se imprimen o no los precios en comanda ';
    END IF;
END
$$;

update configuracion_pedido set precios_comanda=false;

-- ====================================
-- Columna: alerta en resolucion_electronica
-- ====================================
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_name = 'resolucion_electronica'
        AND column_name = 'alerta'
    ) THEN
        ALTER TABLE resolucion_electronica ADD COLUMN alerta integer;
    END IF;
END
$$;


DO $$
BEGIN
    -- Verificar si existe la tabla
    IF NOT EXISTS (
        SELECT 1 
        FROM pg_class c 
        JOIN pg_namespace n ON n.oid = c.relnamespace
        WHERE c.relname = 'producto_factura_prov_temp'
        AND n.nspname = 'public'
    ) THEN
    
        CREATE TABLE public.producto_factura_prov_temp
        (
          id serial PRIMARY KEY,
          codigo_producto varchar(20) NOT NULL,
          cantidad double precision NOT NULL,
          valor double precision NOT NULL,
          id_usuario integer
        );

        ALTER TABLE public.producto_factura_prov_temp
          OWNER TO postgres;
    END IF;
END$$;


DO $$
BEGIN
    -- Verificar si la columna NO existe
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_name='configuracion_pedido'
          AND column_name='informe_fiscal'
    ) THEN
        -- Crear la columna con DEFAULT
        EXECUTE 'ALTER TABLE configuracion_pedido ADD COLUMN informe_fiscal boolean DEFAULT true';
    END IF;
END;
$$;

DO $$
BEGIN
  -- Verificar si la columna beep NO existe
  IF NOT EXISTS (
    SELECT 1
    FROM information_schema.columns
    WHERE table_name='configuracion_pedido'
      AND column_name='beep'
  ) THEN
    ALTER TABLE configuracion_pedido ADD COLUMN beep boolean DEFAULT false;
  END IF;
END$$;



TRUNCATE medio_pago;

INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('1', 'Instrumento no definido ', false, 'INSTRUMENTO NO DEFINIDO');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('10', 'CASH', true, 'EFECTIVO');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('11', 'Reversión Crédito Ahorro ', false, 'REVERSIÓN CRÉDITO AHORRO');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('12', 'Reversión Débito Ahorro ', false, 'REVERSIÓN DÉBITO AHORRO');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('13', 'Crédito Ahorro ', false, 'CRÉDITO AHORRO');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('14', 'Débito Ahorro ', false, 'DÉBITO AHORRO');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('15', 'Bookentry Crédito ', false, 'BOOKENTRY CRÉDITO');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('16', 'Bookentry Débito ', false, 'BOOKENTRY DÉBITO');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('19', 'Crédito Pago negocio corporativo (CTP) ', false, 'CRÉDITO PAGO NEGOCIO CORPORATIVO (CTP)');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('2', 'Crédito ACH ', false, 'CRÉDITO ACH');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('20', 'Cheque ', false, 'CHEQUE');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('21', 'Poyecto bancario ', false, 'PROYECTO BANCARIO');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('24', 'Nota cambiaria esperando aceptación ', false, 'NOTA CAMBIARIA ESPERANDO ACEPTACIÓN');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('25', 'Cheque certificado ', false, 'CHEQUE CERTIFICADO');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('26', 'Cheque Local ', false, 'CHEQUE LOCAL');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('3', 'Débito ACH ', false, 'DÉBITO ACH');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('31', 'DEBIT_TRANSFER', true, 'TRANSFERENCIA DÉBITO');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('34', 'Pago y depósito pre acordado (PPD) ', false, 'PAGO Y DEPÓSITO PRE ACORDADO (PPD)');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('37', 'Pago Negocio Corporativo Ahorros Crédito (CTP) ', false, 'PAGO NEGOCIO CORPORATIVO AHORROS CRÉDITO (CTP)');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('4', 'Reversión débito de demanda ACH ', false, 'REVERSIÓN DÉBITO DE DEMANDA ACH');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('42', 'Consiganción bancaria ', false, 'CONSIGNACIÓN BANCARIA');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('44', 'Nota cambiaria ', false, 'NOTA CAMBIARIA');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('45', 'Transferencia Crédito Bancario ', false, 'TRANSFERENCIA CRÉDITO BANCARIO');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('46', 'Transferencia Débito Interbancario ', false, 'TRANSFERENCIA DÉBITO INTERBANCARIO');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('47', 'Transferencia Débito Bancaria ', false, 'TRANSFERENCIA DÉBITO BANCARIA');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('48', 'CREDIT_CARD', true, 'TARJETA DE CRÉDITO');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('49', 'DEBIT_CARD', true, 'TARJETA DÉBITO');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('5', 'Reversión crédito de demanda ACH  ', false, 'REVERSIÓN CRÉDITO DE DEMANDA ACH');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('50', 'Postgiro ', false, 'POSTGIRO');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('52', 'Pago comercial urgente ', false, 'PAGO COMERCIAL URGENTE');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('53', 'Pago Tesorería Urgente ', false, 'PAGO TESORERÍA URGENTE');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('6', 'Crédito de demanda ACH ', false, 'CRÉDITO DE DEMANDA ACH');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('60', 'Nota promisoria ', false, 'NOTA PROMISORIA');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('61', 'Nota promisoria firmada por el acreedor ', false, 'NOTA PROMISORIA FIRMADA POR EL ACREEDOR');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('62', 'Nota promisoria firmada por el acreedor, avalada por el banco ', false, 'NOTA PROMISORIA FIRMADA POR EL ACREEDOR, AVALADA POR EL BANCO');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('63', 'Nota promisoria firmada por el acreedor, avalada por un tercero ', false, 'NOTA PROMISORIA FIRMADA POR EL ACREEDOR, AVALADA POR UN TERCERO');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('64', 'Nota promisoria firmada pro el banco ', false, 'NOTA PROMISORIA FIRMADA POR EL BANCO');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('65', 'Nota promisoria firmada por un banco avalada por otro banco ', false, 'NOTA PROMISORIA FIRMADA POR UN BANCO AVALADA POR OTRO BANCO');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('66', 'Nota promisoria firmada  ', false, 'NOTA PROMISORIA FIRMADA');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('67', 'Nota promisoria firmada por un tercero avalada por un banco ', false, 'NOTA PROMISORIA FIRMADA POR UN TERCERO AVALADA POR UN BANCO');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('7', 'Débito de demanda ACH ', false, 'DÉBITO DE DEMANDA ACH');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('71', 'Bonos ', false, 'BONOS');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('72', 'Vales ', false, 'VALES');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('77', 'Retiro de una nota por el acreedor sobre un tercero ', false, 'RETIRO DE UNA NOTA POR EL ACREEDOR SOBRE UN TERCERO');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('78', 'Retiro de una nota por el acreedor sobre un tercero avalada por un banco ', false, 'RETIRO DE UNA NOTA POR EL ACREEDOR SOBRE UN TERCERO AVALADA POR UN BANCO');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('9', 'Clearing Nacional o Regional ', false, 'CLEARING NACIONAL O REGIONAL');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('93', 'Giro referenciado ', false, 'GIRO REFERENCIADO');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('94', 'Giro urgente ', false, 'GIRO URGENTE');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('95', 'Giro formato abierto ', false, 'GIRO FORMATO ABIERTO');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('96', 'Método de pago solicitado no usuado ', false, 'MÉTODO DE PAGO SOLICITADO NO USADO');
INSERT INTO public.medio_pago (codigo, nombre, estado, nombre_comercial) VALUES ('97', 'Clearing entre partners ', false, 'CLEARING ENTRE PARTNERS');


DO $$
BEGIN
    -- Verificar si la tabla 'bonos' ya existe
    IF NOT EXISTS (
        SELECT 1
        FROM pg_class c
        JOIN pg_namespace n ON n.oid = c.relnamespace
        WHERE c.relname = 'bonos'
          AND c.relkind = 'r'
          AND n.nspname = 'public'
    ) THEN
        CREATE TABLE bonos
        (
          id serial NOT NULL,
          id_usuario integer,
          fecha_generacion date,
          hora time with time zone,
          observacion text,
          valor double precision,
          CONSTRAINT "Pk_bono" PRIMARY KEY (id),
          CONSTRAINT fk_usuario FOREIGN KEY (id_usuario)
              REFERENCES usuario_sistema (idusuario_sistema) MATCH SIMPLE
              ON UPDATE NO ACTION ON DELETE NO ACTION
        )
        WITH (OIDS=FALSE);
        
        ALTER TABLE bonos OWNER TO postgres;
    END IF;
END
$$;


-- Consecutivo 103 - Cortesía (numeroconsecutivo fijo en 1)
INSERT INTO consecutivos (idconsecutivos, conceptoconsecutivo, numeroconsecutivo)
SELECT 103, 'cortesia', 1
WHERE NOT EXISTS (
    SELECT 1 FROM consecutivos WHERE idconsecutivos = 103
);

-- Consecutivo 104 - Informe (numeroconsecutivo dinámico según tabla consecutivo_informe)
INSERT INTO consecutivos (idconsecutivos, conceptoconsecutivo, numeroconsecutivo)
SELECT 104, 'consecutivo_informe',
       COALESCE((SELECT MAX(numero) FROM consecutivo_informe), 1)  -- si no hay registros, usa 1
WHERE NOT EXISTS (
    SELECT 1 FROM consecutivos WHERE idconsecutivos = 104
);



UPDATE configuracion_pedido SET version = 13;
