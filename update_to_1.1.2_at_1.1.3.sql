
INSERT INTO public.concepto_kardex (id, idoperacion, nombre, imagen) VALUES (31, 1, 'CONTEO MANUAL', 'A');

INSERT INTO public.tipo_empresa (id, nombre)
SELECT 3, 'Comercio al por menor con edicion de precios'
WHERE NOT EXISTS (
    SELECT 1 FROM public.tipo_empresa WHERE id = 3
);
update configuracion_pedido set version = '1.1.3 23 de mayo de 2025';