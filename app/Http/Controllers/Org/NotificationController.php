<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use App\Models\Location;
use App\Models\Member;
use App\Models\Org;
use Illuminate\Support\Facades\Redirect;

class NotificationController extends Controller
{
    public function index($id)
    {
        $org = Org::findOrFail($id);
        $activeLocations = Location::where('org_id', $org->id)->get();
        return View::make('orgs.notifications.index', compact('org', 'activeLocations'));
    }

    public function store(Request $request, $id)
    {
        try {
            Log::info('Iniciando proceso de notificación',["data"=>$request]);
            $request->validate([
                'title' => 'required|string|max:255',
                'message' => 'required|string',
                'sectors' => 'required_without:send_to_all|array',
            ]);
            $org = Org::findOrFail($id);
            $title = $request->input('title');
            $message = $request->input('message');
            $sendToAll = $request->has('send_to_all');
            Log::info('Parámetros recibidos:', [
                'title' => $title,
                'sendToAll' => $sendToAll,
                'orgId' => $org->id
            ]);
            // Obtener destinatarios (solo Members)
            if ($sendToAll) {
                $members = Member::whereHas('orgs', function($q) use ($org) {
                    $q->where('org_id', $org->id);
                })
                ->whereNotNull('email')
                ->where('email', '!=', '')
                ->get();
                Log::info('Enviando a todos los miembros. Total:', ['count' => $members->count()]);
            } else {
                $sectorIds = $request->input('sectors', []);
                $members = Member::whereHas('orgs', function($q) use ($org) {
                    $q->where('org_id', $org->id);
                })
                ->whereHas('services', function($q) use ($sectorIds) {
                    $q->whereIn('locality_id', $sectorIds);
                })
                ->whereNotNull('email')
                ->where('email', '!=', '')
                ->get();
                Log::info('Enviando a miembros de sectores específicos:', [
                    'sectors' => $sectorIds,
                    'memberCount' => $members->count()
                ]);
            }
            // Enviar notificación por email
            Log::info('Iniciando envío de notificaciones a miembros');
            foreach ($members as $member) {
                Log::info('Preparando envío a miembro', [
                    'id' => $member->id,
                    'nombre' => ($member->first_name ?? '') . ' ' . ($member->last_name ?? ''),
                    'email' => $member->email
                ]);
                try {
                    // Verificar configuración SMTP antes de enviar
                    Log::info('Configuración SMTP:', [
                        'host' => Config::get('mail.mailers.smtp.host'),
                        'port' => Config::get('mail.mailers.smtp.port'),
                        'encryption' => Config::get('mail.mailers.smtp.encryption'),
                        'from_address' => Config::get('mail.from.address')
                    ]);
                    Mail::to($member->email)->send(new NotificationMail($title, $message, $org, $member));
                    Log::info('Correo enviado exitosamente a: ' . $member->email);
                } catch (\Exception $e) {
                    Log::error('Error enviando correo a ' . $member->email . ': ' . $e->getMessage());
                }
            }
            Log::info('Proceso de envío finalizado');
            return Redirect::back()->with('success', 'Notificación enviada correctamente.');
        } catch (\Exception $e) {
            Log::error('Error general en el proceso: ' . $e->getMessage());
            return Redirect::back()
                ->with('error', 'Error al enviar la notificación: ' . $e->getMessage())
                ->withInput();
        }
    }
}
