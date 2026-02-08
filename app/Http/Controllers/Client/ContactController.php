<?php

namespace App\Http\Controllers\Client;

// use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Controller as BaseController;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends BaseController
{
    public function index(Request $request)
    {
        $contact = Contact::with('client:CLI_ID,CLI_NOME');

        if ($request->user()->USR_MASTER === 'S') {
            return $contact->get();
        }

        if ($request->user()->USR_MASTER === 'M') {
            $client = $request->user()->client()->get()->toArray();
            $client = array_column($client, 'CLI_ID');
            return $contact->whereIn('CTT_CLI_ID', $client)->get();
        } else {
            $client = $request->user()->client()->first();
            return $contact->where('CTT_CLI_ID', $client->CLI_ID)->get();
        }
    }

    public function show(Request $request, $id)
    {
        if ($request->user()->USR_MASTER === 'S') {
            return Contact::with('client:CLI_ID,CLI_NOME')->find($id);
        } else {
            $client = $request->user()->client()->get()->toArray();
            $client = array_column($client, 'CLI_ID');
            return Contact::with('client:CLI_ID,CLI_NOME')
                ->whereIn('CTT_CLI_ID', $client)
                ->where('CTT_ID', $id)
                ->first();
        }
    }

    public function store(Request $request)
    {
        if ($request->user()->USR_MASTER === 'N') return;

        $this->valid($request);

        $contId = Contact::select('CTT_ID')->max('CTT_ID') + 1;

        $request->request->add(['CTT_ID' => $contId]);

        $contact = Contact::create($request->all());

        if ($contact)
            return true;

        //     ActivityController::store([
        //         'user' => $request->user(),
        //         'module' => 'Contacte',
        //         'activity' => 'Contacte criado',
        //         'query' => '', // $sql,
        //         'httpCode' => 200,
        //         'ip' => $request->ip()
        //     ]);

        // return true;

        // }else{
        //     return false;
        // }

        // $user->client()->attach($contId);
    }

    public function update(Request $request)
    {
        if ($request->user()->USR_MASTER === 'N') return;

        $this->valid($request);

        $contact = Contact::findOrFail($request->CTT_ID);
        $contact->CTT_FORMA_TRATAMENTO = $request->input('CTT_FORMA_TRATAMENTO');
        $contact->CTT_CONTATO = $request->input('CTT_CONTATO');
        $contact->CTT_DDI = $request->input('CTT_DDI');
        $contact->CTT_DDD = $request->input('CTT_DDD');
        $contact->CTT_FONE = $request->input('CTT_FONE');
        $contact->CTT_EMAIL = $request->input('CTT_EMAIL');
        $contact->CTT_ATIVO = $request->input('CTT_ATIVO');
        $contact->CTT_MASTER = $request->input('CTT_MASTER');

        if ($contact->save())
            return true;

        //     ActivityController::store([
        //         'user' => $request->user(),
        //         'module' => 'Contacte',
        //         'activity' => 'Contacte atualizado',
        //         'query' => '', // $sql,
        //         'httpCode' => 200,
        //         'ip' => $request->ip()
        //     ]);

        //     return true;
        // }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->user()->USR_MASTER === 'N') return;

        $contact = Contact::destroy($id);

        if ($contact)
            return true;

        //     ActivityController::store([
        //         'user' => $request->user(),
        //         'module' => 'Contacte',
        //         'activity' => 'Contacte excluido',
        //         'query' => '', // $sql,
        //         'httpCode' => 200,
        //         'ip' => $request->ip()
        //     ]);

        //     return true;
        // }

        // return false;
    }

    private function valid(Request &$request)
    {
        $request->validate([
            // 'CTT_ID' => 'required',
            'CTT_FORMA_TRATAMENTO' => 'required|max:60',
            'CTT_CONTATO' => 'required|max:255',
            'CTT_DDI' => 'max:4',
            'CTT_DDD' => 'max:4',
            'CTT_FONE' => 'max:15',
            'CTT_EMAIL' => 'max:200',
            'CTT_MASTER' => 'required',
            // 'CTT_ATIVO' => 'required',
        ]);

        $request->merge(['CTT_FORMA_TRATAMENTO' => $request->input('CTT_FORMA_TRATAMENTO') . ' ']);

        $request->merge(['CTT_FONE' => preg_replace('/\D/', '', $request->input('CTT_FONE'))]);

        if ($request->input('CTT_ATIVO'))
            $request->merge(['CTT_ATIVO' => 'S']);
        else
            $request->merge(['CTT_ATIVO' => 'N']);
    }
}
