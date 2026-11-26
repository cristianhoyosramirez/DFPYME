<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SyncEstadoConsumo extends BaseCommand
{
    protected $group       = 'Estado';
    protected $name        = 'sync:estado-consumo';
    protected $description = 'Sincroniza el estado de consumo de un cliente específico desde Supabase.';

    public function run(array $params)
    {
        helper('curl');

        // ID del cliente a sincronizar (fijo)
        $idCliente = '960caf31-47f9-4b87-aa03-37fd5c03bf74';

        $estadoActualizado = $this->consultarEstadoSupabase($idCliente);

        if (!is_null($estadoActualizado)) {
            // Aquí puedes guardar el estado en tu tabla local 'estado_pago_consumo' si es necesario
            // Ejemplo: model('estadoPagoConsumoModel')->update($idCliente, ['estado' => $estadoActualizado]);

            CLI::write("✅ Estado de consumo del cliente $idCliente actualizado a: " . ($estadoActualizado ? 'Activo' : 'Inactivo'));
        } else {
            CLI::error("❌ No se pudo obtener el estado de consumo para el cliente $idCliente desde Supabase.");
        }
    }

    private function consultarEstadoSupabase(string $id): ?bool
    {
        //$url = "https://pjhzftkkysmuwjestwsk.supabase.co/rest/v1/estado_consumo?id_cliente=eq.$id";
         $url = 'https://pjhzftkkysmuwjestwsk.supabase.co/rest/v1/estado_consumo?id_cliente=eq.960caf31-47f9-4b87-aa03-37fd5c03bf74';
        $apiKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InBqaHpmdGtreXNtdXdqZXN0d3NrIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDU0NTYwMzcsImV4cCI6MjA2MTAzMjAzN30.-ZLlB3hKU-Xm9_FFI29fbfVQNULWQs07QdfirZG0o0I';

        $headers = [
            "apikey: $apiKey",
            "Authorization: Bearer $apiKey",
            "Content-Type: application/json"
        ];

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5); // 
        $response = curl_exec($curl);
        curl_close($curl);

        if (!$response) {
            return null; // Error de conexión o timeout
        }

        $data = json_decode($response, true);



        $update=model('estadoPagoConsumoModel')
        ->set('mensaje_consumo',$data[0]['mensaje_consumo'])
        ->set('estado_consumo',$data[0]['estado_consumo'])
        ->update();

        if (isset($data[0]['estado_consumo'])) {
            return (bool) $data[0]['estado_consumo']; // Se espera un booleano directamente
        }

        return null; // Si no se obtuvo un valor válido
    }
}
