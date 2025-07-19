<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\LicenciaModel;

class SyncLicencias extends BaseCommand
{
    protected $group       = 'Licencias';
    protected $name        = 'sync:licencias';
    protected $description = 'Sincroniza el estado de la única licencia local desde Supabase.';

    public function run(array $params)
    {
        helper('curl');

        // Obtiene la única licencia registrada
        $licencia = model('LicenciaModel')->select('id_cliente')->first();

        if (!$licencia) {
            CLI::error('❌ No hay ninguna licencia registrada en la base local.');
            return;
        }

        $id = $licencia['id_cliente'];
        $estadoActualizado = $this->consultarLicenciaSupabase($id);



        if (!is_null($estadoActualizado)) {
            //model('LicenciaModel')->update($id, ['estado' => $estadoActualizado]);

            CLI::write("✅ Licencia $id actualizada a: " . ($estadoActualizado ? 'Activa o En Mora' : 'Suspendida o Inactiva'));
        } else {
            CLI::error("❌ No se pudo obtener el estado de la licencia $id desde Supabase.");
        }
    }

    private function consultarLicenciaSupabase(string $id): ?bool
    {
        $url = "https://pjhzftkkysmuwjestwsk.supabase.co/rest/v1/estado_licencia?id_cliente=eq.$id";

        $apiKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InBqaHpmdGtreXNtdXdqZXN0d3NrIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDU0NTYwMzcsImV4cCI6MjA2MTAzMjAzN30.-ZLlB3hKU-Xm9_FFI29fbfVQNULWQs07QdfirZG0o0I';

        $headers = [
            "apikey: $apiKey",
            "Authorization: Bearer $apiKey",
            "Content-Type: application/json"
        ];

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5); // ⏱️ Máximo 5 segundos para respuesta
        $response = curl_exec($curl);
        curl_close($curl);

        if (!$response) {
            return null; // Error de conexión o timeout
        }

        $data = json_decode($response, true);


        if (isset($data[0]['estado_licencia'])) {
            $estado = $data[0]['estado_licencia'];
            $mensaje = $data[0]['mensaje_licencia'];

            // Si el estado es Activa o En Mora → true (permitido)
            // Si el estado es Suspendida o Inactiva → false (bloqueado)
            $update=model('licenciaModel')
            ->set('estado_licencia',$estado)
            ->set('mensaje_licencia',$mensaje)
            ->update();
            return in_array($estado, ['Activa', 'En Mora']);
        }

        return null; // Si no se obtuvo un estado válido
    }
}
